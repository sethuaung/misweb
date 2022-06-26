<?php

//namespace App\Models;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportJournal implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $include = ['branch_code','reference_no','coa_debit','coa_credit','amount_debit','date','amount_credit','descriptions','staff_id','staff_name'];
        return view('exports.all', [
            'rows' => $include
        ]);
    }


}
