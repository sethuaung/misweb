<?php

//namespace App\Models;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportLoan implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $include = [
            'client_id',
            'you_are_a_group_leader',
            'you_are_a_center_leader',
            'guarantor_name_myanmar',
            'loan_number',
            'branch_code',
            'center_code',
            'loan_officer_code',
            'loan_product_code',
            'loan_application_date',
            'first_installment_date',
            'loan_amount',
            'interest_rate',
            'interest_rate_period',
            'loan_term',
            'loan_term_value',
            'repayment_term',
            'currency',
            'transaction_type',
            'group_loan_id'

        ];

        return view('exports.all', [
            'rows' => $include
        ]);
    }


}
