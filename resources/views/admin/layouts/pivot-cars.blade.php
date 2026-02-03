<div class="table-responsive">
    <table class="table table-hover table-bordered align-middle mb-0">

        <thead class="table-light">
            <tr>
                <th class="text-uppercase small text-muted">Kendaraan</th>
                <th class="text-uppercase small text-muted">Plat</th>
                <th class="text-uppercase small text-muted text-center">Jan</th>
                <th class="text-uppercase small text-muted text-center">Feb</th>
                <th class="text-uppercase small text-muted text-center">Mar</th>
                <th class="text-uppercase small text-muted text-center">Apr</th>
                <th class="text-uppercase small text-muted text-center">Mei</th>
                <th class="text-uppercase small text-muted text-center">Jun</th>
                <th class="text-uppercase small text-muted text-center">Jul</th>
                <th class="text-uppercase small text-muted text-center">Agu</th>
                <th class="text-uppercase small text-muted text-center">Sep</th>
                <th class="text-uppercase small text-muted text-center">Okt</th>
                <th class="text-uppercase small text-muted text-center">Nov</th>
                <th class="text-uppercase small text-muted text-center">Des</th>
                <th class="text-uppercase small text-muted text-center">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rekapKendaraan as $r)
                <tr>
                    <td class="fw-semibold">{{ $r->kendaraan }}</td>
                    <td class="text-muted">{{ $r->plat ?? '—' }}</td>

                    @foreach (['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'] as $m)
                        <td class="text-center">{{ $r->$m }}</td>
                    @endforeach

                    <td class="text-center fw-bold text-primary">{{ $r->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center text-muted py-4">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
