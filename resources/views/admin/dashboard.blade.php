@extends('admin.layouts.admin')

@section('title', 'Monitoring Kendaraan Bapekom VI Surabaya')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        {{-- <div>
            <h1 class="text-3xl font-bold text-gray-900">Monitoring Kendaraan</h1>
            <p class="text-gray-600 mt-1">Ringkasan peminjaman, kendaraan, dan sopir yang aktif</p>
        </div> --}}

        {{-- GRID UTAMA (mirip SC) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- KIRI (2 kolom di desktop) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Card: Jadwal Peminjaman --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Jadwal Peminjaman</h2>
                            <p class="text-sm text-gray-500 mt-1">Peminjaman kendaraan mendatang</p>
                        </div>

                        <a href="#"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Peminjaman
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="px-5 pb-5">
                        @if (($bookings ?? collect())->count() === 0)
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                                Tidak ada peminjaman mendatang.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="text-left text-gray-500">
                                        <tr class="border-b">
                                            <th class="py-3 pr-4 font-medium">Tanggal</th>
                                            <th class="py-3 pr-4 font-medium">Kendaraan</th>
                                            <th class="py-3 pr-4 font-medium">Sopir</th>
                                            <th class="py-3 pr-4 font-medium">Pemohon</th>
                                            <th class="py-3 pr-4 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-800">
                                        @foreach ($bookings as $b)
                                            <tr class="border-b last:border-0">
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($b->start_at)->format('d M Y H:i') }}
                                                    <span class="text-gray-400">-</span>
                                                    {{ \Carbon\Carbon::parse($b->end_at)->format('H:i') }}
                                                </td>
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ $b->vehicle->name ?? '-' }}
                                                    <span class="text-gray-400">•</span>
                                                    <span class="text-gray-500">{{ $b->vehicle->plate ?? '' }}</span>
                                                </td>
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ $b->driver->name ?? '— (tanpa sopir)' }}
                                                </td>
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ $b->requester_name ?? ($b->user->name ?? '-') }}
                                                </td>
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    @php
                                                        $status = strtolower($b->status ?? 'pending');
                                                        $badge = match ($status) {
                                                            'approved'
                                                                => 'bg-green-50 text-green-700 ring-green-600/20',
                                                            'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                            'ongoing' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                                            'done' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                                            default
                                                                => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                                        {{ strtoupper($b->status ?? 'PENDING') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card: Statistik Peminjaman terbaru --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-gray-900">Statistik Peminjaman Terbaru</h2>
                        <p class="text-sm text-gray-500 mt-1">Ringkasan 6 periode terakhir (contoh)</p>
                    </div>

                    <div class="px-5 pb-5">
                        {{-- Simple “bar chart” versi Tailwind (tanpa JS) --}}
                        @php
                            $statsData =
                                $stats ??
                                collect([
                                    ['label' => 'Periode 1', 'total' => 12, 'approved' => 10, 'rejected' => 2],
                                    ['label' => 'Periode 2', 'total' => 9, 'approved' => 8, 'rejected' => 1],
                                    ['label' => 'Periode 3', 'total' => 15, 'approved' => 12, 'rejected' => 3],
                                    ['label' => 'Periode 4', 'total' => 11, 'approved' => 10, 'rejected' => 1],
                                    ['label' => 'Periode 5', 'total' => 8, 'approved' => 7, 'rejected' => 1],
                                    ['label' => 'Periode 6', 'total' => 6, 'approved' => 6, 'rejected' => 0],
                                ]);
                            $maxTotal = max(1, $statsData->max('total'));
                        @endphp

                        <div class="grid grid-cols-6 gap-4 items-end h-48">
                            @foreach ($statsData as $s)
                                @php
                                    $hTotal = intval(($s['total'] / $maxTotal) * 100);
                                    $hApproved = intval(($s['approved'] / $maxTotal) * 100);
                                    $hRejected = intval(($s['rejected'] / $maxTotal) * 100);
                                @endphp

                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-full flex items-end justify-center gap-1 h-40">
                                        <div class="w-4 rounded-t bg-blue-600/90" style="height: {{ $hTotal }}%">
                                        </div>
                                        <div class="w-4 rounded-t bg-green-600/85" style="height: {{ $hApproved }}%">
                                        </div>
                                        <div class="w-4 rounded-t bg-red-600/80" style="height: {{ $hRejected }}%">
                                        </div>
                                    </div>
                                    <div class="text-[11px] text-gray-500 text-center leading-tight">
                                        {{ $s['label'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 text-xs text-gray-600">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded bg-blue-600/90"></span> Total
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded bg-green-600/85"></span> Disetujui
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded bg-red-600/80"></span> Ditolak
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN (1 kolom di desktop) --}}
            <div class="space-y-6">

                {{-- Card: Kendaraan --}}
                <div class="bg-white rounded-xl border border-gray-200 flex flex-col h-[520px] overflow-hidden">
                    {{-- HEADER --}}
                    <div class="p-5 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Kendaraan Aktif</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $cars->count() }} kendaraan terdaftar
                        </p>
                    </div>

                    {{-- LIST (SCROLLABLE) --}}
                    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3">
                        @foreach ($cars as $car)
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-3">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    🚗
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $car->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $car->plate_number ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- FOOTER (STICKY BUTTON) --}}
                    <div class="p-4 border-t bg-white sticky bottom-0">
                        <a href="{{ route('admin.cars.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-3 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Kendaraan
                            <span>→</span>
                        </a>
                    </div>
                </div>


                {{-- Card: Sopir --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Sopir Aktif</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ ($drivers ?? collect())->count() }} sopir aktif saat ini
                            </p>
                        </div>

                        <a href="#"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Sopir
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="px-5 pb-5">
                        @if (($drivers ?? collect())->count() === 0)
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                                Belum ada sopir.
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($drivers->take(7) as $d)
                                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                                👨‍✈️
                                            </div>
                                            <div class="leading-tight">
                                                <div class="font-medium text-gray-900">
                                                    {{ $d->name ?? 'Sopir' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $d->phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $driverAvailable = ($d->status ?? 'available') === 'available';
                                        @endphp
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                        {{ $driverAvailable ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' }}">
                                            {{ $driverAvailable ? 'AVAILABLE' : strtoupper($d->status ?? 'ON DUTY') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            @if (($drivers ?? collect())->count() > 7)
                                <div class="mt-4 text-sm text-gray-500">
                                    +{{ ($drivers ?? collect())->count() - 7 }} sopir lainnya
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
