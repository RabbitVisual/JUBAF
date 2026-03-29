<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkinScanner', () => ({
        scanner: null,
        scanning: false,
        cameraBusy: false,
        manualHash: '',
        history: [],
        stats: { checkins: '-', total: '-' },
        feedback: { visible: false, type: 'success', title: '', message: '', extra: '' },
        audioCtx: null,
        lastScannedHash: '',
        scanCooldownMs: 2500,

        init() {
            const initAudio = () => {
                if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            };
            window.addEventListener('click', initAudio, { once: true });
            window.addEventListener('touchstart', initAudio, { once: true });
        },

        playBeep(type) {
            if (!this.audioCtx) return;
            if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
            const oscillator = this.audioCtx.createOscillator();
            const gain = this.audioCtx.createGain();
            oscillator.connect(gain);
            gain.connect(this.audioCtx.destination);
            const now = this.audioCtx.currentTime;
            if (type === 'success') {
                oscillator.frequency.setValueAtTime(880, now);
                oscillator.frequency.exponentialRampToValueAtTime(1320, now + 0.1);
                gain.gain.setValueAtTime(0.25, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.2);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(110, now);
                gain.gain.setValueAtTime(0.25, now);
                gain.gain.linearRampToValueAtTime(0.01, now + 0.3);
            }
            oscillator.start(now);
            oscillator.stop(now + 0.3);
        },

        onScanSuccess(decodedText) {
            if (this.feedback.visible) return;
            if (this.lastScannedHash === decodedText && (Date.now() - (this._lastScanTime || 0)) < this.scanCooldownMs) return;
            this.lastScannedHash = decodedText;
            this._lastScanTime = Date.now();
            this.validateTicket(decodedText);
        },

        toggleScanner() { this.scanning ? this.stopScanner() : this.startScanner(); },

        startScanner() {
            if (typeof Html5Qrcode === 'undefined') {
                this.showFeedback('error', 'Biblioteca não carregada', 'Html5Qrcode não encontrado. Recarregue a página.');
                return;
            }
            this.cameraBusy = true;
            this.scanner = new Html5Qrcode('reader');
            const qrboxSize = Math.min(320, Math.max(200, Math.floor(Math.min(window.innerWidth, window.innerHeight) * 0.5)));
            const config = { fps: 10, qrbox: qrboxSize, aspectRatio: 1 };
            this.scanner.start({ facingMode: 'environment' }, config, this.onScanSuccess.bind(this))
                .then(() => { this.scanning = true; this.cameraBusy = false; })
                .catch(() => {
                    this.cameraBusy = false;
                    this.showFeedback('error', 'Câmera', 'Não foi possível acessar a câmera. Verifique as permissões.');
                });
        },

        stopScanner() {
            if (this.scanner) {
                this.scanner.stop().then(() => {
                    this.scanning = false;
                    this.scanner.clear();
                });
            }
        },

        manualCheckin() {
            const hash = (this.manualHash || '').trim();
            if (!hash) return;
            this.validateTicket(hash);
            this.manualHash = '';
        },

        async validateTicket(hash) {
            try {
                const response = await fetch("{{ route('admin.events.checkin.validate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ticket_hash: hash })
                });
                const data = await response.json();
                const nowStr = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

                if (data.success) {
                    const title = data.headline || 'Liberado!';
                    const parts = [data.payment_note, data.ticket_type].filter(Boolean);
                    const message = parts.length ? parts.join(' · ') : 'Ingresso validado';
                    this.showFeedback('success', title, message, data.user_name || '');
                    const histStatus = data.payment_note || 'Confirmado';
                    this.history.unshift({ name: data.user_name || 'Visitante', time: nowStr, status: histStatus, type: 'success' });
                } else {
                    const type = (data.message || '').includes('JÁ FOI UTILIZADO') ? 'warning' : 'error';
                    this.showFeedback(type, 'Rejeitado', data.message || 'Erro desconhecido');
                    this.history.unshift({ name: '—', time: nowStr, status: 'Falha', type });
                }
                if (this.history.length > 8) this.history.pop();
            } catch (e) {
                this.showFeedback('error', 'Erro', 'Falha de conexão. Tente novamente.');
            }
        },

        showFeedback(type, title, message, extra = '') {
            this.feedback = { visible: true, type, title, message, extra };
            this.playBeep(type);
        }
    }));
});
</script>
<style>
    @keyframes scan-line {
        0% { top: 0; opacity: 0; }
        5% { opacity: 1; }
        95% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan-line { animation: scan-line 2.5s linear infinite; }
    #reader { min-height: 200px; }
    #reader video { max-height: 70vh; object-fit: cover; }
    @media (min-width: 640px) {
        #reader__scan_region video { max-height: 70vh; }
    }
</style>
