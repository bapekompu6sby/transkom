import { initDriverBeams } from "./beams-driver-core";

const driverId = Number(window.__DRIVER_ID__ || 0);
if (driverId > 0) {
    initDriverBeams(driverId);
}
