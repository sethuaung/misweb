<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryRequest extends FormRequest
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
            'product_type' => 'required',
            'title' => 'required|unique:product_categories,title',
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
            //'cost_var_acc_id' => 'required'
        ];

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
