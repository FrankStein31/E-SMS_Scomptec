<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Disposisi - {{ $data->nomor_surat }}</title>
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
            margin-bottom: 20px;
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
        .disposisi-section {
            margin-top: 30px;
            border: 2px solid #000;
            padding: 20px;
        }
        .disposisi-header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .disposisi-content {
            margin: 20px 0;
        }
        .disposisi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .disposisi-table th,
        .disposisi-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .disposisi-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 40px;
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
            Cetak Disposisi
        </button>
        <button onclick="window.close()" style="margin-bottom: 20px; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>LEMBAR DISPOSISI</h1>
        <h2>KEMENTERIAN/LEMBAGA/INSTANSI</h2>
    </div>

    <div class="content">
        <table class="table-info">
            <tr>
                <td>Nomor Surat</td>
                <td>{{ $data->nomor_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td>{{ date('d F Y', strtotime($data->tgl_surat)) }}</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>{{ $data->dari }}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>{{ $data->hal }}</td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>{{ sifatSurat($data->sifat) }}</td>
            </tr>
            <tr>
                <td>Tanggal Diterima</td>
                <td>{{ date('d F Y', strtotime($data->tgl_diterima)) }}</td>
            </tr>
        </table>

        <div class="disposisi-section">
            <div class="disposisi-header">
                INSTRUKSI/DISPOSISI
            </div>
            
            <div class="disposisi-content">
                @if($data->disposisi && $data->disposisi->count() > 0)
                    <table class="disposisi-table">
                        <thead>
                            <tr>
                                <th>Kepada</th>
                                <th>Tindakan</th>
                                <th>Catatan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->disposisi as $disposisi)
                            <tr>
                                <td>{{ $disposisi->user ? $disposisi->user->fullname : '-' }}</td>
                                <td>{{ $disposisi->tindakan ? $disposisi->tindakan->name : '-' }}</td>
                                <td>{{ $disposisi->catatan ?? '-' }}</td>
                                <td>{{ $disposisi->created_at ? date('d/m/Y', strtotime($disposisi->created_at)) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="min-height: 100px; border: 1px dashed #ccc; padding: 20px; text-align: center;">
                        <p style="font-style: italic; color: #666;">Belum ada disposisi untuk surat ini</p>
                    </div>
                @endif
                
                <div style="margin-top: 30px;">
                    <p><strong>Catatan Khusus:</strong></p>
                    <div style="min-height: 80px; border: 1px solid #000; padding: 10px;">
                        <!-- Area untuk catatan khusus -->
                    </div>
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Diterima Oleh,</p>
                <div class="signature-line">
                    {{ $data->createdBy->fullname }}
                </div>
            </div>
            <div class="signature-box">
                <p>Disposisi Oleh,</p>
                <div class="signature-line">
                    Pimpinan/Atasan
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
