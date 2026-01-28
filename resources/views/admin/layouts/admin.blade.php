<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ITATS Labinformatika</title>
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
    </style>
    @stack('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

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

    <script>
        document.querySelectorAll('.sidebar-menu > li > a[data-toggle="collapse"]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                const submenu = document.querySelector(target);
                const icon = this.querySelector('.toggle-icon');

                submenu.classList.toggle('active');
                icon.classList.toggle('rotate-180');
            });
        });
    </script>
    @stack('js')
</body>

</html>
