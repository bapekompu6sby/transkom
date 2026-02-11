import { initDriverBeams } from "./beams-driver-core";
// atau kalau function-nya memang di file ini, skip import ini

const driverId = Number(window.__DRIVER_ID__ || 0);
if (driverId > 0) {
    initDriverBeams(driverId);
}

import * as PusherPushNotifications from "@pusher/push-notifications-web";

let beamsClientSingleton = null;

export async function initDriverBeams(driverId) {
    if (!driverId) return;

    const instanceId = import.meta.env.VITE_BEAMS_INSTANCE_ID;
    const desiredUserId = `driver:${driverId}`;

    if (!instanceId) {
        console.error("VITE_BEAMS_INSTANCE_ID belum diset");
        return;
    }

    // Singleton client (hindari double init)
    if (!beamsClientSingleton) {
        beamsClientSingleton = new PusherPushNotifications.Client({
            instanceId,
        });
    }
    const beamsClient = beamsClientSingleton;

    // Start
    await beamsClient.start();

    // ✅ cek userId yang benar-benar tersimpan di Beams (bukan localStorage)
    let currentBeamsUserId = null;
    try {
        currentBeamsUserId = await beamsClient.getUserId(); // <- penting
    } catch (e) {
        currentBeamsUserId = null;
    }

    // Kalau sudah sesuai, STOP (jangan setUserId lagi)
    if (currentBeamsUserId === desiredUserId) {
        return;
    }

    // Kalau ada user lama (dan beda), reset paksa
    if (currentBeamsUserId && currentBeamsUserId !== desiredUserId) {
        // urutan reset paling aman
        await beamsClient.stop();
        await beamsClient.clearAllState();
        await beamsClient.start();

        // cek lagi biar yakin bersih
        try {
            currentBeamsUserId = await beamsClient.getUserId();
        } catch (e) {
            currentBeamsUserId = null;
        }
    }

    // Token provider (pastikan endpoint POST & auth:driver)
    const tokenProvider = new PusherPushNotifications.TokenProvider({
        url: "/driver/beams-auth",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content"),
            "Content-Type": "application/json",
        },
    });

    // Bind user baru
    await beamsClient.setUserId(desiredUserId, tokenProvider);

    // optional debug
    window.__beamsClient = beamsClient;
}
