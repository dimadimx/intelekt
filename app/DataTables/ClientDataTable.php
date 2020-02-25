<?php

namespace App\DataTables;

use App\Models\Client;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ClientDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->addColumn('action', 'clients.datatables_actions')
            ->editColumn('user_id', function ($data) {
                return $data->user_id. ' ('.$data->user->name.')';
            })
            ->editColumn('registration', function ($data) {
                return $data->registration->format('d-m-Y');
            })
            ->filterColumn('registration', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(registration,'%d-%m-%Y') like ?", ["%$keyword%"]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Client $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Client $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
                'initComplete' => 'function () {
                  this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).
                      appendTo($(column.footer()).empty()).
                      on(\'change\', function () {
                        column.search($(this).val(), false, false, true).draw();
                      });
                  });
                }'
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'user_id',
            'api_uid',
            'api_gid',
            'api_belong_uid',
            'login',
            'phone',
            'registration',
            'warning'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'clientsdatatable_' . time();
    }
}
