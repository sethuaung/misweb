<?php

//namespace App\Models;

namespace App\Exports;

use App\Models\LoanOutstanding;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportLoanOutstanding implements FromView, ShouldAutoSize
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
            'Disburse Date',
            'Last Repayment Date',
            'Client ID',
            'Loan Number',
            'Name',
            'Name Other',
            'Nrc Number',
            'Branch',
            'Center',
            'Co Name',
            'Loan Type',
            'Loan Amount',
            'Total Interest',
            'Installment Amount',
            'Principle Repay',
            'Interest Repay',
            'Principle Outstanding',
            'Interest Outstanding',
            'Total Outstanding'

        ];

        return view('exports.loan_outstanding', [
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
