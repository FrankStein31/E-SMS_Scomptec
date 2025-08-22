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
            // ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->dom('rt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
            ->parameters([
                'scrollY' => '60vh',
                'scrollX' => true,
                'scrollCollapse' => true,
                'autoWidth' => false,
                'paging' => true,
                'pageLength' => 25,
                'lengthChange' => false,
                'language' => [
                    'paginate' => [
                        'previous' => 'Sebelumnya',
                        'next' => 'Selanjutnya'
                    ],
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                    'infoFiltered' => '(difilter dari _MAX_ total data)',
                    'zeroRecords' => 'Tidak ada data yang ditemukan'
                ]
            ])
            ->addTableClass('table-striped table-bordered table-hover');
        // ->selectStyleSingle()
        //     ->buttons([
        //         Button::make('excel'),
        //         Button::make('csv'),
        //         Button::make('pdf'),
        //         Button::make('print'),
        //         Button::make('reset'),
        //         Button::make('reload')
        //     ])
        // ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('instansi'),
            Column::make('kepala'),
            Column::make('alamat'),
            Column::make('kota'),
            Column::make('telp'),
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
