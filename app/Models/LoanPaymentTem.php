<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;


class LoanPaymentTem extends Model
{

    use CrudTrait;

    protected $table = 'loan_payment_tem';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payment_number', 'client_id', 'disbursement_id', 'receipt_no', 'over_days', 'penalty_amount', 'principle', 'interest', 'old_owed', 'other_payment', 'total_payment', 'payment', 'payment_date', 'owed_balance', 'payment_method', 'cash_acc_id',
        'document', 'note', 'disbursement_detail_id', 'principle_balance', 'compulsory_saving'];
    // protected $hidden = [];
    // protected $dates = [];


    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


}
