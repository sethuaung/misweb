<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanProduct;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Product::where(function ($q) use($search_term){
                    return $q->where('name', 'LIKE', '%' . $search_term . '%')
                            ->orWher('code', 'LIKE', '%' . $search_term . '%');
                })
                ->paginate(100);
        } else {
            $results = Product::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {

        return LoanProduct::find($id);

    }
    public function getPrice(Request $request){
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if($product != null){
            $stock = StockMovement::where('product_id',$product_id)
                ->sum('qty_cal');
            return response()->json(['price'=>$product->product_price,'qty'=> $stock]);
        }
        return response()->json(['price'=>0,'qty'=> 0.001]);
    }

}
