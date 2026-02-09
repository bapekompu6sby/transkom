<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1080">

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="toast align-items-center text-bg-dark border-0 mb-2" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="3500">
            <div class="d-flex">
                <div class="toast-body">
                    ✅ {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    {{-- FAILED --}}
    @if (session('failed'))
        <div class="toast align-items-center text-bg-secondary border-0 mb-2" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body">
                    ⚠️ {{ session('failed') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    {{-- ERROR (validation / exception) --}}
    @if ($errors->any())
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    ❌ {{ $errors->first() }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

</div>
