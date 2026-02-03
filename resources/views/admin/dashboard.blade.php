@extends('admin.layouts.admin')

@section('title', 'Monitoring Kendaraan Bapekom VI Surabaya')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Dashboard')

@section('content')
    <div class="space-y-4">

        {{-- ROW 1: Jadwal (kiri) | Kendaraan + Sopir (kanan) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

            {{-- KIRI: Jadwal Peminjaman (2 kolom) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Jadwal Peminjaman</h2>
                            <p class="text-sm text-gray-500 mt-1">Peminjaman kendaraan mendatang</p>
                        </div>

                        <a href="{{ route('admin.trips.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Peminjaman
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="px-5 pb-5">
                        @if (($trips ?? collect())->count() === 0)
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
                                        @foreach ($trips as $b)
                                            <tr class="border-b last:border-0">
                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($b->start_at)->format('d M Y H:i') }}
                                                    <span class="text-gray-400">-</span>
                                                    {{ \Carbon\Carbon::parse($b->end_at)->format('H:i') }}
                                                </td>

                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    {{ $b->car->name ?? '-' }}
                                                    <span class="text-gray-400">•</span>
                                                    <span class="text-gray-500">{{ $b->car->plate_number ?? '' }}</span>
                                                </td>

                                                <td class="py-3 pr-4 whitespace-nowrap">
                                                    @if ((int) ($b->driver_required ?? 0) === 0)
                                                        — (tanpa sopir)
                                                    @else
                                                        {{ $b->driver->name ?? 'Menunggu sopir' }}
                                                    @endif
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

                                                        $label = match ($status) {
                                                            'approved' => 'DISETUJUI',
                                                            'rejected' => 'DITOLAK',
                                                            'ongoing' => 'BERJALAN',
                                                            'done' => 'SELESAI',
                                                            default => 'MENUNGGU',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                                        {{ $label }}
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
            </div>

            {{-- KANAN: Kendaraan Aktif (atas) + Sopir Aktif (bawah) --}}
            <div class="space-y-3">

                {{-- Kendaraan Aktif --}}
                <div class="bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Kendaraan Aktif</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $cars->count() }} kendaraan terdaftar</p>
                    </div>

                    <div class="px-4 py-4 space-y-3 max-h-[260px] overflow-y-auto">
                        @foreach ($cars as $car)
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-3">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    🚗
                                </div>
                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $car->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $car->plate_number ?? '-' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 border-t bg-white">
                        <a href="{{ route('admin.cars.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Kendaraan <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                {{-- Sopir Aktif --}}
                <div class="bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Sopir Aktif</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ ($drivers ?? collect())->count() }} sopir aktif saat ini
                        </p>
                    </div>

                    <div class="px-4 py-4 space-y-3 max-h-[260px] overflow-y-auto">
                        @if (($drivers ?? collect())->count() === 0)
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                                Belum ada sopir.
                            </div>
                        @else
                            @foreach ($drivers as $d)
                                <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                            👨‍✈️
                                        </div>
                                        <div class="leading-tight min-w-0">
                                            <div class="font-medium text-gray-900 truncate">
                                                {{ $d->name ?? 'Sopir' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $d->phone_number ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="p-3 border-t bg-white">
                        <a href="{{ route('admin.drivers.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Manajemen Sopir <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- ROW 2: Pivot full width --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 pt-5 pb-3 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Rekap Peminjaman Kendaraan per Bulan
                        (<span id="pivot-year-label">{{ $pivotYear }}</span>)
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Urut berdasarkan total peminjaman tertinggi</p>
                </div>

                <select id="pivot-year" class="form-select form-select-sm" style="width: 120px;">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" {{ $y == $pivotYear ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="m-3 border border-gray-200 rounded-xl overflow-hidden">
                <div id="pivot-table-container">
                    @include('admin.layouts.pivot-cars', [
                        'rekapKendaraan' => $rekapKendaraan,
                        'pivotYear' => $pivotYear,
                    ])
                </div>
            </div>
        </div>

        {{-- AJAX Pivot --}}
        <script>
            $(document).ready(function() {
                $('#pivot-year').on('change', function() {
                    let year = $(this).val();
                    $('#pivot-year-label').text(year);

                    $.ajax({
                        url: "{{ route('admin.dashboard') }}",
                        type: "GET",
                        data: {
                            pivot_year: year
                        },
                        beforeSend: function() {
                            $('#pivot-table-container').html(`
                                <div class="text-center py-8 text-gray-500">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="spinner-border text-warning" role="status"></span>
                                        <span>Memuat data...</span>
                                    </div>
                                </div>
                            `);
                        },
                        success: function(res) {
                            if (res.html) {
                                $('#pivot-table-container').html(res.html);
                            } else {
                                $('#pivot-table-container').html(`
                                    <div class="text-center py-8 text-red-600">
                                        Format data tidak sesuai (cek console)
                                    </div>
                                `);
                                console.log(res);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            $('#pivot-table-container').html(`
                                <div class="text-center py-8 text-red-600">
                                    Gagal memuat data (${xhr.status})
                                </div>
                            `);
                        }
                    });
                });
            });
        </script>

    </div>
@endsection
