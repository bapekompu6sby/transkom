<nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
    {{-- Left Section --}}
    <div class="flex items-center space-x-4">
        <h1 class="text-sm text-gray-500">
            <span class="font-medium text-gray-900">@yield('breadcrumb-title', 'Admin')</span>
            <span class="mx-2 text-gray-400">-</span>
            <span>@yield('breadcrumb-subtitle', 'lab informatika ITATS')</span>
        </h1>
    </div>

    {{-- Right Section --}}
    <div class="flex items-center space-x-6">
        {{-- User Profile Dropdown --}}
        <div class="relative group">
            <button class="flex items-center space-x-2 hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                <i class="fas fa-user-circle text-gray-600 text-xl"></i>
                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin Jarkom' }}</span>
                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div
                class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 first:rounded-t-lg">
                    <i class="fas fa-user w-4 h-4 mr-2"></i> Profil
                </a>
                <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog w-4 h-4 mr-2"></i> Pengaturan
                </a>
                <hr class="my-1">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100 last:rounded-b-lg">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
