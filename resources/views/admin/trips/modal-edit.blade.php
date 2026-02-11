@foreach ($trips as $trip)
    @php
        // nilai awal driver_required (prioritas old kalau ada)
        $driverReqValue = old('driver_required', (int) ($trip->driver_required ?? 0)); // 0/1
        $driverIdValue = old('driver_id', $trip->driver_id); // bisa null
    @endphp

    <div class="modal fade" id="modalEditTrip-{{ $trip->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">
                        Edit Peminjaman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body pt-2 space-y-5">

                        {{-- SECTION: Informasi Utama --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900">Informasi Utama</p>
                                <span class="text-xs text-gray-500">ID: #{{ $trip->id }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Kendaraan --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Kendaraan</label>
                                    <input type="text" class="form-control rounded-lg" readonly
                                        value="{{ $trip->car->name ?? '-' }} ({{ $trip->car->plate_number ?? '-' }})">
                                    <input type="hidden" name="car_id" value="{{ $trip->car_id }}">
                                </div>

                                {{-- Pengaju --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Pengaju</label>
                                    <input type="text" name="requester_name" class="form-control rounded-lg"
                                        value="{{ old('requester_name', $trip->requester_name) }}"
                                        placeholder="Nama pengaju">
                                    <p class="text-xs text-gray-500 mt-1">Opsional.</p>
                                </div>
                            </div>

                            {{-- Tujuan (full) --}}
                            <div>
                                <label class="text-sm font-medium text-gray-700">Tujuan</label>
                                <input type="text" name="destination" class="form-control rounded-lg"
                                    value="{{ old('destination', $trip->destination) }}"
                                    placeholder="Contoh: Kegiatan dinas ke Surabaya" required>
                            </div>
                        </div>

                        {{-- SECTION: Sopir & Status --}}
                        <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-900">Sopir & Status</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Kebutuhan Sopir --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Kebutuhan Sopir</label>
                                    <select name="driver_required" class="form-select rounded-lg trip-driver-required"
                                        data-trip-id="{{ $trip->id }}" required>
                                        <option value="0" {{ (string) $driverReqValue === '0' ? 'selected' : '' }}>
                                            Tanpa sopir</option>
                                        <option value="1"
                                            {{ (string) $driverReqValue === '1' ? 'selected' : '' }}>Dengan sopir
                                        </option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Jika “Dengan sopir”, admin wajib memilih
                                        sopir.</p>
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Status</label>
                                    @php $statusValue = old('status', $trip->status ?? 'pending'); @endphp
                                    <select name="status" class="form-select rounded-lg" required>
                                        <option value="pending" {{ $statusValue === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $statusValue === 'approved' ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="cancelled" {{ $statusValue === 'cancelled' ? 'selected' : '' }}>
                                            Dibatalkan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Pilih Sopir (full) --}}
                            <div id="driverField-{{ $trip->id }}"
                                class="{{ (string) $driverReqValue === '1' ? '' : 'hidden' }}">
                                <label class="text-sm font-medium text-gray-700">Sopir</label>
                                <select name="driver_id" id="driverSelect-{{ $trip->id }}"
                                    class="form-select rounded-lg trip-driver-select"
                                    {{ (string) $driverReqValue === '1' ? 'required' : 'disabled' }}>
                                    <option value="" disabled {{ empty($driverIdValue) ? 'selected' : '' }}>
                                        -- Pilih sopir --
                                    </option>

                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ (string) $driverIdValue === (string) $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}
                                            {{ !empty($driver->phone_number) ? '(' . $driver->phone_number . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Akan nonaktif otomatis jika memilih “Tanpa sopir”.
                                </p>
                            </div>
                        </div>

                        {{-- SECTION: Jadwal --}}
                        <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-900">Jadwal</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Waktu Mulai</label>
                                    <input type="datetime-local" name="start_at" class="form-control rounded-lg"
                                        required
                                        value="{{ old('start_at', \Carbon\Carbon::parse($trip->start_at)->format('Y-m-d\TH:i')) }}">
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700">Waktu Selesai</label>
                                    <input type="datetime-local" name="end_at" class="form-control rounded-lg" required
                                        value="{{ old('end_at', \Carbon\Carbon::parse($trip->end_at)->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>
                        {{-- SECTION: Surat Khusus (Opsional) --}}
                        <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-900">Surat Khusus (Opsional)</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Jabatan Pemohon</label>
                                    <input type="text" name="requester_position" class="form-control rounded-lg"
                                        value="{{ old('requester_position', $trip->requester_position) }}"
                                        placeholder="Contoh: Pengelola Asrama">
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700">Instansi / Pemakai</label>
                                    <input type="text" name="organization_name" class="form-control rounded-lg"
                                        value="{{ old('organization_name', $trip->organization_name) }}"
                                        placeholder="Contoh: IPPU JATIM">
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Keperluan</label>
                                <textarea name="purpose" rows="2" class="form-control rounded-lg"
                                    placeholder="Contoh: Dinas Kunjungan IPPU Jatim">{{ old('purpose', $trip->purpose) }}</textarea>
                            </div>

                            <div class="md:w-1/2">
                                <label class="text-sm font-medium text-gray-700">Jumlah Peserta</label>
                                <input type="number" min="1" name="participant_count"
                                    class="form-control rounded-lg"
                                    value="{{ old('participant_count', $trip->participant_count) }}"
                                    placeholder="Contoh: 25">
                            </div>

                            <p class="text-xs text-gray-500">
                                Isi bagian ini hanya jika perlu untuk keperluan “Surat Khusus”.
                            </p>
                        </div>


                        {{-- SECTION: Catatan --}}
                        <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-4">
                            <p class="text-sm font-semibold text-gray-900">Catatan</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                                    <textarea name="notes" rows="3" class="form-control rounded-lg" placeholder="Catatan tambahan...">{{ old('notes', $trip->notes) }}</textarea>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700">Catatan Pembatalan
                                        (opsional)</label>
                                    <textarea name="notes_cancel" rows="3" class="form-control rounded-lg" placeholder="Alasan pembatalan...">{{ old('notes_cancel', $trip->notes_cancel) }}</textarea>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500">
                                Tips: isi “Catatan Pembatalan” hanya jika status = Dibatalkan.
                            </p>
                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="modal-footer border-0 flex gap-2">
                        <button type="button" class="btn btn-light rounded-lg flex-1 py-2.5"
                            data-bs-dismiss="modal">
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

{{-- Script: show/hide driver field per modal --}}
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.trip-driver-required').forEach(function(select) {
                const tripId = select.dataset.tripId;
                const field = document.getElementById('driverField-' + tripId);
                const driver = document.getElementById('driverSelect-' + tripId);

                function sync() {
                    const needDriver = select.value === '1';

                    if (needDriver) {
                        field.classList.remove('hidden');
                        driver.disabled = false;
                        driver.setAttribute('required', 'required');
                    } else {
                        field.classList.add('hidden');
                        driver.disabled = true;
                        driver.removeAttribute('required');
                        driver.value = ''; // reset supaya tidak ikut terkirim
                    }
                }

                sync();
                select.addEventListener('change', sync);
            });
        });
    </script>
@endpush
