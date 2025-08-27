@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <!-- <div class="row m-1">
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
            </div> -->
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
                            <div class="table-responsive">
                                {!! $dataTable->table(['id' => 'kotakmasuk-table']) !!}
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
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    
    <script>
        $(document).ready(function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle row click untuk navigasi ke detail
            $('#kotakmasuk-table tbody').on('click', 'tr', function() {
                var data = $('#kotakmasuk-table').DataTable().row(this).data();
                if (data && data.id) {
                    window.location.href = '{{ route("kotakmasuk.show", ":id") }}'.replace(':id', data.id);
                }
            });
            
            // Add hover cursor pointer style
            $('#kotakmasuk-table tbody').on('mouseenter', 'tr', function() {
                $(this).css('cursor', 'pointer');
            });

            // Re-initialize tooltips after DataTable draw
            $('#kotakmasuk-table').on('draw.dt', function() {
                // Destroy existing tooltips
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                
                // Reinitialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        });
    </script>
@endpush

@push('styles')
<style>
    /* Styling untuk indikator tindakan */
    .badge-sm {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
    }
    
    /* Row highlight berdasarkan prioritas tindakan */
    #kotakmasuk-table tbody tr.priority-high {
        background-color: #fff5f5 !important;
        border-left: 4px solid #dc3545;
    }
    
    #kotakmasuk-table tbody tr.priority-medium {
        background-color: #fffbf0 !important;
        border-left: 4px solid #ffc107;
    }
    
    #kotakmasuk-table tbody tr.priority-low {
        background-color: #f0f9ff !important;
        border-left: 4px solid #17a2b8;
    }
    
    /* Hover effect */
    #kotakmasuk-table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    
    /* Icon animation */
    .text-danger i, .text-warning i, .text-info i {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    /* Tooltip untuk tindakan */
    .tindakan-tooltip {
        position: relative;
        cursor: help;
    }
    
    .tindakan-tooltip:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 1000;
        font-size: 12px;
    }
    
    /* Legend untuk indikator prioritas */
    .priority-legend {
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
    }
    
    .priority-legend h6 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 2px;
        margin-right: 0.5rem;
    }
    
    .legend-high { background-color: #dc3545; }
    .legend-medium { background-color: #ffc107; }
    .legend-low { background-color: #17a2b8; }

    /* Multiple tindakan styling */
    .tindakan-multiple {
        cursor: help;
    }

    .tindakan-multiple .badge {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
    }

    .tindakan-multiple .badge.bg-secondary {
        font-size: 0.7em;
        padding: 0.2em 0.4em;
    }

    /* Better tooltip styling */
    .tooltip {
        font-size: 0.875rem;
    }

    .tooltip-inner {
        max-width: 300px;
        text-align: left;
        background-color: #333;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }

    /* Enhanced badge styling */
    .badge-sm {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>
@endpush
