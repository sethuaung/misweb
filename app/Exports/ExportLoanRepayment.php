<?php

//namespace App\Models;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportLoanRepayment implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $include = [
            'client_number',
            'loan_number',
            'principle',
            'interest',
            'penalty_amount',
            'payment',
            'saving_amount',
            'cash_acc_code',
            'payment_date',
        ];

        return view('exports.all', [
            'rows' => $include
        ]);
    }


}
