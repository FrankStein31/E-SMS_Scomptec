<?php

namespace App\DataTables;

use App\Models\MasterTindakanDisposisi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MasterTindakanDisposisiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<MasterTindakanDisposisi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<MasterTindakanDisposisi>
     */
    public function query(MasterTindakanDisposisi $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('mastertindakandisposisi-table')
                    ->columns($this->getColumns())
            ->orderBy(1)
            ->dom('frt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
            ->parameters([
                'scrollY' => '60vh',
                'scrollX' => true,
                'scrollCollapse' => true,
                'autoWidth' => false,
                'paging' => true,
                'pageLength' => 25,
                'lengthChange' => false,
                'searching' => true,
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
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width('50px'),
            Column::make('tindakan')->title('Tindakan'),
            Column::make('satkerid')->title('Satker ID'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MasterTindakanDisposisi_' . date('YmdHis');
    }
}
