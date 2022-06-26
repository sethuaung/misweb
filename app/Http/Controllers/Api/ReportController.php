<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function dashboard(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return view('vendor.backpack.base.dashboard',
            [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'have_search' => 1,

            ]);
    }
    
    public function dashboard_data(){
        return view('vendor.backpack.base.mkt_old');
    }
}
