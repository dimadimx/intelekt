<?php

namespace App\DataTables;

use App\Models\ClientStatistic;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ClientStatisticDataTable extends DataTable
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
            ->addColumn('action', 'client_statistics.datatables_actions')
            ->editColumn('client_id', function ($data) {
                return $data->client_id. ' (<a target="_blank" href="//billing.intelekt.cv.ua/admin/index.cgi?index=15&UID='.$data->client->api_uid.'">'.$data->client->login.'</a>)';
            })
            ->addColumn('client_group', function ($data) {
                return $data->client->api_gid;
            })
            ->editColumn('date', function ($data) {
                return $data->date->format('d-m-Y');
            })
            ->filterColumn('date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(date,'%d-%m-%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('client_group', function ($query, $keyword) {
                $query->whereHas('client', function($q) use($keyword){
                    $q->where('api_gid', $keyword);
                });
            })
            ->filterColumn('client_id', function ($query, $keyword) {
                $query->whereHas('client', function($q) use($keyword){
                    $q->where('login', $keyword);
                });
            })
            ->rawColumns(['client_id', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ClientStatistic $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ClientStatistic $model)
    {
        $query = $model->newQuery();

        $query->whereIn('client_id', Auth::user()->clients->pluck('id'));

        return $query;
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
            'client_id',
            'client_group',
            'date',
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'client_statisticsdatatable_' . time();
    }
}
