<?php

//namespace App\Models;

namespace App\Exports;

use App\Models\GeneralJournal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCashBookDetail implements FromView, ShouldAutoSize
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
            'Date',
            'Reference No',
            'Client ID',
            'Client Name',
            'Account Code',
            'Account Name',
            'Description',
            'In',
            'Out',
            'Balance',

        ];

        return view('exports.cash_book_detail', [
            'rows' => $include,
            'data' => $this->data
        ]);
    }


/*    public function collection()
    {
        return LoanOutstanding::select('disbursement_number,disburse_date')

            ->get();
    }

    public function headings(): array
    {
        return [
            'Disburse Date',
            'Disbursement Number',
        ];
    }*/


}
