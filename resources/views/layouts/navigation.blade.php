<nav class="w-full bg-white border-b border-gray-200">
    <div class="px-4 mx-auto py-3 flex items-center justify-between">

        <!-- Left Section -->
        <div class="flex items-center space-x-3">
            <span class="flex items-center">
                <img src="{{ asset('/storage/assets/Logo_PU.jpg') }}" alt="Logo PU" class="w-6 h-6 inline-block mr-1">
            </span>
            <span class="text-gray-900 font-semibold text-lg">
                TRANSKOM
            </span>
        </div>

        <!-- Right Section -->
        <div>
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 bg-black text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                Masuk
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

    </div>
</nav>
