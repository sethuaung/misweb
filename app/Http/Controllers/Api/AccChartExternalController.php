<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChartExternal;
use App\Models\AccountSection;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccChartExternalController extends Controller
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $section_id = $request->input('section_id');
        $acc_type = $request->acc_type;
        //dd($acc_type);

        if ($search_term)
        {
            $results = AccountChartExternal::where(function ($q) use ($search_term){
                return $q->orWhere('account_chart_externals.external_acc_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('account_chart_externals.external_acc_code', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('account_sections.title', 'LIKE', '%'.$search_term.'%')
                    ;
            })
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('account_chart_externals.section_id',$section_id);
                            }
                        }else{
                            return $q->where('account_chart_externals.section_id',$section_id);
                        }
                    }
                })
                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_chart_externals.section_id',$acc_type);
                        }
                    }
                })
                ->join('account_sections','account_sections.id','=','account_chart_externals.section_id')
                ->selectRaw('account_chart_externals.*,account_sections.title')
                ->paginate(10);
        }
        else
        {
            $results = AccountChartExternal::join('account_sections','account_sections.id','=','account_chart_externals.section_id')
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('section_id',$section_id);
                            }
                        }else{
                            return $q->where('section_id',$section_id);
                        }
                    }
                })

                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_chart_externals.section_id',$acc_type);
                        }
                    }
                })
                ->selectRaw('account_chart_externals.*,account_sections.title')
                ->paginate(10);
        }

        return $results;
    }

    public function  getChart(Request $request){
        $acc_id = $request->main_chart_account;

        $m = AccountChartExternal::find($acc_id);

        if($m != null){
            $c = AccountChartExternal::find($m->id);
            $sec = AccountSection::find($c->section_id);
            if($c != null){
                return response()->json([
                    'error' => 0,
                    'id' => $c->id,
                    'code' => $c->code,
                    'name' => $c->name,
                    'name_kh' => $c->name_kh?$c->name_kh:'',
                    'sec_type' => $sec->description?$sec->description:'',
                    'section_id' => $c->section_id,
                    'parent_id' => $c->parent_id,
                    'sub_section_id' => $c->sub_section_id,
                ]);
            }
        }

        return response()->json(['error' => 1]);


    }

    public function show($id)
    {
        return AccountChartExternal::where('id',$id)
            ->join('account_sections','account_sections.id','=','account_chart_externals.section_id')
            ->selectRaw('account_chart_externals.*,account_sections.title')->first();
    }
//    public function index(Request $request)
//    {
//        $search_term = $request->input('q');
//        $page = $request->input('page');
//
//        if ($search_term)
//        {
//            $results = AccountChartExternal::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
//                 ->paginate(100);
//        }
//        else
//        {
//            $results = AccountChartExternal::paginate(100);
//        }
//
//        return $results;
//    }
//
//    public function show($id)
//    {
//        return Loan::find($id);
//    }



}
