@extends('user.layouts.user')

@section('title', 'Monitoring Kendaraan Bapekom VI Surabaya')

@section('breadcrumb-title', 'Peminjam')
@section('breadcrumb-subtitle', 'Dashboard')

@section('content')
    <div class="space-y-6">

        <div class="bg-white p-4 rounded-lg shadow-md space-y-4">
            {{-- Header --}}
            <div>
                <h1 class="text-xl font-bold text-gray-900">Peminjaman Kendaraan</h1>
                <p class="text-sm text-gray-600 mt-1">Silahkan pilih kendaraan untuk melakukan peminjaman</p>
            </div>

            {{-- Grid Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($cars as $car)
                    <div
                        class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden transition hover:shadow-md">
                        {{-- Header visual --}}
                        @if (!empty($car->image_url))
                            <div class="bg-gray-100 overflow-hidden rounded-lg border">
                                <img src="{{ asset('storage/' . $car->image_url) }}" alt="{{ $car->name }}"
                                    class="block w-full max-w-full h-auto object-contain" style="max-height: 160px;">
                            </div>
                        @else
                            <div class="h-40 bg-gray-100 flex items-center justify-center rounded-lg border">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-16 h-16 rounded-xl flex items-center justify-center bg-white/60">
                                        <i class="fas fa-image text-2xl"></i>
                                    </div>
                                    <span class="mt-2 text-xs font-semibold tracking-wide uppercase">No Image</span>
                                </div>
                            </div>
                        @endif



                        <div class="p-4 space-y-3">
                            {{-- Judul + badge --}}
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                                    {{ $car->name }}
                                </h3>

                                @php
                                    $status = $car->status ?? 'unknown';

                                    // Normalisasi biar typo/variasi aman
                                    $statusNorm = strtolower(str_replace([' ', '_'], '-', $status));

                                    $badge = match ($statusNorm) {
                                        'available' => 'bg-green-100 text-green-700',
                                        'unavailable' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };

                                    $label = match ($statusNorm) {
                                        'available' => 'Tersedia',
                                        'unavailable' => 'Tidak Tersedia',
                                        default => '—',
                                    };
                                @endphp

                                <span
                                    class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="text-sm text-gray-600 space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-id-card text-gray-400 w-4"></i>
                                    <span class="font-medium text-gray-700">Plat:</span>
                                    <span>{{ $car->plate_number ?? '—' }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-gray-400 w-4"></i>
                                    <span class="font-medium text-gray-700">Tahun:</span>
                                    <span>{{ $car->year ?? '—' }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <i class="fas fa-palette text-gray-400 w-4"></i>
                                    <span class="font-medium text-gray-700">Warna:</span>
                                    <span>{{ $car->color ?? '—' }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <i class="fas fa-hashtag text-gray-400 w-4"></i>
                                    <span class="font-medium text-gray-700">NUP:</span>
                                    <span>{{ $car->nup ?? '—' }}</span>
                                </div>
                            </div>

                            <div class="py-3 border-t bg-white">
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalCreateTrip-{{ $car->id }}"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                    Ajukan Peminjaman
                                    <span aria-hidden="true">→</span>
                                </button>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-span-full">
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center text-gray-600">
                            Belum ada kendaraan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        @include('user.modal.modal-create-trip')
    @endsection
