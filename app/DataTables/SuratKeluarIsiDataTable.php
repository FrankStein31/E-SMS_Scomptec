<?php

namespace App\DataTables;

use App\Models\SuratKeluarIsi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SuratKeluarIsiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SuratKeluarIsi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm btnDetail" data-id="' . $row->id . '">Detail</button> '
                    . '<button class="btn btn-danger btn-sm btnHapus" data-id="' . $row->id . '">Hapus</button>';
            })
            ->rawColumns(['action'])

            ->addColumn('kepada', function ($query) {
                $json =  json_decode($query->kepada);
                $kepada = '';
                foreach ($json as $key => $value) {
                    $val = json_decode($value);
                    $kepada .= $val->name;
                }

                return $kepada;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<SuratKeluarIsi>
     */
    public function query(SuratKeluarIsi $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('suratkeluarisi-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('nosurat'),
            Column::make('kodeklasifikasi'),
            Column::make('tgl_surat'),
            Column::make('hal'),
            Column::make('sifat'),
            Column::make('kepada'),
            Column::make('tembusan'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SuratKeluarIsi_' . date('YmdHis');
    }
}
