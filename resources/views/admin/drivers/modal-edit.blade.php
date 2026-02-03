@foreach ($drivers as $driver)
    <div class="modal fade" id="modalEditDriver-{{ $driver->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">
                        Edit Driver
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body pt-2 space-y-4">

                        {{-- Nama --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Driver</label>
                            <input type="text" name="name" class="form-control rounded-lg"
                                placeholder="Nama lengkap" value="{{ old('name', $driver->name) }}" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" class="form-control rounded-lg"
                                placeholder="email@example.com" value="{{ old('email', $driver->email) }}" required>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">No. HP</label>
                            <input type="text" name="phone_number" class="form-control rounded-lg"
                                placeholder="08xxxxxxxxxx" value="{{ old('phone_number', $driver->phone_number) }}">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            @php
                                $statusValue = old('status', $driver->status ?? 'inactive');
                            @endphp
                            <select name="status" class="form-select rounded-lg" required>
                                <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="form-control rounded-lg" placeholder="Catatan tambahan...">{{ old('notes', $driver->notes) }}</textarea>
                        </div>

                    </div>

                    {{-- Footer (full width 2 tombol) --}}
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
    </div>
@endforeach
