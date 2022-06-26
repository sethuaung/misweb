<?php

namespace App\Http\Controllers\Api;


use App\Models\DepositSaving;
use App\Models\Saving;
use App\Models\SavingTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Charge;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Saving::join('clients','clients.id','savings.client_id')
                ->where('saving_number', 'LIKE', '%'.$search_term.'%')
                ->selectRaw('savings.id, savings.saving_number, clients.client_number, clients.name, clients.name_other')
                ->orderBy('savings.id','DESC')
                ->paginate(100);
        }
        else
        {
            $results = Saving::join('clients','clients.id','savings.client_id')
                ->selectRaw('savings.id, savings.saving_number, clients.client_number, clients.name, clients.name_other')
                ->orderBy('savings.id','DESC')
                ->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Saving::find($id);
    }

    public function saving_ajax($id){



        $last_seq = DepositSaving::max('seq');
        $last_seq = $last_seq > 0 ?$last_seq+1:1;


        $setting = getSetting();
        $s_setting = getSettingKey('saving_deposit_no',$setting);

        $arr_setting = $s_setting != null?json_decode($s_setting,true):[];

        $saving_deposit_no = getAutoRef($last_seq, $arr_setting);

        $saving = Saving::find($id);

        $client = \App\Models\Client::find(optional($saving)->client_id);

        $client_name = $client->name??$client->name_other;

        $saving_tran_last_id = SavingTransaction::where('saving_id', $id)->max('id');
        $saving_tran_last = SavingTransaction::find($saving_tran_last_id);

        return ['saving_deposit_no'=>$saving_deposit_no,
            'saving_type' => optional($saving)->saving_type,
            'client_name' => $client_name,
            'available_balance' => optional($saving_tran_last)->available_balance-optional($saving)->minimum_balance_amount
        ];


    }
}
