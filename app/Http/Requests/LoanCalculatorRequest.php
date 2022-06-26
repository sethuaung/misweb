<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class LoanCalculatorRequest extends FormRequest
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
             'branch_id' => 'required',
             'center_leader_id' => 'required',
             'center_code_id' => 'required',
             'loan_officer_id' => 'required',
             'transaction_type_id' => 'required',
             'currency_id' => 'required',
             'client_id' => 'required',
             'loan_application_date' => 'required',
             'first_installment_date' => 'required',
             'loan_production_id' => 'required',
             'loan_amount' => 'required',
             'loan_term_value' => 'required',
             'interest_rate' => 'required',
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
