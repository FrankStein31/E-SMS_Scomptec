<?php

namespace App\DataTables;

use App\Models\MasterSatker;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MasterSatkerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<MasterSatker> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('can_edit', function ($row) {
                // Cek apakah ada child
                $childCount = \App\Models\MasterSatker::where('kodesatker', 'like', $row->kodesatker . '%')
                    ->whereRaw('LENGTH(kodesatker) > ?', [strlen($row->kodesatker)])
                    ->count();

                // Cek apakah ada user yang menggunakan unit kerja ini
                $userCount = \App\Models\User::where('satkerid', $row->satkerid)->count();

                // Cek apakah ini adalah 5 data induk pertama
                $parentIds = \App\Models\MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
                $isTopParent = in_array($row->id, $parentIds);

                return !($childCount > 0 || $userCount > 0 || $isTopParent);
            })
            ->addColumn('can_delete', function ($row) {
                // Cek apakah ada child
                $childCount = \App\Models\MasterSatker::where('kodesatker', 'like', $row->kodesatker . '%')
                    ->whereRaw('LENGTH(kodesatker) > ?', [strlen($row->kodesatker)])
                    ->count();

                // Cek apakah ada user yang menggunakan unit kerja ini
                $userCount = \App\Models\User::where('satkerid', $row->satkerid)->count();

                // Cek apakah ini adalah 5 data induk pertama
                $parentIds = \App\Models\MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
                $isTopParent = in_array($row->id, $parentIds);

                return !($childCount > 0 || $userCount > 0 || $isTopParent);
            })
            ->addColumn('restriction_reason', function ($row) {
                // Cek apakah ada child
                $childCount = \App\Models\MasterSatker::where('kodesatker', 'like', $row->kodesatker . '%')
                    ->whereRaw('LENGTH(kodesatker) > ?', [strlen($row->kodesatker)])
                    ->count();

                // Cek apakah ada user yang menggunakan unit kerja ini
                $userCount = \App\Models\User::where('satkerid', $row->satkerid)->count();

                // Cek apakah ini adalah 5 data induk pertama
                $parentIds = \App\Models\MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
                $isTopParent = in_array($row->id, $parentIds);

                $badgeClass = 'bg-success';
                $text = 'Dapat diedit/dihapus';
                $clickable = false;

                if ($isTopParent) {
                    $badgeClass = 'bg-danger';
                    $text = 'Unit Induk Utama';
                } elseif ($childCount > 0) {
                    $badgeClass = 'bg-warning text-dark';
                    $text = 'Memiliki ' . $childCount . ' cabang';
                    $clickable = true;
                } elseif ($userCount > 0) {
                    $badgeClass = 'bg-info';
                    $text = 'Digunakan ' . $userCount . ' user';
                    $clickable = true;
                }

                $badge = '<span class="badge ' . $badgeClass . '">' . $text . '</span>';

                if ($clickable) {
                    return '<span class="status-clickable" data-id="' . $row->id . '" data-type="' .
                        ($childCount > 0 ? 'children' : 'users') . '" style="cursor: pointer;">' . $badge . '</span>';
                }

                return $badge;
            })
            ->addColumn('detail_data', function ($row) {
                // Data untuk modal detail
                $data = [];

                // Ambil data anak
                $children = \App\Models\MasterSatker::where('kodesatker', 'like', $row->kodesatker . '%')
                    ->whereRaw('LENGTH(kodesatker) > ?', [strlen($row->kodesatker)])
                    ->select('id', 'satker', 'kodesatker')
                    ->get();

                // Ambil data user
                $users = \App\Models\User::where('satkerid', $row->satkerid)
                    ->select('id', 'fullname', 'nip', 'jabatan')
                    ->get();

                return [
                    'children' => $children,
                    'users' => $users,
                    'unit_name' => $row->satker
                ];
            })
            ->rawColumns(['restriction_reason'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<MasterSatker>
     */
    public function query(MasterSatker $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kodesatker');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('mastersatker-table')
                    ->columns($this->getColumns())
                    // ->minifiedAjax()
                    ->orderBy(1)
            ->dom('<"row"<"col-md-6"l><"col-md-6"f>>rt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
                    ->parameters([
                        'scrollY' => '60vh',
                        'scrollX' => true,
                        'scrollCollapse' => true,
                        'autoWidth' => false,
                        'paging' => true,
                        'pageLength' => 25,
            'lengthChange' => true,
            'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            'searching' => true,
                        'language' => [
                'search' => 'Cari:',
                'searchPlaceholder' => 'Cari unit kerja...',
                'lengthMenu' => 'Tampilkan _MENU_ data per halaman',
                            'paginate' => [
                                'previous' => 'Sebelumnya',
                                'next' => 'Selanjutnya'
                            ],
                            'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                            'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                            'infoFiltered' => '(difilter dari _MAX_ total data)',
                'zeroRecords' => 'Tidak ada data yang ditemukan',
                'emptyTable' => 'Tidak ada data tersedia dalam tabel',
                'loadingRecords' => 'Memuat...',
                'processing' => 'Memproses...'
                        ]
                    ])
                    ->addTableClass('table-striped table-bordered table-hover');
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
            Column::make('DT_RowIndex')
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(60),
            Column::make('satker')
                ->title('Unit Kerja')
                ->searchable(true)
                ->orderable(true),
            Column::make('kodesatker')
                ->title('Kode Unit')
                ->searchable(true)
                ->orderable(true),
            Column::computed('restriction_reason')
                ->title('Status')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->width(200),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MasterSatker_' . date('YmdHis');
    }
}
