@extends('admin.layouts.admin')

@section('title', 'Manajemen Peminjaman')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Peminjaman')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Manajemen Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Daftar pengajuan peminjaman kendaraan</p>
                </div>

            </div>

            <div class="overflow-x-auto mt-6">
                <table id="tripsTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Kendaraan</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Pengaju</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tujuan</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Sopir</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</span>
                            </th>
                            {{-- eksport --}}
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Eksport</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($trips as $trip)
                            @php
                                // Status badge
                                $status = strtolower($trip->status ?? 'pending');

                                $badgeClass = match ($status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled', 'canceled' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700', // pending
                                };

                                $statusLabel = match ($status) {
                                    'approved' => 'Disetujui',
                                    'cancelled', 'canceled' => 'Dibatalkan',
                                    default => 'Diproses', // pending
                                };

                                // Sopir required badge
                                $needDriver = (int) ($trip->driver_required ?? 0) === 1;
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                {{-- Kendaraan --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $trip->car->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $trip->car->plate_number ?? '-' }}
                                    </div>
                                </td>

                                {{-- Pengaju --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $trip->requester_name ?? ($trip->user->name ?? '-') }}
                                </td>

                                {{-- Tujuan --}}
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $trip->destination ?? '-' }}
                                </td>

                                {{-- Periode --}}
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    <div class="font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($trip->start_at)->format('d M Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        s/d {{ \Carbon\Carbon::parse($trip->end_at)->format('d M Y H:i') }}
                                    </div>
                                </td>

                                {{-- Sopir --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $needDriver ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $needDriver ? 'Dengan Sopir' : 'Tanpa Sopir' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- Edit/Detail --}}
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalEditTrip-{{ $trip->id }}"
                                            class="text-gray-400 hover:text-gray-600 transition" title="Edit / Detail">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        {{-- Delete --}}
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteTrip-{{ $trip->id }}"
                                            class="text-gray-400 hover:text-gray-600 transition" title="Hapus">
                                            <i class="fas fa-trash-alt text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                                {{-- Eksport --}}
                                <td class="px-6 py-4">
                                    @php $canExport = $trip->status === 'approved'; @endphp

                                    <div class="flex gap-2">
                                        <a href="{{ $canExport ? route('admin.trips.export.basic', $trip->id) : '#' }}"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold
                   {{ $canExport ? 'bg-gray-900 text-white hover:bg-gray-800' : 'bg-gray-200 text-gray-500 cursor-not-allowed pointer-events-none' }}">
                                        Biasa
                                        </a>

                                        <a href="{{ $canExport ? route('admin.trips.export.special', $trip->id) : '#' }}"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold
                   {{ $canExport ? 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed pointer-events-none' }}">
                                            Khusus
                                        </a>
                                    </div>

                                    @if (!$canExport)
                                        <div class="text-[11px] text-gray-500 mt-1">Export aktif setelah disetujui.</div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- modal edit/delete --}}
            @include('admin.trips.modal-edit')
            @include('admin.trips.modal-delete')
        </div>
    </div>
@endsection
