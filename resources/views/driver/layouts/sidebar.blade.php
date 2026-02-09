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
            <li class="#">
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3">Halaman Utama</span>
                </a>
            </li>
            <li class="#">
                <a href="{{ route('user.history.index') }}"
                    class="flex items-center px-0 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <span class="ml-3">Riwayat Peminjaman</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>
