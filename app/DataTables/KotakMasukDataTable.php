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
            ->addColumn('action', function($row) {
                return '<a href="'.route('kotakmasuk.show', $row->id).'" class="btn btn-info btn-sm">Detail</a>';
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
            ->rawColumns(['status','action','unit_pengentri','sifat'])
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
        ->orderBy('tgl_surat','desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kotakmasuk-table')
            ->columns($this->getColumns())
            ->orderBy(0);
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
            Column::make('tgl_surat')->title('Tanggal'),
            Column::computed('status')->title('Status')->exportable(false)->printable(false)->width(80)->addClass('text-center'),
            Column::computed('action')->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'KotakMasuk_' . date('YmdHis');
    }
} 