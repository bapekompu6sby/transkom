@extends('admin.layouts.admin')

@section('title', 'Manajemen Kendaraan')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Kendaraan')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Manajemen Kendaraan</h1>
                    <p class="text-sm text-gray-600 mt-1">Data Kendaraan yang terdaftar</p>
                </div>

                <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreateCar"
                    class="inline-flex items-center gap-2 rounded-lg bg-black px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800 transition">
                    Buat
                    <span class="text-lg leading-none">+</span>
                </button>
            </div>


            <div class="overflow-x-auto mt-6">
                <table id="carsTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Plat</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tahun</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($cars as $car)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $car->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $car->plate_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $car->year }}</td>
                                @php
                                    $status = $car->status ?? 'notavailable';

                                    $statusLabel = match ($status) {
                                        'available' => 'Tersedia',
                                        'notavailable' => 'Tidak Tersedia',
                                        default => '—',
                                    };

                                    $statusClass = match ($status) {
                                        'available' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'notavailable' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                    };
                                @endphp

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalEditCar-{{ $car->id }}"
                                            class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>

                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteCar-{{ $car->id }}"
                                            class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            @include('admin.cars.modal-create')
            @include('admin.cars.modal-edit')
            @include('admin.cars.modal-delete')

        </div>
    </div>

@endsection
