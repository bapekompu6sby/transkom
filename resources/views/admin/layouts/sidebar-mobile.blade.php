<aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="absolute left-0 top-0 w-64 h-full bg-white border-r border-gray-200 shadow-xl">
    {{-- Header Sidebar Mobile --}}
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 flex items-center justify-center">
                <img src="{{ asset('/storage/assets/Logo_PU.jpg') }}" alt="PU Logo">
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-900">Bapekom VI Sby</span>
                <span class="text-xs text-gray-500">Transportasi Bapekom</span>
            </div>
        </div>

        <button @click="sidebarOpen = false" class="p-2 rounded-lg hover:bg-gray-100">
            <i class="fas fa-times text-gray-600"></i>
        </button>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <ul class="sidebar-menu space-y-1">

            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3">Halaman Utama</span>
                </a>
            </li>

            <li>
                <a href="#" data-toggle="collapse" data-target="#driver-menu-mobile"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3 flex-1">Sopir</span>
                    <i class="fas fa-chevron-down w-4 h-4 toggle-icon transition-transform"></i>
                </a>

                <ul id="driver-menu-mobile" class="submenu ml-4 mt-2 pl-4 border-l border-gray-300 space-y-1">
                    <li>
                        <a href="{{ route('admin.drivers.index') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manajemen Sopir</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" data-toggle="collapse" data-target="#car-menu-mobile"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3 flex-1">Kendaraan</span>
                    <i class="fas fa-chevron-down w-4 h-4 toggle-icon transition-transform"></i>
                </a>

                <ul id="car-menu-mobile" class="submenu ml-4 mt-2 pl-4 border-l border-gray-300 space-y-1">
                    <li>
                        <a href="{{ route('admin.cars.index') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            <span class="ml-2">Manajemen Kendaraan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.trips.index') }}" @click="sidebarOpen = false"
                    class="flex items-center px-1 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-2">Peminjaman</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>
