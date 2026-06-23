self.addEventListener('install', (e) => {
    console.log('[Service Worker] Installed');
    // Forces the waiting service worker to become the active service worker
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    console.log('[Service Worker] Activated');
});