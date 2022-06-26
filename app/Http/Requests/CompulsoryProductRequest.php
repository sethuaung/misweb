<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompulsoryProductRequest extends FormRequest
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
     * @return array 'default_saving_deposit_id' => 'Default Saving Deposit',
    'default_saving_interest_id' => 'Default Saving Interest',
    'default_saving_interest_payable_id' => 'Default Saving Interest Payable',
    'default_saving_withdrawal_id' => 'Default Saving Withdrawal',
    'default_saving_interest_withdrawal_id' => 'Default Saving Interest Withdrawal',
     */
    public function rules()
    {
        return [
            'default_saving_deposit_id' => 'required',
            'default_saving_interest_id' => 'required',
            'default_saving_interest_payable_id' => 'required',
            'default_saving_withdrawal_id' => 'required',
            'default_saving_interest_withdrawal_id' => 'required',
            'code' => 'required',
            'product_name' => 'required',
            'saving_amount' => 'required',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ];
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
