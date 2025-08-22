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
                                        <button id="editBtn" class="btn btn-warning btn-sm b-r-22 me-1 text-black">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button id="deleteBtn" class="btn btn-danger btn-sm b-r-22 me-1">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm b-r-22" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="iconoir-plus"></i> Tambah Unit Kerja
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
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                                {{ $dataTable->table(['id' => 'tabelUnitKerja']) }}
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
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
        #tabelUnitKerja thead th {
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
        #tabelUnitKerja {
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
        #tabelUnitKerja tbody td {
            padding: 8px !important;
            vertical-align: middle;
        }

        /* Optimize header height */
        #entrysuratisi-table thead th,
        #tabelEntriSurat thead th,
        #tabelUnitKerja thead th {
            padding: 10px 8px !important;
        }

        /* Row Selection Styling */
        #entrysuratisi-table tbody tr,
        #tabelEntriSurat tbody tr,
        #tabelUnitKerja tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #entrysuratisi-table tbody tr:hover,
        #tabelEntriSurat tbody tr:hover,
        #tabelUnitKerja tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        #entrysuratisi-table tbody tr.selected,
        #tabelEntriSurat tbody tr.selected,
        #tabelUnitKerja tbody tr.selected {
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

        /* Fallback sticky for plain table without DataTables scroll wrappers */
        .table-sticky {
            height: auto !important;
            max-height: none;
            overflow: visible !important;
            /* avoid double scroll with DataTables scrollY */
        }

        .table-sticky table {
            margin-bottom: 0 !important;
        }

        .table-sticky table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #fff;
        }

        /* Force hide length control (Show entries) */
        .dataTables_wrapper .dataTables_length {
            display: none !important;
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
            }

            var table = $('#tabelUnitKerja');

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
                        .LaravelDataTables['tabelUnitKerja']);
                    if (api && api.page) {
                        api.page.len(25).draw(false);
                    }
                } catch (e) {
                    /* ignore */
                }
                applySwap();
            });

            // Terapkan saat tabel selesai digambar
            table.on('draw.dt', applySwap);
            // Coba setelah inisialisasi awal
            setTimeout(applySwap, 500);
            setTimeout(applySwap, 1500);

            // Initialize row click selection and top action buttons
            function initializeRowSelection() {
                try {
                    var tableSelector = '#tabelUnitKerja tbody tr, #mastersatker-table tbody tr';
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
                                $('#tabelUnitKerja tbody tr, #mastersatker-table tbody tr').removeClass(
                                    'selected');
                                clickedRow.addClass('selected');
                                $('#actionButtons').data('selectedId', rowId).removeClass('d-none')
                                    .addClass('d-inline-block show');
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
                    if (!selectedId) {
                        alert('Pilih data terlebih dahulu.');
                        return;
                    }

                    // Ambil data dari baris terpilih
                    var $row = $('#tabelUnitKerja tbody tr#' + selectedId);
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
                    if (!selectedId) {
                        alert('Pilih data terlebih dahulu.');
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

        });
    </script>
@endpush
