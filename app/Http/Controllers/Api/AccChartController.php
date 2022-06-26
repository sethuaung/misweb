<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use App\Models\AccountSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccChartController extends Controller
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
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('account_charts.section_id',$section_id);
                            }
                        }else{
                            return $q->where('account_charts.section_id',$section_id);
                        }
                    }
                })
                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->join('account_sections','account_sections.id','=','account_charts.section_id')
                ->selectRaw('account_charts.*,account_sections.title')
                ->paginate(10);
        }
        else if($request->range)
        {
            $results = AccountChart::selectRaw("code, CONCAT(name ,'-', code) as title")->get();
        }
        else{
            /*$results = AccountChart::join('account_sections','account_charts.section_id','=','account_sections.id')
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
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->selectRaw("account_charts.id, CONCAT(account_charts.name, '-', account_charts.code) as name")
            ->paginate(10);*/

            $results = AccountChart::join('account_sections','account_sections.id','=','account_charts.section_id')
                ->where('status','Active')
                ->selectRaw('account_charts.*,account_sections.title')
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('account_charts.section_id',$section_id);
                            }
                        }else{
                            return $q->where('account_charts.section_id',$section_id);
                        }
                    }
                })
                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->paginate(10);

        }
        }else{
            if ($search_term)
        {
            $results = AccountChart::where(function ($q) use ($search_term){
                    return $q->orWhere('account_charts.name', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_charts.code', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_sections.title', 'LIKE', '%'.$search_term.'%')
                        ;
                })
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('account_charts.section_id',$section_id);
                            }
                        }else{
                            return $q->where('account_charts.section_id',$section_id);
                        }
                    }
                })
                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->join('account_sections','account_sections.id','=','account_charts.section_id')
                ->selectRaw('account_charts.*,account_sections.title')
                ->paginate(10);
        }
        else if($request->range)
        {
            $results = AccountChart::selectRaw("code, CONCAT(name ,'-', code) as title")->get();
        }
        else{
            /*$results = AccountChart::join('account_sections','account_charts.section_id','=','account_sections.id')
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
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->selectRaw("account_charts.id, CONCAT(account_charts.name, '-', account_charts.code) as name")
            ->paginate(10);*/

            $results = AccountChart::join('account_sections','account_sections.id','=','account_charts.section_id')
                ->selectRaw('account_charts.*,account_sections.title')
                ->where(function ($q) use ($section_id){
                    if($section_id != null){
                        if(is_array($section_id)){
                            if(count($section_id)>0){
                                return $q->whereIn('account_charts.section_id',$section_id);
                            }
                        }else{
                            return $q->where('account_charts.section_id',$section_id);
                        }
                    }
                })
                ->where(function ($query) use ($acc_type){
                    if(is_array($acc_type)){
                        if(count($acc_type)>0){
                            return $query->whereIn('account_charts.section_id',$acc_type);
                        }
                    }
                })
                ->paginate(10);

        }
        }


        return $results;
    }

    public function  getChart(Request $request){
        $acc_id = $request->main_chart_account;

        $m = AccountChart::find($acc_id);

        if($m != null){
            $c = AccountChart::find($m->id);
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
        if(companyReportPart() == 'company.moeyan'){
            return AccountChart::where('id',$id)
            ->join('account_sections','account_sections.id','=','account_charts.section_id')
            ->where('status','Active')
            ->selectRaw('account_charts.*,account_sections.title')->first();
        }else{
            return AccountChart::where('id',$id)
            ->join('account_sections','account_sections.id','=','account_charts.section_id')
            ->selectRaw('account_charts.*,account_sections.title')->first();
        }

    }
}
