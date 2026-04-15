import offlineManager from './manager.js';

document.addEventListener('alpine:init', () => {
    Alpine.data('offlineManager', offlineManager);
});
