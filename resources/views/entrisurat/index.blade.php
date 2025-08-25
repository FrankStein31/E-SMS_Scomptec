@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- <div class="row m-1">
                    <div class="col-12">
                        <h5 class="main-title">Entri Surat</h5>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li>
                                <a class="f-s-14 f-w-500" href="#">
                                    <span>Home</span>
                                </a>
                            </li>
                            <li class="active">
                                <a class="f-s-14 f-w-500" href="#">Daftar Entri Surat</a>
                            </li>
                        </ul>
                    </div>
                </div> -->
            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Daftar Entri Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <!-- Action Buttons for Selected Row -->
                                    <div id="actionButtons" class="action-buttons d-none me-2">
                                        <button id="detailBtn" class="btn btn-info btn-sm b-r-22 me-1 btn-action-detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button id="editBtn" class="btn btn-warning btn-sm b-r-22 me-1 btn-action-edit">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                        <button id="deleteBtn" class="btn btn-danger btn-sm b-r-22 me-1 btn-action-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <a href="{{ route('entrisurat.create') }}" class="btn btn-primary btn-sm b-r-22 btn-add-primary">
                                        <i class="iconoir-plus"></i> Tambah Entri Surat
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <select id="filterSifat" class="form-select form-select-sm"
                                        data-placeholder="-- Semua Sifat --">
                                        <option value="">-- Semua Sifat --</option>
                                        <option value="1">Penting</option>
                                        <option value="2">Rahasia</option>
                                        <option value="3">Biasa</option>
                                        <option value="4">Pribadi</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="filterJenis" class="form-select form-select-sm"
                                        data-placeholder="-- Semua Jenis --">
                                        <option value="">-- Semua Jenis --</option>
                                        @foreach (App\Models\MasterJenisSurat::all() as $jenis)
                                            <option value="{{ $jenis->last_id }}">{{ $jenis->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="filterUnit" class="form-select form-select-sm"
                                        data-placeholder="-- Semua Unit Pengentri --">
                                        <option value="">-- Semua Unit Pengentri --</option>
                                        @foreach (App\Models\User::orderBy('fullname')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="filterTujuan" class="form-select form-select-sm"
                                        data-placeholder="-- Semua Tujuan --">
                                        <option value="">-- Semua Tujuan --</option>
                                        @foreach (App\Models\EntrySuratIsi::select('kepada')->distinct()->get() as $row)
                                            @if ($row->kepada)
                                                <option value="{{ $row->kepada }}">{{ $row->kepada }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">  
                                    <div class="input-group">
                                        <input type="text" id="customSearch" class="form-control form-control-sm"
                                            placeholder="Search...">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" id="clearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                {{ $dataTable->table(['id' => 'tabelEntriSurat']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <!-- Note: DataTables Select Extension is now loaded in main layout -->
    <style>
        /* Sticky Table Container */
        .dataTables_wrapper {
            position: relative;
        }

        /* DataTables Scroll Configuration */
        .dataTables_scroll {
            overflow: visible;
        }

        .dataTables_scrollHead {
            overflow: visible;
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: white;
        }

        .dataTables_scrollHeadInner {
            box-sizing: content-box;
        }

        .dataTables_scrollHeadInner table {
            margin-bottom: 0 !important;
        }

        .dataTables_scrollBody {
            overflow: auto;
            max-height: 60vh;
        }

        /* Remove conflicting sticky header styles */
        #entrysuratisi-table thead th,
        #tabelEntriSurat thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            position: relative;
        }

        /* Sticky Pagination */
        .dataTables_wrapper .row:last-child {
            position: sticky;
            bottom: 0;
            background-color: white;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
            z-index: 5;
            margin: 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Table wrapper optimization */
        .table-responsive {
            height: calc(100vh - 300px);
            position: relative;
            overflow: visible;
        }

        /* Ensure table columns maintain consistent width */
        #entrysuratisi-table,
        #tabelEntriSurat {
            table-layout: fixed;
            width: 100%;
        }

        /* Sync scroll between header and body */
        .dataTables_wrapper {
            overflow: visible;
        }

        /* Compact table rows */
        #entrysuratisi-table tbody td,
        #tabelEntriSurat tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Optimize header height */
        #entrysuratisi-table thead th,
        #tabelEntriSurat thead th {
            padding: 10px 8px !important;
        }

        /* Row Selection Styling */
        #entrysuratisi-table tbody tr,
        #tabelEntriSurat tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #entrysuratisi-table tbody tr:hover,
        #tabelEntriSurat tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #entrysuratisi-table tbody tr.selected,
        #tabelEntriSurat tbody tr.selected {
            background-color: #d1ecf1 !important;
        }

        /* Action Buttons */
        .action-buttons {
            transition: all 0.3s ease;
        }

        .action-buttons.show {
            animation: fadeInScale 0.3s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
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
    <script>
        $(document).ready(function() {
            // Initialize Select2 with error handling
            try {
                $('#filterSifat, #filterJenis, #filterUnit, #filterTujuan').select2({
                    width: '100%',
                    allowClear: true
                });
            } catch (error) {
                console.warn('Select2 initialization failed:', error);
            }

            // Filter functionality with error handling
            $('#filterSifat, #filterJenis, #filterUnit, #filterTujuan').on('change', function() {
                try {
                    let sifat = $('#filterSifat').val();
                    let jenis = $('#filterJenis').val();
                    let unit = $('#filterUnit').val();
                    let tujuan = $('#filterTujuan').val();

                    if (window.LaravelDataTables && window.LaravelDataTables['tabelEntriSurat']) {
                        window.LaravelDataTables['tabelEntriSurat'].ajax.url('?sifat=' + sifat + '&jenis=' +
                            jenis + '&unit_pengentri=' + unit + '&tujuan=' + tujuan).load();
                    }
                } catch (error) {
                    console.warn('Filter functionality error:', error);
                }
            });

            // Custom search functionality with error handling
            $('#customSearch').on('keyup', function() {
                try {
                    if (window.LaravelDataTables && window.LaravelDataTables['tabelEntriSurat']) {
                        window.LaravelDataTables['tabelEntriSurat'].search(this.value).draw();
                    }
                } catch (error) {
                    console.warn('Search functionality error:', error);
                }
            });

            // Clear search button
            $('#clearSearch').on('click', function() {
                try {
                    $('#customSearch').val('');
                    if (window.LaravelDataTables && window.LaravelDataTables['tabelEntriSurat']) {
                        window.LaravelDataTables['tabelEntriSurat'].search('').draw();
                    }
                } catch (error) {
                    console.warn('Clear search error:', error);
                }
            });

            // Wait for DataTable to load then add click events with better error handling
            function initializeTableInteractions() {
                try {
                    console.log('Initializing table interactions...');

                    // Debug: Check if table exists
                    if ($('#tabelEntriSurat tbody').length) {
                        console.log('Table found with ID: tabelEntriSurat');
                    }
                    if ($('#entrysuratisi-table tbody').length) {
                        console.log('Table found with ID: entrysuratisi-table');
                    }

                    // Sync horizontal scroll between header and body
                    $('.dataTables_scrollBody').on('scroll', function() {
                        try {
                            var scrollLeft = $(this).scrollLeft();
                            $('.dataTables_scrollHead').scrollLeft(scrollLeft);
                        } catch (error) {
                            console.warn('Scroll sync error:', error);
                        }
                    });

                    // Try both possible table IDs
                    var tableSelector = '#tabelEntriSurat tbody tr, #entrysuratisi-table tbody tr';

                    // Remove any existing handlers
                    $(document).off('click', tableSelector);

                    // Add click handler with debugging
                    $(document).on('click', tableSelector, function(e) {
                        try {
                            console.log('Row clicked!', this);

                            // Prevent action if clicking on buttons
                            if ($(e.target).closest('.btn').length) {
                                console.log('Button clicked, ignoring...');
                                return;
                            }

                            var clickedRow = $(this);
                            var rowId = clickedRow.attr('id');

                            // Toggle selection on same row, clear others
                            if (clickedRow.hasClass('selected')) {
                                // If clicking the same selected row, deselect it
                                clickedRow.removeClass('selected');
                                $('#actionButtons').removeClass('show d-inline-block').addClass('d-none');
                                // Reset edit button visibility when deselecting
                                $('#editBtn').removeClass('d-none');
                                console.log('Row deselected');
                            } else {
                                // Clear all selections first, then select clicked row
                                $('#tabelEntriSurat tbody tr, #entrysuratisi-table tbody tr').removeClass(
                                    'selected');
                                clickedRow.addClass('selected');

                                // Check status baca to determine if edit button should be shown
                                var statusBacaCell = clickedRow.find('td').eq(
                                8); // Kolom status_baca (index ke-8)
                                var statusBacaText = statusBacaCell.text().trim();
                                var sudahDibaca = statusBacaText.includes('Sudah Dibaca');

                                console.log('Status baca:', statusBacaText, 'Sudah dibaca:', sudahDibaca);

                                // Show action buttons with animation
                                $('#actionButtons').removeClass('d-none').addClass('d-inline-block show');

                                // Hide/show edit button based on read status
                                if (sudahDibaca) {
                                    $('#editBtn').addClass('d-none');
                                    console.log('Edit button hidden - surat sudah dibaca');
                                } else {
                                    $('#editBtn').removeClass('d-none');
                                    console.log('Edit button shown - surat belum dibaca');
                                }

                                // Store selected row ID for action buttons
                                $('#actionButtons').data('selectedId', rowId);

                                console.log('Row selected (single mode), ID:', rowId);
                            }
                        } catch (error) {
                            console.error('Row click handler error:', error);
                        }
                    });
                } catch (error) {
                    console.error('Table initialization error:', error);
                }
            }

            // Initialize with delays to ensure proper loading
            setTimeout(initializeTableInteractions, 1000);
            setTimeout(initializeTableInteractions, 3000);

            // Action button handlers with error handling
            $(document).on('click', '#detailBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    if (selectedId) {
                        window.location.href = '/entrisurat/' + selectedId;
                    }
                } catch (error) {
                    console.error('Detail button error:', error);
                }
            });

            $(document).on('click', '#editBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    if (selectedId) {
                        window.location.href = '/entrisurat/' + selectedId + '/edit';
                    }
                } catch (error) {
                    console.error('Edit button error:', error);
                }
            });

            $(document).on('click', '#deleteBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    if (selectedId) {
                        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                            // Create form and submit for DELETE request
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': '/entrisurat/' + selectedId
                            });

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_token',
                                'value': $('meta[name="csrf-token"]').attr('content')
                            }));

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_method',
                                'value': 'DELETE'
                            }));

                            $('body').append(form);
                            form.submit();
                        }
                    }
                } catch (error) {
                    console.error('Delete button error:', error);
                }
            });
        });
    </script>
@endpush
