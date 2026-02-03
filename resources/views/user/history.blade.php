@extends('user.layouts.user')

@section('title', 'Monitoring Kendaraan Bapekom VI Surabaya')

@section('breadcrumb-title', 'Peminjam')
@section('breadcrumb-subtitle', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-4 rounded-lg shadow-md space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Riwayat Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Daftar pengajuan peminjaman kendaraan kamu.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($historys as $trip)
                    @php
                        $status = $trip->status ?? 'unknown';

                        $statusClass = match ($status) {
                            'approved' => 'bg-green-50 text-green-700 ring-green-600/20',
                            'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                            'ongoing' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'done' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                            'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                            'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20',
                            default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                        };

                        $statusLabel = match ($status) {
                            'approved' => 'Disetujui',
                            'pending' => 'Menunggu',
                            'ongoing' => 'Berjalan',
                            'done' => 'Selesai',
                            'rejected' => 'Ditolak',
                            'cancelled' => 'Dibatalkan',
                            default => strtoupper($status),
                        };

                        $carName = $trip->car->name ?? '—';
                        $plate = $trip->car->plate_number ?? '—';
                        $dest = $trip->destination ?? '—';

                        // Driver logic
                        $needsDriver = (int) ($trip->driver_required ?? 0) === 1;
                        $driverName = $trip->driver->name ?? null;
                        $driverPhone = $trip->driver->phone_number ?? null;

                        $driverSummary = !$needsDriver
                            ? 'Tanpa sopir'
                            : ($driverName
                                ? 'Dengan sopir'
                                : 'Sopir: menunggu penentuan');

                        // Date format: simple, readable
                        $startText =
                            optional($trip->start_at)->format('d M Y, H:i') ??
                            \Carbon\Carbon::parse($trip->start_at)->format('d M Y, H:i');
                        $endText =
                            optional($trip->end_at)->format('d M Y, H:i') ??
                            \Carbon\Carbon::parse($trip->end_at)->format('d M Y, H:i');
                    @endphp

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-base font-semibold text-gray-900 truncate">
                                            {{ $carName }}
                                        </h2>
                                        <span class="text-xs text-gray-500">({{ $plate }})</span>

                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-700 mt-2">
                                        <span class="font-medium text-gray-900">Tujuan:</span>
                                        {{ $dest }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium text-gray-900">Mulai:</span> {{ $startText }}
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-900">Selesai:</span> {{ $endText }}
                                        </div>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium text-gray-900">Sopir:</span> {{ $driverSummary }}
                                    </div>
                                </div>

                                {{-- Mini thumbnail (optional, clean) --}}
                                <div class="shrink-0">
                                    <div
                                        class="w-14 h-14 rounded-lg border bg-gray-50 overflow-hidden flex items-center justify-center">
                                        @if (!empty($trip->car?->image_url))
                                            <img src="{{ asset('storage/' . $trip->car->image_url) }}"
                                                alt="{{ $carName }}"
                                                class="max-w-full max-h-full w-auto h-auto object-contain">
                                        @else
                                            <i class="fas fa-car text-gray-300 text-xl"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Details toggle --}}
                            <details class="mt-4">
                                <summary
                                    class="list-none w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold cursor-pointer select-none hover:bg-gray-800 transition">
                                    <span>Detail</span>
                                </summary>



                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-lg border bg-gray-50 p-3">
                                        <div class="text-xs text-gray-500">Pengaju</div>
                                        <div class="font-semibold text-gray-900">{{ $trip->requester_name ?? '—' }}</div>
                                    </div>

                                    <div class="rounded-lg border bg-gray-50 p-3">
                                        <div class="text-xs text-gray-500">Catatan</div>
                                        <div class="text-gray-900">
                                            {{ !empty($trip->notes) ? $trip->notes : '—' }}
                                        </div>
                                    </div>

                                    {{-- Driver detail: hanya kalau butuh sopir --}}
                                    <div class="rounded-lg border bg-gray-50 p-3 sm:col-span-2">
                                        <div class="text-xs text-gray-500">Detail Sopir</div>

                                        @if (!$needsDriver)
                                            <div class="text-gray-900">Tanpa sopir.</div>
                                        @else
                                            @if ($driverName)
                                                <div class="font-semibold text-gray-900">{{ $driverName }}</div>
                                                <div class="text-gray-700 mt-1">
                                                    <span class="font-medium">No. HP:</span> {{ $driverPhone ?? '—' }}
                                                </div>
                                            @else
                                                <div class="text-gray-900">Menunggu penentuan sopir oleh admin.</div>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Cancel notes (kalau ada) --}}
                                    @if (!empty($trip->notes_cancel))
                                        <div class="rounded-lg border bg-red-50 p-3 sm:col-span-2">
                                            <div class="text-xs text-red-700">Catatan Pembatalan</div>
                                            <div class="text-red-900">{{ $trip->notes_cancel }}</div>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center">
                        <div class="text-gray-500">Belum ada riwayat peminjaman.</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
