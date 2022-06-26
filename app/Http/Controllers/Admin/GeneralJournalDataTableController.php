<?php

namespace App\Http\Controllers\Admin;

use App\Models\GeneralJournalDetail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;


class GeneralJournalDataTableController extends Controller
{


    public function index(){
        return view('partials.index');
    }

    public function dataTable(Request $request)
    {

        $model = GeneralJournalDetail::select(
            'general_journals.tran_reference as reference',
            'general_journal_details.tran_type as type',
            'general_journal_details.j_detail_date as date'

        )->leftJoin('general_journals', 'general_journals.id', '=', 'general_journal_details.journal_id');

//        if($request->item_name !=''){
//            $model->where('stock_office_supply.item_id',$request->item_name);
//        }

        return DataTables::of($model)->make(true);
    }


}
