<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class ReportSuratDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()->of($query)
            ->addColumn('jenis', function($row) {
                return $row->jenis_name ?? '-';
            })
            ->addColumn('unit_pengentri', function($row) {
                $user = \App\Models\User::find($row->created_by);
                return $user ? $user->fullname : $row->created_by;
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
            ->rawColumns(['jenis','sifat'])
            ->setRowId('id');
    }

    public function query()
    {
        $request = $this->request();
        $query = DB::table('entry_surat_isis')
            ->select([
                'entry_surat_isis.id',
                'entry_surat_isis.noagenda',
                'entry_surat_isis.sifat',
                'entry_surat_isis.jenis_id',
                'entry_surat_isis.nomor_surat',
                'entry_surat_isis.dari',
                'entry_surat_isis.kepada',
                'entry_surat_isis.hal',
                'entry_surat_isis.created_by',
                'entry_surat_isis.tgl_surat',
                'master_jenis_surats.name as jenis_name',
            ])
            ->leftJoin('master_jenis_surats', 'entry_surat_isis.jenis_id', '=', 'master_jenis_surats.last_id');

        if ($request->filled('jenis_surat')) {
            $query->where('entry_surat_isis.jenis_id', $request->jenis_surat);
        }
        if ($request->filled('sifat_surat') && $request->sifat_surat != 'semua') {
            $sifatMap = [
                'penting' => 1,
                'rahasia' => 2,
                'biasa' => 3,
                'pribadi' => 4,
            ];
            $sifat = $sifatMap[$request->sifat_surat] ?? null;
            if ($sifat) {
                $query->where('entry_surat_isis.sifat', $sifat);
            }
        }
        if ($request->filled('tgl_surat')) {
            $query->whereDate('entry_surat_isis.tgl_surat', $request->tgl_surat);
        }
        if ($request->filled('kepada')) {
            $query->where('entry_surat_isis.kepada', 'like', '%'.$request->kepada.'%');
        }
        return $query->orderBy('entry_surat_isis.tgl_surat', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reportsurat-table')
            ->columns($this->getColumns())
            ->orderBy(0);
    }

    public function getColumns(): array
    {
        return [
            Column::make('noagenda')->title('No. Agenda'),
            Column::make('sifat')->title('Sifat'),
            Column::make('jenis')->title('Jenis'),
            Column::make('nomor_surat')->title('No. Surat'),
            Column::make('dari')->title('Dari'),
            Column::make('kepada')->title('Kepada'),
            Column::make('hal')->title('Hal'),
            Column::make('unit_pengentri')->title('Unit Pengentri'),
            Column::make('tgl_surat')->title('Tanggal'),
        ];
    }

    protected function filename(): string
    {
        return 'ReportSurat_' . date('YmdHis');
    }
} 