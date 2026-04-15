import Dexie from 'dexie';

export const db = new Dexie('JubafAppLocalDB');

db.version(1).stores({
    demands: 'id, status, localidade_id, ordem_servico_id',
    materials: 'id, nome, codigo',
    syncQueue: '++id, action, timestamp',
    offline_media: '++id, demandId, type, blob, timestamp'
});

export async function clearLocalData() {
    await db.demands.clear();
    await db.materials.clear();
    // Do not clear syncQueue or offline_media as they might have pending uploads!
}
