@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Kotak Masuk</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Kotak Masuk</a>
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
                                    <h5>Kotak Masuk Surat</h5>
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
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                No
                                            </th>
                                            <th scope="col">
                                                No. Agenda
                                            </th>
                                            <th scope="col">
                                                Sifat
                                            </th>
                                            <th scope="col">
                                                Jenis
                                            </th>
                                            <th scope="col">
                                                No. Surat
                                            </th>
                                            <th scope="col">
                                                Dari
                                            </th>
                                            <th scope="col">
                                                Tujuan
                                            </th>
                                            <th scope="col">
                                                Hal
                                            </th>
                                            <th scope="col">
                                                Unit Pengentri
                                            </th>
                                            <th scope="col">
                                                Tanggal
                                            </th>
                                            <th scope="col">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data != null)
                                            @foreach ($data as $item)
                                                <tr data-href='{{ route('kotakmasuk.show', $item->id) }}' class="clickable-row">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $item->noagenda }}
                                                    </td>
                                                    <td>
                                                        {{ sifatSurat($item->sifat) }}
                                                    </td>
                                                    <td>
                                                        {{ $item->jenis->name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->nomor_surat }}
                                                    </td>
                                                    <td>
                                                        {{ $item->dari }}
                                                    </td>
                                                    <td>
                                                        {{ $item->kepada }}
                                                    </td>
                                                    <td>
                                                        {{ $item->hal }}
                                                    </td>
                                                    <td>
                                                        {{ $item->createdby->fullname }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $item->tgl_surat }}
                                                    </td>
                                                    <td>
                                                        @php $tujuan = $item->tujuanSurat->first(); @endphp
                                                        @if($tujuan && $tujuan->dibaca)
                                                            <span class="badge bg-success">Dibaca</span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dibaca</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
