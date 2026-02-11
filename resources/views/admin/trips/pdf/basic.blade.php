<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Perintah Jalan</title>

    <style>
        @page {
            margin: 30mm 25mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.5;
        }

        /* HEADER */
        .header {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 2px 0;
            font-size: 12pt;
        }

        .nomor {
            margin-top: 6px;
            font-size: 12pt;
            font-weight: bold;
        }

        /* Divider */
        .divider {
            margin: 25px 0;
            border-top: 1px solid #000;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px 0;
            vertical-align: top;
        }

        .label {
            width: 32%;
            font-weight: bold;
        }

        .colon {
            width: 3%;
        }

        /* Section */
        .section {
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 12.5pt;
        }

        /* Signature */
        .signature {
            margin-top: 60px;
        }

        .signature-box {
            width: 45%;
            float: right;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }

        .small {
            font-size: 10pt;
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

        $jam = $start->format('H:i') . ' – ' . $end->format('H:i');
    @endphp

    <div class="header">
        <h1>SURAT PERINTAH JALAN</h1>
        <p>Angkutan Kendaraan Dinas</p>

        <div class="nomor">
            Nomor: UM 02 04 –1869.{{ str_pad($trip->id, 2, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <div class="divider"></div>

    <div class="section">Data Perjalanan</div>
    <table>
        <tr>
            <td class="label">Nama Pemohon</td>
            <td class="colon">:</td>
            <td>{{ $trip->requester_name ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Tujuan</td>
            <td class="colon">:</td>
            <td>{{ $trip->destination ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal</td>
            <td class="colon">:</td>
            <td>{{ $tanggal }}</td>
        </tr>

        <tr>
            <td class="label">Waktu</td>
            <td class="colon">:</td>
            <td>{{ $jam }}</td>
        </tr>

        <tr>
            <td class="label">Catatan</td>
            <td class="colon">:</td>
            <td>{{ $trip->notes ?? '-' }}</td>
        </tr>
    </table>

    <div class="section">Data Kendaraan</div>
    <table>
        <tr>
            <td class="label">Jenis Kendaraan</td>
            <td class="colon">:</td>
            <td>{{ $trip->car->name ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Nomor Polisi</td>
            <td class="colon">:</td>
            <td>{{ $trip->car->plate_number ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Pengemudi</td>
            <td class="colon">:</td>
            <td>{{ $trip->driver->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="signature">
        <div class="signature-box">
            Surabaya, {{ now()->translatedFormat('d F Y') }}<br>
            <span class="small">Pejabat yang Berwenang</span>

            <div class="signature-space"></div>

            <strong>Sunaryo</strong><br>
            <span class="small">NIP: 197304172008121001</span>
        </div>
    </div>

</body>

</html>
