<?php

namespace App\Models;

use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class TransactionHistory extends Model
{
    use CrudTrait;
    use Excludable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'transaction_histories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference_no', 'tran_type', 'deleted_by', 'deleted_at', 'customer_name', 'description', 'amount'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function saveHistory($data)
    {
        $reference_no = '';
        if ($data->reference_no == "") {
            if ($data->tran_type == "transfer") {
                $transfer = \App\Models\Transfer::where('id', $data->tran_id)->first();
                $reference_no = $transfer->reference_no;
                $trans_description = $transfer->description;
                $trans_amount = $transfer->t_amount;
            }
        } else {
            $reference_no = $data->reference_no;
            $description = $data->note;
            $journal_detail = \App\Models\GeneralJournalDetail::where('journal_id', $data->id)->first();
            $amount = $journal_detail->dr != 0 ? $journal_detail->dr : $journal_detail->cr;
        }
        $history = new \App\Models\TransactionHistory;
        $history->reference_no = $reference_no;
        $history->tran_id = $data->tran_id ? $data->tran_id : 0;
        $history->tran_type = $data->tran_type;
        $history->deleted_by = auth()->user()->id;
        $history->deleted_at = \Carbon\Carbon::now();
        $history->customer_name = $data->name;
        $history->description = $trans_description ?? $description;
        $history->amount = $trans_amount ?? $amount;
        $history->save();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    function user()
    {
        return $this->belongsTo(BackpackUser::class, 'deleted_by');
    }

    function client()
    {
        return $this->belongsTo(Client::class, 'customer_name');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
