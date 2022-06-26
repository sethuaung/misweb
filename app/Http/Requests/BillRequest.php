<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            'supplier_id' => 'required',
            'p_date' => 'required',
        ];
        

        $arr = [];
        foreach (\DB::select( "describe purchases")  as $field){
            if(str_contains($field->Type,'varchar')){
                $arr[$field->Field] = isset($required[$field->Field]) ?'required|max:190' :'max:190';
            }else if(str_contains($field->Type,'double')){
                $arr[$field->Field] = isset($required[$field->Field])?'required|numeric' : 'numeric';
            }else{
                if(isset($required[$field->Field])){
                    $arr[$field->Field] =$required[$field->Field];
                }
            }
        }
        $paid = request()->paid;
        $cash_acc_id = request()->cash_acc_id ;
        if($paid >0){
            if(!($cash_acc_id>0)){
                $arr['cash_acc'] = 'required';
            }
        }
        if(isUseBranch()){
            $arr['branch_id'] = 'required|numeric';
        }
        $p_type = request()->purchase_type;
        if(companyReportPart() == 'company.myanmarelottery_account'){
            if($p_type!='bill-only-from-order'){
                $arr['warehouse_id'] = 'required|numeric';
            }else{

            }
        }

        if(companyReportPart() == 'company.myanmarelottery_account'){
            $arr['round_id'] = 'required|numeric';
        }

        if(isSettingAccount()){
            $arr['miss_config_account'] = 'required';
        }
        $supplier_id = request()->supplier_id;
        if($supplier_id != null){
            if(isMissAccountForSupply($supplier_id)){
                $arr['miss_config_account_for_supply'] = 'required';
            }
        }
        $purchase_type = request()->purchase_type;

//        if($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order'){
//            if (!(isLogCode()>0)){
//                $arr['warehouse_id'] = 'required';
//            }
//        }
        $_discount = null;
       if (request()->discount != null){
           $_discount = str_replace('%', '',request()->discount);
       }
//      $arr['discount'] = 'number';
        $arr[$_discount] = 'number';
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
