@foreach ($cars as $car)
    <div class="modal fade" id="modalDeleteCar-{{ $car->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-lg font-semibold text-gray-900">Hapus Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-2">
                    <p class="text-sm text-gray-700">Apakah yakin ingin menghapus mobil ini?</p>
                    <p class="mt-2 text-sm font-medium text-gray-900">
                        {{ $car->name }} — {{ $car->plate_number }}
                    </p>
                </div>

                <div class="modal-footer border-0 flex gap-2">
                    <button type="button" class="btn btn-light rounded-lg flex-1 py-2.5" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="flex-1">
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
