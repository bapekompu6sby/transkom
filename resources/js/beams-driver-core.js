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

    if (!beamsClientSingleton) {
        beamsClientSingleton = new PusherPushNotifications.Client({
            instanceId,
        });
    }
    const beamsClient = beamsClientSingleton;

    await beamsClient.start();

    let currentBeamsUserId = null;
    try {
        currentBeamsUserId = await beamsClient.getUserId();
    } catch (e) {
        currentBeamsUserId = null;
    }

    if (currentBeamsUserId === desiredUserId) return;

    if (currentBeamsUserId && currentBeamsUserId !== desiredUserId) {
        await beamsClient.stop();
        await beamsClient.clearAllState();
        await beamsClient.start();
    }

    const csrf = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    const tokenProvider = new PusherPushNotifications.TokenProvider({
        url: "/driver/beams-auth",
        headers: {
            "X-CSRF-TOKEN": csrf ?? "",
            "Content-Type": "application/json",
        },
    });

    await beamsClient.setUserId(desiredUserId, tokenProvider);

    window.__beamsClient = beamsClient; // optional debug
}
