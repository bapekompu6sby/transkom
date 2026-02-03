<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Car;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(): View
    {
        $cars = Car::query()->orderByDesc('id')->get();
        return view('admin.cars.index', compact('cars'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'year'         => ['required', 'integer', 'min:1900', 'max:2100'],
            'color'        => ['nullable', 'string', 'max:50'],
            'plate_number' => ['required', 'string', 'max:20'],
            'nup'          => ['nullable', 'string', 'max:64'],
            'status'       => ['required', 'in:available,notavailable'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['plate_number'] = strtoupper(trim($data['plate_number']));
        $data['nup'] = $data['nup'] ? trim($data['nup']) : null;

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('car', 'public');
        }

        unset($data['image']);

        try {
            Car::create($data);

            return redirect()
                ->route('admin.cars.index')
                ->with('success', 'Mobil berhasil ditambahkan.');
        } catch (Throwable $e) {
            report($e);

            if (!empty($data['image_url'])) {
                Storage::disk('public')->delete($data['image_url']);
            }

            return redirect()
                ->route('admin.cars.index')
                ->withInput()
                ->with('failed', 'Gagal menambahkan mobil. Silakan coba lagi.');
        }
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        // Normalisasi input sebelum validate
        $request->merge([
            'plate_number' => strtoupper(trim((string) $request->input('plate_number', ''))),
            'nup' => $request->filled('nup') ? trim((string) $request->input('nup')) : null,
        ]);

        $data = $request->validate(
            [
                'name'         => ['required', 'string', 'max:100'],
                'year'         => ['required', 'integer', 'min:1900', 'max:2100'],
                'color'        => ['nullable', 'string', 'max:50'],

                'plate_number' => ['required', 'string', 'max:20',],

                'nup'          => ['nullable', 'string', 'max:64'],
                'status'       => ['required', 'in:available,notavailable'],
                'description'  => ['nullable', 'string'],

                'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            [
                'plate_number.unique' => 'Plat nomor sudah terdaftar. Gunakan plat yang berbeda.',
                'image.image'         => 'File harus berupa gambar.',
                'image.mimes'         => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                'image.max'           => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $oldImagePath = $car->image_url;
        $newImagePath = null;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $request->file('image')->store('car', 'public');
                $data['image_url'] = $newImagePath;
            }

            unset($data['image']); // jangan simpan UploadedFile ke DB

            $car->update($data);

            // Hapus file lama kalau ada file baru
            if ($newImagePath && $oldImagePath && $oldImagePath !== $newImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

            return redirect()
                ->route('admin.cars.index')
                ->with('success', 'Mobil berhasil diperbarui.');
        } catch (Throwable $e) {
            report($e);

            // rollback file baru kalau DB gagal update
            if ($newImagePath && $newImagePath !== $oldImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            return redirect()
                ->route('admin.cars.index')
                ->withInput()
                ->with('failed', 'Gagal memperbarui mobil. Silakan coba lagi.');
        }
    }


    public function destroy(Car $car): RedirectResponse
    {
        try {
            $car->delete();

            return redirect()
                ->route('admin.cars.index')
                ->with('success', 'Mobil berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.cars.index')
                ->with('failed', 'Gagal menghapus mobil. Silakan coba lagi.');
        }
    }
}
