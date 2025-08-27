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

            @php
                $indicator = $data->getPriorityIndicatorForUser(Auth::user()->id);
            @endphp
            @if($indicator)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $indicator['color'] }} alert-dismissible fade show" role="alert">
                            <i class="fas {{ $indicator['icon'] }} me-2"></i>
                            <strong>Tindakan Diperlukan:</strong> {{ $indicator['text'] }}
                            @if($indicator['level'] == 'high')
                                - Surat ini memerlukan tindakan segera dari Anda.
                            @elseif($indicator['level'] == 'medium')
                                - Surat ini memerlukan perhatian khusus dari Anda.
                            @else
                                - Silakan tindaklanjuti sesuai instruksi disposisi.
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
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
                                        @php
                                            $indicator = $data->getPriorityIndicatorForUser(Auth::user()->id);
                                        @endphp
                                        @if($indicator)
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Tindakan
                                            </th>
                                            <td>
                                                <div class="d-flex align-items-start flex-column">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas {{ $indicator['icon'] }} text-{{ $indicator['color'] }} me-2" style="font-size: 1.2em;"></i>
                                                        @if($tujuanUser && $tujuanUser->dibaca == 1)
                                                            <span class="text-{{ $indicator['color'] }} fw-bold">Tindakan yang Diperlukan</span>
                                                        @else
                                                            <span class="badge bg-{{ $indicator['color'] }} badge-lg">Tindakan yang Diperlukan</span>
                                                        @endif
                                                        @if($indicator['level'] == 'high' && (!$tujuanUser || $tujuanUser->dibaca == 0))
                                                            <small class="text-muted ms-2">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Memerlukan tindakan segera
                                                            </small>
                                                        @elseif($indicator['level'] == 'medium' && (!$tujuanUser || $tujuanUser->dibaca == 0))
                                                            <small class="text-muted ms-2">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Memerlukan perhatian
                                                            </small>
                                                        @endif
                                                    </div>
                                                    
                                                    @if(isset($indicator['tindakans']) && count($indicator['tindakans']) > 0)
                                                        <div class="tindakan-list">
                                                            @foreach($indicator['tindakans'] as $index => $tindakan)
                                                                @if($tujuanUser && $tujuanUser->dibaca == 1)
                                                                    <span class="badge bg-outline-{{ $indicator['color'] }} text-{{ $indicator['color'] }} me-1 mb-1 border border-{{ $indicator['color'] }}">
                                                                        {{ $tindakan }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-{{ $indicator['color'] }} me-1 mb-1">
                                                                        {{ $tindakan }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
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

                                @php
                                    $userDisposisi = $data->getLatestDisposisiForUser(Auth::user()->id);
                                @endphp
                                @if($userDisposisi)
                                    <div class="mt-4">
                                        <h6 class="mb-3">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Disposisi Terbaru untuk Anda
                                        </h6>
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-sm-3">
                                                        <strong>Tanggal Disposisi:</strong>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @if($userDisposisi->remitten)
                                                            {{ \Carbon\Carbon::parse($userDisposisi->remitten)->format('d-m-Y') }}
                                                        @else
                                                            {{ $userDisposisi->created_at->format('d-m-Y') }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-3">
                                                        <strong>Tindakan:</strong>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @foreach($userDisposisi->tindakans as $tindakan)
                                                            @if($tujuanUser && $tujuanUser->dibaca == 1)
                                                                <span class="text-primary">{{ $tindakan->tindakan }}</span>@if(!$loop->last), @endif
                                                            @else
                                                                <span class="badge bg-primary me-1">{{ $tindakan->tindakan }}</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @if($userDisposisi->content)
                                                    <div class="row mb-2">
                                                        <div class="col-sm-3">
                                                            <strong>Catatan:</strong>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <div class="catatan-content">
                                                                {!! $userDisposisi->content !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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

    /* Tindakan indicator styling */
    .badge-lg {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
        font-weight: 600;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-warning {
        color: #fd7e14 !important;
    }

    .text-info {
        color: #0dcaf0 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .bg-warning {
        background-color: #fd7e14 !important;
    }

    .bg-info {
        background-color: #0dcaf0 !important;
    }

    /* Pulsing animation for high priority */
    @keyframes pulse-danger {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    .bg-danger {
        animation: pulse-danger 2s infinite;
    }

    /* Icon styling for better visibility */
    .fas.fa-exclamation-triangle,
    .fas.fa-exclamation-circle,
    .fas.fa-info-circle {
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Text styling for read tindakan */
    .text-danger.fw-bold {
        font-weight: 700 !important;
        font-size: 1.1em;
    }

    .text-warning.fw-bold {
        font-weight: 700 !important;
        font-size: 1.1em;
    }

    .text-info.fw-bold {
        font-weight: 700 !important;
        font-size: 1.1em;
    }

    .text-primary {
        font-weight: 600;
        font-size: 1em;
    }

    /* Disposisi card styling */
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }

    .border-left-primary .card-body {
        background-color: #f8f9ff;
    }

    /* Alert responsive styling */
    @media (max-width: 768px) {
        .alert {
            font-size: 0.9em;
        }
        .badge-lg {
            font-size: 0.8em;
            padding: 0.4em 0.6em;
        }
    }

    /* Tindakan list styling */
    .tindakan-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .tindakan-list .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    /* Outline badge styling for read state */
    .badge.bg-outline-danger {
        background-color: transparent !important;
        color: #dc3545 !important;
        border: 1px solid #dc3545 !important;
    }

    .badge.bg-outline-warning {
        background-color: transparent !important;
        color: #fd7e14 !important;
        border: 1px solid #fd7e14 !important;
    }

    .badge.bg-outline-info {
        background-color: transparent !important;
        color: #0dcaf0 !important;
        border: 1px solid #0dcaf0 !important;
    }

    /* Tindakan container */
    .d-flex.align-items-start.flex-column {
        width: 100%;
    }

    /* Better spacing for tindakan section */
    .tindakan-list .badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }

    /* Catatan content styling */
    .catatan-content {
        color: #212529;
        font-size: 0.95em;
        line-height: 1.5;
        padding: 0;
        margin: 0;
    }

    .catatan-content p {
        margin-bottom: 0.5rem;
        color: #212529;
    }

    .catatan-content p:last-child {
        margin-bottom: 0;
    }

    /* Remove any default styling from rich text editor content */
    .catatan-content * {
        background-color: transparent !important;
        background: none !important;
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
