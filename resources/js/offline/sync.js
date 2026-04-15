import { db } from './db';
import axios from 'axios';

export async function downloadData() {
    try {
        const response = await axios.get('/demandas/offline/sync');
        const { demands, materials } = response.data;
        
        await db.transaction('rw', db.demands, db.materials, async () => {
            await db.demands.clear();
            await db.materials.clear();
            
            if (demands && demands.length > 0) {
                await db.demands.bulkPut(demands);
            }
            if (materials && materials.length > 0) {
                await db.materials.bulkPut(materials);
            }
        });
        
        return { success: true, count: demands ? demands.length : 0 };
    } catch (error) {
        console.error('Download failed:', error);
        throw error;
    }
}

export async function uploadData() {
    const queue = await db.syncQueue.orderBy('timestamp').toArray();
    if (queue.length === 0) {
        return { success: true, count: 0, errors: [] };
    }

    console.warn(
        '[JUBAF] Fila IndexedDB legada (painel antigo) descartada; endpoint de sync foi removido.'
    );

    let cleared = 0;
    for (const item of queue) {
        if (item.action === 'upload_photo' && item.mediaId != null) {
            await db.offline_media.delete(item.mediaId);
        }
        await db.syncQueue.delete(item.id);
        cleared++;
    }

    return { success: true, count: cleared, errors: [] };
}

// Helper to queue actions
export async function queueAction(action, payload, mediaId = null) {
    return await db.syncQueue.add({
        action,
        payload,
        mediaId, // Link to offline_media if applicable
        uuid: self.crypto.randomUUID(),
        timestamp: Date.now()
    });
}
