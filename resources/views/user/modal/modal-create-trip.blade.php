@foreach ($cars as $car)
    @php
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
            <div class="modal-content rounded-4 border-0 shadow">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <div class="w-100">
                        <h5 class="modal-title fw-semibold text-dark mb-1">Ajukan Peminjaman Kendaraan</h5>

                        {{-- Progress mini --}}
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: 33%;" data-step-progress>
                                </div>
                            </div>
                            <small class="text-muted" data-step-label>1/3</small>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('user.trips.store') }}" method="POST" data-trip-wizard>
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $car->id }}">

                    <div class="modal-body pt-3">

                        {{-- Alert gagal --}}
                        @if ($isOld && session('failed'))
                            <div class="alert alert-danger mb-3">
                                {{ session('failed') }}
                            </div>
                        @endif

                        {{-- Kendaraan (tetap tampil di semua step, tapi kecil) --}}
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Kendaraan</label>
                            <input type="text" class="form-control rounded-3"
                                value="{{ $car->name }} ({{ $car->plate_number ?? '-' }})" readonly>
                        </div>

                        {{-- STEP 1: Inti perjalanan --}}
                        <div class="wizard-step" data-step="1">
                            <div class="mb-3">
                                <label class="form-label mb-2">Jenis perjalanan</label>

                                <div class="btn-group w-100" role="group" aria-label="Jenis perjalanan">
                                    <input type="radio" class="btn-check" name="trip_type"
                                        id="tripRegular-{{ $car->id }}" value="regular"
                                        {{ old('trip_type', 'regular') === 'regular' ? 'checked' : '' }}>

                                    <label class="btn btn-outline-dark" for="tripRegular-{{ $car->id }}">
                                        Biasa
                                    </label>

                                    <input type="radio" class="btn-check" name="trip_type"
                                        id="tripSpecial-{{ $car->id }}" value="special"
                                        {{ old('trip_type') === 'special' ? 'checked' : '' }}>

                                    <label class="btn btn-outline-dark" for="tripSpecial-{{ $car->id }}">
                                        Khusus
                                    </label>
                                </div>

                                <div class="form-text">
                                    Biasa: ringkas. Khusus: wajib data dinas.
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <input type="text" name="destination" class="form-control rounded-3"
                                    placeholder="Contoh: Surabaya" value="{{ $oldDest }}" required>
                            </div>

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Mulai</label>
                                    <input type="datetime-local" name="start_at" class="form-control rounded-3"
                                        value="{{ $oldStart }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Selesai</label>
                                    <input type="datetime-local" name="end_at" class="form-control rounded-3"
                                        value="{{ $oldEnd }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: Pemohon --}}
                        <div class="wizard-step d-none" data-step="2">
                            <div class="mb-3">
                                <label class="form-label">Pengaju</label>
                                <input type="text" name="requester_name" class="form-control rounded-3"
                                    value="{{ $oldRequester }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="requester_position" class="form-control rounded-3"
                                    placeholder="Contoh: Pengelola Asrama"
                                    value="{{ $isOld ? old('requester_position') : '' }}" data-special>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Instansi / Pemakai</label>
                                <input type="text" name="organization_name" class="form-control rounded-3"
                                    placeholder="Contoh: BAPEKOM VI Surabaya"
                                    value="{{ $isOld ? old('organization_name') : '' }}" data-special>
                            </div>
                            {{-- nip --}}
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control rounded-3"
                                    placeholder="Contoh: 198001012010011001" value="{{ $isOld ? old('nip') : '' }}" data-special>
                            </div>
                        </div>

                        {{-- STEP 3: Detail --}}
                        <div class="wizard-step d-none" data-step="3">

                            <div class="mb-3">
                                <label class="form-label">Keperluan</label>
                                <textarea name="purpose" class="form-control rounded-3" rows="2"
                                    placeholder="Contoh: Dinas Kunjungan IPPU Jatim" data-special>{{ $isOld ? old('purpose') : '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah Peserta</label>
                                <input type="number" name="participant_count" min="1"
                                    class="form-control rounded-3" placeholder="Contoh: 25"
                                    value="{{ $isOld ? old('participant_count') : '' }}" data-special>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kebutuhan Sopir</label>
                                <select name="driver_required" class="form-select rounded-3" required>
                                    <option value="0" {{ (string) $oldDriverReq === '0' ? 'selected' : '' }}>
                                        Tanpa
                                        sopir</option>
                                    <option value="1" {{ (string) $oldDriverReq === '1' ? 'selected' : '' }}>
                                        Dengan sopir</option>
                                </select>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Catatan (opsional)</label>
                                <textarea name="notes" rows="2" class="form-control rounded-3" placeholder="Keterangan tambahan">{{ $oldNotes }}</textarea>
                            </div>
                        </div>




                        {{-- STEP 4: Review --}}
                        <div class="wizard-step d-none" data-step="4">
                            <div class="p-3 rounded-3 border bg-light">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Pengaju</span>
                                    <span class="fw-semibold" data-review="requester_name">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Tujuan</span>
                                    <span class="fw-semibold text-end ms-3" data-review="destination">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Waktu</span>
                                    <span class="fw-semibold text-end ms-3" data-review="time_range">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Sopir</span>
                                    <span class="fw-semibold" data-review="driver_required">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Catatan</span>
                                    <span class="fw-semibold text-end ms-3" data-review="notes">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Jabatan</span>
                                    <span class="fw-semibold text-end ms-3" data-review="requester_position">-</span>
                                </div>
                                {{-- nip --}}
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">NIP</span>
                                    <span class="fw-semibold text-end ms-3" data-review="nip">-</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Instansi</span>
                                    <span class="fw-semibold text-end ms-3" data-review="organization_name">-</span>
                                </div>

                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Keperluan</span>
                                    <span class="fw-semibold text-end ms-3" data-review="purpose">-</span>
                                </div>

                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Jumlah Peserta</span>
                                    <span class="fw-semibold" data-review="participant_count">-</span>
                                </div>

                            </div>

                            <small class="text-muted d-block mt-2">
                                Kalau sudah oke, gas ajukan. Kalau belum, klik “Kembali” 😌
                            </small>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer border-0 d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-3 flex-fill" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="button" class="btn btn-outline-dark rounded-3 flex-fill d-none" data-prev>
                            Kembali
                        </button>

                        <button type="button" class="btn btn-dark rounded-3 flex-fill" data-next>
                            Lanjut
                        </button>

                        <button type="submit" class="btn btn-dark rounded-3 flex-fill d-none" data-submit>
                            Ajukan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach

@if (old('car_id'))
    <script>
        (() => {
            const carId = @json(old('car_id'));
            const modalEl = document.getElementById(`modalCreateTrip-${carId}`);
            if (!modalEl) return;
            new bootstrap.Modal(modalEl).show();
        })();
    </script>
@endif
<script>
    (() => {
        const setMode = (form) => {
            const mode = form.querySelector('[name="trip_type"]:checked')?.value || 'regular';
            const specialEls = form.querySelectorAll('[data-special]');

            specialEls.forEach((el) => {
                const wrap = el.closest('.mb-3') || el.closest('.col') || el.parentElement;

                if (mode === 'special') {
                    // tampilkan + wajib
                    if (wrap) wrap.classList.remove('d-none');
                    el.required = true;
                } else {
                    // sembunyikan + tidak wajib + kosongkan nilai biar gak nyangkut
                    if (wrap) wrap.classList.add('d-none');
                    el.required = false;
                    if (el.tagName === 'SELECT' || el.tagName === 'TEXTAREA' || el.tagName ===
                        'INPUT') {
                        el.value = '';
                    }
                }
            });
        };

        document.querySelectorAll('form[data-trip-wizard]').forEach((form) => {
            // initial
            setMode(form);

            // on change
            form.querySelectorAll('[name="trip_type"]').forEach((r) => {
                r.addEventListener('change', () => setMode(form));
            });

            // kalau modal dibuka ulang, reset lagi
            const modal = form.closest('.modal');
            modal?.addEventListener('shown.bs.modal', () => setMode(form));
        });
    })();
</script>

<script>
    (() => {
        document.querySelectorAll('[data-trip-wizard]').forEach((form) => {
            const modal = form.closest('.modal');
            const steps = [...form.querySelectorAll('.wizard-step')];

            const btnPrev = form.querySelector('[data-prev]');
            const btnNext = form.querySelector('[data-next]');
            const btnSubmit = form.querySelector('[data-submit]');

            const progressBar = modal.querySelector('[data-step-progress]');
            const stepLabel = modal.querySelector('[data-step-label]');

            let step = 1;
            const total = 4;

            const input = (name) => form.querySelector(`[name="${name}"]`);

            const formatDT = (val) => {
                if (!val) return '-';
                const d = new Date(val);
                if (Number.isNaN(d.getTime())) return val;
                return d.toLocaleString('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'short'
                });
            };

            const setReview = () => {
                const requester = input('requester_name')?.value?.trim() || '-';
                const position = input('requester_position')?.value?.trim() || '-';
                const org = input('organization_name')?.value?.trim() || '-';
                const nip = input('nip')?.value?.trim() || '-';

                const dest = input('destination')?.value?.trim() || '-';
                const start = input('start_at')?.value || '';
                const end = input('end_at')?.value || '';

                const purpose = input('purpose')?.value?.trim() || '-';
                const count = input('participant_count')?.value || '-';

                const driverReq = input('driver_required')?.value === '1' ?
                    'Dengan sopir' :
                    'Tanpa sopir';

                const notes = input('notes')?.value?.trim() || '-';

                modal.querySelector('[data-review="requester_name"]').textContent = requester;
                modal.querySelector('[data-review="destination"]').textContent = dest;
                modal.querySelector('[data-review="time_range"]').textContent =
                    `${formatDT(start)} → ${formatDT(end)}`;
                modal.querySelector('[data-review="driver_required"]').textContent = driverReq;
                modal.querySelector('[data-review="notes"]').textContent = notes;

                // aman tanpa optional chaining assignment
                const elPos = modal.querySelector('[data-review="requester_position"]');
                if (elPos) elPos.textContent = position;

                const elOrg = modal.querySelector('[data-review="organization_name"]');
                if (elOrg) elOrg.textContent = org;

                const elNip = modal.querySelector('[data-review="nip"]');
                if (elNip) elNip.textContent = nip;

                const elPurpose = modal.querySelector('[data-review="purpose"]');
                if (elPurpose) elPurpose.textContent = purpose;

                const elCount = modal.querySelector('[data-review="participant_count"]');
                if (elCount) elCount.textContent = count;
            };


            const validateStep = () => {
                if (step === 1) {
                    const a = input('destination');
                    const s = input('start_at');
                    const e = input('end_at');

                    if (!a.value.trim()) {
                        a.focus();
                        return false;
                    }
                    if (!s.value) {
                        s.focus();
                        return false;
                    }
                    if (!e.value) {
                        e.focus();
                        return false;
                    }
                    if (new Date(e.value) <= new Date(s.value)) {
                        e.focus();
                        return false;
                    }
                }

                // Step 2 (Pemohon) sengaja ga wajib biar minimalis
                // Kalau mau wajib, baru ditambah validasi di sini.

                // ✅ driver_required ada di Step 3
                if (step === 3) {
                    const dr = input('driver_required');
                    if (!dr.value) {
                        dr.focus();
                        return false;
                    }
                }

                return true;
            };

            const render = () => {
                steps.forEach((el) => el.classList.add('d-none'));
                form.querySelector(`.wizard-step[data-step="${step}"]`)?.classList.remove('d-none');

                btnPrev.classList.toggle('d-none', step === 1);
                btnNext.classList.toggle('d-none', step === total);
                btnSubmit.classList.toggle('d-none', step !== total);

                const pct = (step / total) * 100;
                progressBar.style.width = `${pct}%`;
                stepLabel.textContent = `${step}/${total}`;

                // ✅ Review ada di Step 4
                if (step === 4) setReview();
            };

            btnNext?.addEventListener('click', () => {
                if (!validateStep()) return;
                step = Math.min(total, step + 1);
                render();
            });

            btnPrev?.addEventListener('click', () => {
                step = Math.max(1, step - 1);
                render();
            });

            modal?.addEventListener('show.bs.modal', () => {
                step = 1;
                render();
            });

            render();
        });
    })();
</script>
