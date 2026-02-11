import "./bootstrap";
import "./dataTables";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import { initDriverBeams } from "./beams-driver-core";

import Alpine from "alpinejs";

console.log("Instance ID:", import.meta.env.VITE_BEAMS_INSTANCE_ID);

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", async () => {
    const driverId = Number(window.__DRIVER_ID__ || 0);
    if (driverId <= 0) return;

    try {
        await initDriverBeams(driverId);
        console.log("Beams ready for driver:", driverId);
    } catch (e) {
        console.error("Beams init error:", e);
    }
});
