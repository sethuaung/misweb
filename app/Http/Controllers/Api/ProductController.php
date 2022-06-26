<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductLottery;
use App\Models\ServiceCategory;
use App\Models\StockMovementSerial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Gas\Models\G_input;
use Modules\Gas\Models\G_inputDetail;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $group_id = $request->input('group_id');
        $category_id = $request->input('category_id');

        $warehouse_id = $request->input('warehouse_id');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){
                if($category_id >0){
                    $query->where('category_id',$category_id);
                }
                if($group_id >0){
                    $query->where('group_id',$group_id);
                }
               if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }
                if($category_id >0 || $group_id>0 || $warehouse_id>0)  {
                    return $query;
                }

            })->where(function ($query) use ($search_term){
               return $query->where('product_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('sku', $search_term)
                    ->orWhere('upc', $search_term);
            })
                ->where('status','Active')
                ->paginate(10);
        }
        else
        {
            //return null;
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($category_id >0 || $group_id>0)  {
                    return $query;
                }


                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

            })
                ->where('status','Active')
                ->paginate(10);
        }

        return $results;
    }

    public function jqueryProduct(Request $request)
    {
        //return $request->all();
        $search_term = $request->input('term');
        $group_id = $request->input('group_id');
        $category_id = $request->input('category_id');

        $warehouse_id = $request->input('warehouse_id');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

               if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

                if($category_id >0 || $group_id>0 || $warehouse_id>0)  {
                    return $query;
                }

            })->where(function ($query) use ($search_term){
               return $query->where('product_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('sku', '=', $search_term)
                    ->orWhere('upc', '=', $search_term);
            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/

             ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->limit(20)->get();


        }
        else
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($category_id >0 || $group_id>0)  {
                    return $query;
                }


                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/
                ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->limit(20)->get();
        }
        // dd($results);
        return $results;
    }
    public function jqueryProductGas(Request $request)
    {
        //return $request->all();
        $search_term = $request->input('term');
        $group_id = $request->input('group_id');
        $category_id = $request->input('category_id');

        $warehouse_id = $request->input('warehouse_id');
        $page = $request->input('page');
        $id = StockMovementSerial::select('product_id')->groupby('product_id')->get()->toArray();



        if ($search_term)
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

               if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

                if($category_id >0 || $group_id>0 || $warehouse_id>0)  {
                    return $query;
                }

            })->where(function ($query) use ($search_term){
               return $query->where('product_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('sku', '=', $search_term)
                    ->orWhere('upc', '=', $search_term);
            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/

             ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->whereIn('id',$id)
                ->limit(20)->get();


        }
        else
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($category_id >0 || $group_id>0)  {
                    return $query;
                }


                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/
                ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->whereIn('id',$id)
                ->limit(20)->get();
        }

        return $results;
    }
    public function jqueryProductGasdelivery(Request $request)
    {
        //return $request->all();
        $search_term = $request->input('term');
        $group_id = $request->input('group_id');
        $category_id = $request->input('category_id');

        $warehouse_id = $request->input('warehouse_id');
        $page = $request->input('page');
        $id_input = G_input::select('id')
//            ->where('status','complete')
            ->get()->toArray();

//        $id = G_inputDetail::whereIn('gas_input_id',$id_input)->get()->toArray();

//return $id;



        if ($search_term)
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

               if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

                if($category_id >0 || $group_id>0 || $warehouse_id>0)  {
                    return $query;
                }

            })->where(function ($query) use ($search_term){
               return $query->where('product_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('sku', '=', $search_term)
                    ->orWhere('upc', '=', $search_term);
            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/

             ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
//                ->whereIn('id',$id)
                ->limit(20)->get();


        }
        else
        {
            $results = Product::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($category_id >0 || $group_id>0)  {
                    return $query;
                }


                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/
                ->selectRaw("id , product_name as value,ct_length,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
//                ->whereIn('id',$id)
                ->limit(20)->get();
        }

        return $results;
    }

    public function jqueryProductLottery(Request $request)
    {
        //return $request->all();
        $search_term = $request->input('term');
        $group_id = $request->input('group_id');
        $category_id = $request->input('category_id');

        $warehouse_id = $request->input('warehouse_id');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = ProductLottery::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

                if($category_id >0 || $group_id>0 || $warehouse_id>0)  {
                    return $query;
                }

            })->where(function ($query) use ($search_term){
                return $query->where('product_name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('sku', '=', $search_term)
                    ->orWhere('upc', '=', $search_term);
            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/

                ->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->limit(20)->get();


        }
        else
        {
            $results = ProductLottery::where(function ($query) use ($category_id,$group_id,$warehouse_id){

                if($category_id >0){
                    $query->where('category_id',$category_id);
                }

                if($group_id >0){
                    $query->where('group_id',$group_id);
                }

                if($category_id >0 || $group_id>0)  {
                    return $query;
                }


                if($warehouse_id >0){
                    $query->whereHas('warehouse', function($q) use ($warehouse_id){
                        $q->where('warehouse_id',$warehouse_id);
                    });
                }

            })
                /*->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name,'-',product_name_kh) as label")
                ->where('status','Active')
                ->limit(20)->get();*/
                ->selectRaw("id , product_name as value,
                CONCAT(COALESCE(product_code,upc,sku),'-',product_name) as label")
                ->where('status','Active')
                ->limit(20)->get();
        }

        return $results;
    }

    public function show($id)
    {
        return Product::where('status','Active')->find($id);
    }

    public function getAccFromCategory(Request $request){
        $category_id = $request->category_id;
        $m = ProductCategory::find($category_id);
        if($m != null){
            return response()->json([
                'purchase_acc_id' => $m->purchase_acc_id  ,
                'transportation_in_acc_id' => $m->transportation_in_acc_id  ,
                'purchase_return_n_allow_acc_id' => $m->purchase_return_n_allow_acc_id  ,
                'purchase_discount_acc_id' => $m->purchase_discount_acc_id  ,
                'sale_acc_id' => $m->sale_acc_id  ,
                'sale_return_n_allow_acc_id' => $m->sale_return_n_allow_acc_id  ,
                'sale_discount_acc_id' => $m->sale_discount_acc_id  ,
                'stock_acc_id' => $m->stock_acc_id  ,
                'adj_acc_id' => $m->adj_acc_id  ,
                'cost_acc_id' => $m->cost_acc_id  ,
                'cost_var_acc_id' => $m->cost_var_acc_id
            ]);
        }

        return response()->json([
            'purchase_acc_id' => 0  ,
            'transportation_in_acc_id' => 0 ,
            'purchase_return_n_allow_acc_id' => 0  ,
            'purchase_discount_acc_id' => 0 ,
            'sale_acc_id' => 0  ,
            'sale_return_n_allow_acc_id' => 0 ,
            'sale_discount_acc_id' => 0 ,
            'stock_acc_id' => 0 ,
            'adj_acc_id' => 0 ,
            'cost_acc_id' => 0 ,
            'cost_var_acc_id' => 0
        ]);
    }
    public function getAccFromServiceCategory(Request $request){
        $category_id = $request->category_id;
        $m = ServiceCategory::find($category_id);
        if($m != null){
            return response()->json([
                'purchase_acc_id' => $m->purchase_acc_id  ,
                'transportation_in_acc_id' => $m->transportation_in_acc_id  ,
                'purchase_return_n_allow_acc_id' => $m->purchase_return_n_allow_acc_id  ,
                'purchase_discount_acc_id' => $m->purchase_discount_acc_id  ,
                'sale_acc_id' => $m->sale_acc_id  ,
                'sale_return_n_allow_acc_id' => $m->sale_return_n_allow_acc_id  ,
                'sale_discount_acc_id' => $m->sale_discount_acc_id  ,
                'stock_acc_id' => $m->stock_acc_id  ,
                'adj_acc_id' => $m->adj_acc_id  ,
                'cost_acc_id' => $m->cost_acc_id  ,
                'cost_var_acc_id' => $m->cost_var_acc_id
            ]);
        }

        return response()->json([
            'purchase_acc_id' => 0  ,
            'transportation_in_acc_id' => 0 ,
            'purchase_return_n_allow_acc_id' => 0  ,
            'purchase_discount_acc_id' => 0 ,
            'sale_acc_id' => 0  ,
            'sale_return_n_allow_acc_id' => 0 ,
            'sale_discount_acc_id' => 0 ,
            'stock_acc_id' => 0 ,
            'adj_acc_id' => 0 ,
            'cost_acc_id' => 0 ,
            'cost_var_acc_id' => 0
        ]);
    }

    public function getAccOp(Request $request){
        $acc_id = $request->acc_id;
        if($acc_id >0){
            $m = AccountChart::find($acc_id);

            if($m != null){
                return '<option value="'.$m->id.'">'.$m->name.'</option>';
            }

        }
        return '<option value=""></option>';
    }
}
