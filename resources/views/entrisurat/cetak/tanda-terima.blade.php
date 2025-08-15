<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Surat - {{ $data->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
        }
        .table-info td {
            padding: 5px;
            vertical-align: top;
        }
        .table-info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #000;
            padding-top: 5px;
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
            Cetak Tanda Terima
        </button>
        <button onclick="window.close()" style="margin-bottom: 20px; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>TANDA TERIMA SURAT</h2>
        <h3>KEMENTERIAN/LEMBAGA/INSTANSI</h3>
    </div>

    <div class="content">
        <table class="table-info">
            <tr>
                <td>Nomor Surat</td>
                <td>: {{ $data->nomor_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td>: {{ date('d-m-Y', strtotime($data->tgl_surat)) }}</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>: {{ $data->dari }}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>: {{ $data->hal }}</td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>: {{ sifatSurat($data->sifat) }}</td>
            </tr>
            <tr>
                <td>Jenis Surat</td>
                <td>: {{ $data->jenis ? $data->jenis->name : '-' }}</td>
            </tr>
            <tr>
                <td>Klasifikasi</td>
                <td>: {{ $data->kode_klasifikasi }}</td>
            </tr>
            <tr>
                <td>Tanggal Diterima</td>
                <td>: {{ date('d-m-Y', strtotime($data->tgl_diterima)) }}</td>
            </tr>
            <tr>
                <td>Diterima Oleh</td>
                <td>: {{ $data->createdBy->fullname }}</td>
            </tr>
            @if($data->lampiran)
            <tr>
                <td>Lampiran</td>
                <td>: {{ $data->lampiran }}</td>
            </tr>
            @endif
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <p>Yang Menerima,</p>
                <div class="signature-line">
                    {{ $data->createdBy->fullname }}
                </div>
            </div>
            <div class="signature-box">
                <p>Tanggal: {{ date('d-m-Y') }}</p>
                <div class="signature-line">
                    Tanda Tangan & Stempel
                </div>
            </div>
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
