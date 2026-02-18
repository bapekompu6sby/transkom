<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    {{-- Logo --}}
    <div class="px-6 py-4">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8  flex items-center justify-center">
                <img src="{{ asset('/storage/assets/Logo_PU.jpg') }}" alt="PU Logo">
            </div>
            <div class
="flex flex-col">
                <span class="text-sm font-bold text-gray-900">Bapekom VI Sby</span>
                <span class="text-xs text-gray-500">Transportasi Bapekom</span>
            </div>
        </div>
    </div>

    {{-- Menu Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <ul class="sidebar-menu space-y-1">
            {{-- Halaman Utama --}}
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3">Halaman Utama</span>
                </a>
            </li>

            <li>
                <a href="#" data-toggle="collapse" data-target="#driver-menu"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3 flex-1">Sopir</span>
                    <i class="fas fa-chevron-down w-4 h-4 toggle-icon transition-transform"></i>
                </a>

                <!-- SUBMENU -->
                <ul id="driver-menu" class="submenu ml-1 mt-2 pl-1 border-l border-gray-300 space-y-1">

                    <li>
                        <a href="{{ route('admin.drivers.index') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manajemen Sopir</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="#" data-toggle="collapse" data-target="#car-menu"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3 flex-1">Kendaraan</span>
                    <i class="fas fa-chevron-down w-4 h-4 toggle-icon transition-transform"></i>
                </a>

                <!-- SUBMENU -->
                <ul id="car-menu" class="submenu ml-1 mt-2 pl-1 border-l border-gray-300 space-y-1">

                    <li>
                        <a href="{{ route('admin.cars.index') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manajemen Kendaraan</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="#" data-toggle="collapse" data-target="#trip-menu"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3 flex-1">Peminjaman</span>
                    <i class="fas fa-chevron-down w-4 h-4 toggle-icon transition-transform"></i>
                </a>
                <!-- SUBMENU -->
                <ul id="trip-menu" class="submenu ml-1 mt-2 pl-1 border-l border-gray-300 space-y-1">
                    <li>
                        <a href="{{ route('admin.trips.index') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manajemen Peminjaman</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.dashboard') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manambahkan Peminjaman</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>
