// Service worker de l'accordeur BombardeLab.
// À chaque mise en ligne, mettre ici le même numéro que APP_VERSION dans accordeur.php.
const CACHE_VERSION = 'accordeur-0.1.2';
const FONT_CACHE = 'accordeur-fonts';

const APP_SHELL = [
    './accordeur.php',
    './manifest.webmanifest',
    './logo-anim.gif',
    './icons/icon-192.png',
    './icons/icon-512.png',
    './icons/apple-touch-icon.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_VERSION).then(cache =>
            // Un fichier manquant ne doit pas bloquer l'installation, et on ne garde
            // jamais la page de connexion à la place de l'accordeur
            Promise.all(APP_SHELL.map(url => fetch(url).then(res => {
                if (cacheable(res)) return cache.put(url, res);
            }).catch(() => {})))
        ).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.filter(k => k !== CACHE_VERSION && k !== FONT_CACHE).map(k => caches.delete(k))
        )).then(() => self.clients.claim())
    );
});

// On ne met en cache que les vraies réponses (pas une redirection vers la page de connexion)
function cacheable(response) {
    return response && response.ok && !response.redirected && response.type === 'basic';
}

self.addEventListener('fetch', event => {
    const req = event.request;
    if (req.method !== 'GET') return;
    const url = new URL(req.url);

    // Police Google Fonts : cache d'abord, mise à jour en arrière-plan
    if (url.hostname === 'fonts.googleapis.com' || url.hostname === 'fonts.gstatic.com') {
        event.respondWith(
            caches.open(FONT_CACHE).then(async cache => {
                const cached = await cache.match(req);
                const network = fetch(req).then(res => {
                    if (res && (res.ok || res.type === 'opaque')) cache.put(req, res.clone());
                    return res;
                }).catch(() => cached);
                return cached || network;
            })
        );
        return;
    }

    if (url.origin !== self.location.origin) return;
    // Laisser passer la déconnexion et tout ce qui est hors du dossier de l'appli
    if (!url.pathname.startsWith(new URL('./', self.registration.scope).pathname)) return;

    // Page HTML : réseau d'abord (pour avoir la dernière version), cache si hors ligne
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).then(res => {
                if (cacheable(res)) {
                    const copy = res.clone();
                    caches.open(CACHE_VERSION).then(c => c.put('./accordeur.php', copy));
                }
                return res;
            }).catch(() => caches.match('./accordeur.php'))
        );
        return;
    }

    // Autres fichiers (logo, icônes, manifeste) : cache d'abord
    event.respondWith(
        caches.match(req).then(cached => cached || fetch(req).then(res => {
            if (cacheable(res)) {
                const copy = res.clone();
                caches.open(CACHE_VERSION).then(c => c.put(req, copy));
            }
            return res;
        }))
    );
});
