<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Products\Inventory;
use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $arr = [
            'product_name' => 'required:unique:products,product_name',
            //'sku' => 'required:unique:products,sku',
            //'upc' => 'required:unique:products,upc',
            'upc' => 'required|unique:products,upc',
            //'purchase_acc_id' => 'required',
            //'transportation_in_acc_id' => 'required',
            'purchase_return_n_allow_acc_id' => 'required',
            'purchase_discount_acc_id' => 'required',
            'sale_acc_id' => 'required',
            'sale_return_n_allow_acc_id' => 'required',
            'sale_discount_acc_id' => 'required',
            'stock_acc_id' => 'required',
            'adj_acc_id' => 'required',
            'cost_acc_id' => 'required',
            //'cost_var_acc_id' => 'required',
            'unit_id' => 'required',
            'currency_id' => 'required'
        ];


        $unit_variant = request()->unit_variant;
        $default_sale_unit = request()->default_sale_unit;
        $default_purchase_unit = request()->default_purchase_unit;
        $unit_id = request()->unit_id;

        $code = request()->upc;
        $product = Inventory::where('upc',$code)->first();

        if($unit_variant !=null){
            if(is_array($unit_variant)){
                if(!in_array($default_sale_unit,$unit_variant)){
                    $arr['incorrect_sale_unit'] = 'required';
                }
                if(!in_array($default_purchase_unit,$unit_variant)){
                    $arr['incorrect_purchase_unit'] = 'required';
                }
                if(!in_array($unit_id,$unit_variant)){
                    $arr['incorrect_warehouse_unit'] = 'required';
                }
            }
        }
//        if($product != null){
//            //return ['code already exists' => 'required'.$this->id];
//            $arr['code already exists'] = 'required';
//        }

        return $arr;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
