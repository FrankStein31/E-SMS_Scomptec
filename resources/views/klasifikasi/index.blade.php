@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid klasifikasi-container">
            <div class="row">
                <div id="alert-container">
                    @include('layout.alert')
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Daftar Klasifikasi</h5>

                                <div class="d-flex align-items-center gap-2">
                                    <div id="actionButtons" class="action-buttons d-none me-2">
                                        <button id="editBtn" class="btn btn-custom btn-action-edit btn-sm b-r-22">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                        <button id="deleteBtn" class="btn btn-custom btn-action-delete btn-sm b-r-22">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>

                                    <form method="get" class="d-flex align-items-center gap-2">
                                        <select id="filterKodeUtama" class="form-select form-select-sm"
                                            style="min-width:220px;max-width:320px;">
                                            <option value="">Semua Kode</option>
                                            @foreach ($kodeUtama as $kode => $row)
                                                <option value="{{ $kode }}">{{ $kode }} -
                                                    {{ $row->klasifikasi }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <a class="btn btn-primary btn-sm b-r-22 btn-add-primary" id="btnTambah">
                                        <i class="iconoir-plus"></i> Tambah Klasifikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{ $dataTable->table(['id' => 'masterklasifikasi-table']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Add/Edit --}}
        <div class="modal fade" id="modalKlasifikasi" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formKlasifikasi">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Tambah Klasifikasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="klasifikasi_id">
                            <div class="mb-2">
                                <label>Kode Klasifikasi</label>
                                <input type="text" name="kodeklasifikasi" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label>Klasifikasi</label>
                                <textarea name="klasifikasi" class="form-control" required></textarea>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col">
                                    <label>Retensi Aktif</label>
                                    <input type="number" name="retensi_aktif" class="form-control" value=0 required>
                                </div>
                                <div class="col">
                                    <label>Retensi Inaktif</label>
                                    <input type="number" name="retensi_inaktif" class="form-control" value=0 required>
                                </div>
                            </div>
                            <div class="mb-2 mt-2" style="display: none;">
                                <label>Keterangan</label>
                                <select name="keterangan" class="form-control" required>
                                    <option value="1" selected>Dinilai Kembali</option>
                                    <option value="2">Musnah</option>
                                    <option value="3">Permanen</option>
                                </select>
                            </div>
                            <div class="mb-2" style="display: none;">
                                <label>Retensi</label>
                                <input type="number" name="retensi" class="form-control" value="">
                            </div>
                            <div class="mb-2">
                                <label>Parent</label>
                                <select name="parent" id="selectParent" class="form-control">
                                    <option value="">-- Pilih Parent (Opsional) --</option>
                                    @foreach ($kodeUtama as $kode => $row)
                                        <option value="{{ $kode }}">{{ $kode }} - {{ $row->klasifikasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    <script>
        $(document).ready(function() {

            initializeSelect2();
            initializeDataTable();
            setupRowSelection();
            setupActionButtons();
            setupFilterHandlers();
            setupModalHandlers();
            setupFormHandlers();
            $(document).on('click', function(e) {
                const $target = $(e.target);
                if ($target.closest('#actionButtons').length > 0 ||
                    $target.closest('#modalKlasifikasi').length > 0) {
                    return;
                }
                if ($target.closest('#masterklasifikasi-table tbody tr').length > 0) {
                    return;
                }
                if (!$('#actionButtons').hasClass('d-none')) {
                    deselectRow();
                }
            });
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$('#actionButtons').hasClass('d-none')) {
                    deselectRow();
                }
            });
        });

        function initializeSelect2() {
            try {
                $('#filterKodeUtama').select2({
                    width: 'resolve',
                    placeholder: 'Pilih Kode',
                    allowClear: true,
                    dropdownParent: $('.card-header')
                });

                // Initialize Select2 for parent dropdown in modal
                $('#selectParent').select2({
                    width: '100%',
                    placeholder: 'Pilih Parent (Opsional)',
                    allowClear: true,
                    dropdownParent: $('#modalKlasifikasi')
                });
            } catch (error) {
                console.warn('Select2 initialization failed:', error);
            }
        }

        function initializeDataTable() {
            window.table = null;
            try {
                window.table = window.LaravelDataTables['masterklasifikasi-table'];
                if (!window.table) {
                    console.warn('DataTable not found, retrying...');
                    setTimeout(() => {
                        window.table = window.LaravelDataTables['masterklasifikasi-table'];
                    }, 1000);
                }
            } catch (error) {
                console.error('DataTable initialization error:', error);
            }
        }

        function setupRowSelection() {
            $('#masterklasifikasi-table tbody').on('click', 'tr', function(e) {
                try {
                    if ($(e.target).closest('button').length > 0) return;

                    let row = $(this);
                    let rowId = row.attr('id');
                    if (!rowId) return;

                    if (row.hasClass('selected')) {
                        deselectRow();
                    } else {
                        selectRow(row, rowId);
                    }
                } catch (error) {
                    console.error('Row click error:', error);
                }
            });
        }

        function selectRow(row, rowId) {
            $('#masterklasifikasi-table tbody tr').removeClass('selected');
            row.addClass('selected');
            $('#actionButtons').removeClass('d-none fade-out').addClass('show');
            $('#actionButtons').data('selectedId', rowId);
        }

        function deselectRow() {
            $('#masterklasifikasi-table tbody tr').removeClass('selected');
            $('#actionButtons').addClass('fade-out');
            setTimeout(() => {
                $('#actionButtons').addClass('d-none').removeClass('show fade-out');
                $('#actionButtons').data('selectedId', null);
            }, 200);
        }

        function setupActionButtons() {
            $('#editBtn').on('click', function() {
                let id = $('#actionButtons').data('selectedId');
                if (!id) {
                    alert('Tidak ada data yang dipilih!');
                    return;
                }
                editKlasifikasi(id);
            });
            $('#deleteBtn').on('click', function() {
                let id = $('#actionButtons').data('selectedId');
                if (!id) {
                    alert('Tidak ada data yang dipilih!');
                    return;
                }
                if (confirm(
                        'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.'
                        )) {
                    deleteKlasifikasi(id);
                } else {
                    deselectRow();
                }
            });
            $('#btnTambah').click(function() {
                showAddModal();
            });
        }

        function setupFilterHandlers() {
            $('#filterKodeUtama').on('change', function() {
                try {
                    const filterValue = $(this).val();

                    if (window.table && window.table.settings && window.table.ajax) {
                        window.table.settings()[0].ajax.data = function(d) {
                            d.filter_kode = filterValue;
                            return d;
                        };
                        window.table.ajax.reload();
                    } else {
                        console.warn('DataTable not available for filtering');
                    }
                } catch (error) {
                    console.error('Filter error:', error);
                }
            });
        }

        function setupModalHandlers() {
            $(document).on('click', '.btnEdit', function() {
                const id = $(this).data('id');
                if (!id) {
                    alert('ID tidak ditemukan!');
                    return;
                }
                editKlasifikasi(id);
            });
            $(document).on('click', '.btnHapus', function() {
                const id = $(this).data('id');
                if (!id) {
                    alert('ID tidak ditemukan!');
                    return;
                }
                if (confirm('Yakin hapus data?')) {
                    deleteKlasifikasi(id);
                } else {
                    deselectRow();
                }
            });

            $('#modalKlasifikasi').on('hidden.bs.modal', function() {

                if (!$(this).data('saved')) {
                    deselectRow();
                }
                $(this).removeData('saved');
            });
        }

        function setupFormHandlers() {
            $('#formKlasifikasi').submit(function(e) {
                e.preventDefault();
                saveKlasifikasi();
            });
        }

        function showAddModal() {
            try {
                clearAlerts(); // Clear any existing alerts
                $('#formKlasifikasi')[0].reset();
                $('#modalTitle').text('Tambah Klasifikasi');
                $('#klasifikasi_id').val('');
                
                // Reset Select2 parent dropdown
                $('#selectParent').val(null).trigger('change');
                
                $('#modalKlasifikasi').modal('show');
            } catch (error) {
                console.error('Add modal error:', error);
            }
        }

        function editKlasifikasi(id) {
            clearAlerts();

            $.get('/klasifikasi/' + id)
                .done(function(res) {
                    if (res.success) {
                        populateForm(res.data, 'Edit Klasifikasi');
                        $('#modalKlasifikasi').modal('show');
                        deselectRow();
                    } else {
                        $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-search me-2"></i>
                        Data tidak ditemukan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                    }
                })
                .fail(function(xhr) {
                    console.error('Edit request failed:', xhr);

                    $("#alert-container").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal mengambil data!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

                    deselectRow();
                });
        }

        function populateForm(data, title) {
            $('#modalTitle').text(title);
            $('#klasifikasi_id').val(data.id);
            $('[name=kodeklasifikasi]').val(data.kodeklasifikasi);
            $('[name=klasifikasi]').val(data.klasifikasi);
            $('[name=retensi_aktif]').val(data.retensi_aktif);
            $('[name=retensi_inaktif]').val(data.retensi_inaktif);
            $('[name=keterangan]').val(data.keterangan);
            $('[name=retensi]').val(data.retensi);
            
            // Set Select2 parent value
            $('#selectParent').val(data.parent).trigger('change');
        }

        function deleteKlasifikasi(id) {
            $.ajax({
                url: '/klasifikasi/' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.html) {
                        $("#alert-container").html(res.html);
                        if (typeof window.autoDismissNewAlerts === 'function') {
                            window.autoDismissNewAlerts();
                        }
                    }

                    if (res.success) {
                        $('#modalKlasifikasi').modal('hide');
                        reloadTable();
                        deselectRow();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Gagal hapus data!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $("#alert-container").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
                    if (typeof window.autoDismissNewAlerts === 'function') {
                        window.autoDismissNewAlerts();
                    }

                    deselectRow();
                }
            });
        }

        function clearAlerts() {
            $("#alert-container").empty();
        }

        function handleDeleteSuccess(res) {
            if (res.html) {
                $("#alert-container").html(res.html);
            }

            if (res.success) {
                $('#modalKlasifikasi').modal('hide');
                reloadTable();
                deselectRow();
            }
        }

        function saveKlasifikasi() {
            try {
                const id = $('#klasifikasi_id').val();
                const url = '/klasifikasi' + (id ? '/' + id : '');
                const formData = prepareFormData(id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $.param(formData),
                    success: function(res) {
                        if (res.html) {
                            $("#alert-container").html(res.html);
                            if (typeof window.autoDismissNewAlerts === 'function') {
                                window.autoDismissNewAlerts();
                            }
                        }

                        if (res.success) {
                            $('#modalKlasifikasi').modal('hide');
                            $('#modalKlasifikasi').data('saved', true);
                            if ($.fn.DataTable.isDataTable('#masterklasifikasi-table')) {
                                $('#masterklasifikasi-table').DataTable().ajax.reload(null, false);
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal simpan data!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $("#alert-container").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                        if (typeof window.autoDismissNewAlerts === 'function') {
                            window.autoDismissNewAlerts();
                        }

                        deselectRow();
                    }
                });
            } catch (error) {
                console.error('Form submit error:', error);
                $("#alert-container").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Terjadi kesalahan saat menyimpan data!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
                if (typeof window.autoDismissNewAlerts === 'function') {
                    window.autoDismissNewAlerts();
                }
                deselectRow();
            }
        }
        function prepareFormData(id) {
            let formData = $('#formKlasifikasi').serializeArray();
            formData.push({
                name: '_token',
                value: '{{ csrf_token() }}'
            });
            if (id) formData.push({
                name: '_method',
                value: 'PUT'
            });
            return formData;
        }

        function handleSaveSuccess(res) {
            if (res.html) {
                $("#alert-container").html(res.html);
            }

            if (res.success) {
                $('#modalKlasifikasi').modal('hide');
                reloadTable();
                deselectRow();
            }
        }

        function handleAjaxError(xhr, defaultMessage) {
            console.error('AJAX request failed:', xhr);

            if (xhr.responseJSON && xhr.responseJSON.html) {
                $("#alert-container").html(xhr.responseJSON.html);
            } else {
                $("#alert-container").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${xhr.responseJSON?.message ?? defaultMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
            }
        }

        function reloadTable() {
            if (window.table && window.table.ajax) {
                deselectRow();
                window.table.ajax.reload(null, false);
            }
        }
    </script>
@endpush

@push('css')
    <style>
        /* Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 32px;
            padding: 2px 8px;
            font-size: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 28px;
        }

        /* Select2 in Modal */
        .modal .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            font-size: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
        }

        .modal .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
            padding-left: 0;
            padding-right: 20px;
        }

        .modal .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 6px;
        }

        /* Select2 dropdown styling */
        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
            color: white;
        }

        /* Container Styling */
        .klasifikasi-container {
            padding-left: 24px;
            padding-right: 24px;
        }

        /* DataTable Controls */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin: 10px 0;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 1rem;
            font-weight: 400;
        }

        .dataTables_wrapper .dataTables_length select {
            margin: 0 4px;
            height: 32px;
            font-size: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input[type="search"] {
            margin-left: 4px;
            height: 32px;
            font-size: 1rem;
            width: 160px;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
                margin-bottom: 8px;
            }

            .dataTables_wrapper .dataTables_filter input[type="search"] {
                width: 100%;
            }
        }

        /* DataTable Layout */
        .dataTables_wrapper .dataTables_paginate {
            margin: 10px 0;
        }

        .dataTables_wrapper .dataTables_info {
            margin-top: 10px;
            color: #6c757d;
            margin-left: 0;
        }

        .dataTables_wrapper {
            position: relative;
            overflow: visible;
        }

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

        /* Table Optimization */
        .table-responsive {
            height: calc(100vh - min(20vh, 200px));
            overflow: auto;
        }

        #masterklasifikasi-table {
            table-layout: fixed;
            width: 100%;
        }

        .dataTables_wrapper .row:last-child {
            position: sticky;
            bottom: 0;
            background-color: white;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
            z-index: 0;
            margin: 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Table Row Styling */
        #masterklasifikasi-table tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Nomor urut styling */
        #masterklasifikasi-table tbody td:first-child,
        #masterklasifikasi-table thead th:first-child {
            width: 60px;
            text-align: center;
            background-color: #f8f9fa;
        }

        #masterklasifikasi-table tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #masterklasifikasi-table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #masterklasifikasi-table tbody tr.selected {
            background-color: #d1ecf1 !important;
        }

        /* Action Buttons Animation */
        .action-buttons {
            transition: all 0.3s ease;
        }

        .action-buttons.show {
            animation: fadeInScale 0.3s ease forwards;
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
    </style>
@endpush
