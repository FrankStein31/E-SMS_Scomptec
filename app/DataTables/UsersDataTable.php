<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('satker_name', function ($row) {
                return $row->satker ? $row->satker->satker : '-';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['satker']);
        // Filter by jabatan/usergroup jika ada request
        $jabatan = $this->request->get('jabatan');
        if ($jabatan) {
            $query->where('jabatan', $jabatan);
        }
        return $query->orderBy('username');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
            ->columns($this->getColumns())
                    ->orderBy(1)
            ->dom('frt<"row justify-content-between"<"col-auto"p><"col-auto"i>>')
            ->parameters([
                'scrollY' => '60vh',
                'scrollX' => true,
                'scrollCollapse' => true,
                'autoWidth' => false,
                'paging' => true,
                'pageLength' => 25,
                'lengthChange' => false,
                'searching' => true,
                'language' => [
                    'paginate' => [
                        'previous' => 'Sebelumnya',
                        'next' => 'Selanjutnya'
                    ],
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                    'infoFiltered' => '(difilter dari _MAX_ total data)',
                    'zeroRecords' => 'Tidak ada data yang ditemukan',
                    'search' => 'Cari:',
                    'searchPlaceholder' => 'Ketik untuk mencari...'
                ]
            ])
            ->addTableClass('table-striped table-bordered table-hover');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('username')->title('Username'),
            Column::make('fullname')->title('Nama Lengkap'),
            Column::make('nip')->title('NIP'),
            Column::make('pangkat')->title('Pangkat'),
            Column::make('jabatan')->title('Jabatan'),
            Column::make('satker_name')->title('Unit Kerja'),
            Column::make('email')->title('Email'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
