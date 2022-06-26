<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MFS;
use App\Models\LoanCalculate;
use App\Models\LoanProduct;
use App\Models\Loan2;

class CloseJournalController extends Controller
{
    public function index(){
        return view('partials.close-journal');
     }

}
