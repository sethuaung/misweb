<?php

namespace App\DataTables;

// use App\User;
use App\Models\{Loan, LoanCalculate};
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PlanRepaymentsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query);
            // ->addColumn('action', 'planrepaymentsdatatable.action');
        // return $this->datatables
        //     ->eloquent($this->query())
        //     ->addColumn('action', 'eloquent.tables.users-action');
        // return $this->datatables
        //     ->eloquent($query)
        //     ->addColumn('action', 'planrepaymentsdatatable.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Loan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function query(Loan $model)
    public function query(Loan $mode)
    {
        $row = Loan::with(['loan_schedule' => function($query){
            $query->select('id', 'disbursement_id', 'date_s', 'principal_s');
            // ->whereRaw('DATE(date_s) = DATE(NOW())')
            // ->orderBy('id', 'DESC')
            // ->first();
        }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        ->whereHas('loan_schedule', function($q){
            $q->whereRaw('DATE(date_s) = DATE(NOW())');
            $q->where('date_p', NULL);
        })
        ->where('disbursement_status', 'Activated')
        ->orderBy('id','desc')->get()
        ->map(function ($value, $key) {

            $row = LoanCalculate::select('date_s', 'principal_s')
            ->where('disbursement_id', $value->id)
            ->whereRaw('DATE(date_s) = DATE(NOW())')
            ->where('date_p', NULL)
            ->first();

            $value->due_date = ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('d M Y') : '';
            $value->installment_amount = ($row) ? $row->principal_s : 0;
            return $value;
        });

        // dd($row);
        // return $model->newQuery()->select('id', 'add-your-columns-here', 'created_at', 'updated_at');
        // return $row;
        return $this->applyScopes($row);
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
                    // ->addAction(['width' => '80px'])
                    // ->parameters($this->getBuilderParameters());
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset', 'reload'],
                        'initComplete' => "function () {
                            this.api().columns().every(function () {
                                var column = this;
                                var input = document.createElement(\"input\");
                                $(input).attr(\"style\", \"text-align: center;width: 100%\");
                                $(input).appendTo($(column.footer()).empty())
                                .on('keyup', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }",
                        'scrollX' => true,
                        'responsive' => true,
                        'autoWidth' => false
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
            'disbursement_number' => ['title' => 'Loan Number'],
            'client_name.name' => ['title' => 'Client Name'],
            'client_name.nrc_number' => ['title' => 'NRC'],
            'client_name.primary_phone_number' => ['title' => 'Phone'],
            'branch_name.title' => ['title' => 'Branch'],
            'center_leader_name.title' => ['title' => 'Center'],
            'officer_name.name' => ['title' => 'CO Name'],
            'due_date' => ['title' => 'Due Date'],
            'installment_amount' => ['title' => 'Installment Amount'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'PlanRepayments_' . date('YmdHis');
    }
}
