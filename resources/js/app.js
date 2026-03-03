import "./bootstrap";
import "./dataTables";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import { initDriverBeams } from "./beams-driver-core";

import Alpine from "alpinejs";

import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById("calendar");
    if (!el) return;

    // ✅ guard: jangan init dua kali
    if (el.dataset.fcInit === "1") return;
    el.dataset.fcInit = "1";

    const calendar = new Calendar(el, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        locale: "id",
        initialView: "dayGridMonth",
        height: "auto",
        headerToolbar: false,
        displayEventTime: false,
        nowIndicator: true,
        selectable: false,
        editable: false,

        eventDidMount: function (info) {
            // generate random pastel color
            const hue = Math.floor(Math.random() * 360);
            const bgColor = `hsl(${hue}, 70%, 85%)`;
            const borderColor = `hsl(${hue}, 70%, 60%)`;

            info.el.style.backgroundColor = bgColor;
            info.el.style.borderColor = borderColor;

            // ✅ GUNAKAN CSS VARIABLE INI AGAR TEKS DALAMNYA IKUT BERUBAH WARNA
            info.el.style.setProperty("--fc-event-text-color", "#111827");
        },
        events: async (info, success, failure) => {
            try {
                const start = encodeURIComponent(info.startStr);
                const end = encodeURIComponent(info.endStr);

                const res = await fetch(
                    `/api/vehicle-events?start=${start}&end=${end}`,
                    {
                        headers: { Accept: "application/json" },
                        credentials: "same-origin",
                    },
                );

                if (!res.ok) {
                    console.error("Events API error:", res.status);
                    success([]);
                    return;
                }

                const data = await res.json();
                success(Array.isArray(data) ? data : []);
            } catch (e) {
                console.error("Events load error:", e);
                success([]);
            }
        },
        datesSet: (arg) => {
            const dt = arg.view.currentStart;
            const text = dt.toLocaleDateString("id-ID", {
                month: "long",
                year: "numeric",
            });
            const sub = document.getElementById("calendarSubTitle");
            if (sub) sub.textContent = text;
        },
    });

    // ✅ simpan instance buat tombol custom
    window.__calendar = calendar;

    calendar.render();
});

document.addEventListener("DOMContentLoaded", () => {
    const cal = window.__calendar;
    if (!cal) return;

    document
        .getElementById("btnToday")
        ?.addEventListener("click", () => cal.today());
    document
        .getElementById("btnPrev")
        ?.addEventListener("click", () => cal.prev());
    document
        .getElementById("btnNext")
        ?.addEventListener("click", () => cal.next());

    document.querySelectorAll(".viewBtn").forEach((btn) => {
        btn.addEventListener("click", () => {
            cal.changeView(btn.dataset.view);

            // highlight active
            document.querySelectorAll(".viewBtn").forEach((b) => {
                b.classList.remove("bg-gray-900", "text-white");
            });
            btn.classList.add("bg-gray-900", "text-white");
        });
    });
});

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
