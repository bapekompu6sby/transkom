@extends('admin.layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('breadcrumb-title', 'Admin')
@section('breadcrumb-subtitle', 'Manajemen Pengguna')

@section('content')
    <div class="">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 shadow-md">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-600 mt-1">Data Pengguna yang terdaftar</p>
            </div>
            <div class="overflow-x-auto mt-6">
                <table id="usersTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span
                                    class="text-xs font-semibold text-gray-700 uppercase tracking-wider">No.HP</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users ?? [] as $user)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600 text-sm"></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $user->email ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $user->number_phone ?? '-' }}</span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <p class="text-gray-500">Tidak ada data pengguna</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
