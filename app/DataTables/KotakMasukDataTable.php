<?php

namespace App\DataTables;

use App\Models\EntrySuratIsi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class KotakMasukDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('status', function($row) {
                $tujuan = $row->tujuanSurat->first();
                if($tujuan) {
                    if($tujuan->dibaca == 1) return '<span class="badge bg-success">Dibaca</span>';
                    return '<span class="badge bg-secondary">Belum Dibaca</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('unit_pengentri', function($row) {
                return $row->createdBy->fullname;
            })
            ->addColumn('sifat', function($row) {
                switch($row->sifat) {
                    case 1:
                        return '<span class="badge bg-primary">Biasa</span>';
                    case 2:
                        return '<span class="badge bg-warning">Segera</span>';
                    case 3:
                        return '<span class="badge bg-danger">Rahasia</span>';
                    case 4:
                        return '<span class="badge bg-success">Penting</span>';
                    default:
                        return '<span class="badge bg-secondary">'.$row->sifat.'</span>';
                }
            })
            ->addColumn('tgl_surat_formatted', function($row) {
                return date('d/m/Y', strtotime($row->tgl_surat));
            })
            ->rawColumns(['status','unit_pengentri','sifat'])
            ->setRowId('id');
    }

    public function query(EntrySuratIsi $model): QueryBuilder
    {
        return $model->with([
            'jenis',
            'createdBy',
            'tujuanSurat' => function($q) {
                $q->where('userid_tujuan', Auth::user()->id);
            }
        ])
        ->whereHas('tujuanSurat', function($q) {
            $q->where('userid_tujuan', Auth::user()->id);
        })
        ->orderBy('tgl_surat','desc')
        ->orderBy('created_at','desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kotakmasuk-table')
            ->columns($this->getColumns())
            ->orderBy(7, 'desc') // Order by tanggal surat (index 7) descending
            ->dom('frt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
            ->parameters([
                'scrollY' => '65vh',
                'scrollX' => true,
                'scrollCollapse' => true,
                'autoWidth' => false,
                'paging' => true,
                'pageLength' => 25,
                'lengthChange' => false,
                'searching' => true,
                'language' => [
                    'search' => 'Cari:',
                    'searchPlaceholder' => 'Cari data...',
                    'paginate' => [
                        'previous' => 'Previous',
                        'next' => 'Next'
                    ]
                ]
            ])
            ->addTableClass('table-striped table-bordered table-hover')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('noagenda')->title('No. Agenda'),
            Column::make('sifat')->title('Sifat'),
            Column::make('jenis.name')->title('Jenis'),
            Column::make('nomor_surat')->title('No. Surat'),
            Column::make('dari')->title('Dari'),
            Column::make('kepada')->title('Kepada'),
            Column::make('hal')->title('Hal'),
            Column::make('unit_pengentri')->title('Unit Pengentri'),
            Column::make('tgl_surat_formatted')->title('Tanggal')->name('tgl_surat'),
            Column::computed('status')->title('Status')->exportable(false)->printable(false)->width(80)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'KotakMasuk_' . date('YmdHis');
    }
} 