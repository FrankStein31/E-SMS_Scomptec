<!DOCTYPE html>
<html>

<head>
    <title>Cetak Surat Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <h3>Daftar Surat Keluar</h3>
    <button class="no-print" onclick="window.print()">🖨️ Cetak Halaman Ini</button>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Surat</th>
                <th>Sifat</th>
                <th>Jenis</th>
                <th>Hal</th>
                <th>Tanggal</th>
                <th>Klasifikasi</th>
                <th>Kepada</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Satker</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suratKeluar as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->no_surat }}</td>
                    <td>{{ $surat->sifat }}</td>
                    <td>{{ $surat->jenis }}</td>
                    <td>{{ $surat->hal }}</td>
                    <td>{{ $surat->tgl_surat }}</td>
                    <td>{{ $surat->klasifikasi }}</td>
                    <td>{{ $surat->kepada }}</td>
                    <td>{{ $surat->nama }}</td>
                    <td>{{ $surat->jabatan }}</td>
                    <td>{{ $surat->satker }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
