<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client_number' => 'required',
            'name' => 'required',
            'dob' => 'required',
            'branch_id' => 'required',
            // 'center_leader_id' => 'required',
            //'nrc_number' => 'required',
            'primary_phone_number' => 'required',
            'loan_officer_id' => 'required',
            'nrc_old.1' => ($this->request->get('nrc_type') == 'New Format') ? 'required' : '',
            'nrc_old.2' => ($this->request->get('nrc_type') == 'New Format') ? 'required' : '',
            'nrc_old.3' => ($this->request->get('nrc_type') == 'New Format') ? 'required' : '',
            'nrc_old.4' => ($this->request->get('nrc_type') == 'New Format') ? 'required' : '',
            'nrc_number_new' => ($this->request->get('nrc_type') == 'Old Format') ? 'required' : '',
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
    /*public function messages()
    {
        return [
            'nrc_old.1.required' => 'NRC Number field is required',
            'nrc_old.2.required' => 'NRC Number field is required',
            'nrc_old.3.required' => 'NRC Number field is required',
            'nrc_old.4.required' => 'NRC Number field is required',
        ];
    }*/
}

