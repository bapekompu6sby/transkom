@foreach ($cars as $car)
    <div class="modal fade" id="modalEditCar-{{ $car->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">Edit Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body pt-2 space-y-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Mobil</label>
                            <input type="text" name="name" class="form-control rounded-lg"
                                value="{{ old('name', $car->name) }}" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Foto Mobil</label>

                            <div class="mt-2 space-y-3">

                                {{-- Gambar lama --}}
                                @if ($car->image_url)
                                    <div class="rounded-lg border bg-gray-50 p-2">
                                        <p class="text-xs font-medium text-gray-600 mb-2">Foto saat ini</p>

                                        <div class="w-full overflow-hidden rounded-lg border bg-white">
                                            <img src="{{ asset('storage/' . $car->image_url) }}"
                                                alt="Foto {{ $car->name }}"
                                                class="block w-full max-w-full h-auto object-contain"
                                                style="max-height: 240px;">
                                        </div>
                                    </div>
                                @endif
                                <div class="rounded-lg border bg-gray-50 p-2">
                                    <p class="text-xs font-medium text-gray-600 mb-2">Foto saat ini</p>
                                    {{-- Preview gambar baru --}}
                                    <img class="image-preview hidden w-full max-w-full h-auto object-contain rounded-lg border mt-2"
                                        style="max-height: 240px;" alt="Preview gambar baru" />

                                    <input type="file" name="image"
                                        class="form-control rounded-lg mt-2 image-input"
                                        accept="image/png,image/jpeg,image/jpg,image/webp" />
                                </div>

                                <p class="text-xs text-gray-500">
                                    Format: JPG/JPEG/PNG/WEBP. Maks 2MB.
                                </p>
                            </div>
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Tahun</label>
                                <input type="number" name="year" class="form-control rounded-lg"
                                    value="{{ old('year', $car->year) }}" required min="1900" max="2100">
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Warna (opsional)</label>
                                <input type="text" name="color" class="form-control rounded-lg"
                                    value="{{ old('color', $car->color) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Plat Nomor</label>
                                <input type="text" name="plate_number" class="form-control rounded-lg"
                                    value="{{ old('plate_number', $car->plate_number) }}" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">NUP (opsional)</label>
                                <input type="text" name="nup" class="form-control rounded-lg"
                                    value="{{ old('nup', $car->nup) }}">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="form-select rounded-lg" required>
                                @php $v = old('status', $car->status ?? '-'); @endphp
                                <option value="available" {{ $v === 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="notavailable" {{ $v === 'notavailable' ? 'selected' : '' }}>Tidak
                                    Tersedia</option>
                            </select>
                        </div>


                        <div>
                            <label class="text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                            <textarea name="description" rows="2" class="form-control rounded-lg"
                                placeholder="Catatan/deskripsi kendaraan...">{{ old('description', $car->description) }}</textarea>
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
                document.querySelectorAll('.modal').forEach((modal) => {
                    const input = modal.querySelector('.image-input');
                    const preview = modal.querySelector('.image-preview');

                    if (!input || !preview) return;

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
                });
            })
            ();
        </script>

    </div>
@endforeach
