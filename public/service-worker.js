const CACHE_NAME = 'noteapp-v1';
const urlsToCache = [
  '/note/',
  '/note/dashboard',
  '/note/notes',
  '/note/plans',
  '/note/offline.html',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
    .catch(() => caches.match('/note/offline.html'))
  );
});