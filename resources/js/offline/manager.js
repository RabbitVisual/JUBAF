import { db } from './db';
import { downloadData, uploadData, queueAction } from './sync';
import Compressor from 'compressorjs';

export default () => ({
    online: navigator.onLine,
    syncing: false,
    syncStatus: 'Sincronizar',
    lastSync: localStorage.getItem('lastSync'),
    syncColor: 'gray',
    queueCount: 0,
    queueItems: [],
    localDemands: [],
    selectedDemand: null,
    
    // Consent
    imageConsent: false,
    
    // Radar
    radarActive: false,
    radarWatchId: null,
    targetDistance: null,
    targetBearing: null,
    currentHeading: 0,
    
    async init() {
        if (!window.isSecureContext && window.location.hostname !== 'localhost') {
            console.warn('Insecure context: Camera and Geolocation may fail.');
        }

        window.addEventListener('online', () => {
            this.online = true;
            this.processQueue();
        });
        window.addEventListener('offline', () => this.online = false);
        
        await this.refreshData();
        this.updateSyncColor();
        
        setInterval(() => {
            this.updateQueueCount();
            this.updateSyncColor();
        }, 5000);
        
        this.initCompass();
    },
    
    initCompass() {
        if (window.DeviceOrientationEvent) {
            window.addEventListener('deviceorientation', (e) => {
                if (e.webkitCompassHeading) {
                    // iOS
                    this.currentHeading = e.webkitCompassHeading;
                } else if (e.alpha !== null) {
                    // Android/Others
                    this.currentHeading = 360 - e.alpha; 
                }
            }, true);
        }
    },

    async requestCompassPermission() {
        if (typeof DeviceOrientationEvent !== 'undefined' && typeof DeviceOrientationEvent.requestPermission === 'function') {
            try {
                const response = await DeviceOrientationEvent.requestPermission();
                if (response === 'granted') {
                    this.initCompass();
                } else {
                    alert('Permissão de bússola negada.');
                }
            } catch (e) {
                console.error(e);
            }
        }
    },
    
    async refreshData() {
        this.localDemands = await db.demands.toArray();
        this.updateQueueCount();
        this.loadQueueItems();
    },
    
    async updateQueueCount() {
        this.queueCount = await db.syncQueue.count();
    },
    
    async loadQueueItems() {
        this.queueItems = await db.syncQueue.orderBy('timestamp').reverse().toArray();
    },
    
    updateSyncColor() {
        if (!this.lastSync) {
            this.syncColor = 'gray';
            return;
        }
        
        const lastSyncTime = localStorage.getItem('lastSyncISO');
        if (!lastSyncTime) {
             this.syncColor = 'gray';
             return;
        }
        
        const diffHours = (new Date() - new Date(lastSyncTime)) / (1000 * 60 * 60);
        
        if (diffHours < 4) this.syncColor = 'green';
        else if (diffHours < 12) this.syncColor = 'amber';
        else this.syncColor = 'red';
    },
    
    async processQueue() {
        if (!this.online) return;
        this.syncing = true;
        this.syncStatus = 'Enviando...';
        await uploadData();
        this.syncStatus = 'Sincronizado';
        this.syncing = false;
        await this.refreshData();
        setTimeout(() => this.syncStatus = 'Sincronizar', 2000);
    },

    async sync() {
        if (!this.online) return;
        
        this.syncing = true;
        this.syncStatus = 'Baixando...';
        try {
            await uploadData();
            const downloadResult = await downloadData();
            
            const now = new Date();
            this.lastSync = now.toLocaleString('pt-BR');
            localStorage.setItem('lastSync', this.lastSync);
            localStorage.setItem('lastSyncISO', now.toISOString());
            
            this.syncStatus = `Ok (${downloadResult.count})`;
            await this.refreshData();
            this.updateSyncColor();
        } catch (e) {
            console.error(e);
            this.syncStatus = 'Erro';
        } finally {
            this.syncing = false;
            setTimeout(() => this.syncStatus = 'Sincronizar', 3000);
        }
    },
    
    handleLinkClick(e) {
        if (this.online) return; 

        const link = e.target.closest('a');
        if (!link) return;

        const href = link.href;
        if (href.match(/\/demandas\/\d+$/)) {
            e.preventDefault();
            const id = href.split('/').pop();
            this.openDemand(id);
        }
    },
    
    async saveStatus() {
        if (!this.selectedDemand) return;
        
        const payload = {
            ordem_id: this.selectedDemand.ordem_servico?.id,
            demanda_id: this.selectedDemand.id,
            observacoes: this.selectedDemand.observacoes_temp,
            client_timestamp: new Date().toISOString()
        };
        
        await queueAction('concluir_ordem', payload);
        
        const demand = await db.demands.get(this.selectedDemand.id);
        if (demand) {
            demand.status = 'concluida';
            await db.demands.put(demand);
            this.localDemands = await db.demands.toArray();
        }
        this.selectedDemand = null;
        alert('Ação salva offline. Sincronize para enviar.');
    },

    async openDemand(demandId) {
        const demand = this.localDemands.find(d => d.id == demandId);
        if (demand) {
            this.selectedDemand = demand;
            this.stopRadar();
            this.imageConsent = false; // Reset consent
        } else {
            alert('Demanda não encontrada offline. Sincronize antes.');
        }
    },
    
    compressImage(file) {
        return new Promise((resolve, reject) => {
            new Compressor(file, {
                quality: 0.6,
                maxWidth: 1600,
                maxHeight: 1600,
                success(result) {
                    resolve(result);
                },
                error(err) {
                    reject(err);
                },
            });
        });
    },

    async capturePhoto(event) {
        const file = event.target.files[0];
        if (!file || !this.selectedDemand) return;
        
        try {
            const compressedBlob = await this.compressImage(file);
            
            const mediaId = await db.offline_media.add({
                demandId: this.selectedDemand.id,
                type: 'photo',
                blob: compressedBlob,
                timestamp: Date.now(),
                consent: this.imageConsent // Save consent
            });
            
            await queueAction('upload_photo', {
                demandId: this.selectedDemand.id,
                description: 'Foto ' + new Date().toLocaleTimeString()
            }, mediaId);
            
            await this.updateQueueCount();
            alert('Foto salva na fila de envio!');
        } catch (e) {
            console.error(e);
            alert('Erro ao salvar foto: ' + e.message);
        }
    },
    
    toggleRadar() {
        if (this.radarActive) {
            this.stopRadar();
        } else {
            this.requestCompassPermission();
            this.startRadar();
        }
    },
    
    startRadar() {
        if (!this.selectedDemand?.localidade) {
            alert('Localidade sem coordenadas.');
            return;
        }
        
        const targetLat = this.selectedDemand.localidade.latitude;
        const targetLon = this.selectedDemand.localidade.longitude;
        
        if (targetLat === null || targetLon === null) {
             alert('Coordenadas inválidas para esta demanda.');
             return;
        }
        
        this.radarActive = true;
        
        if (navigator.geolocation) {
            this.radarWatchId = navigator.geolocation.watchPosition((pos) => {
                const userLat = pos.coords.latitude;
                const userLon = pos.coords.longitude;
                
                this.targetDistance = this.calculateDistance(userLat, userLon, targetLat, targetLon);
                this.targetBearing = this.calculateBearing(userLat, userLon, targetLat, targetLon);
                
            }, (err) => {
                console.error(err);
                alert('Erro de GPS: ' + err.message);
                this.stopRadar();
            }, {
                enableHighAccuracy: true
            });
        } else {
            alert('Geolocalização não suportada.');
        }
    },
    
    stopRadar() {
        this.radarActive = false;
        if (this.radarWatchId) {
            navigator.geolocation.clearWatch(this.radarWatchId);
            this.radarWatchId = null;
        }
    },
    
    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; 
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return Math.round(R * c);
    },
    
    calculateBearing(startLat, startLng, destLat, destLng) {
        startLat = startLat * Math.PI / 180;
        startLng = startLng * Math.PI / 180;
        destLat = destLat * Math.PI / 180;
        destLng = destLng * Math.PI / 180;

        const y = Math.sin(destLng - startLng) * Math.cos(destLat);
        const x = Math.cos(startLat) * Math.sin(destLat) -
                  Math.sin(startLat) * Math.cos(destLat) * Math.cos(destLng - startLng);
        const brng = Math.atan2(y, x);
        const deg = (brng * 180 / Math.PI + 360) % 360; 
        return deg;
    },
    
    getRelativeBearing() {
        if (!this.targetBearing) return 0;
        return (this.targetBearing - this.currentHeading + 360) % 360;
    }
});
