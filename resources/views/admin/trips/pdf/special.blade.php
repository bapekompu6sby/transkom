<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Khusus</title>
    <style>
        @page {
            margin: 18mm 18mm 16mm 18mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.25;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 10pt;
        }

        .title {
            font-weight: bold;
            font-size: 12pt;
            margin: 0;
            text-transform: uppercase;
        }

        .sub {
            margin: 2px 0 0 0;
            text-transform: uppercase;
        }

        .code {
            margin: 2px 0 0 0;
            font-size: 9.5pt;
        }

        .hr {
            border-top: 1px solid #000;
            margin: 6px 0 8px 0;
        }

        table.meta {
            width: 100%;
            border-collapse: collapse;
        }

        table.meta td {
            padding: 2px 0;
            vertical-align: top;
        }

        td.label {
            width: 33%;
        }

        td.colon {
            width: 3%;
        }

        .sign-row {
            width: 100%;
            margin-top: 18px;
        }

        .sign-row td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .sig-title {
            font-size: 11pt;
        }

        .sig-space {
            height: 60px;
            /* ruang tanda tangan */
        }

        .sig-name {
            font-weight: bold;
            margin-top: 4px;
        }

        .sig-nip {
            font-size: 11pt;
        }


        .space-ttd {
            margin-top: 17.5px;
        }

        .space-before-next {
            margin-top: 35px;
            /* jarak sebelum PERINTAH JALAN */
        }

        .block {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    @php
        $start = \Carbon\Carbon::parse($trip->start_at);
        $end = \Carbon\Carbon::parse($trip->end_at);
        $sameDay = $start->isSameDay($end);

        $tanggal = $sameDay
            ? $start->translatedFormat('d F Y')
            : $start->translatedFormat('d F Y') . ' s/d ' . $end->translatedFormat('d F Y');

        // gaya dokumen: "jam berangkat ... sampai selesai"
        $jamBerangkat = $start->format('H.i') . ' – ' . $end->format('H.i');

        $days = max(
            1,
            $start
                ->copy()
                ->startOfDay()
                ->diffInDays($end->copy()->startOfDay()) + 1,
        );

        $nomor =
            $trip->created_at->format('d m') .
            ' - ' .
            $trip->created_at->format('Y') .
            '.' .
            str_pad($trip->id, 2, '0', STR_PAD_LEFT);

        $pemohonNama = $trip->requester_name ?? '-';
        $pemohonJabatan = $trip->requester_position ?? '-';
        $tujuan = $trip->destination ?? '-';
        $keperluan = $trip->purpose ?? '-';
        $pengikut = $trip->participant_count ?? '-';

        $pemakai = $trip->organization_name ?? '-';
        $pengemudi = $trip->driver->name ?? '-';

        $kendaraan = ($trip->car->name ?? '-') . ' / ' . ($trip->car->plate_number ?? '-');

        // “Mengetahui” (sementara hardcode — nanti bisa dari setting)
        $mengetahuiNama = 'Ivo Fauziah, S.E., M.T.';
        $mengetahuiNip = '198606172010122007';

        $petugasNama = 'Sunaryo';
        $petugasNip = '197304172008121001';

        $pemohonNip = $trip->nip ?? '-';

        function toRoman($month)
        {
            $romans = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
            ];
            return $romans[$month];
        }

        $nomorHeader = sprintf(
            'F-01/DM/P-TU/%03d/BD04/%s/%d',
            $trip->id,
            toRoman($trip->created_at->month),
            $trip->created_at->year,
        );

    @endphp

    {{-- ================== PERMOHONAN ================== --}}
    <div class="block">
        <div class="center">
            <p class="title">PERMOHONAN</p>
            <p class="sub small">ANGKUTAN KENDARAAN BERMOTOR KENDARAAN DINAS</p>
            <p class="code">{{ $nomorHeader }}</p>
            <p class="code bold">(DIISI PEMOHON)</p>
        </div>
        <div class="hr"></div>

        <table class="meta">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $pemohonNama }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td>{{ $pemohonJabatan }}</td>
            </tr>
            <tr>
                <td class="label">Tujuan</td>
                <td class="colon">:</td>
                <td>{{ $tujuan }}</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="colon">:</td>
                <td>{{ $keperluan }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal yang diperlukan</td>
                <td class="colon">:</td>
                <td>{{ $tanggal }}</td>
            </tr>
            <tr>
                <td class="label">Jam berangkat</td>
                <td class="colon">:</td>
                <td>{{ $jamBerangkat }}</td>
            </tr>
            <tr>
                <td class="label">Lama pemakaian</td>
                <td class="colon">:</td>
                <td>{{ $days }} ({{ $days === 1 ? 'satu' : $days }}) hari</td>
            </tr>
            <tr>
                <td class="label">Jumlah pengikut</td>
                <td class="colon">:</td>
                <td>{{ $pengikut }} orang</td>
            </tr>
        </table>

        <table class="sign-row">
            <tr>
                <td>
                    <div class="sig-title">
                        Mengetahui,<br>
                        Kepala Subbagian Tata Usaha
                    </div>

                    <div class="sig-space"></div>

                    <div class="sig-name">{{ $mengetahuiNama }}</div>
                    <div class="sig-nip">NIP : {{ $mengetahuiNip }}</div>
                </td>

                <td>
                    <div class="sig-title">Pemohon</div>
                    <div class="space-ttd"></div>

                    <div class="sig-space"></div>

                    <div class="sig-name">{{ $pemohonNama }}</div>
                    <div class="sig-nip">NIP : {{ $pemohonNip }}</div>
                </td>
            </tr>
        </table>


    </div>

    {{-- ================== PERINTAH JALAN ================== --}}
    <div class="block">
        <div class="space-before-next"></div>

        <div class="center">
            <p class="title">PERINTAH JALAN</p>
            <p class="sub small">ANGKUTAN BERMOTOR KENDARAAN DINAS</p>
            <p class="code">{{ $nomorHeader }}</p>
            <p class="code">Nomor : <span class="bold">{{ $nomor }}</span></p>
        </div>
        <div class="hr"></div>

        <table class="meta">
            <tr>
                <td class="label">Kendaraan</td>
                <td class="colon">:</td>
                <td>{{ $kendaraan }}</td>
            </tr>
            <tr>
                <td class="label">Pemakai</td>
                <td class="colon">:</td>
                <td>{{ $pemakai }}</td>
            </tr>
            <tr>
                <td class="label">Pengemudi</td>
                <td class="colon">:</td>
                <td>{{ $pengemudi }}</td>
            </tr>
            <tr>
                <td class="label">Tujuan</td>
                <td class="colon">:</td>
                <td>{{ $tujuan }}</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="colon">:</td>
                <td>{{ $keperluan }}</td>
            </tr>
            <tr>
                <td class="label">Berangkat pukul</td>
                <td class="colon">:</td>
                <td>{{ $jamBerangkat }}</td>
            </tr>
        </table>

        <table class="sign-row" style="margin-top:16px;">
            <tr>
                <td></td>
                <td>
                    <div class="sign-title">
                        Surabaya, {{ now()->translatedFormat('d M Y') }}<br>
                        Petugas Pemeliharaan
                    </div>

                    <div class="sig-space"></div>

                    <div class="sig-name">{{ $petugasNama }}</div>
                    <div class="sig-nip">NIP : {{ $petugasNip }}</div>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>
