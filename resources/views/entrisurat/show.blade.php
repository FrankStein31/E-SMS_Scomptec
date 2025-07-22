@extends('layout.main')

@push('css')
    <script src="{{ asset('assets/scanner.js') }}"></script>
    <!-- Tambahkan CSS untuk loading dan slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        /* Loading spinner */
        .scan-loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .scan-loading .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
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

            // Tampilkan loading
            document.getElementById('scanLoading').style.display = 'flex';

            scanRequest.source_name = sourceName;
            log("Attempts to scan with source = " + scanRequest.source_name + " ...");
            scanner.scan(handleScanResult, scanRequest);
        }

        /** Checks response before parsing and performs fallback scanning if possible. */
        function handleScanResult(successful, mesg, response) {
            // Sembunyikan loading
            document.getElementById('scanLoading').style.display = 'none';

            var errorMesg = successful ? undefined : mesg;

            if (successful && response != null) {
                var responseAsJson = JSON.parse(response);
                if (responseAsJson != null && responseAsJson.image_count == 0 && responseAsJson.last_transfer_rc ==
                    "TWRC_FAILURE") {
                    errorMesg = "Device failure";
                } else {
                    try {
                        displayImagesOnPage(successful, mesg, response);
                    } catch (exp) {
                        errorMesg = exp;
                    }
                }
            }

            // feel free to check response and add other failure criteria ...

            if (errorMesg) {
                log("Error occurred when scanning with source = " + scanRequest.source_name + ": " + errorMesg, true);
                if (scanRequest.source_name == 'default') { // fall back to select
                    log("Failed to scan with source = " + scanRequest.source_name + "; attempt source = 'select' ...");
                    scan('select');
                } else { // report final error here ...
                    log("Fatal error: Failed to scan (tried both default and select)", true);
                }
            } else {
                log("Scan succeeds with source = " + scanRequest.source_name);
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
            if (!successful) { // On error
                console.error('Failed: ' + mesg);
                var loading = document.getElementById('scanLoading');
                if (loading) loading.style.display = 'none';
                return;
            }

            if (successful && mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) { // User cancelled.
                console.info('User cancelled');
                var loading = document.getElementById('scanLoading');
                if (loading) loading.style.display = 'none';
                return;
            }

            // Bersihkan slider sebelum menambah gambar baru
            var sliderContainer = document.getElementById('scannedImagesSlider');
            if (sliderContainer) {
                sliderContainer.innerHTML = '';
            }
            var scannedImages = scanner.getScannedImages(response, true, false); // returns an array of ScannedImage
            for (var i = 0;
                (scannedImages instanceof Array) && i < scannedImages.length; i++) {
                var scannedImage = scannedImages[i];
                processScannedImage(scannedImage);
            }
            // Inisialisasi slider setelah gambar ditambahkan
            initImageSlider();
            var loading = document.getElementById('scanLoading');
            if (loading) loading.style.display = 'none';
        }

        /** Images scanned so far. */
        var imagesScanned = [];

        /** Processes a ScannedImage */
        function processScannedImage(scannedImage) {
            imagesScanned.push(scannedImage);
            var elementImg = scanner.createDomElementFromModel({
                'name': 'img',
                'attributes': {
                    'class': 'scanned',
                    'src': scannedImage.src
                }
            });

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
                }
            };

            // Tambahkan gambar ke container slider
            var sliderContainer = document.getElementById('scannedImagesSlider');
            var slideDiv = document.createElement('div');
            slideDiv.style.position = 'relative';
            slideDiv.appendChild(elementImg);
            slideDiv.appendChild(closeBtn);
            if (sliderContainer) sliderContainer.appendChild(slideDiv);

            var inputImg = document.getElementById('images_input');
            if (inputImg) inputImg.value = scannedImage.src;
            var scanBtn = document.getElementById('scan_btn');
            if (scanBtn) scanBtn.remove();
            var loading = document.getElementById('scanLoading');
            if (loading) loading.style.display = 'none';
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
                    <h4 class="main-title">Detail Entri Surat</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Daftar Entri Surat
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Detail</a>
                        </li>
                    </ul>
                </div>
            </div>

            @include('layout.alert')

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Detail Entri Surat</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover table-striped align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            No. Agenda
                                        </th>
                                        <td>
                                            {{ $data->noagenda }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Sifat
                                        </th>
                                        <td>
                                            {{ sifatSurat($data->sifat) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Jenis
                                        </th>
                                        <td>
                                            {{ $data->jenis->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            No. Surat
                                        </th>
                                        <td>
                                            {{ $data->nomor_surat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Dari
                                        </th>
                                        <td>
                                            {{ $data->dari }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Tujuan
                                        </th>
                                        <td>
                                            {{ $data->kepada }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Hal
                                        </th>
                                        <td>
                                            {{ $data->hal }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Unit Pengentri
                                        </th>
                                        <td>
                                            {{ $data->createdBy->fullname }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Tanggal
                                        </th>
                                        <td>
                                            {{ $data->created_at->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Tambahkan elemen loading -->
                            <div id="scanLoading" class="scan-loading">
                                <div class="spinner"></div>
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
                                    <button type="submit"
                                        class="btn btn-warning btn-sm mb-3 d-lg-inline-flex align-items-center b-r-22">Simpan
                                        File
                                        Scan</button>
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
                                                            <img src="{{ asset('uploads/' . $scan->nama_file) }}" alt="">
                                                            <button class="btn btn-sm btn-danger btn-close-scan-file" style="position:absolute;top:5px;right:5px;z-index:10;" data-id="{{ $scan->id }}">&times;</button>
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
    </main>
@endsection

@push('scripts')
    <!-- Tambahkan jQuery dan Slick slider JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
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
        });
    </script>
    <script>
    $(document).on('click', '.btn-close-scan-file', function(e){
        e.preventDefault();
        if(confirm('Yakin hapus file scan ini?')){
            var id = $(this).data('id');
            var btn = $(this);
            $.ajax({
                url: '/entrisurat/scan/'+id+'/delete',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', _method: 'DELETE'},
                success: function(res){
                    if(res.success){
                        // Setelah hapus, reload halaman dan kirim pesan sukses
                        location.reload();
                    } else {
                        alert(res.message || 'Gagal hapus file scan!');
                    }
                },
                error: function(){
                    alert('Gagal hapus file scan!');
                }
            });
        }
        });
    </script>
@endpush
