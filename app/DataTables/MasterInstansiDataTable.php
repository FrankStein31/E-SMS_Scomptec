<?php

namespace App\DataTables;

use App\Models\MasterInstansi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MasterInstansiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<MasterInstansi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() // Menambahkan kolom nomor urut otomatis
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<MasterInstansi>
     */
    public function query(MasterInstansi $model): QueryBuilder
    {
        return $model->orderBy('instansi')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('masterinstansi-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->dom('<"row justify-content-end"<"col-auto"f>>rt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
            ->parameters([
                'scrollY' => '60vh',
                'scrollX' => true,
                'scrollCollapse' => true,
                'autoWidth' => false,
                'paging' => true,
                'pageLength' => 25,
                'lengthChange' => false,
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
                    'searchPlaceholder' => 'Ketik untuk mencari...'
                ]
            ])
            ->addTableClass('table-striped table-bordered table-hover');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->searchable(false)
                ->orderable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('instansi')->title('Instansi'),
            Column::make('kepala')->title('Kepala'),
            Column::make('alamat')->title('Alamat'),
            Column::make('kota')->title('Kota'),
            Column::make('telp')->title('Telepon'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MasterInstansi_' . date('YmdHis');
    }
}
