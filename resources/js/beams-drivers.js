import * as PusherPushNotifications from "@pusher/push-notifications-web";

export async function initDriverBeams(driverId) {
    if (!driverId) return;

    const beamsClient = new PusherPushNotifications.Client({
        instanceId: import.meta.env.VITE_BEAMS_INSTANCE_ID,
    });

    await beamsClient.start();

    const tokenProvider = new PusherPushNotifications.TokenProvider({
        url: "/driver/beams-auth",
    });

    await beamsClient.setUserId(`driver:${driverId}`, tokenProvider);

    // optional: simpan ke window biar gampang debug
    window.__beamsClient = beamsClient;
}
