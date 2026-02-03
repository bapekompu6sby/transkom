<div class="modal fade" id="modalCreateCar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-lg font-semibold text-gray-900">Menambahkan Mobil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body pt-2 space-y-4">


                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Mobil</label>
                        <input type="text" name="name" class="form-control rounded-lg"
                            placeholder="Contoh: Toyota Avanza" value="{{ old('name') }}" required>
                    </div>

                    <!-- Upload Gambar -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Foto Mobil (opsional)</label>

                        <input type="file" name="image" accept="image/*" class="form-control rounded-lg"
                            id="imageInput">

                        <p class="text-xs text-gray-500 mt-1">
                            Format: JPG, PNG, WEBP. Maks 2MB.
                        </p>

                        <!-- Preview -->
                        <img id="imagePreview" class="hidden mt-2 w-28 h-20 object-cover rounded-lg border bg-gray-50"
                            alt="Preview gambar">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" name="year" class="form-control rounded-lg" placeholder="2024"
                                value="{{ old('year') }}" required min="1900" max="2100">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Warna (opsional)</label>
                            <input type="text" name="color" class="form-control rounded-lg"
                                placeholder="Hitam / Putih / Silver" value="{{ old('color') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Plat Nomor</label>
                            <input type="text" name="plate_number" class="form-control rounded-lg"
                                placeholder="L 1234 AB" value="{{ old('plate_number') }}" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">NUP (opsional)</label>
                            <input type="text" name="nup" class="form-control rounded-lg"
                                placeholder="Nomor Unit Pemerintah" value="{{ old('nup') }}">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-select rounded-lg" required>
                            @php $v = old('status', $car->status ?? '-'); @endphp
                            <option value="available" {{ $v === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="notavailable" {{ $v === 'notavailable' ? 'selected' : '' }}>Tidak Tersedia
                            </option>
                        </select>
                    </div>


                    <div>
                        <label class="text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                        <textarea name="description" rows="2" class="form-control rounded-lg"
                            placeholder="Catatan/deskripsi kendaraan...">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div class="modal-footer border-0 flex gap-2">
                    <button type="button" class="btn btn-light rounded-lg flex-1 py-2.5" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-dark rounded-lg flex-1 py-2.5">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script>
        (() => {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            const modal = document.getElementById('modalCreateCar');

            if (!input || !preview || !modal) return;

            input.addEventListener('change', () => {
                const file = input.files[0];

                if (!file || !file.type.startsWith('image/')) {
                    preview.src = '';
                    preview.classList.add('hidden');
                    return;
                }

                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            });

            // reset preview saat modal ditutup
            modal.addEventListener('hidden.bs.modal', () => {
                preview.src = '';
                preview.classList.add('hidden');
                input.value = '';
            });
        })();
    </script>


</div>
