self.addEventListener('install', (e) => {
    console.log('[Service Worker] Installed');
    // Forces the waiting service worker to become the active service worker
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    console.log('[Service Worker] Activated');
});

// A simple pass-through fetch so the browser recognizes the SW,
// but we still always get fresh data from the server for the live scanner.
self.addEventListener('fetch', (e) => {
    e.respondWith(fetch(e.request));
});