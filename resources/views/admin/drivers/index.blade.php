@extends('admin.layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Pengguna')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <?php
            echo '<pre>';
            print_r($cars->toArray());
            echo '</pre>';
            ?>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-600 mt-1">Data Pengguna yang terdaftar</p>
            </div>
            <div class="overflow-x-auto mt-6">
                <table id="carsTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
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
                                {{-- <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $car->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $car->plate_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $car->year }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $car->status }}</td> --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fas fa-eye text-lg"></i>
                                        </button>
                                        <button class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fas fa-edit text-lg"></i>
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
        </div>
    </div>

@endsection
