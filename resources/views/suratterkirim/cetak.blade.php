<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Dynamic title for the printout --}}
    <title>Surat Keluar - {{ $suratTerkirim->nosurat ?? 'Nomor Surat Tidak Tersedia' }}</title>

    {{-- Basic CSS for print layout --}}
    <style>
        /* General body and font styles for formal documents */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 25mm;
            /* Standard A4 margins (approx 1 inch on all sides) */
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }

        /* Header section with logo and institution details */
        .header-section {
            text-align: center;
            margin-bottom: 30px;
            overflow: auto;
            /* Clear floats */
        }

        .header-section .logo {
            float: left;
            margin-right: 20px;
            width: 80px;
            /* Adjust logo width as seen in the image */
            height: auto;
        }

        .header-section .institution-details {
            overflow: hidden;
            /* Contains floated logo */
            text-align: center;
        }

        .header-section h2 {
            margin: 0;
            font-size: 14pt;
            /* Larger font for institution name */
            text-transform: uppercase;
        }

        .header-section p {
            margin: 0;
            font-size: 10pt;
            /* Smaller font for address and contact */
            line-height: 1.3;
        }

        .header-divider {
            border-top: 2px solid #000;
            /* Thicker line for separator */
            margin-top: 15px;
            margin-bottom: 20px;
            clear: both;
            /* Ensure divider is below floated content */
        }

        /* Letter metadata section (Nomor, Sifat, Lampiran, Hal) */
        .letter-meta {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            /* For clean table borders if used */
        }

        .letter-meta td {
            padding: 2px 0;
            /* Minimal padding */
            vertical-align: top;
            font-size: 11pt;
        }

        .letter-meta .label-col {
            width: 80px;
            /* Width for labels like 'Nomor', 'Sifat' */
        }

        .letter-meta .date-col {
            text-align: right;
            width: 150px;
            /* Allocate space for date */
        }

        /* Recipient address section */
        .recipient-address {
            margin-top: 20px;
            text-align: left;
        }

        .recipient-address p {
            margin: 0;
            line-height: 1.3;
        }

        .recipient-address .indent {
            text-indent: 40px;
            /* Indent for 'Tempat' */
        }

        /* Letter main content (Isi) */
        .letter-content {
            margin-top: 30px;
            text-align: justify;
            /* Justify text for formal look */
        }

        .letter-content p {
            margin-bottom: 1em;
        }

        /* Signature block */
        .signature-block {
            margin-top: 50px;
            text-align: right;
        }

        .signature-block p {
            margin: 0;
            line-height: 1.2;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            /* Underline name as in the image */
            margin-top: 50px;
            /* Space for physical signature */
            display: inline-block;
            /* Allows underline to apply only to text */
            padding-bottom: 2px;
        }

        /* Tembusan (Carbon Copy) section */
        .tembusan-section {
            margin-top: 40px;
            font-size: 10pt;
            /* Smaller font for tembusan */
        }

        .tembusan-section ol {
            padding-left: 20px;
            /* Indent list */
            margin-top: 5px;
        }

        .tembusan-section li {
            margin-bottom: 3px;
        }

        /* Print-specific adjustments */
        @media print {
            body {
                margin: 0;
                /* Let printer handle physical margins */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .header-section .logo {
                -webkit-filter: grayscale(100%);
                /* Optional: Print logo in grayscale */
                filter: grayscale(100%);
            }
        }
    </style>
</head>

<body>

    <div class="print-container">
        {{-- Header Section: Logo, Institution Name, Address --}}
        <div class="header-section">
            {{-- Assuming your logo path is dynamic or static --}}
            <img src="{{ asset('assets/images/logo/logo_jatim.png') }}" alt="Logo Provinsi Jawa Timur" class="logo">
            <div class="institution-details">
                <h2>PEMERINTAH PROVINSI JAWA TIMUR</h2>
                <h3>SEKRETARIAT DAERAH</h3>
                <p>Jl. Pahlawan 110 Telepon 3524001 - 3524011</p>
                <p><strong>SURABAYA 60174</strong></p>
            </div>
            <div class="header-divider"></div>
        </div>

        {{-- Date and Recipient Block (Right Aligned) --}}
        <p style="text-align: right; margin-bottom: 10px;">Surabaya,
            {{ $suratTerkirim->tgl_surat ? $suratTerkirim->tgl_surat->format('d F Y') : '-' }}</p>

        {{-- Letter Metadata Table --}}
        <table class="letter-meta">
            <tr>
                <td class="label-col">Nomor</td>
                <td>: {{ $suratTerkirim->nosurat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Sifat</td>
                <td>: {{ $suratTerkirim->sifat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Lampiran</td>
                <td>: {{ $suratTerkirim->jml_lampiran > 0 ? $suratTerkirim->jml_lampiran . ' (satu) berkas' : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label-col">Hal</td>
                <td>: {{ $suratTerkirim->hal ?? '-' }}</td>
            </tr>
        </table>

        {{-- Recipient Address Block --}}
        <div class="recipient-address">
            <p>Kepada Yth.</p>
            @if ($suratTerkirim->kepada_detail)
                {{-- kepda_detail accessor should output names and jabatan with <br> for new lines --}}
                {!! $suratTerkirim->kepada_detail !!}
            @else
                <p>- Tidak ada penerima -</p>
            @endif
            <p>di -</p>
            <p class="indent">Tempat</p>
        </div>

        {{-- Letter Main Content (Isi Surat) --}}
        <div class="letter-content">
            {{-- This should be the main body of the letter. Assuming 'isi' holds HTML content. --}}
            {!! $suratTerkirim->isi ?? '<p>Isi surat belum tersedia.</p>' !!}
        </div>

        {{-- Signature Block --}}
        <div class="signature-block">
            {{-- Assuming 'ttd_nama' or a derived accessor holds the signatory's name --}}
            <p>a.n. GUBERNUR JAWA TIMUR</p>
            <p>SEKRETARIS DAERAH</p>
            <br><br><br><br> {{-- Space for physical signature --}}
            <p class="signature-name">{{ $suratTerkirim->ttd_nama ?? 'NAMA PENANDATANGAN' }}</p>
            <p>NIP. -</p>
        </div>

        {{-- Tembusan Section --}}
        @if ($suratTerkirim->tembusan_nama && $suratTerkirim->tembusan_nama != '-')
            <div class="tembusan-section">
                <p>Tembusan Yth.:</p>
                <ol>
                    {{-- If tembusan is a simple string from tembusan_nama accessor --}}
                    <li>{{ $suratTerkirim->tembusan_nama }}</li>
                    {{-- If you need to list multiple tembusan entries from the original array:
                    @foreach ($suratTerkirim->tembusan as $t)
                        @if (isset($t['name'])) <li>{{ $t['name'] }}</li> @endif
                    @endforeach
                    --}}
                </ol>
            </div>
        @endif

    </div>

</body>

</html>
