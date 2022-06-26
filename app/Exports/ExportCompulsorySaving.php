<?php

//namespace App\Models;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportCompulsorySaving implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $include = [
            'client_number',
            'account_number',
            'payment_date',
            'nrc',
            'saving_name',
            'available_balance',
            'principle',
            'interest',
            'cash_from',
            'cash_balance',
            'cash_withdrawal',
            'cash_remaining',
            'cash_acc_code'
        ];

        return view('exports.all', [
            'rows' => $include
        ]);
    }


}
