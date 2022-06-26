<?php

namespace App\Models;

use App\Http\Controllers\Admin\LoanOutstandingCrudController;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;

class LoanPaymentU extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payment_number', 'client_id', 'disbursement_id', 'receipt_no', 'over_days', 'penalty_amount', 'principle', 'interest', 'old_owed', 'other_payment', 'total_payment', 'payment', 'payment_date', 'owed_balance', 'payment_method', 'cash_acc_id',
        'document', 'note', 'disbursement_detail_id', 'principle_balance', 'compulsory_saving', 'principle_pd', 'penalty_pd', 'service_pd', 'compulsory_pd',
    ];
    // protected $hidden = [];
    protected $dates = ['payment_date'];


}
