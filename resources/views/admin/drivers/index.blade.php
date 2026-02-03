@extends('admin.layouts.admin')

@section('title', 'Manajemen Sopir')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Sopir')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Manajemen Sopir</h1>
                    <p class="text-sm text-gray-600 mt-1">Data Sopir yang terdaftar</p>
                </div>

                <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreateDriver"
                    class="inline-flex items-center gap-2 rounded-lg bg-black px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800 transition">
                    Buat
                    <span class="text-lg leading-none">+</span>
                </button>
            </div>


            <div class="overflow-x-auto mt-6">
                <table id="driversTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No.HP/WA</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</span>
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
                        @forelse($drivers as $driver)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $driver->name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $driver->phone_number ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $driver->email ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $isActive = ($driver->status ?? 'inactive') === 'active';
                                    @endphp

                                    <div class="flex items-center gap-3 js-toggle-driver cursor-pointer"
                                        data-driver-id="{{ $driver->id }}">

                                        {{-- Toggle --}}
                                        <div
                                            class="relative w-[64px] h-[32px] rounded-full transition
            {{ $isActive ? 'bg-green-500' : 'bg-gray-200' }}">

                                            {{-- Knob --}}
                                            <div
                                                class="absolute top-1 left-1 h-6 w-6 rounded-full bg-white shadow
                flex items-center justify-center text-xs font-bold transition
                {{ $isActive ? 'translate-x-8 text-green-600' : 'text-red-500' }}">
                                                {{ $isActive ? '✓' : '✕' }}
                                            </div>
                                        </div>

                                        {{-- Label --}}
                                        <span
                                            class="text-sm font-medium {{ $isActive ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- Edit -> Modal --}}
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalEditDriver-{{ $driver->id }}"
                                            class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        {{-- Delete -> Modal --}}
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteDriver-{{ $driver->id }}"
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
            @include('admin.drivers.modal-create')
            @include('admin.drivers.modal-edit')
            @include('admin.drivers.modal-delete')
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const getCsrf = () =>
                    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    @json(csrf_token());

                const CSRF = getCsrf();

                document.querySelectorAll('.js-toggle-driver').forEach((el) => {
                    el.addEventListener('click', async () => {
                        const driverId = el.dataset.driverId;
                        if (!driverId) return;

                        try {
                            const res = await fetch(`/admin/drivers/toggle-status/${driverId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': CSRF,
                                    'Accept': 'application/json',
                                },
                            });

                            if (!res.ok) throw new Error('Request gagal');
                            const data = await res.json();

                            const isActive = data.status === 'active';

                            const toggle = el.querySelector('.relative');
                            const knob = toggle.querySelector('div');
                            const label = el.querySelector('span');

                            toggle.classList.toggle('bg-green-500', isActive);
                            toggle.classList.toggle('bg-gray-200', !isActive);

                            knob.classList.toggle('translate-x-8', isActive);
                            knob.classList.toggle('text-green-600', isActive);
                            knob.classList.toggle('text-red-500', !isActive);
                            knob.textContent = isActive ? '✓' : '✕';

                            label.textContent = isActive ? 'Aktif' : 'Nonaktif';
                            label.classList.toggle('text-green-600', isActive);
                            label.classList.toggle('text-gray-600', !isActive);

                        } catch (err) {
                            alert('Gagal mengubah status sopir');
                            console.error(err);
                        }
                    });
                });
            });
        </script>


    </div>
@endsection
