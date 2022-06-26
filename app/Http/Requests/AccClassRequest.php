<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class AccClassRequest extends FormRequest
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
        $required =  [
            'name' => 'required'
        ];

        $arr = [];
        foreach (\DB::select( "describe classes")  as $field){
            if(str_contains($field->Type,'varchar')){
                $arr[$field->Field] = isset($required[$field->Field]) ?'required|max:190' :'max:190';
            }else{
                if(isset($required[$field->Field])){
                    $arr[$field->Field] = $required[$field->Field];
                }
            }
        }
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
