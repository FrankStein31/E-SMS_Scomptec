<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 40px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        @media print {
            body {
                margin: 0;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <h2>Laporan Surat</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Agenda</th>
                <th>Sifat</th>
                <th>Jenis</th>
                <th>No. Surat</th>
                <th>Dari</th>
                <th>Tujuan</th>
                <th>Hal</th>
                <th>Unit</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->noagenda ?? '-' }}</td>
                    <td>{{ sifatSurat($item->sifat ?? 0) }}</td>
                    <td>{{ $item->jenis_id ?? '-' }}</td>
                    <td>{{ $item->nomor_surat ?? '-' }}</td>
                    <td>{{ $item->dari ?? '-' }}</td>
                    <td>{{ $item->kepada ?? '-' }}</td>
                    <td>{{ $item->hal ?? '-' }}</td>
                    <td>{{ $item->created_by ?? '-' }}</td>
                    <td>{{ $item->tgl_surat ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>
