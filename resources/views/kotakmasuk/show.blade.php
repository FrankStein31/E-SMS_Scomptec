@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <a href="{{ route('kotakmasuk.index') }}" class="btn btn-secondary btn-sm mb-3 btn-back-secondary"
                       style="background-color: #e9ecef !important; color: #000000 !important; border-color: #ced4da !important; font-weight: 600 !important; text-decoration: none;"
                       onmouseover="this.style.backgroundColor='#6c757d'; this.style.color='#ffffff'; this.style.borderColor='#5c636a';"
                       onmouseout="this.style.backgroundColor='#e9ecef'; this.style.color='#000000'; this.style.borderColor='#ced4da';">
                        <i class="iconoir-arrow-left"></i> Kembali ke Kotak Masuk
                    </a>
                    <!-- <h5 class="main-title">Kotak Masuk</h5>
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
                                    Kotak Masuk
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Detail</a>
                        </li>
                    </ul> -->
                </div>
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Detail Kotak Masuk Surat : {{ $data->nomor_surat }}</h5>
                                </div>
                                <div class="col text-end">
                                    {{-- <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
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

                                <div class="mb-4"></div>
                                @if($tujuanUser && $tujuanUser->dibaca == 0)
                                    <form action="{{ route('kotakmasuk.tandai-dibaca', [$data->id, $tujuanUser->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm b-r-22 btn-mark-read" 
                                                style="background-color: #fff3cd !important; color: #000000 !important; border-color: #ffeaa7 !important; font-weight: 600 !important;"
                                                onmouseover="this.style.backgroundColor='#6c757d'; this.style.color='#ffffff'; this.style.borderColor='#5c636a';"
                                                onmouseout="this.style.backgroundColor='#fff3cd'; this.style.color='#000000'; this.style.borderColor='#ffeaa7';">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                    <button class="btn btn-info btn-sm b-r-22 btn-disposisi" disabled
                                            style="background-color: #d1ecf1 !important; color: #000000 !important; border-color: #bee5eb !important; font-weight: 600 !important; opacity: 0.6;">
                                        Disposisi
                                    </button>
                                    <div class="text-danger mt-2 small">Anda harus menandai surat sudah dibaca untuk melakukan disposisi</div>
                                @elseif($tujuanUser && $tujuanUser->dibaca == 1)
                                    <button class="btn btn-success btn-sm b-r-22 btn-already-read" disabled
                                            style="background-color: #d1edcc !important; color: #000000 !important; border-color: #c3e6cb !important; font-weight: 600 !important; opacity: 0.6;">
                                        Sudah Dibaca
                                    </button>
                                    @if($users->isEmpty())
                                        <button class="btn btn-info btn-sm b-r-22 btn-disposisi" disabled
                                                style="background-color: #d1ecf1 !important; color: #000000 !important; border-color: #bee5eb !important; font-weight: 600 !important; opacity: 0.6;">
                                            Disposisi
                                        </button>
                                        <div class="text-danger mt-2 small">Anda merupakan posisi paling bawah, tidak dapat mendisposisikan surat</div>
                                    @else
                                        <a href="{{ route('kotakmasuk.disposisi', $data->id) }}" class="btn btn-info btn-sm b-r-22 btn-disposisi"
                                           style="background-color: #d1ecf1 !important; color: #000000 !important; border-color: #bee5eb !important; font-weight: 600 !important; text-decoration: none;"
                                           onmouseover="this.style.backgroundColor='#6c757d'; this.style.color='#ffffff'; this.style.borderColor='#5c636a';"
                                           onmouseout="this.style.backgroundColor='#d1ecf1'; this.style.color='#000000'; this.style.borderColor='#bee5eb';">
                                            Disposisi
                                        </a>
                                    @endif
                                @endif
                                <!-- {{-- <a href="" class="btn btn-info btn-sm b-r-22" hidden>Riw. Surat</a> --}}
                                {{-- <a href="" class="btn btn-info btn-sm b-r-22" hidden>Cetak</a> --}} -->
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

@push('styles')
<style>
    .btn-back-secondary.btn-secondary {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #000000 !important;
        font-weight: 600 !important;
    }
    
    .btn-back-secondary.btn-secondary:hover,
    .btn-back-secondary.btn-secondary:focus,
    .btn-back-secondary.btn-secondary:active {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: #ffffff !important;
    }
    
    .btn-mark-read.btn-warning {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #000000 !important;
        font-weight: 600 !important;
    }
    
    .btn-mark-read.btn-warning:hover,
    .btn-mark-read.btn-warning:focus,
    .btn-mark-read.btn-warning:active {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: #ffffff !important;
    }
    
    .btn-disposisi.btn-info {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #000000 !important;
        font-weight: 600 !important;
    }
    
    .btn-disposisi.btn-info:hover,
    .btn-disposisi.btn-info:focus,
    .btn-disposisi.btn-info:active {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: #ffffff !important;
    }
    
    .btn-already-read.btn-success {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #000000 !important;
        font-weight: 600 !important;
    }
    
    .btn-already-read.btn-success:hover,
    .btn-already-read.btn-success:focus,
    .btn-already-read.btn-success:active {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: #ffffff !important;
    }
    
    /* Focus states for accessibility */
    .btn-back-secondary:focus,
    .btn-back-secondary:active,
    .btn-mark-read:focus,
    .btn-mark-read:active,
    .btn-disposisi:focus,
    .btn-disposisi:active,
    .btn-already-read:focus,
    .btn-already-read:active {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25) !important;
    }
    
    /* Disabled button styling for better readability */
    .btn-disposisi:disabled,
    .btn-already-read:disabled {
        opacity: 0.6;
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #6c757d !important;
    }
</style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush
