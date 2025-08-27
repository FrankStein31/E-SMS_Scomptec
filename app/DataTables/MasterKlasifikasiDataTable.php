<?php

namespace App\DataTables;

use App\Models\MasterKlasifikasi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MasterKlasifikasiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<MasterKlasifikasi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('keterangan', function($row) {
                if($row->keterangan == 1) return 'Dinilai Kembali';
                if($row->keterangan == 2) return 'Musnah';
                return 'Permanen';
            })
            ->addColumn('parent_info', function($row) {
                if($row->parent) {
                    // Cari parent berdasarkan kode klasifikasi
                    $parent = \App\Models\MasterKlasifikasi::where('kodeklasifikasi', $row->parent)->first();
                    if($parent) {
                        return $row->parent . ' (' . $parent->klasifikasi . ')';
                    }
                    return $row->parent;
                }
                return '-';
            })
            ->addColumn('action', function($row) {
                return '<button class="btn btn-warning btn-sm btnEdit" data-id="'.$row->id.'">Edit</button> '
                    . '<button class="btn btn-danger btn-sm btnHapus" data-id="'.$row->id.'">Hapus</button>';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<MasterKlasifikasi>
     */
    public function query(MasterKlasifikasi $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Filter kolom kodeklasifikasi jika ada
        if ($this->request->has('filter_kode') && !empty($this->request->get('filter_kode'))) {
            $filterValue = $this->request->get('filter_kode');
            $query->where('kodeklasifikasi', 'like', $filterValue.'%');
        }

        return $query->orderBy('kodeklasifikasi');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('masterklasifikasi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->dom('<"row justify-content-end"<"col-auto"f>>rt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
                    ->parameters([
                        'scrollY' => '60vh',
                        'scrollX' => true,
                        'scrollCollapse' => true,
                        'autoWidth' => false,
                        'paging' => true,
                        'pageLength' => 25,
                        'lengthChange' => false,
                        'responsive' => true,
                        'autoWidth' => false,
                        'searchDelay' => 1000, // Delay 1 detik sebelum search
                        'language' => [
                            'paginate' => [
                                'previous' => 'Sebelumnya',
                                'next' => 'Selanjutnya'
                            ],
                            'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                            'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                            'infoFiltered' => '(difilter dari _MAX_ total data)',
                            'zeroRecords' => 'Tidak ada data yang ditemukan',
                            'search' => 'Cari:',
                            'searchPlaceholder' => 'Ketik untuk mencari...',
                            'url' => asset('assets/datatables/id.json')
                        ]
                    ])->addTableClass('table-striped table-bordered table-hover');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id'),
            Column::make('kodeklasifikasi')->title('Kode Klasifikasi'),
            Column::make('klasifikasi')->title('Nama Klasifikasi'),
            Column::make('retensi_aktif')->visible(false),
            Column::make('retensi_inaktif')->visible(false),
            Column::make('keterangan')->visible(false),
            Column::make('retensi')->visible(false),
            Column::computed('parent_info')->title('Parent')->searchable(false)->orderable(false),
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MasterKlasifikasi_' . date('YmdHis');
    }
}
