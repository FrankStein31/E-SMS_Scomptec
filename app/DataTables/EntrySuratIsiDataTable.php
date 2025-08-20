<?php

namespace App\DataTables;

use App\Models\EntrySuratIsi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EntrySuratIsiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<EntrySuratIsi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('jenis', function($row) {
                return $row->jenis ? $row->jenis->name : '-';
            })
            ->addColumn('sifat_label', function($row) {
                switch ($row->sifat) {
                    case 1: return '<span class="badge bg-danger">Penting</span>';
                    case 2: return '<span class="badge bg-warning">Rahasia</span>';
                    case 3: return '<span class="badge bg-info">Biasa</span>';
                    case 4: return '<span class="badge bg-secondary">Pribadi</span>';
                    default: return '-';
                }
            })
            ->addColumn('unit_pengentri', function($row) {
                return $row->createdBy ? $row->createdBy->fullname : '-';
            })
            ->addColumn('status_baca', function ($row) {
                // Cek apakah ada yang sudah membaca surat ini
                $sudahDibaca = $row->tujuan()->where('dibaca', 1)->exists();
                if ($sudahDibaca) {
                    return '<span class="badge bg-success">Sudah Dibaca</span>';
                } else {
                    return '<span class="badge bg-secondary">Belum Dibaca</span>';
                }
            })
            ->addColumn('tgl_surat_formatted', function($row) {
                return date('d/m/Y', strtotime($row->tgl_surat));
            })
            ->rawColumns(['sifat_label', 'status_baca'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<EntrySuratIsi>
     */
    public function query(EntrySuratIsi $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['createdBy', 'tujuan']);
        // Filter sifat
        $sifat = $this->request->get('sifat');
        if ($sifat !== null && $sifat !== '') {
            $query->where('sifat', $sifat);
        }
        // Filter jenis
        $jenis = $this->request->get('jenis');
        if ($jenis !== null && $jenis !== '') {
            $query->where('jenis_id', $jenis);
        }
        // Filter unit pengentri
        $unit = $this->request->get('unit_pengentri');
        if ($unit !== null && $unit !== '') {
            $query->where('created_by', $unit);
        }
        // Filter tujuan
        $tujuan = $this->request->get('tujuan');
        if ($tujuan !== null && $tujuan !== '') {
            $query->where('kepada', 'LIKE', '%' . $tujuan . '%');
        }
        return $query->orderBy('tgl_surat', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('entrysuratisi-table')
                    ->columns($this->getColumns())
                    // ->minifiedAjax()
                    ->orderBy(9, 'desc') // Order by tanggal surat (index 9) descending
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
                                'previous' => 'Previous',
                                'next' => 'Next'
                            ]
                        ]
                    ])
                    ->addTableClass('table-striped table-bordered');
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
            Column::make('noagenda')->title('No. Agenda'),
            Column::make('sifat_label')->title('Sifat'),
            Column::make('jenis')->title('Jenis'),
            Column::make('nomor_surat')->title('No. Surat'),
            Column::make('dari'),
            Column::make('kepada')->title('Tujuan'),
            Column::make('hal'),
            Column::make('unit_pengentri')->title('Unit Pengentri'),
            Column::make('status_baca')->title('Status Baca'),
            Column::make('tgl_surat_formatted')->title('Tanggal')->name('tgl_surat'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'EntrySuratIsi_' . date('YmdHis');
    }
}