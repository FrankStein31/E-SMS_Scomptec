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

        /* Custom Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Outlined Card Borders */
        .card.border-primary {
            border: 2px solid #007bff !important;
            background-color: rgba(0, 123, 255, 0.02);
        }

        .card.border-success {
            border: 2px solid #28a745 !important;
            background-color: rgba(40, 167, 69, 0.02);
        }

        .card.border-info {
            border: 2px solid #17a2b8 !important;
            background-color: rgba(23, 162, 184, 0.02);
        }

        .card.border-warning {
            border: 2px solid #ffc107 !important;
            background-color: rgba(255, 193, 7, 0.02);
        }

        /* Text color adjustments for better readability */
        .text-primary {
            color: #0056b3 !important;
            font-weight: 600;
        }

        .text-success {
            color: #1e7e34 !important;
            font-weight: 600;
        }

        .text-info {
            color: #117a8b !important;
            font-weight: 600;
        }

        .text-warning {
            color: #d39e00 !important;
            font-weight: 600;
        }

        .text-dark {
            color: #343a40 !important;
            font-weight: 500;
        }

        .opacity-75 {
            opacity: 0.75;
        }

        /* Table improvements */
        .table th {
            border-top: none;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        /* Chart containers */
        #trendChart,
        #sifatChart {
            min-height: 300px;
        }

        /* Enhanced Button Styling for Better Text Visibility */
        .btn-action-detail {
            background-color: #e3f2fd !important;
            border-color: #bbdefb !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-detail:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-action-edit {
            background-color: #fff3cd !important;
            border-color: #ffeaa7 !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-edit:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-action-delete {
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-delete:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-add-primary {
            background-color: #cfe2ff !important;
            border-color: #b6d4fe !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-add-primary:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        /* Focus states for accessibility */
        .btn-action-detail:focus,
        .btn-action-detail:active,
        .btn-action-edit:focus,
        .btn-action-edit:active,
        .btn-action-delete:focus,
        .btn-action-delete:active,
        .btn-add-primary:focus,
        .btn-add-primary:active {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25) !important;
        }
    </style>
@endpush

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <!-- <div class="col-12 ">
                            <h5 class="main-title">Statistik</h5>
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
                        </div> -->
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-1 text-primary">{{ number_format($summary['total_masuk']) }}</h4>
                                    <p class="mb-0 text-dark">Total Surat Masuk</p>
                                    <small class="text-muted">
                                        @if ($summary['growth_masuk'] >= 0)
                                            <i class="fas fa-arrow-up text-success"></i> <span
                                                class="text-success">+{{ $summary['growth_masuk'] }}%</span>
                                        @else
                                            <i class="fas fa-arrow-down text-danger"></i> <span
                                                class="text-danger">{{ $summary['growth_masuk'] }}%</span>
                                        @endif
                                        dari tahun lalu
                                    </small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-inbox fa-2x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-1 text-success">{{ number_format($summary['total_keluar']) }}</h4>
                                            <p class="mb-0 text-dark">Total Surat Keluar</p>
                                            <small class="text-muted">
                                                @if ($summary['growth_keluar'] >= 0)
    <i class="fas fa-arrow-up text-success"></i> <span class="text-success">+{{ $summary['growth_keluar'] }}%</span>
@else
    <i class="fas fa-arrow-down text-danger"></i> <span class="text-danger">{{ $summary['growth_keluar'] }}%</span>
    @endif
                                                dari tahun lalu
                                            </small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-paper-plane fa-2x text-success opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-1 text-info">
                                        {{ number_format($summary['bulan_ini_masuk'] + $summary['bulan_ini_keluar']) }}</h4>
                                    <p class="mb-0 text-dark">Bulan Ini</p>
                                    <small class="text-muted">
                                        {{ $summary['bulan_ini_masuk'] }} masuk, {{ $summary['bulan_ini_keluar'] }} keluar
                                    </small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-month fa-2x text-info opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-1 text-warning">
                                        {{ number_format($summary['avg_masuk_per_bulan'] + $summary['avg_keluar_per_bulan']) }}
                                    </h4>
                                    <p class="mb-0 text-dark">Rata-rata/Bulan</p>
                                    <small class="text-muted">
                                        {{ $summary['avg_masuk_per_bulan'] }} masuk,
                                        {{ $summary['avg_keluar_per_bulan'] }} keluar
                                    </small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                            name="tahun">
                                            <option value="">Pilih Tahun</option>
                                            @foreach (getListTahun() as $item)
                                                <option value="{{ $item }}"
                                                    {{ isset($tahun) && $tahun == $item ? 'selected' : '' }}>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="jenis_surat">
                                            <option value="">Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->last_id }}"
                                                    {{ isset($jenis) && $jenis == $item->last_id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary btn-sm b-r-22 btn-add-primary mt-3">Tampilkan</button>
                            </form>
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Grafik Surat Masuk & Keluar per Bulan</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <figure class="highcharts-figure">
                                        <div id="container"></div>
                                    </figure>
                                </div>
                            </div>

                            <!-- Additional Statistics Row -->
                            <div class="row mt-4">
                                <!-- Trend Chart -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Trend 6 Bulan Terakhir</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="trendChart"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sifat Surat Pie Chart -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Distribusi Sifat Surat</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="sifatChart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Top Jenis Surat Table -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Top 10 Jenis Surat ({{ $tahun ?: date('Y') }})</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Jenis Surat</th>
                                                            <th class="text-center">Surat Masuk</th>
                                                            <th class="text-center">Surat Keluar</th>
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Persentase</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $totalAllJenis = array_sum(
                                                                array_column($jenisStatistik, 'total'),
                                                            );
                                                        @endphp
                                                        @foreach ($jenisStatistik as $index => $jenis)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $jenis['jenis'] }}</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-primary">{{ number_format($jenis['masuk']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-success">{{ number_format($jenis['keluar']) }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <strong>{{ number_format($jenis['total']) }}</strong>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($totalAllJenis > 0)
                                                                        {{ round(($jenis['total'] / $totalAllJenis) * 100, 1) }}%
                                                                    @else
                                                                        0%
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if (count($jenisStatistik) == 0)
                                                            <tr>
                                                                <td colspan="6" class="text-center text-muted">Tidak
                                                                    ada data untuk ditampilkan</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        let bulan = datas.map(item => item.nama_bulan);
        let masuk = datas.map(item => item.jumlah_masuk);
        let keluar = datas.map(item => item.jumlah_keluar);

        let trendData = @json($monthlyTrend);
        let sifatData = @json($sifatStatistik);

        // Main Chart - Monthly Statistics
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Statistik Surat Masuk & Keluar per Bulan ({{ $tahun ?: date('Y') }})'
            },
            xAxis: {
                categories: bulan,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Surat'
                }
            },
            tooltip: {
                shared: true,
                formatter: function() {
                    let tooltip = '<b>' + this.x + '</b><br/>';
                    this.points.forEach(function(point) {
                        tooltip += point.series.name + ': <b>' + point.y + '</b><br/>';
                    });
                    let total = this.points.reduce((sum, point) => sum + point.y, 0);
                    tooltip += 'Total: <b>' + total + '</b>';
                    return tooltip;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Surat Masuk',
                    data: masuk,
                    color: '#007bff'
                },
                {
                    name: 'Surat Keluar',
                    data: keluar,
                    color: '#28a745'
                }
            ]
        });

        // Trend Chart - Last 6 Months
        Highcharts.chart('trendChart', {
            chart: {
                type: 'line',
                height: 300
            },
            title: {
                text: null
            },
            xAxis: {
                categories: trendData.map(item => item.nama_bulan)
            },
            yAxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Total Surat',
                data: trendData.map(item => item.total),
                color: '#17a2b8'
            }]
        });

        // Sifat Chart - Pie Chart
        Highcharts.chart('sifatChart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 300
            },
            title: {
                text: null
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Jumlah: <b>{point.y}</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Sifat Surat',
                colorByPoint: true,
                data: sifatData.map(item => ({
                    name: item.sifat,
                    y: item.jumlah,
                    color: item.color
                }))
            }]
        });
    </script>
@endpush
