@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Disposisi</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a class="f-s-14 f-w-500" href="{{ route('disposisi.index') }}">
                                <span>
                                    Disposisi
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Detail</a>
                        </li>
                    </ul>
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
                                    <h5>Desposisi Surat</h5>
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
                                                {{ $disposisi->noagenda }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Sifat
                                            </th>
                                            <td>
                                                {{ sifatSurat($disposisi->sifat) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Jenis
                                            </th>
                                            <td>
                                                {{ $disposisi->jenis->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                No. Surat
                                            </th>
                                            <td>
                                                {{ $disposisi->nomor_surat }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Dari
                                            </th>
                                            <td>
                                                {{ $disposisi->dari }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Tujuan
                                            </th>
                                            <td>
                                                {{ $disposisi->kepada }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Hal
                                            </th>
                                            <td>
                                                {{ $disposisi->hal }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Unit Pengentri
                                            </th>
                                            <td>
                                                {{ $disposisi->createdBy->fullname }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Tanggal
                                            </th>
                                            <td>
                                                {{ $disposisi->created_at->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mb-4"></div>
                                <a href="" class="btn btn-info btn-sm b-r-22" >Ubah</a>
                                {{-- <a href="{{ route('kotakmasuk.disposisi', $disposisi->id) }}" class="btn btn-info btn-sm b-r-22">Disposisi</a> --}}
                                <a href="" class="btn btn-info btn-sm b-r-22" >Riw. Surat</a>
                                <a href="" class="btn btn-info btn-sm b-r-22" >Cetak</a>
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

@push('js')
    <script>
        $(document).ready(function() {
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush
