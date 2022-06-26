<?php

namespace App\Exports;

use App\Models\CompulsorySavingTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SavingDepositExport implements FromView, ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        //dd($data);
    }

    public function view(): View
    {
        $include = [
            'Reference No',
            'Account No',
            'Client ID',
            'NRC Number',
            'Client Name(Eng)',
            'Client Name(MM)',
            'Co Name',
            'Branches',
            'Center',
            'Date',
            'Interest Amount',
            'Accrued Interest',
            'Deposit Amount',
            'Saving Principle',
        ];
        //dd($this->data);
        return view('exports.saving_interest', [
            'rows' => $include,
            'data' => $this->data
        ]);
    }
}
