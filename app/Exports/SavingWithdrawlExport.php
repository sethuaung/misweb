<?php

namespace App\Exports;

use App\Models\CompulsorySavingTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SavingWithdrawlExport implements FromView, ShouldAutoSize
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
            'Saving No',
            'Client NRC',
            'Client ID',
            'Client Name(Eng)',
            'Client Name(MM)',
            'Co Name',
            'Branches',
            'Center',
            'Date',
            'Withdrawl Amount',
        ];
        //dd($this->data);
        return view('exports.saving_withdrawl', [
            'rows' => $include,
            'data' => $this->data
        ]);
    }
}
