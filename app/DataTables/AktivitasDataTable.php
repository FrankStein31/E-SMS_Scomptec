<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class AktivitasDataTable extends DataTable
{
    public function dataTable($query): DataTableAbstract
    {
        return datatables()->of($query)
            ->addColumn('action', function($row) {
                return '';
            })
            ->rawColumns(['action']);
    }

    public function query(): \Illuminate\Support\Collection
    {
        $user = $this->request->get('user');
        $tanggal = $this->request->get('tanggal');
        $jenis = $this->request->get('jenis');

        $entri = DB::table('entry_surat_isis')
            ->select('created_at as waktu', 'created_by as user_id', DB::raw("'Entri Surat Masuk' as aktivitas"), 'nomor_surat as no_surat', 'hal', 'dari as user_nama')
            ->when($user, function($q) use ($user) { $q->where('created_by', $user); })
            ->when($tanggal, function($q) use ($tanggal) { $q->whereDate('created_at', $tanggal); });
        $keluar = DB::table('surat_keluar_isis')
            ->select('created_at as waktu', 'user_id_pembuat as user_id', DB::raw("'Buat Surat Keluar' as aktivitas"), 'nosurat as no_surat', 'hal', 'ttd_nama as user_nama')
            ->when($user, function($q) use ($user) { $q->where('user_id_pembuat', $user); })
            ->when($tanggal, function($q) use ($tanggal) { $q->whereDate('created_at', $tanggal); });
        if ($jenis == 'masuk') {
            $query = $entri;
        } elseif ($jenis == 'keluar') {
            $query = $keluar;
        } else {
            $query = $entri->unionAll($keluar);
        }
        return $query->orderBy('waktu', 'desc')->get();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('aktivitas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
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

    public function getColumns(): array
    {
        return [
            Column::make('waktu')->title('Waktu'),
            Column::make('user_nama')->title('User'),
            Column::make('aktivitas')->title('Aktivitas'),
            Column::make('no_surat')->title('No Surat'),
            Column::make('hal')->title('Perihal'),
        ];
    }

    protected function filename(): string
    {
        return 'Aktivitas_' . date('YmdHis');
    }
} 