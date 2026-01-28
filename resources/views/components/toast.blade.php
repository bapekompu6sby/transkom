@props([
    'bgColor' => 'bg-gray-800',
    'title' => 'Notification',
])

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition.opacity.duration.300ms
    class="fixed top-5 right-5 z-[9999] w-full max-w-sm rounded-xl shadow-lg overflow-hidden">
    <div class="flex items-start gap-3 p-4 text-white {{ $bgColor }}">
        {{-- Icon --}}
        <div class="mt-1">
            <i class="fa-solid fa-circle-check text-xl"></i>
        </div>

        {{-- Content --}}
        <div class="flex-1">
            <div class="font-semibold leading-tight">{{ $title }}</div>
            <div class="text-sm opacity-90 mt-1">
                {{ $slot }}
            </div>
        </div>

        {{-- Close --}}
        <button @click="show = false" class="text-white/70 hover:text-white" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
