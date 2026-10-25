"use strict";

const CACHE_PREFIX = "erag-pwa-";
const CACHE_NAME = `${CACHE_PREFIX}offline-v2`;
const LEGACY_CACHE_NAME = "offline-cache-v1";
const OFFLINE_URL = "offline.html";

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.add(new Request(OFFLINE_URL, { cache: "reload" })))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil((async () => {
        if (self.registration.navigationPreload) {
            await self.registration.navigationPreload.enable();
        }

        // Only remove caches this package created, never the app's own caches.
        const cacheNames = await caches.keys();
        await Promise.all(
            cacheNames
                .filter((name) => name !== CACHE_NAME && (name.startsWith(CACHE_PREFIX) || name === LEGACY_CACHE_NAME))
                .map((name) => caches.delete(name))
        );

        await self.clients.claim();
    })());
});

self.addEventListener("fetch", (event) => {
    // Only page loads need the offline fallback. Assets, API and Inertia requests go
    // straight to the network without passing through the service worker.
    if (event.request.mode !== "navigate") {
        return;
    }

    event.respondWith((async () => {
        try {
            const preloadedResponse = await event.preloadResponse;

            return preloadedResponse || await fetch(event.request);
        } catch (error) {
            return (await caches.match(OFFLINE_URL)) || Response.error();
        }
    })());
});
