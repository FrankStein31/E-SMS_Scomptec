<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - {{ $data->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .content {
            margin: 20px 0;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-info td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ddd;
        }
        .table-info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .content-body {
            margin: 30px 0;
            min-height: 200px;
        }
        .scanned-images {
            margin-top: 30px;
        }
        .scanned-images img {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
            border: 1px solid #ddd;
        }
        .tujuan-section {
            margin-top: 30px;
        }
        .tujuan-list {
            margin: 10px 0;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="margin-bottom: 20px; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px;">
            Cetak Surat
        </button>
        <button onclick="window.close()" style="margin-bottom: 20px; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>KEMENTERIAN/LEMBAGA/INSTANSI</h1>
        <h2>UNIT KERJA</h2>
        <p>Alamat Instansi</p>
    </div>

    <div class="content">
        <table class="table-info">
            <tr>
                <td>Nomor</td>
                <td>{{ $data->nomor_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>{{ date('d F Y', strtotime($data->tgl_surat)) }}</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>{{ $data->dari }}</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>
                    @if($data->tujuan && $data->tujuan->count() > 0)
                        @foreach($data->tujuan as $tujuan)
                            {{ $tujuan->user->fullname }}@if(!$loop->last), @endif
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Hal</td>
                <td><strong>{{ $data->hal }}</strong></td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>{{ sifatSurat($data->sifat) }}</td>
            </tr>
            <tr>
                <td>Klasifikasi</td>
                <td>{{ $data->kode_klasifikasi }}</td>
            </tr>
            @if($data->lampiran)
            <tr>
                <td>Lampiran</td>
                <td>{{ $data->lampiran }}</td>
            </tr>
            @endif
        </table>

        <div class="content-body">
            <p><strong>Isi Surat:</strong></p>
            <div style="margin: 20px 0; min-height: 150px; border: 1px solid #ddd; padding: 15px;">
                <!-- Area untuk isi surat -->
                <p style="font-style: italic;">Isi surat akan ditampilkan di sini...</p>
            </div>
        </div>

        @if($data->FileScan && $data->FileScan->count() > 0)
        <div class="scanned-images">
            <h3>File Scan:</h3>
            @foreach($data->FileScan as $scan)
                <img src="{{ asset('uploads/' . $scan->nama_file) }}" alt="Scan {{ $loop->iteration }}">
            @endforeach
        </div>
        @endif

        <div style="margin-top: 50px; text-align: right;">
            <p>{{ date('d F Y') }}</p>
            <br><br><br>
            <p>{{ $data->createdBy->fullname }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads if requested
        if (window.location.search.includes('auto_print=true')) {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>
</html>
