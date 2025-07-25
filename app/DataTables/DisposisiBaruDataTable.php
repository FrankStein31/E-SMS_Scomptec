<?php

namespace App\DataTables;

use App\Models\DisposisiBaru;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DisposisiBaruDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<DisposisiBaru> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="' . route('disposisi.show', $row->entrysurat_id) . '" class="btn btn-sm btn-primary">
                    <i class="fa fa-eye"></i> Lihat
                </a>';
            })
            ->addColumn('no_agenda', function ($row) {
                return $row->entrysurat->noagenda ?? '-';
            })
            ->addColumn('sifat_surat', function ($row) {
                return sifatSurat($row->entrysurat->sifat ?? '');
            })
            ->addColumn('jenis_surat', function ($row) {
                return $row->entrysurat->jenis->name ?? '-';
            })
            ->addColumn('nomor_surat', function ($row) {
                return $row->entrysurat->nomor_surat ?? '-';
            })
            ->addColumn('dari', function ($row) {
                return $row->entrysurat->dari ?? '-';
            })
            ->addColumn('tujuan', function ($row) {
                $tahap = [];
                $loginId = auth()->id();
                // Ambil tujuan awal dari EntrySuratTujuan (urut created_at ASC)
                $entry = $row->entrysurat;
                $kepadaAwalArr = [];
                if ($entry) {
                    $tujuanList = $entry->tujuanSurat()->orderBy('created_at', 'asc')->get();
                    if ($tujuanList->count()) {
                        foreach ($tujuanList as $t) {
                            $user = \App\Models\User::find($t->userid_tujuan);
                            if ($user) {
                                $nama = $user->fullname;
                                if ($user->id == $loginId) {
                                    $nama = '<b>' . $nama . ' (Anda)</b>';
                                }
                                $tahap[] = $nama;
                            }
                        }
                    } elseif ($entry->kepada) {
                        $kepadaAwalArr = array_unique(explode(',', $entry->kepada));
                        foreach ($kepadaAwalArr as $uid) {
                            $user = \App\Models\User::find($uid);
                            if ($user) {
                                $nama = $user->fullname;
                                if ($user->id == $loginId) {
                                    $nama = '<b>' . $nama . ' (Anda)</b>';
                                }
                                $tahap[] = $nama;
                            }
                        }
                    }
                }
                // Lanjutkan dengan riwayat disposisi
                $riwayat = \App\Models\DisposisiBaru::where('entrysurat_id', $row->entrysurat_id)
                    ->orderBy('created_at', 'asc')->get();
                foreach ($riwayat as $r) {
                    $kepadaArr = array_unique(explode(',', $r->kepada));
                    foreach ($kepadaArr as $uid) {
                        $user = \App\Models\User::find($uid);
                        if ($user) {
                            $nama = $user->fullname;
                            if ($user->id == $loginId) {
                                $nama = '<b>' . $nama . ' (Anda)</b>';
                            }
                            $tahap[] = $nama;
                        }
                    }
                }
                // Tampilkan max 2 tahap awal, lalu ... jika lebih, dan tahap terakhir
                $total = count($tahap);
                if ($total > 4) {
                    $tahapRingkas = array_slice($tahap, 0, 2);
                    $tahapRingkas[] = '...';
                    $tahapRingkas[] = $tahap[$total-1];
                } else {
                    $tahapRingkas = $tahap;
                }
                return implode(' <i class="fa fa-arrow-right"></i> ', $tahapRingkas);
            })
            ->addColumn('hal', function ($row) {
                return $row->entrysurat->hal ?? '-';
            })
            ->addColumn('unit_pengentri', function ($row) {
                return $row->entrysurat->createdBy->fullname ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->entrysurat->tgl_surat ?? '-';
            })
            ->filterColumn('no_agenda', function($query, $keyword) {
                $query->whereHas('entrysurat', function($q) use ($keyword) {
                    $q->where('noagenda', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('jenis_surat', function($query, $keyword) {
                $query->whereHas('entrysurat.jenis', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nomor_surat', function($query, $keyword) {
                $query->whereHas('entrysurat', function($q) use ($keyword) {
                    $q->where('nomor_surat', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('dari', function($query, $keyword) {
                $query->whereHas('entrysurat', function($q) use ($keyword) {
                    $q->where('dari', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('tujuan', function($query, $keyword) {
                $userIds = \App\Models\User::where('fullname', 'like', "%{$keyword}%")->pluck('id')->toArray();
                if (!empty($userIds)) {
                    $query->where(function($q) use ($userIds) {
                        foreach ($userIds as $userId) {
                            $q->orWhereRaw("FIND_IN_SET(?, kepada)", [$userId]);
                        }
                    });
                }
            })
            ->filterColumn('hal', function($query, $keyword) {
                $query->whereHas('entrysurat', function($q) use ($keyword) {
                    $q->where('hal', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('unit_pengentri', function($query, $keyword) {
                $query->whereHas('entrysurat.createdBy', function($q) use ($keyword) {
                    $q->where('fullname', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('tanggal', function($query, $keyword) {
                $query->whereHas('entrysurat', function($q) use ($keyword) {
                    $q->where('tgl_surat', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['tujuan','action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<DisposisiBaru>
     */
    public function query(DisposisiBaru $model): QueryBuilder
    {
        $userId = Auth::user()->id;
        return $model->newQuery()
            ->with(['tindakans', 'entrysurat.jenis', 'entrysurat.createdBy'])
            ->where(function($query) use ($userId) {
                $query->whereRaw("FIND_IN_SET(?, kepada)", [$userId]);
            })
            ->latest();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('disposisi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 'desc')
                    // ->selectStyleSingle()
                    // ->responsive(true)
                    // ->autoWidth(false)
                    // ->buttons([
                    //     Button::make('excel'),
                    //     Button::make('csv'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // ])
                    ->parameters([
                        'processing' => true,
                        'serverSide' => true,
                        'language' => [
                            'search' => 'Cari:',
                            'lengthMenu' => 'Tampilkan _MENU_ data per halaman',
                            'zeroRecords' => 'Tidak ada data yang ditemukan',
                            'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                            'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                            'infoFiltered' => '(difilter dari _MAX_ total data)',
                            'paginate' => [
                                'first' => 'Pertama',
                                'last' => 'Terakhir',
                                'next' => 'Selanjutnya',
                                'previous' => 'Sebelumnya'
                            ]
                        ]
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                  ->title('No')
                  ->searchable(false)
                  ->orderable(false)
                  ->width(50),
            Column::make('no_agenda')
                  ->title('No. Agenda')
                  ->searchable(true),
            Column::make('sifat_surat')
                  ->title('Sifat')
                  ->searchable(false),
            Column::make('jenis_surat')
                  ->title('Jenis')
                  ->searchable(true),
            Column::make('nomor_surat')
                  ->title('No. Surat')
                  ->searchable(true),
            Column::make('dari')
                  ->title('Dari')
                  ->searchable(true),
            Column::make('tujuan')
                  ->title('Tujuan')
                  ->searchable(true),
            Column::make('hal')
                  ->title('Hal')
                  ->searchable(true),
            Column::make('unit_pengentri')
                  ->title('Unit Pengentri')
                  ->searchable(true),
            Column::make('tanggal')
                  ->title('Tanggal')
                  ->searchable(true),
            Column::computed('action')
                  ->title('Aksi')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DisposisiBaru_' . date('YmdHis');
    }
}
