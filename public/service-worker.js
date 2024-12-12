self.addEventListener('install', (event) => {
    event.waitUntil(
      caches.open('pwa-cache-v1').then((cache) => {
        return cache.addAll([
          '/', // Página inicial
          '/css/app.css', // CSS generado por Laravel Mix
          '/js/app.js', // JS generado por Laravel Mix
          '/images/icons/icon-192x192.png',
          '/images/icons/icon-512x512.png',
        ]);
      })
    );
  });
  
  // public/js/service-worker.js

self.addEventListener('fetch', function(event) {
  event.respondWith(
      fetch(event.request).catch(function() {
          return caches.match('/offline'); // Servir la página offline
      })
  );
});
