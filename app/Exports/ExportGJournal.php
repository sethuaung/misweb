<?php

//namespace App\Models;

namespace App\Exports;

use App\Models\GeneralJournal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportGJournal implements FromView, ShouldAutoSize
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
            'Type',
            'Date',
            'Journal No',
            'Reference No',
            'Branch',
            'Login ID',
            'Client ID',
            'Client Name',
            'FRD Account Code',
            'Account Code',
            'Account Title and Description',
            'Description',
            'Debit',
            'Credit',
        ];

        return view('exports.general_journal', [
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
