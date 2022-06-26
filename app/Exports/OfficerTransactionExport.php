<?php

namespace App\Exports;

use App\Models\Loan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class OfficerTransactionExport implements FromView
{
    public function __construct($view, $search = "")
    {
        $this->view = $view;
        $this->data = $search;
    }

    public function view(): View
    {
        $search = $this->data;
        $searchArray = [];
        foreach ($search as $key => $value) {
            if (strpos($value, '|') !== false) {
                $valueArray = explode("|", $value);
                $value = $valueArray[0];
            }
            ${$key} = (!empty($value)) ? $value : '';
        }

        $data = Loan::orderBy('id', 'DESC');
        $data->where('disbursement_status', 'Activated');

        if (!empty($branch_id)) {
            $data->where('branch_id', $branch_id);
        }
        if (!empty($center_id)) {
            $data->where('center_leader_id', $center_id);
        }
        if (!empty($loan_officer_id)) {
            $data->where('loan_officer_id', $loan_officer_id);
        }
        if (!empty($from_to)) {
            $from_to_array = explode(" - ", $from_to);
            $from = Carbon::createFromFormat('m/d/Y', $from_to_array[0])->toDateString();
            $to = Carbon::createFromFormat('m/d/Y', $from_to_array[1])->toDateString();
            $data->where('loan_application_date', '>=', $from);
            $data->where('loan_application_date', '<=', $to);
        }
        // if (!empty($search)) {
        //     $data->where('payment_number', 'LIKE', '%'.$search.'%');
        // }
        $reports = $data->get();

        $params = ['page'=>'page', 'reports'=>$reports];

        return view($this->view, $params);
    }
}
