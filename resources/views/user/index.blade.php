@extends('layout.main')

@section('content')
    <style>
        .select2-container .select2-results__option {
            text-align: left !important;
            padding-left: 8px;
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
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                {{-- <div class="col-12">
                    <h5 class="main-title">Daftar User</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="/">
                                <span>Home</span>   
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">User</a>
                        </li>
                    </ul>
                </div> --}}
            </div>
            <!-- Breadcrumb end -->
            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Daftar User</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <!-- Action Buttons for Selected Row -->
                                        <div id="actionButtons" class="action-buttons d-none me-2">
                                            <button id="editBtn"
                                                class="btn btn-warning btn-sm b-r-22 me-1 btn-action-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button id="deleteBtn"
                                                class="btn btn-danger btn-sm b-r-22 me-1 btn-action-delete">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <button class="btn btn-primary btn-sm b-r-22 btn-add-primary" id="btnTambah">
                                            <i class="iconoir-plus"></i> Tambah User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-group-text" for="group">User Group</label>
                                    <select id="filterJabatan" class="form-select form-select-sm">
                                        <option value="">-- Semua Jabatan --</option>
                                        @foreach ($userGroups as $ug)
                                            <option value="{{ $ug }}">{{ $ug }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                {{ $dataTable->table(['id' => 'tabelUser']) }}
                            </div>
                        </div>
                    </div>
                    <!-- Default Card end -->
                </div>

                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <!-- Form di luar modal-content agar tombol submit terdeteksi -->
                        <form action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Username:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="username" class="form-control" value=""
                                                id="username" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Password:</label>
                                        <div class="col-sm-9">
                                            <input type="password" id="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Nama
                                            Lengkap:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fullname" class="form-control" id="fullname"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">NIP:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nip" id="nip" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Pangkat:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="pangkat" id="pangkat" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Jabatan:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jabatan" id="jabatan" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Unit
                                            Kerja:</label>
                                        <div class="col-sm-9">
                                            <select id="satkerSelect" name="satkerid" id="satkerid2"
                                                class="form-select select2">
                                                <option value="">Pilih Unit
                                                    Kerja</option>
                                                @foreach ($masterSatkers as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->satker }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" class="form-control" name="" id="satkertest"> --}}
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Email:</label>
                                        <div class="col-sm-9">
                                            <input type="email" name="email" id="email" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" data-prevent-double>Simpan</button>
                                </div>
                            </div> <!-- /.modal-content -->
                        </form>
                    </div> <!-- /.modal-dialog -->
                </div>
                <!-- Blank end -->
            </div>
    </main>
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
        #users-table thead th,
        #tabelUser thead th {
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
        #users-table,
        #tabelUser {
            table-layout: fixed;
            width: 100%;
        }

        /* Sync scroll between header and body */
        .dataTables_wrapper {
            overflow: visible;
        }

        /* Compact table rows */
        #users-table tbody td,
        #tabelUser tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Optimize header height */
        #users-table thead th,
        #tabelUser thead th {
            padding: 10px 8px !important;
        }

        /* Row Selection Styling */
        #users-table tbody tr,
        #tabelUser tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #users-table tbody tr:hover,
        #tabelUser tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #users-table tbody tr.selected,
        #tabelUser tbody tr.selected {
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
    </style>
    <script>
        $(document).ready(function() {
            // Filter jabatan
            $('#filterJabatan').on('change', function() {
                let val = $(this).val();
                if (window.LaravelDataTables && window.LaravelDataTables['tabelUser']) {
                    window.LaravelDataTables['tabelUser'].ajax.url('?jabatan=' + val).load();
                }
            });

            // Wait for DataTable to load then add click events
            function initializeTableInteractions() {
                try {
                    console.log('Initializing user table interactions...');

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
                    var tableSelector = '#tabelUser tbody tr, #users-table tbody tr';

                    // Remove any existing handlers
                    $(document).off('click', tableSelector);

                    // Add click handler for row selection
                    $(document).on('click', tableSelector, function(e) {
                        try {
                            console.log('User row clicked!', this);

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
                                console.log('User row deselected');
                            } else {
                                // Clear all selections first, then select clicked row
                                $('#tabelUser tbody tr, #users-table tbody tr').removeClass('selected');
                                clickedRow.addClass('selected');

                                // Show action buttons with animation
                                $('#actionButtons').removeClass('d-none').addClass('d-inline-block show');

                                // Store selected row ID for action buttons
                                $('#actionButtons').data('selectedId', rowId);

                                console.log('User row selected, ID:', rowId);
                            }
                        } catch (error) {
                            console.error('User row click handler error:', error);
                        }
                    });
                } catch (error) {
                    console.error('User table initialization error:', error);
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
                        console.log('Edit button clicked for user ID:', selectedId);
                        // Get user data and show edit modal
                        $.get('/user?id=' + selectedId, function(data) {
                            $('#exampleModal2 #username').val(data.username);
                            $('#exampleModal2 #fullname').val(data.fullname);
                            $('#exampleModal2 #nip').val(data.nip);
                            $('#exampleModal2 #pangkat').val(data.pangkat);
                            $('#exampleModal2 #jabatan').val(data.jabatan);
                            $('#exampleModal2 #satkerid2').val(data.satkerid).trigger('change');
                            $('#exampleModal2 #email').val(data.email);
                            $('#exampleModal2 form').attr('action', '/user/' + data.id);
                            $('#exampleModal2').modal('show');
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
                        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                            console.log('Delete button clicked for user ID:', selectedId);
                            $.ajax({
                                url: '/user/' + selectedId,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    if (res.success) {
                                        $('#actionButtons').removeClass('show d-inline-block')
                                            .addClass('d-none');
                                        window.LaravelDataTables['tabelUser'].ajax.reload(null,
                                            false);
                                        alert('User berhasil dihapus!');
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

            // Tambah User AJAX
            $('#exampleModal form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success) {
                            $('#exampleModal').modal('hide');
                            $('#exampleModal form')[0].reset();
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            window.LaravelDataTables['tabelUser'].ajax.reload(null, false);
                            alert('User berhasil ditambahkan!');
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

            // Update User AJAX
            $('#exampleModal2 form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();
                data += '&_method=PUT';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(res) {
                        if (res.success) {
                            $('#exampleModal2').modal('hide');
                            $('#actionButtons').removeClass('show d-inline-block').addClass(
                                'd-none');
                            window.LaravelDataTables['tabelUser'].ajax.reload(null, false);
                            alert('User berhasil diupdate!');
                        } else if (res.message) {
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Gagal update data!';
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

            // Initialize Select2
            try {
                // Initialize select2 for filter
                $('#filterJabatan').select2({
                    width: '100%',
                    allowClear: true
                });

                // Initialize Select2 for modals
                $('#exampleModal').on('shown.bs.modal', function() {
                    $(this).find('.select2').select2({
                        dropdownParent: $('#exampleModal'),
                        width: '100%',
                        placeholder: 'Pilih Unit Kerja',
                        allowClear: true
                    });
                });

                $('#exampleModal2').on('shown.bs.modal', function() {
                    $(this).find('.select2').select2({
                        dropdownParent: $('#exampleModal2'),
                        width: '100%',
                        placeholder: 'Pilih Unit Kerja',
                        allowClear: true
                    });
                });
            } catch (error) {
                console.warn('Select2 initialization failed:', error);
            }
        });
    </script>
@endpush
