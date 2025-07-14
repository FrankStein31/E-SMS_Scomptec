@extends('layout.main')

@push('css')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        #container {
            height: 600px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: auto;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }
    </style>
@endpush

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h4 class="main-title">Statistik</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        {{-- <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Laporan
                                </span>
                            </a>
                        </li> --}}
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Statistik</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Filter</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" class="app-form" method="get">
                                <div class="row">
                                    <div class="col">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="jenis_surat">
                                            <option selected>Pilih Tahun</option>
                                            @foreach (getListTahun() as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="jenis_surat">
                                            <option selected>Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Tampilkan</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Grafik</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
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

    <script>
        let datas = @json($data);
        let arrayFix = [];

        for (let item of datas) {
            arrayFix.push([item.nama_bulan, item.jumlah]);
        }

        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Statistik'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    autoRotation: [-45, -90],
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'JUMLAH'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Jumlah : <b>{point.y:.1f}</b>'
            },
            series: [{
                name: 'Jumlah',
                colors: [
                    '#8E7DBE'
                ],
                colorByPoint: true,
                groupPadding: 0,
                data: arrayFix,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    inside: true,
                    verticalAlign: 'top',
                    format: '{point.y:.1f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    </script>
@endpush
