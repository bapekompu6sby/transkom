<div class="modal fade" id="modalCreateDriver" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-lg font-semibold text-gray-900">
                    Menambahkan Driver
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <form action="#" method="POST">
                @csrf

                <div class="modal-body pt-2 space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Driver</label>
                        <input type="text" name="name" class="form-control rounded-lg" placeholder="Nama lengkap"
                            required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="form-control rounded-lg"
                            placeholder="email@example.com" required>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" name="phone_number" class="form-control rounded-lg"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-select rounded-lg" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="form-control rounded-lg" placeholder="Catatan tambahan..."></textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 flex gap-2">
                    <button type="button" class="btn btn-light rounded-lg flex-1" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-dark rounded-lg flex-1">
                        Simpan
                    </button>
                </div>


            </form>
        </div>
    </div>
</div>
