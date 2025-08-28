show @extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Daftar Unit Kerja</h5>
                                </div>
                                <div class="col text-end">
                                    <!-- Action Buttons for Selected Row -->
                                    <div id="actionButtons" class="action-buttons d-none me-2">
                                        <button id="editBtn" class="btn btn-warning btn-sm b-r-22 me-1 btn-action-edit">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                        <button id="deleteBtn" class="btn btn-danger btn-sm b-r-22 me-1 btn-action-delete">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm b-r-22 btn-add-primary"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fas fa-plus"></i> Tambah Unit Kerja
                                    </button>
                                    <!-- Modal Tambah Unit Kerja -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <form action="{{ route('unitkerja.store') }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Unit</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Unit Kerja:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="satker" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Kode Unit:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="kodesatker"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary" data-prevent-double>Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-sticky">
                                {{ $dataTable->table(['id' => 'mastersatker-table']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Edit Unit Kerja Modal (generic) -->
    <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editUnitForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUnitModalLabel">Edit Unit Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Unit Kerja:</label>
                            <div class="col-sm-9">
                                <input type="text" name="satker" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Kode Unit:</label>
                            <div class="col-sm-9">
                                <input type="text" name="kodesatker" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" data-prevent-double>Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Hidden delete form for dynamic submission -->
    <form id="deleteUnitForm" action="#" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Detail Unit Kerja -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong id="childrenTitle">Unit Kerja Anak</strong></h6>
                            <div id="childrenList">
                                <div class="text-muted">Memuat data...</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>User yang Menggunakan</strong></h6>
                            <div id="usersList">
                                <div class="text-muted">Memuat data...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

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
        #tabelEntriSurat thead th,
        #mastersatker-table thead th {
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
            /* Use Bootstrap row negative margins so content aligns flush to the edges */
            margin-left: calc(var(--bs-gutter-x) * -0.5);
            margin-right: calc(var(--bs-gutter-x) * -0.5);
            margin-top: 0;
            margin-bottom: 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Left-align pagination and remove extra gaps */
        .dataTables_wrapper .dataTables_paginate .pagination {
            margin: 0;
            justify-content: flex-start !important;
        }

        /* Table wrapper optimization */
        .table-responsive {
            height: calc(100vh - 300px);
            position: relative;
            overflow: visible;
        }

        /* Ensure table columns maintain consistent width */
        #entrysuratisi-table,
        #tabelEntriSurat,
        #mastersatker-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Sync scroll between header and body */
        .dataTables_wrapper {
            overflow: visible;
        }

        /* Compact table rows */
        #entrysuratisi-table tbody td,
        #tabelEntriSurat tbody td,
        #mastersatker-table tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Optimize header height */
        #entrysuratisi-table thead th,
        #tabelEntriSurat thead th,
        #mastersatker-table thead th {
            padding: 10px 8px !important;
        }

        /* Row Selection Styling */
        #entrysuratisi-table tbody tr,
        #tabelEntriSurat tbody tr,
        #mastersatker-table tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #entrysuratisi-table tbody tr:hover,
        #tabelEntriSurat tbody tr:hover,
        #mastersatker-table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #entrysuratisi-table tbody tr.selected,
        #tabelEntriSurat tbody tr.selected,
        #mastersatker-table tbody tr.selected {
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

        /* Button Styling - Updated */
        .btn-custom {
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 16px;
            border: none;
            text-transform: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Detail Button - Blue */
        .btn-detail {
            background-color: #007bff;
            color: white;
        }

        .btn-detail:hover {
            background-color: #0056b3;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
        }

        /* Edit Button - Yellow/Orange */
        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            color: #000;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
        }

        /* Delete Button - Red */
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        }

        /* Add Button - Blue */
        .btn-add {
            background-color: #007bff;
            color: white;
        }

        .btn-add:hover {
            background-color: #0056b3;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
        }

        /* Icon spacing */
        .btn-custom i {
            font-size: 11px;
        }

        /* Action buttons container */
        .action-buttons .btn {
            margin-right: 6px;
        }

        .action-buttons .btn:last-child {
            margin-right: 0;
        }

        /* Override Bootstrap button styles */
        .btn-custom:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-custom:active {
            transform: translateY(0);
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

        /* Status clickable styling */
        .status-clickable {
            transition: all 0.2s ease;
        }

        .status-clickable:hover {
            transform: scale(1.05);
            opacity: 0.8;
        }

        .status-clickable .badge {
            pointer-events: none;
        }

        /* Modal styling */
        .modal-xl {
            max-width: 90%;
        }

        .list-group-item {
            border: 1px solid #dee2e6;
            margin-bottom: 2px;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        /* Search and Length Menu Styling */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: normal;
            white-space: nowrap;
            text-align: left;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 14px;
            width: 250px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_length label {
            font-weight: normal;
            text-align: left;
            white-space: nowrap;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 14px;
            margin: 0 8px;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Top controls styling */
        .dataTables_wrapper .row:first-child {
            margin-bottom: 15px;
            padding: 0 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dataTables_wrapper .row:first-child .col-md-6:first-child {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .dataTables_wrapper .row:first-child .col-md-6:last-child {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper .row:first-child {
                flex-direction: column;
                gap: 10px;
            }
            
            .dataTables_wrapper .row:first-child .col-md-6 {
                width: 100%;
            }
            
            .dataTables_wrapper .dataTables_filter input {
                width: 200px;
            }
        }

        /* Force standard DataTables layout */
        .dataTables_wrapper .dataTables_filter {
            position: relative;
        }

        .dataTables_wrapper .dataTables_length {
            position: relative;
        }

        /* Clear any conflicting floats */
        .dataTables_wrapper .row:first-child::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Ensure search is always on the right */
        .dataTables_wrapper .row:first-child {
            width: 100%;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function swapInfoAndPaginate(wrapper) {
                var bottomRow = wrapper.find('div.row').last();
                var infoCol = bottomRow.children().filter(function() {
                    return $(this).find('.dataTables_info').length;
                });
                var paginateCol = bottomRow.children().filter(function() {
                    return $(this).find('.dataTables_paginate').length;
                });
                if (infoCol.length && paginateCol.length) {
                    // Pindahkan pagination ke kiri (sebelum info)
                    if (paginateCol.index() > infoCol.index()) {
                        paginateCol.insertBefore(infoCol);
                    }
                    // Tata letak kiri/kanan
                    // Use Bootstrap row flex and ensure alignment without extra spacing
                    bottomRow.addClass('align-items-center');
                    infoCol.addClass('ms-auto text-md-end');
                    paginateCol.removeClass('text-md-end').addClass('me-auto text-start');
                    // Ensure ul.pagination aligns to the far left
                    paginateCol.find('.pagination').addClass('justify-content-start').css('margin', 0);
                }
                
                // Ensure search input has proper styling
                wrapper.find('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari unit kerja...');
                wrapper.find('.dataTables_length select').addClass('form-select');
                
                // Ensure proper Bootstrap grid classes for responsive layout
                wrapper.find('.row:first-child .col-md-6').addClass('d-flex align-items-center');
                wrapper.find('.dataTables_length').parent().addClass('justify-content-start');
                wrapper.find('.dataTables_filter').parent().addClass('justify-content-end');
            }

            var table = $('#mastersatker-table');

            // Sync horizontal scroll between header and body (consistent with Entri Surat)
            $(document).on('scroll', '.dataTables_scrollBody', function() {
                try {
                    var scrollLeft = $(this).scrollLeft();
                    $(this).closest('.dataTables_wrapper').find('.dataTables_scrollHead').scrollLeft(
                        scrollLeft);
                } catch (e) {
                    /* ignore */
                }
            });

            function applySwap() {
                var wrapper = table.closest('.dataTables_wrapper');
                if (wrapper.length) swapInfoAndPaginate(wrapper);
            }

            // Set default page length to 25 on init (fallback)
            table.on('init.dt', function() {
                try {
                    var api = table.DataTable ? table.DataTable() : (window.LaravelDataTables && window
                        .LaravelDataTables['mastersatker-table']);
                    if (api && api.page) {
                        api.page.len(25).draw(false);
                    }
                } catch (e) {
                    /* ignore */
                }
                applySwap();
                
                // Initialize search placeholder
                $('.dataTables_filter input').attr('placeholder', 'Cari unit kerja...');
            });

            // Terapkan saat tabel selesai digambar
            table.on('draw.dt', function() {
                applySwap();
                // Re-apply Bootstrap classes after each draw
                $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari unit kerja...');
                $('.dataTables_length select').addClass('form-select');
                
                // Ensure proper positioning
                $('.dataTables_wrapper .row:first-child .col-md-6').addClass('d-flex align-items-center');
                $('.dataTables_length').parent().addClass('justify-content-start');
                $('.dataTables_filter').parent().addClass('justify-content-end');
            });
            
            // Coba setelah inisialisasi awal
            setTimeout(function() {
                applySwap();
                $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari unit kerja...');
                $('.dataTables_length select').addClass('form-select');
                $('.dataTables_wrapper .row:first-child .col-md-6').addClass('d-flex align-items-center');
                $('.dataTables_length').parent().addClass('justify-content-start');
                $('.dataTables_filter').parent().addClass('justify-content-end');
            }, 500);
            setTimeout(function() {
                applySwap();
                $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari unit kerja...');
                $('.dataTables_length select').addClass('form-select');
                $('.dataTables_wrapper .row:first-child .col-md-6').addClass('d-flex align-items-center');
                $('.dataTables_length').parent().addClass('justify-content-start');
                $('.dataTables_filter').parent().addClass('justify-content-end');
            }, 1500);

            // Initialize row click selection and top action buttons
            function determineUnitType(unitCode, unitName) {
                // Konversi ke string dan trim untuk memastikan
                unitCode = String(unitCode || '').trim();
                unitName = String(unitName || '').toLowerCase();
                
                // Berdasarkan panjang kode satker dan nama unit
                var codeLength = unitCode.length;
                
                // Cek berdasarkan nama unit untuk kasus spesifik
                if (unitName.includes('kantor') || unitName.includes('ktr') || 
                    unitName.includes('pusat') || unitName.includes('pst')) {
                    return 'Unit/Kantor';
                }
                
                if (unitName.includes('divisi') || unitName.includes('div')) {
                    return 'Sub Divisi';
                }
                
                if (unitName.includes('seksi') || unitName.includes('sks')) {
                    return 'Sub Seksi';
                }
                
                if (unitName.includes('bagian') || unitName.includes('bag')) {
                    return 'Sub Bagian';
                }
                
                if (unitName.includes('cabang') || unitName.includes('cab')) {
                    return 'Unit Kerja';
                }
                
                // Berdasarkan panjang kode (hierarki)
                if (codeLength <= 2) {
                    return 'Unit Utama'; // Level paling atas
                }
                else if (codeLength <= 4) {
                    return 'Unit Cabang'; // Level menengah
                }
                else if (codeLength <= 6) {
                    return 'Sub Unit'; // Level bawah
                }
                else {
                    return 'Unit Kerja'; // Default untuk level paling bawah
                }
            }

            function initializeRowSelection() {
                try {
                    var tableSelector = '#mastersatker-table tbody tr';
                    // Remove any existing handlers
                    $(document).off('click', tableSelector);
                    // Add delegated click handler
                    $(document).on('click', tableSelector, function(e) {
                        try {
                            if ($(e.target).closest('.btn').length)
                                return; // ignore button clicks inside row
                            var clickedRow = $(this);
                            var rowId = clickedRow.attr('id');
                            if (!rowId) return;
                            
                            if (clickedRow.hasClass('selected')) {
                                clickedRow.removeClass('selected');
                                $('#actionButtons').removeClass('show d-inline-block').addClass('d-none');
                            } else {
                                $('#mastersatker-table tbody tr').removeClass('selected');
                                clickedRow.addClass('selected');
                                
                                // Get row data from DataTable
                                var table = $('#mastersatker-table').DataTable();
                                var rowData = table.row(clickedRow).data();
                                
                                // Check if can edit/delete
                                var canEdit = rowData && rowData.can_edit;
                                var canDelete = rowData && rowData.can_delete;
                                var restrictionReason = rowData && rowData.restriction_reason;
                                
                                // Show/hide buttons based on permissions
                                if (canEdit) {
                                    $('#editBtn').show().prop('disabled', false);
                                } else {
                                    $('#editBtn').hide();
                                }
                                
                                if (canDelete) {
                                    $('#deleteBtn').show().prop('disabled', false);
                                } else {
                                    $('#deleteBtn').hide();
                                }
                                
                                // Show action buttons container
                                $('#actionButtons').data('selectedId', rowId)
                                    .data('canEdit', canEdit)
                                    .data('canDelete', canDelete)
                                    .data('restrictionReason', restrictionReason)
                                    .removeClass('d-none').addClass('d-inline-block show');
                            }
                        } catch (err) {
                            console.error('Row click error:', err);
                        }
                    });
                } catch (error) {
                    console.error('Row selection init error:', error);
                }
            }

            // Delay to ensure table is drawn
            setTimeout(initializeRowSelection, 1000);
            setTimeout(initializeRowSelection, 3000);

            // Action button handlers
            $(document).on('click', '#editBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    var canEdit = $('#actionButtons').data('canEdit');
                    var restrictionReason = $('#actionButtons').data('restrictionReason');
                    
                    if (!selectedId) {
                        alert('Pilih data terlebih dahulu.');
                        return;
                    }
                    
                    if (!canEdit) {
                        alert('Tidak dapat mengedit: ' + (restrictionReason || 'Data tidak dapat diubah'));
                        return;
                    }

                    // Ambil data dari baris terpilih
                    var $row = $('#mastersatker-table tbody tr#' + selectedId);
                    if (!$row.length) {
                        $row = $('tr#' + selectedId).first();
                    }
                    if (!$row.length) {
                        alert('Tidak dapat menemukan baris yang dipilih.');
                        return;
                    }
                    var tds = $row.find('td');
                    var satker = tds.eq(1).text().trim();
                    var kodesatker = tds.eq(2).text().trim();

                    // Set form action dan nilai input
                    var $form = $('#editUnitForm');
                    $form.attr('action', '/unitkerja/' + selectedId);
                    $form.find('input[name="satker"]').val(satker);
                    $form.find('input[name="kodesatker"]').val(kodesatker);

                    // Tampilkan modal
                    var modalEl = document.getElementById('editUnitModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } catch (error) {
                    console.error('Edit button error:', error);
                }
            });
            
            $(document).on('click', '#deleteBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    var canDelete = $('#actionButtons').data('canDelete');
                    var restrictionReason = $('#actionButtons').data('restrictionReason');
                    
                    if (!selectedId) {
                        alert('Pilih data terlebih dahulu.');
                        return;
                    }
                    
                    if (!canDelete) {
                        alert('Tidak dapat menghapus: ' + (restrictionReason || 'Data tidak dapat dihapus'));
                        return;
                    }
                    
                    if (!confirm('Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                        return;
                    }
                    var $form = $('#deleteUnitForm');
                    $form.attr('action', '/unitkerja/' + selectedId);
                    $form.trigger('submit');
                } catch (error) {
                    console.error('Delete button error:', error);
                }
            });

            // Event handler untuk status clickable
            $(document).on('click', '.status-clickable', function(e) {
                e.stopPropagation(); // Prevent row selection
                
                var unitId = $(this).data('id');
                var type = $(this).data('type');
                
                if (!unitId) return;
                
                // Show modal dengan loading
                $('#detailModalLabel').text('Detail Unit Kerja');
                $('#childrenList').html('<div class="text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>');
                $('#usersList').html('<div class="text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>');
                
                var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
                
                // Fetch data detail
                $.ajax({
                    url: '/unitkerja/detail/' + unitId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;
                            
                            // Update modal title
                            $('#detailModalLabel').text('Detail Unit Kerja: ' + data.unit_name);
                            
                            // Tentukan jenis unit kerja berdasarkan kode dan hierarki
                            var unitType = determineUnitType(data.unit_code, data.unit_name);
                            $('#childrenTitle').text(unitType + ' di Bawahnya');
                            
                            // Render children data
                            var childrenHtml = '';
                            if (data.children && data.children.length > 0) {
                                childrenHtml = '<div class="list-group">';
                                data.children.forEach(function(child) {
                                    childrenHtml += '<div class="list-group-item">' +
                                        '<div class="d-flex w-100 justify-content-between">' +
                                        '<h6 class="mb-1">' + child.satker + '</h6>' +
                                        '<small class="text-muted">' + child.kodesatker + '</small>' +
                                        '</div>' +
                                        '</div>';
                                });
                                childrenHtml += '</div>';
                            } else {
                                var noDataText = 'Tidak ada ' + unitType.toLowerCase() + ' di bawahnya';
                                childrenHtml = '<div class="text-muted">' + noDataText + '</div>';
                            }
                            $('#childrenList').html(childrenHtml);
                            
                            // Render users data
                            var usersHtml = '';
                            if (data.users && data.users.length > 0) {
                                usersHtml = '<div class="list-group">';
                                data.users.forEach(function(user) {
                                    usersHtml += '<div class="list-group-item">' +
                                        '<div class="d-flex w-100 justify-content-between">' +
                                        '<h6 class="mb-1">' + user.fullname + '</h6>' +
                                        '<small class="text-muted">' + (user.nip || '-') + '</small>' +
                                        '</div>' +
                                        '<p class="mb-1">' + (user.jabatan || '-') + '</p>' +
                                        '<small class="text-muted">' + (user.pangkat || '-') + '</small>' +
                                        '</div>';
                                });
                                usersHtml += '</div>';
                            } else {
                                usersHtml = '<div class="text-muted">Tidak ada user yang menggunakan</div>';
                            }
                            $('#usersList').html(usersHtml);
                        } else {
                            $('#childrenList').html('<div class="text-danger">Gagal memuat data anak</div>');
                            $('#usersList').html('<div class="text-danger">Gagal memuat data user</div>');
                        }
                    },
                    error: function() {
                        $('#childrenList').html('<div class="text-danger">Gagal memuat data anak</div>');
                        $('#usersList').html('<div class="text-danger">Gagal memuat data user</div>');
                    }
                });
            });

        });
    </script>
@endpush
