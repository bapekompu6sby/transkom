import "./bootstrap";
import "./dataTables";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import { initDriverBeams } from "./beams-driver";

import Alpine from "alpinejs";

console.log("Instance ID:", import.meta.env.VITE_BEAMS_INSTANCE_ID);

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", async () => {
    if (window.__DRIVER_ID__) {
        try {
            await initDriverBeams(window.__DRIVER_ID__);
            console.log("Beams ready for driver:", window.__DRIVER_ID__);
        } catch (e) {
            console.error("Beams init error:", e);
        }
    }
});
