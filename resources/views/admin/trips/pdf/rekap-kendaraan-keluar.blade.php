<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cek List Data Kendaraan Keluar</title>

    <style>
        @page {
            size: A4;
            margin: 10mm 12mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            /* ini ukuran resmi dokumen */
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 6mm;
        }

        .header .title {
            font-weight: 700;
            font-size: 14pt;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .header .subtitle {
            font-weight: 700;
            font-size: 13pt;
            text-transform: uppercase;
            margin-top: 2px;
        }

        table.checklist {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table.checklist th,
        table.checklist td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: middle;
        }

        table.checklist thead th {
            text-align: center;
            font-weight: 700;
        }

        table.checklist tbody td {
            height: 8mm;
        }

        /* Lebar kolom */
        .col-no {
            width: 4%;
            text-align: center;
            padding-left: 0;
            padding-right: 0;
        }

        .col-requester {
            width: 16%;
        }

        .col-nama {
            width: 18%;
        }

        .col-tujuan {
            width: 24%;
        }

        .col-nopol {
            width: 12%;
        }

        .col-tgl {
            width: 26%;
            text-align: center;
        }

        .footer {
            margin-top: 7mm;
            width: 100%;
        }

        .sign-block {
            width: 70mm;
            float: right;
            font-size: 12pt;
        }

        .sign-space {
            height: 22mm;
        }

        .sign-name {
            font-weight: 700;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">CEK LIST DATA KENDARAAN KELUAR</div>
        <div class="subtitle">{{ $monthName }} {{ $year }}</div>
    </div>

    <table class="checklist">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-requester">PENGAJU</th>
                <th class="col-nama">DRIVER</th>
                <th class="col-tujuan">TUJUAN</th>
                <th class="col-nopol">NOPOL</th>
                <th class="col-tgl">TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= $totalRows; $i++)
                @php $row = $items[$i - 1] ?? null; @endphp
                <tr>
                    <td class="col-no">{{ $i }}</td>
                    <td class="col-requester">{{ $row['requester_name'] ?? '' }}</td>
                    <td class="col-nama">{{ $row['nama'] ?? '' }}</td>
                    <td class="col-tujuan">{{ $row['tujuan'] ?? '' }}</td>
                    <td class="col-nopol">{{ $row['nopol'] ?? '' }}</td>
                    <td class="col-tgl">
                        {!! nl2br(e($row['tanggal'] ?? '')) !!}
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        <div class="sign-block">
            <div>{{ $signCity }}, {{ $signDay }} {{ $signMonthName }} {{ $signYear }}</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ $signName }}</div>
            <div>{{ $signNip }}</div>
        </div>
        <div class="clearfix"></div>
    </div>

</body>

</html>
