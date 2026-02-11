<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Bapekom VI Surabaya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .sidebar-menu li.active>a {
            background-color: #f3f4f6;
            color: #1f2937;
            border-left: 3px solid #1f2937;
        }

        .submenu {
            display: none;
            background-color: #f9fafb;
        }

        .submenu.active {
            display: block;
        }

        .nav-toggle:hover {
            background-color: #f3f4f6;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-50">


    <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false"
        x-effect="document.body.classList.toggle('overflow-hidden', sidebarOpen)" class="relative flex h-screen">


        {{-- Sidebar Desktop (tampil md ke atas) --}}
        <div class="hidden md:flex">
            @include('admin.layouts.sidebar')
        </div>


        {{-- Mobile Sidebar (off-canvas) --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 md:hidden">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false"></div>

            {{-- Sidebar Panel --}}
            @include('admin.layouts.sidebar-mobile')
        </div>


        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Navbar --}}
            @include('admin.layouts.navbar')

            {{-- Content Area --}}
            <main class="flex-1 overflow-auto bg-gray-100">
                <div class="p-2">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>
    @include('admin.layouts.toast')
    <script>
        window.lastSeenOrderId = {{ (int) ($lastSeenId ?? 0) }};
    </script>


    {{-- script submenu kamu, aku upgrade biar support mobile + desktop --}}
    <script>
        document.querySelectorAll('a[data-toggle="collapse"]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                const submenu = document.querySelector(target);
                const icon = this.querySelector('.toggle-icon');
                if (!submenu) return;

                submenu.classList.toggle('active');
                if (icon) icon.classList.toggle('rotate-180');
            });
        });
    </script>

    <!-- Bootstrap JS dulu -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Baru script toast kamu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toast').forEach(toastEl => {
                new bootstrap.Toast(toastEl).show();
            });
        });
    </script>

    <script>
        const badge = document.getElementById('notif-badge');
        const bellBtn = document.getElementById('bell-btn');

        // Pastikan variabel-variabel ini ada
        let lastSeenOrderId = window.lastSeenOrderId ?? 0; // kalau kamu set dari blade
        let latestOrderIdFromServer = lastSeenOrderId;

        // mode debug
        const DEBUG = true;

        function log(...args) {
            if (DEBUG) console.log('[TripNotif]', ...args);
        }

        function showBadge() {
            badge.classList.remove('hidden');
        }

        function hideBadge() {
            badge.classList.add('hidden');
        }

        async function checkNewOrder() {
            const url = `/admin/trips/check?last_seen_id=${lastSeenOrderId}`;

            log('fetch →', url, '| lastSeenOrderId =', lastSeenOrderId);

            try {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                log('status:', res.status);

                if (!res.ok) {
                    log('response not ok, skip');
                    return;
                }

                const data = await res.json();
                log('data:', data);

                const latestId = Number(data.latest_id ?? 0);
                const isNew = Boolean(data.new);

                latestOrderIdFromServer = latestId;

                // ✅ FIX: kalau pertama kali load (lastSeen = 0),
                // set lastSeen = latestId supaya badge gak nyala karena data lama.
                if ((lastSeenOrderId === 0 || Number.isNaN(lastSeenOrderId)) && latestId > 0) {
                    lastSeenOrderId = latestId;
                    hideBadge();
                    log('INIT: set lastSeenOrderId = latestId', latestId, '(prevent old trips triggering badge)');
                    return;
                }

                // ✅ FIX: hanya munculin badge kalau memang ada trip baru dibanding lastSeen
                // (jangan cuma percaya data.new kalau backend kamu masih berubah-ubah)
                if (latestId > lastSeenOrderId) {
                    showBadge();
                    log('BADGE ON: latestId > lastSeenOrderId', latestId, '>', lastSeenOrderId);
                } else {
                    hideBadge();
                    log('BADGE OFF: no new trip. latestId <= lastSeenOrderId', latestId, '<=', lastSeenOrderId);
                }

            } catch (e) {
                log('fetch error:', e);
            }
        }

        // polling tiap 5 detik, tapi stop kalau tab nggak aktif
        let timer = null;

        function startPolling() {
            if (timer) clearInterval(timer);
            checkNewOrder();
            timer = setInterval(checkNewOrder, 5000);
            log('polling started');
        }

        function stopPolling() {
            if (timer) clearInterval(timer);
            timer = null;
            log('polling stopped');
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopPolling();
            else startPolling();
        });

        // klik lonceng = dianggap sudah lihat
        bellBtn?.addEventListener('click', () => {
            hideBadge();

            // update last seen ke latest yg barusan kita terima
            const before = lastSeenOrderId;
            lastSeenOrderId = latestOrderIdFromServer;

            log('CLICK bell: lastSeen updated', before, '→', lastSeenOrderId);
        });

        // mulai
        startPolling();
    </script>



    @stack('js')
</body>

</html>
