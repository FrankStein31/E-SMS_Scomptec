<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Umum */
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
            margin: 10mm;
            /* Margin untuk layout cetak */
            background-color: #f8f8f8;
            /* Warna latar belakang lembut */
        }

        /* Header */
        h3 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 18pt;
            font-weight: 700;
        }

        /* Tombol Cetak */
        .no-print {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            /* Sudut lebih membulat */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            display: flex;
            /* Untuk ikon dan teks sejajar */
            align-items: center;
            justify-content: center;
            gap: 8px;
            /* Jarak antara ikon dan teks */
        }

        .no-print:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            /* Bayangan untuk kesan modern */
            background-color: #fff;
            border-radius: 10px;
            /* Sudut tabel membulat */
            overflow: hidden;
            /* Pastikan sudut membulat diterapkan */
        }

        th,
        td {
            border: 1px solid #e0e0e0;
            /* Border lebih tipis dan lembut */
            padding: 12px 15px;
            /* Padding lebih besar */
            text-align: left;
        }

        thead th {
            background-color: #f2f2f2;
            /* Latar belakang header lebih terang */
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10pt;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
            /* Warna zebra striping untuk baris genap */
        }

        tbody tr:hover {
            background-color: #eef;
            /* Efek hover pada baris tabel */
        }

        /* Badge untuk Sifat Surat */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            /* Sudut badge lebih lembut */
            font-size: 9pt;
            font-weight: 600;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            line-height: 1;
        }

        .bg-danger {
            background-color: #dc3545;
            /* Merah */
        }

        .bg-warning {
            background-color: #ffc107;
            /* Kuning */
            color: #333;
            /* Teks gelap untuk kontras */
        }

        .bg-info {
            background-color: #17a2b8;
            /* Biru muda */
        }


        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .kop-kiri {
            flex: 0 0 auto;
            margin-right: 15px;
        }

        .kop-logo {
            width: 80px;
            height: auto;
        }

        .kop-kanan {
            flex: 1;
            text-align: center;
        }

        .kop-nama {
            font-size: 16pt;
            font-weight: 700;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }

        .kop-alamat,
        .kop-kontak {
            font-size: 10pt;
            margin: 2px 0;
            color: #333;
        }

        .garis-pemisah {
            border: 2px solid #000;
            margin: 10px 0 20px;
        }


        /* Media Cetak */
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 20mm 20mm 20mm;

                /* top, right, bottom, left */
                /* Remove default headers/footers */
                @top-left {
                    content: none;
                }

                @top-right {
                    content: none;
                }

                @bottom-left {
                    content: none;
                }

                @bottom-right {
                    content: none;
                }
            }

            /* Reset and base styles */
            * {
                box-shadow: none !important;
                text-shadow: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: white !important;
                color: #000 !important;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, Arial, sans-serif;
                font-size: 10pt;
                line-height: 1.4;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                -moz-print-color-adjust: exact;
            }

            /* Hide non-printable elements */
            .no-print,
            .btn,
            button,
            input[type="button"],
            input[type="submit"],
            .sidebar,
            .navigation,
            .nav,
            .footer,
            .advertisement {
                display: none !important;
            }

            /* Headings optimization */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                color: #000 !important;
                page-break-after: avoid;
                margin: 12pt 0 6pt 0;
                font-weight: bold;
            }

            h1 {
                font-size: 16pt;
            }

            h2 {
                font-size: 14pt;
            }

            h3 {
                font-size: 12pt;
            }

            h4,
            h5,
            h6 {
                font-size: 11pt;
            }

            /* Paragraph spacing */
            p {
                margin: 6pt 0;
                orphans: 3;
                widows: 3;
            }

            /* Table styling */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                border-spacing: 0 !important;
                margin: 12pt 0;
                background: white !important;
                font-size: 9pt;
                page-break-inside: avoid;
            }

            /* Table headers */
            thead {
                display: table-header-group;
                /* Repeat on every page */
            }

            thead th {
                background: #f0f0f0 !important;
                color: #000 !important;
                font-weight: bold;
                text-align: left;
                padding: 8pt 6pt;
                border: 1px solid #333 !important;
                page-break-after: avoid;
                vertical-align: top;
            }

            /* Table cells */
            td,
            th {
                border: 1px solid #444 !important;
                padding: 6pt;
                vertical-align: top;
                word-wrap: break-word;
                hyphens: auto;
            }

            /* Alternating row colors */
            tbody tr:nth-child(even) {
                background: #f8f8f8 !important;
            }

            tbody tr:nth-child(odd) {
                background: white !important;
            }

            /* Table footer */
            tfoot {
                display: table-footer-group;
                font-weight: bold;
                background: #e9e9e9 !important;
            }

            /* Prevent page breaks in critical elements */
            tr,
            td,
            th {
                page-break-inside: avoid;
            }

            /* Images and media */
            img {
                max-width: 100% !important;
                height: auto !important;
                page-break-inside: avoid;
            }

            /* Lists */
            ul,
            ol {
                margin: 6pt 0;
                padding-left: 20pt;
            }

            li {
                margin: 2pt 0;
            }

            /* Links */
            a {
                color: #000 !important;
                text-decoration: underline;
            }

            /* Print-specific classes */
            .print-only {
                display: block !important;
            }

            .page-break {
                page-break-before: always;
            }

            .no-page-break {
                page-break-inside: avoid;
            }

            /* Date/timestamp styling */
            .tanggal-cetak,
            .print-date,
            .timestamp {
                text-align: right;
                font-size: 8pt;
                color: #666 !important;
                margin-top: 15pt;
                border-top: 1px solid #ccc;
                padding-top: 6pt;
            }

            /* Header/title area */
            .print-header {
                text-align: center;
                margin-bottom: 20pt;
                padding-bottom: 10pt;
                border-bottom: 2px solid #000;
            }

            .print-title {
                font-size: 16pt;
                font-weight: bold;
                color: #000 !important;
                margin: 0 0 6pt 0;
            }

            .print-subtitle {
                font-size: 12pt;
                color: #333 !important;
                margin: 0;
            }

            /* Footer information */
            .print-footer {
                margin-top: 20pt;
                padding-top: 10pt;
                border-top: 1px solid #ccc;
                font-size: 8pt;
                color: #666 !important;
            }

            /* Data formatting */
            .currency,
            .number {
                text-align: right;
                font-family: 'Courier New', monospace;
            }

            .center {
                text-align: center;
            }

            .right {
                text-align: right;
            }

            /* Status indicators */
            .status-active,
            .status-success {
                background: #e8f5e8 !important;
                color: #2d5a2d !important;
                padding: 2pt 4pt;
                border-radius: 2pt;
            }

            .status-inactive,
            .status-error {
                background: #f5e8e8 !important;
                color: #5a2d2d !important;
                padding: 2pt 4pt;
                border-radius: 2pt;
            }

            .status-warning {
                background: #f5f1e8 !important;
                color: #5a4d2d !important;
                padding: 2pt 4pt;
                border-radius: 2pt;
            }

            /* Compact table variant */
            .table-compact {
                font-size: 8pt;
            }

            .table-compact td,
            .table-compact th {
                padding: 4pt;
            }

            /* Summary/total rows */
            .total-row,
            .summary-row {
                font-weight: bold;
                background: #e9e9e9 !important;
                border-top: 2px solid #000 !important;
            }

            /* Force exact colors for important elements */
            .force-print-colors * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <div class="kop-kiri">
            <img src="{{ asset('assets/images/logo/logo_jatim.png') }}" alt="Logo" class="kop-logo">
        </div>
        <div class="kop-kanan">
            <p class="kop-provinsi">PEMERINTAH PROVINSI JAWA TIMUR</p>
            <h2 class="kop-nama">SEKRETARIAT DAERAH</h2>
            <p class="kop-alamat">
                <strong>Jl. Pahlawan 110</strong> &nbsp;&nbsp;
                <strong>Telepon</strong> 3524001 - 3524011 <br>
                <strong><span class="kop-kota">S U R A B A Y A</span></strong> 60174
            </p>
        </div>
    </div>
    <hr class="kop-garis">

    </div>
    <hr class="garis-pemisah">
    <h3>Daftar Surat Keluar</h3>
    <button class="no-print" onclick="window.print()">🖨️ Cetak Halaman Ini</button>
    <div class="tanggal-cetak">
        Surabaya:<span id="waktuCetak">{{ \Carbon\Carbon::now()->format('d/m/Y, H.i') }}</span>
    </div>
    <table class="table table-sm table-hover table-striped align-middle mb-0">
        <thead style="text-align: center;">
            <tr>
                <th rowspan="2" scope="col">No</th>
                <th rowspan="2" scope="col">No. Surat</th>
                <th rowspan="2" scope="col">Sifat</th>
                <th rowspan="2" scope="col">Jenis</th>
                <th rowspan="2" scope="col">Hal</th>
                <th rowspan="2" scope="col">Tgl. Surat</th>
                <th rowspan="2" scope="col">Klasifikasi</th>
                <th rowspan="2" scope="col">Kepada</th>
                <th colspan="3" style="text-align: center; background-color: #f0f0f0;">Yang Memfinalkan</th>
            </tr>
            <tr>
                <th scope="col">Nama</th>
                <th scope="col">Jabatan</th>
                <th scope="col">Satker</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suratKeluar as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->nosurat }}</td>
                    <td>
                        @if ($surat->sifat == 1)
                            <span class="badge bg-danger">Penting</span>
                        @elseif($surat->sifat == 2)
                            <span class="badge bg-warning">Rahasia</span>
                        @else
                            <span class="badge bg-info">Biasa</span>
                        @endif
                    </td>
                    <td>{{ $surat->jenis->name }}</td>
                    <td>{{ $surat->hal }}</td>
                    <td>{{ $surat->tgl_surat }}</td>
                    <td>{{ $surat->klasifikasi['klasifikasi'] ?? '-' }}</td>
                    <td>
                        @php
                            $kepada = $surat->kepada;
                            $names = [];
                            if ($kepada) {
                                $arr = is_array($kepada) ? $kepada : json_decode($kepada);
                                if ($arr) {
                                    foreach ($arr as $k) {
                                        $data = is_string($k) ? json_decode($k) : $k;
                                        if (isset($data->name)) {
                                            $names[] = $data->name;
                                        }
                                    }
                                }
                            }
                            echo implode(', ', $names);
                        @endphp
                    </td>
                    <td>{{ $surat->user_id_pembuat }}</td>
                    <td>{{ $surat->pembuat->jabatan ?? '-' }}</td>
                    <td>{{ $surat->userFinal->satker->satker ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
<script>
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID');
    const jam = now.getHours().toString().padStart(2, '0') + '.' + now.getMinutes().toString().padStart(2, '0');
    document.getElementById('waktuCetak').innerText = `${tanggal}, ${jam}`;
</script>


</html>
