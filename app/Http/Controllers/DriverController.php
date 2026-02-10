<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:255'],
            'status'       => ['required', 'in:active,inactive'],
            'notes'        => ['nullable', 'string'],
        ]);

        $data['phone_number'] = isset($data['phone_number'])
            ? preg_replace('/\s+/', '', $data['phone_number'])
            : null;

        // Tambahkan password default
        $data['password'] = Hash::make('driverbapekom6sby');

        Driver::create($data);

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', 'Driver berhasil ditambahkan.');
    }


    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:255'],
            'status'       => ['required', 'in:active,inactive'],
            'notes'        => ['nullable', 'string'],
        ]);

        $data['phone_number'] = isset($data['phone_number'])
            ? preg_replace('/\s+/', '', $data['phone_number'])
            : null;

        $data['password'] = Hash::make('driverbapekom6sby');

        $driver->update($data);

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $driver->delete();

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', 'Driver berhasil dihapus.');
    }

    public function toggleStatus(Driver $driver)
    {
        $driver->status = $driver->status === 'active' ? 'inactive' : 'active';
        $driver->save();

        return response()->json([
            'success' => true,
            'status' => $driver->status,
        ]);
    }
}
