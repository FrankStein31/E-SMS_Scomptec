<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function query(): DatabaseQueryBuilder
    {
        $user = $this->request->get('user');
        $tanggal = $this->request->get('tanggal');
        $jenis = $this->request->get('jenis');

        $loginUser = Auth::user();
        $isOperator = $loginUser->usergroupid == 1;
        $kodesatker = null;
        if (!$isOperator) {
            $loginSatker = \App\Models\MasterSatker::where('userid', $loginUser->id)->first();
            $kodesatker = $loginSatker ? $loginSatker->kodesatker : null;
        }

        // Ambil user id yang satu bagian satker parent
        $userIds = null;
        if (!$isOperator && $kodesatker) {
            $userIds = \App\Models\User::whereHas('masterSatker', function($q) use ($kodesatker) {
                $q->where('kodesatker', 'like', $kodesatker . '%')
                  ->whereRaw('LENGTH(kodesatker) > ?', [strlen($kodesatker)]);
            })->pluck('id')->toArray();
            // Tambahkan id user login agar bisa lihat aktivitas sendiri
            if (!in_array($loginUser->id, $userIds)) {
                $userIds[] = $loginUser->id;
            }
        }

        $entri = DB::table('entry_surat_isis')
            ->leftJoin('users as u1', 'entry_surat_isis.created_by', '=', 'u1.id')
            ->select([
                'entry_surat_isis.created_at as waktu',
                'entry_surat_isis.created_by as user_id',
                DB::raw("'Entri Surat Masuk' as aktivitas"),
                'entry_surat_isis.nomor_surat as no_surat',
                'entry_surat_isis.hal',
                'u1.fullname as user_nama'
            ])
            ->when($user, function ($q) use ($user) {
                $q->where('entry_surat_isis.created_by', $user);
            })
            ->when($tanggal, function ($q) use ($tanggal) {
                $q->whereDate('entry_surat_isis.created_at', $tanggal);
            })
            ->when($userIds, function ($q) use ($userIds) {
                $q->whereIn('entry_surat_isis.created_by', $userIds);
            });

        $keluar = DB::table('surat_keluar_isis')
            ->leftJoin('users as u2', 'surat_keluar_isis.user_id_pembuat', '=', 'u2.id')
            ->select([
                'surat_keluar_isis.created_at as waktu',
                'surat_keluar_isis.user_id_pembuat as user_id',
                DB::raw("'Buat Surat Keluar' as aktivitas"),
                'surat_keluar_isis.nosurat as no_surat',
                'surat_keluar_isis.hal',
                'u2.fullname as user_nama'
            ])
            ->when($user, function ($q) use ($user) {
                $q->where('surat_keluar_isis.user_id_pembuat', $user);
            })
            ->when($tanggal, function ($q) use ($tanggal) {
                $q->whereDate('surat_keluar_isis.created_at', $tanggal);
            })
            ->when($userIds, function ($q) use ($userIds) {
                $q->whereIn('surat_keluar_isis.user_id_pembuat', $userIds);
            });

        if ($jenis == 'masuk') {
            return $entri;
        } elseif ($jenis == 'keluar') {
            return $keluar;
        } else {
            return $entri->unionAll($keluar);
        }
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('aktivitas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc');
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