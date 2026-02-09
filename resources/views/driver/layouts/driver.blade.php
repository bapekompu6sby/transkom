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
        {{-- <div class="hidden md:flex">
            @include('user.layouts.sidebar')
        </div> --}}


        {{-- Mobile Sidebar (off-canvas) --}}
        {{-- <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 md:hidden"> --}}
            {{-- Overlay --}}
            {{-- <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false"></div> --}}

            {{-- Sidebar Panel --}}
            {{-- @include('user.layouts.sidebar-mobile')
        </div> --}}


        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Navbar --}}
            @include('driver.layouts.navbar')

            {{-- Content Area --}}
            <main class="flex-1 overflow-auto bg-gray-100">
                <div class="p-2">
                    @yield('content')
                </div>
            </main>
        </div>

        @include('user.layouts.toast')
    </div>

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

    @stack('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carInput = document.getElementById('tripCarId');

            document.querySelectorAll('.openTripModal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const carId = btn.getAttribute('data-car-id');
                    if (carInput) carInput.value = carId;
                });
            });
        });
    </script>

</body>

</html>
