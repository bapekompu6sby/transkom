@foreach ($cars as $car)
    @php
        // kunci old() biar hanya modal mobil yang terakhir submit yang terisi
        $isOld = (string) old('car_id') === (string) $car->id;

        $oldRequester = $isOld ? old('requester_name', auth()->user()->name) : auth()->user()->name;
        $oldDriverReq = $isOld ? old('driver_required', '0') : '0';
        $oldDest = $isOld ? old('destination', '') : '';
        $oldStart = $isOld ? old('start_at', '') : '';
        $oldEnd = $isOld ? old('end_at', '') : '';
        $oldNotes = $isOld ? old('notes', '') : '';
    @endphp

    <div class="modal fade" id="modalCreateTrip-{{ $car->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">
                        Ajukan Peminjaman Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <form action="{{ route('user.trips.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="car_id" value="{{ $car->id }}">

                    <div class="modal-body pt-2 space-y-4">

                        {{-- Alert gagal (hanya muncul di modal yang terakhir submit) --}}
                        @if ($isOld && session('failed'))
                            <div class="alert alert-danger mb-0">
                                {{ session('failed') }}
                            </div>
                        @endif

                        {{-- Kendaraan --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Kendaraan</label>
                            <input type="text" class="form-control rounded-lg"
                                value="{{ $car->name }} ({{ $car->plate_number ?? '-' }})" readonly>
                        </div>

                        {{-- Pengaju --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Pengaju</label>
                            <input type="text" name="requester_name" class="form-control rounded-lg"
                                value="{{ $oldRequester }}">
                        </div>

                        {{-- Kebutuhan Sopir --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Kebutuhan Sopir</label>
                            <select name="driver_required" class="form-select rounded-lg" required>
                                <option value="0" {{ (string) $oldDriverReq === '0' ? 'selected' : '' }}>
                                    Tanpa sopir
                                </option>
                                <option value="1" {{ (string) $oldDriverReq === '1' ? 'selected' : '' }}>
                                    Dengan sopir (dipilihkan admin)
                                </option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Jika memilih “Dengan sopir”, penentuan sopir dilakukan oleh admin.
                            </p>
                        </div>

                        {{-- Tujuan --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Tujuan</label>
                            <input type="text" name="destination" class="form-control rounded-lg"
                                placeholder="Contoh: Kegiatan dinas ke Surabaya" value="{{ $oldDest }}" required>
                        </div>

                        {{-- Waktu --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Waktu Mulai</label>
                            <input type="datetime-local" name="start_at" class="form-control rounded-lg"
                                value="{{ $oldStart }}" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Waktu Selesai</label>
                            <input type="datetime-local" name="end_at" class="form-control rounded-lg"
                                value="{{ $oldEnd }}" required>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="form-control rounded-lg"
                                placeholder="Keterangan tambahan bila diperlukan">{{ $oldNotes }}</textarea>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer border-0 flex gap-2">
                        <button type="button" class="btn btn-light rounded-lg flex-1" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-dark rounded-lg flex-1">
                            Ajukan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Auto-open modal yang terakhir submit kalau ada old(car_id) --}}
@if (old('car_id'))
    <script>
        (() => {
            const carId = @json(old('car_id'));
            const modalEl = document.getElementById(`modalCreateTrip-${carId}`);
            if (!modalEl) return;

            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        })();
    </script>
@endif
