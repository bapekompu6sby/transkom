@foreach ($trips as $trip)
    <div class="modal fade" id="modalDeleteTrip-{{ $trip->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">
                        Hapus trip
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body pt-2">
                    <p class="text-sm text-gray-700">
                        Apakah yakin ingin menghapus trip ini?
                    </p>

                    {{-- (Opsional) tampilkan nama biar user yakin --}}
                    <p class="mt-2 text-sm font-medium text-gray-900">
                        tujuan: {{ $trip->destination }}
                    </p>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 flex gap-2">
                    <button type="button" class="btn btn-light rounded-lg flex-1 py-2.5" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark rounded-lg w-full py-2.5">
                            Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endforeach
