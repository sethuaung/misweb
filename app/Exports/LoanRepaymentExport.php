<?php

namespace App\Exports;

use App\Models\LoanPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class LoanRepaymentExport implements FromView
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

        $data = LoanPayment::orderBy('id', 'DESC');

        if (!empty($branch_id)) {
            $data->whereHas('loan_disbursement', function($query) use($branch_id) {
                $query->where('branch_id', $branch_id);
            });
        }
        if (!empty($center_id)) {
            $data->whereHas('loan_disbursement', function($query) use($center_id) {
                    $query->where('center_leader_id', $center_id);
            });
        }
        if (!empty($loan_officer_id)) {
            $data->whereHas('loan_disbursement', function($query) use($loan_officer_id) {
                    $query->where('loan_officer_id', $loan_officer_id);
            });
        }
        if (!empty($from_to)) {
            $from_to_array = explode(" - ", $from_to);
            $from = Carbon::createFromFormat('m/d/Y', $from_to_array[0])->toDateString();
            $to = Carbon::createFromFormat('m/d/Y', $from_to_array[1])->toDateString();
            $data->where('payment_date', '>=', $from);
            $data->where('payment_date', '<=', $to);
        }
        if (!empty($applicant_number_id)) {
            $data->where('disbursement_id', $applicant_number_id);
        }
        if (!empty($client_name)) {
            $data->whereHas('loan_disbursement', function($query) use($client_name) {
                $query->whereHas('client_name', function($q) use($client_name) {
                    $q->where('name', 'LIKE', '%'.$client_name.'%');
                });
            });
        }
        if (!empty($nrc_number)) {
            $data->whereHas('loan_disbursement', function($query) use($nrc_number) {
                $query->whereHas('client_name', function($q) use($nrc_number) {
                    $q->where('nrc_number', 'LIKE', '%'.$nrc_number.'%');
                });
            });
        }
        if (!empty($search)) {
            $data->where('payment_number', 'LIKE', '%'.$search.'%');
        }
        $reports = $data->get();

        $params = ['page'=>'page', 'reports'=>$reports];

        return view($this->view, $params);
    }
}
