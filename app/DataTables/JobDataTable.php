<?php

namespace App\DataTables;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Imtigger\LaravelJobStatus\JobStatus;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class JobDataTable extends DataTable
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
            ->editColumn('output', function ($data) {
                return print_r($data->output, TRUE);
            })
            ->rawColumns(['output']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param JobStatus $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(JobStatus $model)
    {
        $query = $model->newQuery();

        $query->whereInput(Auth::user()->id);

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
            'job_id',
            'queue',
            'progress_now',
            'progress_max',
            'status',
            'input',
            'output'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'jobsdatatable_' . time();
    }
}
