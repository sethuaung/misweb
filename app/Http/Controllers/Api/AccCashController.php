<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccCashController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $section_id = $request->input('section_id');
        $acc_type = $request->acc_type;
        //dd($acc_type);
        if(companyReportPart() == 'company.moeyan'){
            if ($search_term)
        {
            $results = AccountChart::where('status','Active')->where(function ($q) use ($search_term){
                    return $q->orWhere('account_charts.name', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_charts.code', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_sections.title', 'LIKE', '%'.$search_term.'%')
                        ;
                })
                ->where('section_id',10)
                ->join('account_sections','account_sections.id','=','account_charts.section_id')
                ->selectRaw('account_charts.*,account_sections.title')
                ->paginate(10);
        }
        else
        {
            $results = AccountChart::join('account_sections','account_sections.id','=','account_charts.section_id')
                ->where('section_id',10)
                ->where('status','Active')
                ->selectRaw('account_charts.*,account_sections.title')
            ->paginate(10);
        }
        }
        else{
            if ($search_term)
        {
            $results = AccountChart::where(function ($q) use ($search_term){
                    return $q->orWhere('account_charts.name', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_charts.code', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_sections.title', 'LIKE', '%'.$search_term.'%')
                        ;
                })
                ->where('section_id',10)
                ->join('account_sections','account_sections.id','=','account_charts.section_id')
                ->selectRaw('account_charts.*,account_sections.title')
                ->paginate(10);
        }
        else
        {
            $results = AccountChart::join('account_sections','account_sections.id','=','account_charts.section_id')

                ->where('section_id',10)
                ->selectRaw('account_charts.*,account_sections.title')
            ->paginate(10);
        }
        }
        

        return $results;
    }

    public function show($id)
    {
        if(companyReportPart() == 'company.moeyan'){
            return AccountChart::where('id',$id)->where('section_id',10)
            ->where('status','Active')
            ->join('account_sections','account_sections.id','=','account_charts.section_id')
            ->selectRaw('account_charts.*,account_sections.title')->first();
        }
        else{
            return AccountChart::where('id',$id)->where('section_id',10)
            ->join('account_sections','account_sections.id','=','account_charts.section_id')
            ->selectRaw('account_charts.*,account_sections.title')->first();
        }
    }
}
