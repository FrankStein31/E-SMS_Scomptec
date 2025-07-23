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
        $searchValue = $this->request->input('columns')[0]['search']['value'] ?? null;
        if ($searchValue) {
            $query->where('kodeklasifikasi', 'like', $searchValue.'%');
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
                    // ->minifiedAjax()
                    ->orderBy(0) ;
                    // ->selectStyleSingle()
                    // ->buttons([
                    //     Button::make('excel'),
                    //     Button::make('csv'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id'),
            Column::make('kodeklasifikasi'),
            Column::make('klasifikasi'),
            Column::make('retensi_aktif'),
            Column::make('retensi_inaktif'),
            Column::make('keterangan'),
            Column::make('retensi'),
            Column::make('parent'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
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
