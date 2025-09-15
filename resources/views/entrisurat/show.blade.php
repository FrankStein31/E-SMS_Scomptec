@extends('layout.main')

@push('css')
    <script src="{{ asset('assets/scanner.js') }}"></script>
    <!-- Tambahkan CSS untuk loading dan slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        /* Loading spinner */
        .scan-loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .scan-loading .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .scan-loading .loading-text {
            color: white;
            font-size: 16px;
            text-align: center;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .scan-loading .loading-subtext {
            color: #ccc;
            font-size: 12px;
            text-align: center;
            margin-top: 10px;
            font-family: 'Poppins', Arial, sans-serif;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Slider untuk gambar scan */
        .scanned-images-slider {
            margin-top: 20px;
        }

        .scanned-images-slider .slick-slide {
            padding: 0 10px;
        }

        .scanned-images-slider img {
            max-height: 200px;
            margin: 0 auto;
        }

        .slick-prev:before,
        .slick-next:before {
            color: #000;
        }

        /* Compact detail styles */
        .detail-table th {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }

        .detail-table td {
            font-size: 0.85rem;
            color: #212529;
        }

        .detail-table tr:hover {
            background-color: #f5f5f5;
        }

        /* Custom button styling for better visibility */
        .btn-save-scan {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-save-scan:hover {
            background-color: #e9ecef !important;
            border-color: #6c757d !important;
            color: #495057 !important;
        }

        .btn-save-scan:focus,
        .btn-save-scan:active {
            background-color: #e9ecef !important;
            border-color: #6c757d !important;
            color: #495057 !important;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }

        /* Badge styling for kepada field */
        .badge-kepada {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            background-color: #6f42c1 !important;
            color: white !important;
            border-radius: 0.375rem;
        }

        .badge-klasifikasi {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            background-color: #17a2b8 !important;
            color: white !important;
            border-radius: 0.375rem;
        }
    </style>

    <script>
        // Please read scanner.js developer's guide at: http://asprise.com/document-scan-upload-image-browser/ie-chrome-firefox-scanner-docs.html

        var scanRequest = {
            "source_name": "select", // Device selection: "select" (prompts device selection dialog), "default" (uses the current default device) or the exact device name.

            "use_asprise_dialog": false, // Whether to use Asprise Scanning Dialog
            "show_scanner_ui": false, // Whether scanner UI should be shown

            "twain_cap_setting": { // Optional scanning settings
                "ICAP_PIXELTYPE": "TWPT_RGB", // Color
                "ICAP_SUPPORTEDSIZES": "TWSS_USLETTER" // Paper size: TWSS_USLETTER, TWSS_A4, ...
            },

            "output_settings": [{
                "type": "return-base64",
                "format": "jpg"
            }]
        };

        /** Sets the source_name in request and triggers the scan */
        function scan(sourceName) {
            sourceName = typeof sourceName !== 'undefined' ? sourceName :
                'default'; // Use 'default' if sourceName is not specified.

            // Cek apakah scanner object tersedia
            if (typeof scanner === 'undefined' || !scanner || !scanner.scan) {
                log("❌ ERROR: scanner.js tidak dapat dimuat atau tidak tersedia!", true);
                document.getElementById('scanLoading').style.display = 'none';
                showScannerInstallModal();
                return;
            }

            // Reset flag scan success setiap kali mulai scan
            window.scanSuccessful = false;
            window.scannerDetected = false;
            window.scanAttemptStarted = true;

            // Tampilkan loading
            document.getElementById('scanLoading').style.display = 'flex';

            // Set timeout untuk deteksi software scanner - berikan waktu lebih lama untuk response
            var scanTimeout = setTimeout(function() {
                var loadingElement = document.getElementById('scanLoading');

                // Jika timeout terpicu DAN masih loading DAN belum ada response sama sekali
                if (window.scanAttemptStarted && !window.scannerDetected && !window.scanSuccessful &&
                    loadingElement && loadingElement.style.display === 'flex') {

                    log("❌ TIMEOUT: Scanner software TIDAK terdeteksi dalam 30 detik - Software belum terinstall!",
                        true);

                    // Pastikan loading disembunyikan
                    loadingElement.style.display = 'none';

                    // Reset flags
                    window.scanAttemptStarted = false;
                    window.scannerDetected = false;
                    window.scanSuccessful = false;

                    // Clear timeout
                    if (window.currentScanTimeout) {
                        clearTimeout(window.currentScanTimeout);
                        window.currentScanTimeout = null;
                    }

                    // TAMPILKAN MODAL karena software tidak terinstall
                    showScannerInstallModal();

                } else {
                    // Jika sudah ada response dari scanner atau loading sudah hilang, jangan tampilkan modal
                    log("Timeout reached but scanner already responded or loading finished - no modal needed");
                }
            }, 30000); // Perbesar jadi 30 detik untuk memberi waktu scanner software merespons

            // Store timeout ID
            window.currentScanTimeout = scanTimeout;

            scanRequest.source_name = sourceName;
            log("Memulai scan dengan source = " + scanRequest.source_name + " ...");

            try {
                // Coba panggil scanner.scan - JANGAN set scannerDetected di sini
                scanner.scan(handleScanResult, scanRequest);

                // Hanya log bahwa scanner.js berhasil dipanggil, bukan berarti software terdeteksi
                log("Scanner.js method called, waiting for response...");

            } catch (error) {
                // Jika error saat memanggil scanner.scan, berarti software tidak ada
                log("ERROR: Scanner software tidak ditemukan atau error loading - " + error, true);
                clearTimeout(scanTimeout);
                document.getElementById('scanLoading').style.display = 'none';
                window.scanAttemptStarted = false;
                window.scannerDetected = false;
                window.scanSuccessful = false;
                showScannerInstallModal();
            }
        }

        /** Checks response before parsing and performs fallback scanning if possible. */
        function handleScanResult(successful, mesg, response) {
            // PENTING: Clear timeout SEGERA saat dapat response apapun - ini mencegah modal muncul
            if (window.currentScanTimeout) {
                clearTimeout(window.currentScanTimeout);
                window.currentScanTimeout = null;
                log("Timeout cleared - scanner software responded");
            }

            // Set flag bahwa scanner software terdeteksi SEGERA saat dapat response
            window.scannerDetected = true;
            window.scanAttemptStarted = false;
            window.scanSuccessful = true; // Set langsung jadi true karena sudah ada response

            // Sembunyikan loading
            var loadingElement = document.getElementById('scanLoading');
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }

            log("✅ Scanner software detected - response received: successful=" + successful + ", message=" + (mesg ||
                'none'));

            // Cek user cancel PERTAMA - ini bukan error software
            if (mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) {
                log("User cancelled scan - scanner working but user cancelled");
                return; // Keluar langsung tanpa proses lain
            }

            // Cek jika successful dan ada response
            if (successful && response != null) {
                try {
                    var responseAsJson = JSON.parse(response);

                    // Jika ada response JSON valid, berarti scanner terhubung
                    if (responseAsJson != null) {
                        log("Scanner software detected and working properly");

                        // Cek apakah ada gambar atau tidak
                        if (responseAsJson.image_count && responseAsJson.image_count > 0) {
                            log("SUCCESS: " + responseAsJson.image_count + " images captured");
                            displayImagesOnPage(successful, mesg, response);
                        } else {
                            log("Scanner connected but no images captured (possibly no document in scanner)");
                        }
                        return; // Keluar dengan sukses
                    }
                } catch (exp) {
                    log("Error parsing scanner response: " + exp, true);
                    // Meskipun parsing error, tapi ada response berarti software terdeteksi
                }
            }

            // Cek jika successful tapi response kosong
            if (successful && (!response || response == null)) {
                log("Scanner responded successfully but no data returned");
                return;
            }

            // Jika tidak successful dan ada error message
            if (!successful && mesg) {
                log("Scanner software detected but scan failed: " + mesg, true);

                // Jika masih 'default', coba 'select' sebagai fallback
                if (scanRequest.source_name == 'default') {
                    log("Retrying with source 'select'...");
                    scan('select');
                    return;
                } else {
                    // Sudah coba keduanya tapi tetap ada response, berarti software OK
                    log("Both default and select tried - scanner software working but may have device issues");
                }
            }
        }

        function log(mesg, isError) {
            var line = (new Date().toLocaleTimeString()) + " " + (isError ? "ERROR " : " INFO ") + mesg;
            var textArea = document.getElementById("textarea_logging");
            if (textArea) {
                textArea.value = textArea.value + '\r' + line;
            } else {
                alert(line);
            }
        }

        // --------------- below functions are identical with many other demo scripts ---------------
        /** Processes the scan result */
        function displayImagesOnPage(successful, mesg, response) {
            var loading = document.getElementById('scanLoading');

            // Set flag sukses SEGERA - scanner software berfungsi
            window.scanSuccessful = true;
            window.scannerDetected = true;

            if (!successful) { // On error
                console.error('Display images failed: ' + mesg);
                if (loading) loading.style.display = 'none';
                // Jangan reset scanSuccessful karena kalau sampai sini berarti software terdeteksi
                log("Image display failed but scanner software is working: " + mesg, true);
                return false;
            }

            if (successful && mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) {
                console.info('User cancelled during image processing');
                if (loading) loading.style.display = 'none';
                window.scanSuccessful = true; // User cancel tetap dianggap sukses (software terdeteksi)
                log("User cancelled during image processing - scanner software OK");
                return false;
            }

            // Bersihkan slider sebelum menambah gambar baru
            var sliderContainer = document.getElementById('scannedImagesSlider');
            if (sliderContainer) {
                sliderContainer.innerHTML = '';
            }

            var scannedImages = scanner.getScannedImages(response, true, false);
            var hasImages = false;

            for (var i = 0;
                (scannedImages instanceof Array) && i < scannedImages.length; i++) {
                var scannedImage = scannedImages[i];
                processScannedImage(scannedImage);
                hasImages = true;
            }

            // Inisialisasi slider setelah gambar ditambahkan
            if (hasImages) {
                initImageSlider();
                log("✅ SCAN BERHASIL! " + scannedImages.length + " gambar berhasil di-scan dan diproses");
            } else {
                log("Scanner berhasil terhubung tapi tidak ada gambar yang di-scan");
            }

            if (loading) loading.style.display = 'none';

            // PENTING: Pastikan flag sukses untuk mencegah modal timeout
            window.scanSuccessful = true;
            window.scannerDetected = true;

            return hasImages;
        }

        /** Images scanned so far. */
        var imagesScanned = [];

        /** Processes a ScannedImage */
        function processScannedImage(scannedImage) {
            // PENTING: Mark scan as successful SEGERA untuk mencegah modal muncul
            window.scanSuccessful = true;

            // Clear timeout jika ada untuk mencegah modal timeout
            if (window.currentScanTimeout) {
                clearTimeout(window.currentScanTimeout);
                window.currentScanTimeout = null;
            }

            imagesScanned.push(scannedImage);
            
            // Buat link dengan GLightbox untuk gambar hasil scan
            var linkElement = document.createElement('a');
            linkElement.href = scannedImage.src;
            linkElement.className = 'glightbox';
            linkElement.setAttribute('data-gallery', 'scan-gallery');
            linkElement.setAttribute('data-title', 'File Scan Baru');
            // linkElement.setAttribute('data-description', 'Hasil scan terbaru');
            
            var elementImg = scanner.createDomElementFromModel({
                'name': 'img',
                'attributes': {
                    'class': 'scanned',
                    'src': scannedImage.src,
                    'style': 'cursor: pointer; max-height: 200px; width: auto;'
                }
            });

            linkElement.appendChild(elementImg);

            // Tambahkan tombol X (hapus) di pojok kanan atas
            var closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.className = 'btn btn-sm btn-danger btn-close-scan';
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '5px';
            closeBtn.style.right = '5px';
            closeBtn.style.zIndex = '10';
            closeBtn.onclick = function(e) {
                e.preventDefault();
                var slideDiv = this.parentNode;
                if (slideDiv && slideDiv.parentNode) {
                    $(slideDiv).remove();
                    // Jika sudah tidak ada slide, reset input
                    if ($('#scannedImagesSlider > div').length === 0) {
                        var inputImg = document.getElementById('images_input');
                        if (inputImg) inputImg.value = '';
                    }
                    // Reinitialize GLightbox setelah menghapus gambar
                    if (typeof GLightbox !== 'undefined') {
                        setTimeout(function() {
                            initGLightbox();
                        }, 100);
                    }
                }
            };

            // Tambahkan gambar ke container slider
            var sliderContainer = document.getElementById('scannedImagesSlider');
            var slideDiv = document.createElement('div');
            slideDiv.style.position = 'relative';
            slideDiv.appendChild(linkElement);
            slideDiv.appendChild(closeBtn);
            if (sliderContainer) sliderContainer.appendChild(slideDiv);

            var inputImg = document.getElementById('images_input');
            if (inputImg) inputImg.value = scannedImage.src;
            var scanBtn = document.getElementById('scan_btn');
            if (scanBtn) scanBtn.remove();
            var loading = document.getElementById('scanLoading');
            if (loading) loading.style.display = 'none';

            // Reinitialize GLightbox untuk gambar baru
            if (typeof GLightbox !== 'undefined') {
                // Destroy existing instance dan buat yang baru
                setTimeout(function() {
                    initGLightbox();
                }, 100);
            }

            log("Gambar berhasil diproses dan ditambahkan ke slider dengan GLightbox");
        }

        /** Initialize image slider */
        function initImageSlider() {
            // Pastikan jQuery dan Slick sudah dimuat
            try {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.slick !== 'undefined') {
                    var $slider = $('#scannedImagesSlider');
                    if ($slider.length) {
                        // Destroy slick jika sudah ada
                        if ($slider.hasClass('slick-initialized')) {
                            $slider.slick('unslick');
                        }
                        $slider.slick({
                            dots: true,
                            infinite: true,
                            speed: 300,
                            slidesToShow: 1,
                            adaptiveHeight: true,
                            arrows: true
                        });
                    }
                }
            } catch (e) {
                console.error('Slick/initImageSlider error:', e);
            }
        }

        /** Show scanner install modal */
        function showScannerInstallModal() {
            // Log untuk debugging yang lebih jelas
            log("🚨 MENAMPILKAN MODAL: Scanner software TIDAK terdeteksi/terinstall!", true);

            var modal = document.getElementById('scannerInstallModal');
            if (modal) {
                try {
                    // Jika menggunakan Bootstrap 5
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bootstrapModal = new bootstrap.Modal(modal);
                        bootstrapModal.show();
                        log("Modal displayed via Bootstrap 5");
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        // Fallback untuk Bootstrap 4 dengan jQuery
                        $(modal).modal('show');
                        log("Modal displayed via Bootstrap 4/jQuery");
                    } else {
                        // Fallback manual jika Bootstrap tidak tersedia
                        modal.style.display = 'block';
                        modal.classList.add('show');
                        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                        // Tambahkan backdrop
                        var backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                        log("Modal displayed manually");
                    }
                } catch (e) {
                    console.error('Error showing modal:', e);
                    log("Error showing modal, using alert fallback: " + e, true);
                    // Fallback dengan alert jika modal error
                    if (confirm(
                            '❌ Scanner Software Tidak Terdeteksi!\n\nUntuk menggunakan fitur scan, Anda perlu menginstall software scanner terlebih dahulu.\n\n📥 Klik OK untuk download software scanner.'
                        )) {
                        window.open('https://drive.google.com/file/d/1XK2jaOzOMG7w8hrhtPxqrNoxliu80lPE/view?usp=sharing',
                            '_blank');
                    }
                }
            } else {
                // Fallback dengan alert jika modal tidak ada
                log("Modal element not found, using alert fallback", true);
                if (confirm(
                        '❌ Scanner Software Tidak Terdeteksi!\n\nUntuk menggunakan fitur scan, Anda perlu menginstall software scanner terlebih dahulu.\n\n📥 Klik OK untuk download software scanner.'
                    )) {
                    window.open('https://drive.google.com/file/d/1XK2jaOzOMG7w8hrhtPxqrNoxliu80lPE/view?usp=sharing',
                        '_blank');
                }
            }
        }
    </script>
@endpush

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12 ">
                    <a href="{{ route('entrisurat.index') }}" class="btn btn-secondary btn-sm mb-3">
                        <i class="iconoir-arrow-left"></i> Kembali ke Daftar Entri Surat
                    </a>
                    <a href="{{ route('disposisi.riwayat', $data->id) }}" class="btn btn-info btn-sm mb-3">
                        <i class="iconoir-eye"></i> Riwayat Surat
                    </a>
                </div>
            </div>

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Detail Entri Surat</h5>
                            </div>
                            <div>
                                <button
                                    class="btn btn-primary btn-sm rounded-pill shadow-sm d-flex align-items-center gap-2"
                                    type="button" id="toggleDetailBtn"
                                    style="font-size:0.95rem; font-family:'Poppins',Arial,sans-serif;">
                                    <i class="fa fa-chevron-down"></i>
                                    <span>Detail Lainnya</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover table-striped align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="col" class="px-3 py-2" style="width:80px; min-width:80px;">Dari:</th>
                                        <td class="align-middle px-3 py-2" style="width:70%">{{ $data->dari }}</td>
                                        <td class="text-end align-middle px-3 py-2" style="width:30%; white-space:nowrap;">
                                            {{ date('d-m-Y', strtotime($data->tgl_diterima)) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-3 py-2">Kepada:</th>
                                        <td class="align-middle px-3 py-2" colspan="2">
                                            @if ($data->kepada)
                                                {{ $data->kepada }}
                                            @elseif($data->tujuanSurat && count($data->tujuanSurat) > 0)
                                                @foreach ($data->tujuanSurat as $tujuan)
                                                    {{ $tujuan->user->FullName ?? '-' }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="p-0">
                                            <div class="collapse mt-2" id="detailCollapse" style="display:none;">
                                                <table class="table table-sm table-borderless mb-0 detail-table"
                                                    style="margin-bottom:0;">
                                                    <tr>
                                                        <th class="px-3 py-1" style="width:140px;">No. Surat</th>
                                                        <td class="px-3 py-1">{{ $data->nomor_surat }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Kepada</th>
                                                        <td class="px-3 py-1">{{ $data->kepada }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Hal</th>
                                                        <td class="px-3 py-1">{{ $data->hal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Kepada</th>
                                                        <td class="px-3 py-1">
                                                            @if ($data->kepada)
                                                                {{ $data->kepada }}
                                                            @elseif($data->tujuanSurat && count($data->tujuanSurat) > 0)
                                                                @foreach ($data->tujuanSurat as $tujuan)
                                                                    {{ $tujuan->user->FullName ?? '-' }}@if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Sifat</th>
                                                        <td class="px-3 py-1">{{ sifatSurat($data->sifat) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Jenis</th>
                                                        <td class="px-3 py-1">{{ $data->jenis ? $data->jenis->name : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">No. Agenda</th>
                                                        <td class="px-3 py-1">{{ $data->noagenda }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Tanggal surat</th>
                                                        <td class="px-3 py-1">
                                                            {{ date('d-m-Y', strtotime($data->tgl_surat)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Tanggal terima</th>
                                                        <td class="px-3 py-1">
                                                            {{ date('d-m-Y', strtotime($data->tgl_diterima)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Tembusan</th>
                                                        <td class="px-3 py-1">{{ $data->tembusan ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Klasifikasi</th>
                                                        <td class="px-3 py-1">
                                                            @if ($data->klasifikasi)
                                                                {{ $data->klasifikasi->klasifikasi }}
                                                                <small
                                                                    class="text-muted">({{ $data->klasifikasi->kodeklasifikasi }})</small>
                                                            @elseif($data->kode_klasifikasi)
                                                                @php
                                                                    // Coba cari klasifikasi berdasarkan kode atau ID
                                                                    $klasifikasi = null;

                                                                    // Coba cari berdasarkan kode klasifikasi
                                                                    $klasifikasiByKode = App\Models\MasterKlasifikasi::where(
                                                                        'kodeklasifikasi',
                                                                        $data->kode_klasifikasi,
                                                                    )->first();
                                                                    if ($klasifikasiByKode) {
                                                                        $klasifikasi = $klasifikasiByKode;
                                                                    } else {
                                                                        // Jika tidak ditemukan berdasarkan kode, coba cari berdasarkan ID (ULID)
                                                                        $klasifikasiById = App\Models\MasterKlasifikasi::find(
                                                                            $data->kode_klasifikasi,
                                                                        );
                                                                        if ($klasifikasiById) {
                                                                            $klasifikasi = $klasifikasiById;
                                                                        }
                                                                    }
                                                                @endphp

                                                                @if ($klasifikasi)
                                                                    {{ $klasifikasi->klasifikasi }}
                                                                    <small
                                                                        class="text-muted">({{ $klasifikasi->kodeklasifikasi }})</small>
                                                                @else
                                                                    {{ $data->kode_klasifikasi }}
                                                                    <small class="text-muted">(Data klasifikasi tidak
                                                                        ditemukan)</small>
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Alamat</th>
                                                        <td class="px-3 py-1">
                                                            {{ !empty($data->alamat) ? $data->alamat : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Referensi</th>
                                                        <td class="px-3 py-1">{{ $data->referensi_id ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-3 py-1">Unit Pengentri</th>
                                                        <td class="px-3 py-1">
                                                            @if ($data->createdBy)
                                                                {{ $data->createdBy->fullname }}
                                                                @if ($data->createdBy->Jabatan)
                                                                    <small
                                                                        class="text-muted d-block">{{ $data->createdBy->Jabatan }}</small>
                                                                @endif
                                                            @else
                                                                {{ $data->created_by ? 'ID: ' . $data->created_by : '-' }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($data->jumlah_lampiran)
                                                        <tr>
                                                            <th class="px-3 py-1">Jumlah Lampiran</th>
                                                            <td class="px-3 py-1">
                                                                {{ $data->jumlah_lampiran }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="px-3 py-1">Lampiran</th>
                                                        <td class="px-3 py-1">
                                                            @if ($data->lampiran)
                                                                <div class="text-wrap">{{ $data->lampiran }}</div>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($data->isi)
                                                        <tr>
                                                            <th class="px-3 py-1">Isi Surat</th>
                                                            <td class="px-3 py-1">
                                                                <div
                                                                    style="max-height: 150px; overflow-y: auto; font-size: 0.85rem; line-height: 1.5;">
                                                                    {!! nl2br(e($data->isi)) !!}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($data->tgl_diarahkan)
                                                        <tr>
                                                            <th class="px-3 py-1">Tanggal Diarahkan</th>
                                                            <td class="px-3 py-1">
                                                                {{ date('d-m-Y', strtotime($data->tgl_diarahkan)) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($data->terdisposisi)
                                                        <tr>
                                                            <th class="px-3 py-1">Status Disposisi</th>
                                                            <td class="px-3 py-1">
                                                                Sudah Didisposisi
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Tambahkan elemen loading -->
                            <div id="scanLoading" class="scan-loading">
                                <div class="spinner"></div>
                                <div class="loading-text">Menghubungkan ke Scanner...</div>
                                <div class="loading-subtext">Pastikan scanner/printer terhubung dan software scanner sudah
                                    terinstall. Tunggu hingga 30 detik untuk koneksi.</div>
                            </div>

                            <div class="container-fluid">
                                <!-- ... bagian sebelumnya tetap sama ... -->

                                <form action="{{ route('entrisurat.post.file.scan', $data->id) }}" method="post">
                                    @csrf
                                    <!-- Ganti div images dengan slider container -->
                                    <div id="scannedImagesSlider" class="scanned-images-slider"></div>
                                    <br>
                                    <input type="text" id="images_input" hidden name="images_input">
                                    <button type="button"
                                        class="btn btn-secondary btn-sm mb-3 d-lg-inline-flex align-items-center b-r-22"
                                        onclick="scan('default');" id="scan_btn">Scan File</button>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <button type="submit"
                                            class="btn btn-save-scan btn-sm d-lg-inline-flex align-items-center b-r-22">
                                            <i class="fa fa-save me-1"></i>Simpan File Scan
                                        </button>
                                        <div class="d-flex gap-2">
                                            <!-- Button untuk Tanda Terima dengan modal -->
                                            <button type="button"
                                                class="btn btn-info btn-sm d-lg-inline-flex align-items-center b-r-22"
                                                data-bs-toggle="modal" data-bs-target="#exportModal"
                                                data-export-type="tanda-terima">
                                                <i class="fa fa-receipt me-1"></i>Cetak Tanda Terima
                                            </button>

                                            <!-- Button untuk Cetak dengan modal -->
                                            <button type="button"
                                                class="btn btn-success btn-sm d-lg-inline-flex align-items-center b-r-22"
                                                data-bs-toggle="modal" data-bs-target="#exportModal"
                                                data-export-type="surat">
                                                <i class="fa fa-print me-1"></i>Cetak
                                            </button>

                                            <!-- Button untuk Cetak Disposisi dengan modal -->
                                            <button type="button"
                                                class="btn btn-primary btn-sm d-lg-inline-flex align-items-center b-r-22"
                                                data-bs-toggle="modal" data-bs-target="#exportModal"
                                                data-export-type="disposisi">
                                                <i class="fa fa-file-text me-1"></i>Cetak Disposisi
                                            </button>
                                        </div>
                                    </div>
                                    <div class="app-form">
                                        <textarea id="textarea_logging" rows="4" class="form-control" placeholder="Write your thoughts here...">--- Logging ---</textarea>
                                    </div>
                                </form>

                                <!-- ... bagian setelahnya tetap sama ... -->

                                @if ($data->FileScan->count() != 0)
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>File Scan</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="scanned-images-slider">
                                                    @foreach ($data->FileScan as $scan)
                                                        <div style="position:relative;">
                                                            <a href="{{ asset('uploads/' . $scan->nama_file) }}" 
                                                               class="glightbox" 
                                                               data-gallery="existing-scan-gallery"
                                                               data-title="File Scan - {{ $scan->nama_file }}">
                                                                <img src="{{ asset('uploads/' . $scan->nama_file) }}"
                                                                     alt="File Scan"
                                                                     style="cursor: pointer; max-height: 200px; width: auto; display: block; margin: 0 auto;">
                                                            </a>
                                                            <button class="btn btn-sm btn-danger btn-close-scan-file"
                                                                style="position:absolute;top:5px;right:5px;z-index:10;"
                                                                data-id="{{ $scan->id }}">&times;</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Default Card end -->
            </div>
            <!-- Blank end -->
        </div>

        <!-- Modal untuk Scanner Install Warning -->
        <div class="modal fade" id="scannerInstallModal" tabindex="-1" aria-labelledby="scannerInstallModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="scannerInstallModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Software Scanner Diperlukan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-scanner fa-3x text-warning mb-3"></i>
                            <h6 class="mb-3">Untuk menggunakan fitur scan, Anda perlu menginstall software tambahan</h6>
                            <p class="text-muted">
                                Software scanner diperlukan untuk menghubungkan aplikasi dengan mesin scanner/printer Anda.
                                Silakan download dan install software berikut:
                            </p>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>Langkah instalasi:</strong><br>
                                1. Download software dari link di bawah<br>
                                2. Install software dengan hak administrator<br>
                                3. Restart browser Anda<br>
                                4. Coba fitur scan kembali
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti Saja</button>
                        <a href="https://drive.google.com/file/d/1XK2jaOzOMG7w8hrhtPxqrNoxliu80lPE/view?usp=sharing"
                            target="_blank" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>Download Software Scanner
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk pilihan format export -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exportModalLabel">
                            <i class="fas fa-download me-2"></i>Pilih Format Export
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4" id="exportDescription">Pilih format file yang ingin Anda download:</p>

                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 export-format-btn"
                                    data-format="word" style="min-height: 120px;">
                                    <i class="fas fa-file-word fa-3x mb-3 text-primary"></i>
                                    <strong>Microsoft Word</strong>
                                    <small class="text-muted mt-1">Format .docx</small>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 export-format-btn"
                                    data-format="excel" style="min-height: 120px;">
                                    <i class="fas fa-file-excel fa-3x mb-3 text-success"></i>
                                    <strong>Microsoft Excel</strong>
                                    <small class="text-muted mt-1">Format .xlsx</small>
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>File akan otomatis terdownload setelah Anda memilih format.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <!-- Tambahkan jQuery dan Slick slider JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        // Inisialisasi slider untuk gambar yang sudah ada
        $(document).ready(function() {
            $('.scanned-images-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true,
                arrows: true
            });

            // Tunggu sebentar untuk memastikan DOM dan Slick sudah siap
            setTimeout(function() {
                console.log('Initializing GLightbox...');
                initGLightbox();
            }, 500);
        });

        // Fungsi untuk inisialisasi GLightbox
        function initGLightbox() {
            if (typeof GLightbox !== 'undefined') {
                // Destroy existing instances first
                const existingLightboxes = document.querySelectorAll('.glightbox');
                
                const lightbox = GLightbox({
                    touchNavigation: true,
                    loop: true,
                    autoplayVideos: false,
                    closeOnOutsideClick: true,
                    keyboardNavigation: true,
                    descPosition: 'bottom',
                    width: '90vw',
                    height: '90vh',
                    videosWidth: '90vw',
                    selector: '.glightbox', // Pastikan selector benar
                    skin: 'clean',
                    moreText: 'Lihat lebih banyak',
                    moreLength: 60,
                    slideEffect: 'slide',
                    openEffect: 'zoom',
                    closeEffect: 'zoom',
                    onOpen: function() {
                        console.log('GLightbox opened');
                    },
                    beforeSlideChange: function(slide, data) {
                        console.log('GLightbox: Before slide change');
                    },
                    afterSlideChange: function(slide, data) {
                        console.log('GLightbox: After slide change');
                    }
                });
                
                console.log('GLightbox initialized successfully with', existingLightboxes.length, 'images');
                
                // Test click handler - tambahkan manual click handler jika perlu
                existingLightboxes.forEach((el, index) => {
                    console.log('GLightbox element', index, ':', el.href);
                    // Manual click handler sebagai fallback
                    el.addEventListener('click', function(e) {
                        console.log('Manual click triggered for:', this.href);
                    });
                });
                
                return lightbox;
            } else {
                console.error('GLightbox not loaded');
                return null;
            }
        }
    </script>

    <!-- Script untuk menangani export modal -->
    <script>
        $(document).ready(function() {
            let currentExportType = '';

            // Ketika tombol export diklik
            $('button[data-export-type]').on('click', function() {
                currentExportType = $(this).data('export-type');

                // Update judul modal berdasarkan tipe export
                let title = '';
                let description = '';

                switch (currentExportType) {
                    case 'tanda-terima':
                        title = '<i class="fas fa-receipt me-2"></i>Export Tanda Terima Surat';
                        description = 'Pilih format file untuk Tanda Terima Surat:';
                        // Tampilkan kedua tombol untuk tanda terima dengan layout yang rapi
                        $('.export-format-btn[data-format="word"]').parent().show().removeClass('col-12').addClass('col-6');
                        $('.export-format-btn[data-format="excel"]').parent().show().removeClass('col-12').addClass('col-6');
                        break;
                    case 'surat':
                        title = '<i class="fas fa-print me-2"></i>Export Surat';
                        // description = 'Pilih format file untuk Surat:';
                        // Sembunyikan tombol Word untuk surat - hanya Excel
                        $('.export-format-btn[data-format="word"]').parent().hide();
                        $('.export-format-btn[data-format="excel"]').parent().removeClass('col-6').addClass('col-12');
                        break;
                    case 'disposisi':
                        title = '<i class="fas fa-file-text me-2"></i>Export Lembar Disposisi';
                        // description = 'Pilih format file untuk Lembar Disposisi:';
                        // Sembunyikan tombol Word untuk disposisi - hanya Excel
                        $('.export-format-btn[data-format="word"]').parent().hide();
                        $('.export-format-btn[data-format="excel"]').parent().removeClass('col-6').addClass('col-12');
                        break;
                }

                $('#exportModalLabel').html(title);
                $('#exportDescription').text(description);
            });

            // Ketika format dipilih
            $('.export-format-btn').on('click', function() {
                const format = $(this).data('format');
                let url = '';

                // Tentukan URL berdasarkan tipe export dan format
                const dataId = '{{ $data->id }}';

                if (currentExportType === 'tanda-terima') {
                    if (format === 'word') {
                        url = '{{ route('entrisurat.exportWord', ':id') }}'.replace(':id', dataId);
                    } else if (format === 'excel') {
                        url = '{{ route('entrisurat.exportExcel', ':id') }}'.replace(':id', dataId);
                    }
                } else if (currentExportType === 'surat') {
                    // Hanya Excel untuk surat - Word di-comment
                    // if (format === 'word') {
                    //     url = '{{ route('entrisurat.exportSuratWord', ':id') }}'.replace(':id', dataId);
                    // } else 
                    if (format === 'excel') {
                        url = '{{ route('entrisurat.exportSuratExcel', ':id') }}'.replace(':id', dataId);
                    }
                } else if (currentExportType === 'disposisi') {
                    // Hanya Excel untuk disposisi - Word di-comment
                    // if (format === 'word') {
                    //     url = '{{ route('entrisurat.exportSuratDisWord', ':id') }}'.replace(':id', dataId);
                    // } else 
                    if (format === 'excel') {
                        url = '{{ route('entrisurat.exportSuratDisExcel', ':id') }}'.replace(':id',
                        dataId);
                    }
                }

                // Buka URL di tab baru untuk download
                if (url) {
                    window.open(url, '_blank');

                    // Tutup modal setelah 1 detik
                    setTimeout(function() {
                        $('#exportModal').modal('hide');
                    }, 1000);
                }
            });

            // Efek hover untuk tombol format
            $('.export-format-btn').hover(
                function() {
                    $(this).removeClass('btn-outline-primary btn-outline-success')
                        .addClass($(this).data('format') === 'word' ? 'btn-primary' : 'btn-success');
                },
                function() {
                    $(this).removeClass('btn-primary btn-success')
                        .addClass($(this).data('format') === 'word' ? 'btn-outline-primary' :
                            'btn-outline-success');
                }
            );
        });
    </script>

    <script>
                $(document).on('click', '.btn-close-scan-file', function(e) {
            e.preventDefault();
            if (confirm('Yakin hapus file scan ini?')) {
                var id = $(this).data('id');
                var btn = $(this);
                $.ajax({
                    url: '/entrisurat/scan/' + id + '/delete',
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        btn.closest('div').remove();
                        // Reinitialize GLightbox setelah menghapus
                        setTimeout(function() {
                            initGLightbox();
                        }, 100);
                        if (res.success) {
                            alert('File scan berhasil dihapus');
                        }
                    },
                    error: function() {
                        alert('Gagal menghapus file scan');
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('toggleDetailBtn');
            var detail = document.getElementById('detailCollapse');
            btn.addEventListener('click', function() {
                if (detail.style.display === 'none') {
                    detail.style.display = 'block';
                    btn.textContent = 'Tutup Detail';
                } else {
                    detail.style.display = 'none';
                    btn.textContent = 'Detail Lainnya';
                }
            });
        });
    </script>
@endpush
