@extends('driver.layouts.driver')

@section('title', 'Penugasan Driver - Bapekom VI Surabaya')

@section('breadcrumb-title', 'Driver')
@section('breadcrumb-subtitle', 'Penugasan')

@section('content')
    @php
        $now = now();
    @endphp

    <div class="space-y-6">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md space-y-4 sm:space-y-6 ">

            {{-- Header + Live Clock --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-gray-900">Penugasan Driver</h1>
                    <p class="text-sm text-gray-600 mt-1">Daftar penugasan aktif sesuai rentang waktu saat ini.</p>
                </div>

                <div class="w-full sm:w-auto">
                    <div
                        class="rounded-xl border bg-gray-50 px-4 py-3 flex items-center justify-between sm:justify-end gap-4">
                        <div class="text-xs text-gray-500">
                            Waktu sekarang <span class="font-semibold text-gray-700">(WIB)</span>
                        </div>

                        <div class="text-right leading-tight">
                            <div id="liveDate" class="text-sm font-semibold text-gray-900">
                                {{ $now->timezone('Asia/Jakarta')->format('d M Y') }}
                            </div>
                            <div id="liveTime" class="text-base sm:text-lg font-extrabold text-gray-900 tabular-nums">
                                {{ $now->timezone('Asia/Jakarta')->format('H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Grid Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
                @if ($trips->isEmpty())
                    {{-- Empty state span full --}}
                    <div
                        class="col-span-1 sm:col-span-2 xl:col-span-3 rounded-lg border border-dashed p-6 sm:p-10 text-center">
                        <div class="text-gray-900 font-semibold">Belum ada penugasan aktif</div>
                        <div class="text-sm text-gray-600 mt-1">
                            Kalau harusnya ada tapi kosong, cek driver_id, status, dan range waktu trip-nya.
                        </div>
                    </div>
                @else
                    @foreach ($trips as $trip)
                        @php
                            $isActiveNow = $now->between($trip->start_at, $trip->end_at);
                            $requester = $trip->requester_name ?: $trip->user->name ?? 'Peminjam';
                            $carName = $trip->car->name ?? ($trip->car->merk ?? 'Kendaraan');
                            $carPlate = $trip->car->plate_number ?? ($trip->car->plat_nomor ?? null);
                        @endphp

                        <div class="rounded-xl border bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                            {{-- Accent --}}
                            <div class="h-1 {{ $isActiveNow ? 'bg-green-500' : 'bg-blue-600' }}"></div>

                            <div class="p-4 sm:p-5 space-y-4">
                                {{-- Title + badge --}}
                                {{-- Title + badge --}}
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm text-gray-500">Tujuan</div>
                                        <div class="text-base sm:text-lg font-bold text-gray-900 truncate">
                                            {{ $trip->destination }}
                                        </div>
                                    </div>

                                    @if ($isActiveNow)
                                        <span
                                            class="inline-flex max-w-full sm:max-w-none items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 border border-green-200 break-words">
                                            Waktunya bertugas, {{ $trip->driver->name }}!
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-200">
                                            Approved
                                        </span>
                                    @endif
                                </div>


                                {{-- Meta --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-lg bg-gray-50 p-3">
                                        <div class="text-xs text-gray-500">Peminjam</div>
                                        <div class="font-semibold text-gray-900 truncate">
                                            {{ $requester }}
                                        </div>
                                    </div>

                                    <div class="rounded-lg bg-gray-50 p-3">
                                        <div class="text-xs text-gray-500">Kendaraan</div>
                                        <div class="font-semibold text-gray-900 truncate">
                                            {{ $carName }}
                                        </div>
                                        @if ($carPlate)
                                            <div class="text-xs text-gray-600 truncate">
                                                Plat: {{ $carPlate }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Time range (FIX UI Mobile) --}}
                                <div class="rounded-lg border p-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500">Mulai</div>
                                            <div class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($trip->start_at)->format('d M, H:i') }}
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Selesai</div>
                                            <div class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($trip->end_at)->format('d M, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- Notes --}}
                                @if (!empty($trip->notes))
                                    <div class="text-sm text-gray-700">
                                        <div class="text-xs text-gray-500">Catatan</div>
                                        <div class="line-clamp-2">{{ $trip->notes }}</div>
                                    </div>
                                @endif


                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            (function() {
                // WIB offset (UTC+7) tanpa tergantung timezone device user
                function getWibDate() {
                    const now = new Date();
                    const utcMs = now.getTime() + (now.getTimezoneOffset() * 60000);
                    return new Date(utcMs + (7 * 60 * 60 * 1000));
                }

                function pad(n) {
                    return String(n).padStart(2, '0');
                }

                const elDate = document.getElementById('liveDate');
                const elTime = document.getElementById('liveTime');

                if (!elDate || !elTime) return;

                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                function render() {
                    const d = getWibDate();

                    const day = pad(d.getDate());
                    const mon = months[d.getMonth()];
                    const year = d.getFullYear();

                    const hh = pad(d.getHours());
                    const mm = pad(d.getMinutes());
                    const ss = pad(d.getSeconds());

                    elDate.textContent = `${day} ${mon} ${year}`;
                    elTime.textContent = `${hh}:${mm}:${ss}`;
                }

                render();
                setInterval(render, 1000);
            })();
        </script>
    @endpush

@endsection
