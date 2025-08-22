@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid klasifikasi-container">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Daftar Klasifikasi</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <div id="actionButtons" class="action-buttons d-none me-2">
                                        <button id="editBtn" class="btn btn-warning btn-sm b-r-22">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                        <button id="deleteBtn" class="btn btn-danger btn-sm b-r-22">
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
                                    <a class="btn btn-primary btn-sm b-r-22" id="btnTambah">
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
        <!-- Modal Tambah/Edit -->
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
                            <div class="row">
                                <div class="col">
                                    <label>Retensi Aktif</label>
                                    <input type="number" name="retensi_aktif" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label>Retensi Inaktif</label>
                                    <input type="number" name="retensi_inaktif" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-2 mt-2">
                                <label>Keterangan</label>
                                <select name="keterangan" class="form-control" required>
                                    <option value="1">Dinilai Kembali</option>
                                    <option value="2">Musnah</option>
                                    <option value="3">Permanen</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Retensi</label>
                                <input type="number" name="retensi" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Parent</label>
                                <input type="text" name="parent" class="form-control">
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

            try {
                $('#filterKodeUtama').select2({
                    width: 'resolve',
                    placeholder: 'Pilih Kode',
                    allowClear: true,
                    dropdownParent: $('.card-header')
                });
            } catch (error) {
                console.warn('Select2 initialization failed:', error);
            }


            var table;
            try {
                table = window.LaravelDataTables['masterklasifikasi-table'];
                if (!table) {
                    console.warn('DataTable not found, retrying...');
                    setTimeout(function() {
                        table = window.LaravelDataTables['masterklasifikasi-table'];
                    }, 1000);
                }
            } catch (error) {
                console.error('DataTable initialization error:', error);
            }


            $('#masterklasifikasi-table tbody').on('click', 'tr', function(e) {
                try {

                    if ($(e.target).closest('button').length > 0) return;

                    let row = $(this);
                    let rowId = row.attr('id');

                    if (!rowId) return;


                    if (row.hasClass('selected')) {
                        row.removeClass('selected');
                        $('#actionButtons').addClass('d-none');
                        $('#actionButtons').data('selectedId', null);
                    } else {

                        $('#masterklasifikasi-table tbody tr').removeClass('selected');
                        row.addClass('selected');
                        $('#actionButtons').removeClass('d-none');
                        $('#actionButtons').data('selectedId', rowId);
                    }
                } catch (error) {
                    console.error('Row click error:', error);
                }
            });

            $('#editBtn').on('click', function() {
                let id = $('#actionButtons').data('selectedId');
                if (!id) {
                    alert('Tidak ada data yang dipilih!');
                    return;
                }

                $.get('/klasifikasi/' + id, function(res) {
                    if (res.success) {
                        let d = res.data;
                        $('#modalTitle').text('Edit Klasifikasi');
                        $('#klasifikasi_id').val(d.id);
                        $('[name=kodeklasifikasi]').val(d.kodeklasifikasi);
                        $('[name=klasifikasi]').val(d.klasifikasi);
                        $('[name=retensi_aktif]').val(d.retensi_aktif);
                        $('[name=retensi_inaktif]').val(d.retensi_inaktif);
                        $('[name=keterangan]').val(d.keterangan);
                        $('[name=retensi]').val(d.retensi);
                        $('[name=parent]').val(d.parent);
                        $('#modalKlasifikasi').modal('show');
                    } else {
                        alert('Data tidak ditemukan!');
                    }
                }).fail(function() {
                    alert('Gagal mengambil data!');
                });
            });

            $('#deleteBtn').on('click', function() {
                let id = $('#actionButtons').data('selectedId');
                if (!id) {
                    alert('Tidak ada data yang dipilih!');
                    return;
                }

                if (!confirm('Yakin hapus data?')) {
                    return;
                }

                $.ajax({
                    url: '/klasifikasi/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#modalKlasifikasi').modal('hide');
                            if (table && table.ajax) {
                                table.ajax.reload(null, false);
                            }
                            alert('Data berhasil dihapus!');
                            // reset action button
                            $('#actionButtons').addClass('d-none').data('selectedId', null);
                        } else if (res.message) {
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Delete request failed:', xhr);
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
            });

            $('#filterKodeUtama').on('change', function() {
                try {
                    var filterValue = $(this).val();

                    if (table && table.settings && table.ajax) {
                        table.settings()[0].ajax.data = function(d) {
                            d.filter_kode = filterValue;
                            return d;
                        };

                        table.ajax.reload();
                    } else {
                        console.warn('DataTable not available for filtering');
                    }
                } catch (error) {
                    console.error('Filter error:', error);
                }
            });

            $('#btnTambah').click(function() {
                try {
                    $('#formKlasifikasi')[0].reset();
                    $('#modalTitle').text('Tambah Klasifikasi');
                    $('#klasifikasi_id').val('');
                    $('#modalKlasifikasi').modal('show');
                } catch (error) {
                    console.error('Add button error:', error);
                }
            });

            $(document).on('click', '.btnEdit', function() {
                try {
                    let id = $(this).data('id');
                    if (!id) {
                        alert('ID tidak ditemukan!');
                        return;
                    }

                    $.get('/klasifikasi/' + id, function(res) {
                        if (res.success) {
                            let d = res.data;
                            $('#modalTitle').text('Edit Klasifikasi');
                            $('#klasifikasi_id').val(d.id);
                            $('[name=kodeklasifikasi]').val(d.kodeklasifikasi);
                            $('[name=klasifikasi]').val(d.klasifikasi);
                            $('[name=retensi_aktif]').val(d.retensi_aktif);
                            $('[name=retensi_inaktif]').val(d.retensi_inaktif);
                            $('[name=keterangan]').val(d.keterangan);
                            $('[name=retensi]').val(d.retensi);
                            $('[name=parent]').val(d.parent);
                            $('#modalKlasifikasi').modal('show');
                        } else {
                            alert('Data tidak ditemukan!');
                        }
                    }).fail(function(xhr) {
                        console.error('Edit request failed:', xhr);
                        alert('Gagal mengambil data!');
                    });
                } catch (error) {
                    console.error('Edit button error:', error);
                    alert('Terjadi kesalahan saat mengedit data!');
                }
            });

            $(document).on('click', '.btnHapus', function() {
                try {
                    if (confirm('Yakin hapus data?')) {
                        let id = $(this).data('id');
                        if (!id) {
                            alert('ID tidak ditemukan!');
                            return;
                        }

                        $.ajax({
                            url: '/klasifikasi/' + id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    $('#modalKlasifikasi').modal('hide');
                                    if (table && table.ajax) {
                                        table.ajax.reload(null, false);
                                    }
                                    alert('Data berhasil dihapus!');
                                } else if (res.message) {
                                    alert(res.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Delete request failed:', xhr);
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
                } catch (error) {
                    console.error('Delete button error:', error);
                    alert('Terjadi kesalahan saat menghapus data!');
                }
            });

            $('#formKlasifikasi').submit(function(e) {
                try {
                    e.preventDefault();
                    let id = $('#klasifikasi_id').val();
                    let url = '/klasifikasi' + (id ? '/' + id : '');
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
                                $('#modalKlasifikasi').modal('hide');
                                if (table && table.ajax) {
                                    table.ajax.reload(null, false);
                                }
                                alert('Data berhasil disimpan!');
                            }
                        },
                        error: function(xhr) {
                            console.error('Save request failed:', xhr);
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
                } catch (error) {
                    console.error('Form submit error:', error);
                    alert('Terjadi kesalahan saat menyimpan data!');
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
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

        .klasifikasi-container {
            padding-left: 24px;
            padding-right: 24px;
        }

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

        /* Table wrapper optimization */
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

        #masterklasifikasi-table tbody td {
            padding: 8px !important;
            vertical-align: middle;
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
@endpush
