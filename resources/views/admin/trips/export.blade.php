@extends('admin.layouts.admin')

@section('title', 'Manajemen Peminjaman')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Peminjaman')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Eksport Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Eksport daftar pengajuan peminjaman kendaraan</p>
                </div>
            </div>

            <div class="mt-6 border-t pt-6">
                <form action="{{ route('admin.trips.export.rekap') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    @csrf
                    {{-- Bulan --}}
                    <div class="md:col-span-4">
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select id="bulan" name="bulan"
                            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                                $selectedMonth = (int) request('bulan', now()->month);
                            @endphp

                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" @selected($selectedMonth === $num)>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div class="md:col-span-3">
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        @php
                            $selectedYear = (int) request('tahun', now()->year);
                            $minYear = now()->year - 5;
                            $maxYear = now()->year + 1;
                        @endphp
                        <select id="tahun" name="tahun"
                            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            @for ($y = $maxYear; $y >= $minYear; $y--)
                                <option value="{{ $y }}" @selected($selectedYear === $y)>{{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-md bg-black px-4 py-2 text-white text-sm font-semibold shadow hover:bg-gray-800">
                            Eksport </button>

                        <a href="{{ url()->current() }}"
                            class="w-full inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-gray-700 text-sm font-semibold hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="mt-4 text-xs text-gray-500">
                    Hasil eksport akan menampilkan data peminjaman sesuai bulan & tahun yang dipilih.
                </div>
            </div>
        </div>
    </div>
@endsection
