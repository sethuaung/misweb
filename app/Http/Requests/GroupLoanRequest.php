<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class GroupLoanRequest extends FormRequest
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
        $id = request()->id;

        if($id>0){
            return [
                'group_code' => 'required|unique:group_loans,group_code,'.$id,
                //'group_name' => 'required|unique:group_loans,group_name,'.$id
            ];
        }else{
            return [
                'group_code' => 'required|unique:group_loans,group_code',
                //'group_name' => 'required|unique:group_loans,group_name'
            ];
        }

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
