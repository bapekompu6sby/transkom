<nav class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">
    {{-- Left Section --}}
    <div class="flex items-center space-x-4">

        {{-- Hamburger (mobile only) --}}
        <button @click="sidebarOpen = true"
            class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-100 transition">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <h1 class="text-sm text-gray-500">
            <span class="font-medium text-gray-900">@yield('breadcrumb-title', 'Admin')</span>
            <span class="mx-2 text-gray-400">-</span>
            <span>@yield('breadcrumb-subtitle', 'Bapekom VI Surabaya')</span>

        </h1>
    </div>

    {{-- Right Section --}}
    <div class="flex items-center space-x-6">
        {{-- User Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false"
            @keydown.escape.window="open = false">
            <button type="button" @click="open = !open"
                class="flex items-center space-x-2 hover:bg-gray-100 px-3 py-2 rounded-lg transition"
                :aria-expanded="open.toString()" aria-haspopup="true">
                <i class="fas fa-user-circle text-gray-600 text-xl"></i>
                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Peminjam' }}</span>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                    <i class="fas fa-user w-4 h-4 mr-2"></i> Profil
                </a>

                <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog w-4 h-4 mr-2"></i> Pengaturan
                </a>

                <hr class="my-1">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100 rounded-b-lg">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>
