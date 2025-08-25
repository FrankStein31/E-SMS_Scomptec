@extends('layout.main')

@section('content')
    <div class="container-fluid tindakan-disposisi-container">
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Data Tindakan Disposisi</h5>
                <div class="d-flex align-items-center">
                    <!-- Action Buttons for Selected Row -->
                    <div id="actionButtons" class="action-buttons d-none me-2">
                        <button id="editBtn" class="btn btn-warning btn-sm b-r-22 me-1 btn-action-edit">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button id="deleteBtn" class="btn btn-danger btn-sm b-r-22 me-1 btn-action-delete">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <button class="btn btn-primary btn-sm b-r-22 btn-add-primary" id="btnTambah">
                        <i class="iconoir-plus"></i> Tambah Tindakan
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="margin-top: 15px;">
                    {{ $dataTable->table(['id' => 'tabelTindakan']) }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="modalTindakan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTindakan">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Tindakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="tindakan_id">
                        <div class="mb-2">
                            <label>Tindakan</label>
                            <input type="text" name="tindakan" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Satker ID</label>
                            <input type="text" name="satkerid" class="form-control" required>
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
        #mastertindakandisposisi-table thead th,
        #tabelTindakan thead th {
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
        #mastertindakandisposisi-table,
        #tabelTindakan {
            table-layout: fixed;
            width: 100%;
        }

        /* Sync scroll between header and body */
        .dataTables_wrapper {
            overflow: visible;
        }

        /* Compact table rows */
        #mastertindakandisposisi-table tbody td,
        #tabelTindakan tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Optimize header height */
        #mastertindakandisposisi-table thead th,
        #tabelTindakan thead th {
            padding: 10px 8px !important;
        }

        /* Row Selection Styling */
        #mastertindakandisposisi-table tbody tr,
        #tabelTindakan tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #mastertindakandisposisi-table tbody tr:hover,
        #tabelTindakan tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #mastertindakandisposisi-table tbody tr.selected,
        #tabelTindakan tbody tr.selected {
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

        .tindakan-disposisi-container {
            padding-left: 24px;
            padding-right: 24px;
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
            // Wait for DataTable to load then add click events
            function initializeTableInteractions() {
                try {
                    console.log('Initializing tindakan disposisi table interactions...');

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
                    var tableSelector = '#tabelTindakan tbody tr, #mastertindakandisposisi-table tbody tr';

                    // Remove any existing handlers
                    $(document).off('click', tableSelector);

                    // Add click handler for row selection
                    $(document).on('click', tableSelector, function(e) {
                        try {
                            console.log('Tindakan row clicked!', this);

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
                                console.log('Tindakan row deselected');
                            } else {
                                // Clear all selections first, then select clicked row
                                $('#tabelTindakan tbody tr, #mastertindakandisposisi-table tbody tr')
                                    .removeClass('selected');
                                clickedRow.addClass('selected');

                                // Show action buttons with animation
                                $('#actionButtons').removeClass('d-none').addClass('d-inline-block show');

                                // Store selected row ID for action buttons
                                $('#actionButtons').data('selectedId', rowId);

                                console.log('Tindakan row selected, ID:', rowId);
                            }
                        } catch (error) {
                            console.error('Tindakan row click handler error:', error);
                        }
                    });
                } catch (error) {
                    console.error('Tindakan table initialization error:', error);
                }
            }

            // Initialize with delays to ensure proper loading
            setTimeout(initializeTableInteractions, 1000);
            setTimeout(initializeTableInteractions, 3000);

            // Action button handlers
            $(document).on('click', '#editBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    if (selectedId) {
                        console.log('Edit button clicked for tindakan ID:', selectedId);
                        // Get tindakan data and show edit modal
                        $.get('/tindakan-disposisi/' + selectedId, function(res) {
                            if (res.success) {
                                let d = res.data;
                                $('#modalTitle').text('Edit Tindakan');
                                $('#tindakan_id').val(d.id);
                                $('[name=tindakan]').val(d.tindakan);
                                $('[name=satkerid]').val(d.satkerid);
                                $('#modalTindakan').modal('show');
                            }
                        });
                    }
                } catch (error) {
                    console.error('Edit button error:', error);
                }
            });

            $(document).on('click', '#deleteBtn', function() {
                try {
                    var selectedId = $('#actionButtons').data('selectedId');
                    if (selectedId) {
                        if (confirm('Apakah Anda yakin ingin menghapus data tindakan ini?')) {
                            console.log('Delete button clicked for tindakan ID:', selectedId);
                            $.ajax({
                                url: '/tindakan-disposisi/' + selectedId,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    if (res.success) {
                                        $('#actionButtons').removeClass('show d-inline-block')
                                            .addClass('d-none');
                                        window.LaravelDataTables['tabelTindakan'].ajax.reload(
                                            null, false);
                                        alert('Data berhasil dihapus!');
                                    } else if (res.message) {
                                        alert(res.message);
                                    }
                                },
                                error: function(xhr) {
                                    let msg = 'Gagal hapus data!';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        msg = xhr.responseJSON.message;
                                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                        let errors = xhr.responseJSON.errors;
                                        msg = Object.values(errors).flat()[0];
                                    }
                                    alert(msg);
                                }
                            });
                        }
                    }
                } catch (error) {
                    console.error('Delete button error:', error);
                }
            });

            // Tambah
            $('#btnTambah').click(function() {
                $('#formTindakan')[0].reset();
                $('#modalTitle').text('Tambah Tindakan');
                $('#tindakan_id').val('');
                $('#modalTindakan').modal('show');
            });

            // Simpan
            $('#formTindakan').submit(function(e) {
                e.preventDefault();
                let id = $('#tindakan_id').val();
                let url = '/tindakan-disposisi' + (id ? '/' + id : '');
                let method = id ? 'PUT' : 'POST';
                let formData = $(this).serializeArray();
                formData.push({
                    name: '_token',
                    value: '{{ csrf_token() }}'
                });
                if (id) formData.push({
                    name: '_method',
                    value: 'PUT'
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $.param(formData),
                    success: function(res) {
                        if (res.success) {
                            $('#modalTindakan').modal('hide');
                            $('#actionButtons').removeClass('show d-inline-block').addClass(
                                'd-none');
                            window.LaravelDataTables['tabelTindakan'].ajax.reload(null, false);
                            alert('Data berhasil disimpan!');
                        } else if (res.message) {
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Gagal simpan data!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            msg = Object.values(errors).flat()[0];
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
