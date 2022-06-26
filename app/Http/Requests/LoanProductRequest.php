<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class LoanProductRequest extends FormRequest
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
        return [
            'code' => 'required',
            //'branches[]' => 'required',
            'name' => 'required',
            'principal_default' => 'required',
            'principal_max' => 'required',
            'principal_min' => 'required',
            'loan_term_value' => 'required',
            'loan_term' => 'required',
            'repayment_term' => 'required',
            'interest_rate_default' => 'required',
            'interest_rate_min' => 'required',
            'interest_rate_max' => 'required',
            'interest_rate_period' => 'required',
            'interest_method' => 'required',
           // 'accounting_rule' => 'required',
            'fund_source_id' => 'required',
            //'loan_portfolio_id' => 'required',
            //'interest_receivable_id' => 'required',
            //'fee_receivable_id' => 'required',
            //'penalty_receivable_id' => 'required',
            //'overpayment_id' => 'required',
            'income_for_interest_id' => 'required',
            'income_from_penalty_id' => 'required',
            //'income_from_recovery_id' => 'required',
            //'loan_written_off_id' => 'required',
            //'compulsory_id' => 'required',
            //'loan_products_charge' => 'required',
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
