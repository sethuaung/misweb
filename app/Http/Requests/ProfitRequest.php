<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Illuminate\Foundation\Http\FormRequest;

class ProfitRequest extends FormRequest
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
            'currency_id' => 'required',
            'reference_no' => 'required',
            'date_general' => 'required',
            'cash_acc_id' => 'required',
        ];

        $id = request()->id;

        $arr = [];
        foreach (\DB::select( "describe general_journals")  as $field){
            if(str_contains($field->Type,'varchar')){
                $arr[$field->Field] = isset($required[$field->Field]) ?'required|max:190' :'max:190';
            }else{
                if(isset($required[$field->Field])){
                    $arr[$field->Field] = $required[$field->Field];
                }
            }
        }

        //$arr['j_dr.*'] = 'number';

        /*if($id >0){
            $general = GeneralJournalDetail::where('tran_id',$id)->where('tran_type','profit')->first();

            if($general != null){

            }else{
                $arr['transaction type cannot update'] = 'required';
            }
        }*/

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
