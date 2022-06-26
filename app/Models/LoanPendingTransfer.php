<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class LoanPendingTransfer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $table = 'loans';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function group_loans()
    {
        return $this->belongsTo('App\Models\GroupLoan', 'group_loan_id');
    }

    public function branch_id()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    /*public function updated_by_user()
    {
        return $this->belongsTo('App\User','updated_by');
    }*/
    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function leader_name()
    {
        return $this->belongsTo(User::class, 'center_leader_id');
    }


    public function center_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // public function clients()
    // {
    //     return $this->belongsTo(Client::class, 'client_id');
    // }

    public function officer_name()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    public function guarantor_name()
    {
        return $this->belongsTo(Guarantor::class, 'guarantor_id');
    }

    public function guarantor2_name()
    {
        return $this->belongsTo(Guarantor::class, 'guarantor2_id');
    }

    public function loan_objective_name()
    {
        return $this->belongsTo(LoanObjective::class, 'loan_objective_id');
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function loan_product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_production_id');
    }

    public function currency_name()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function center_leader_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function disbursement()
    {
        return $this->hasMany(PaidDisbursement::class, 'contract_id');
    }

    public function withdrawal_cash()
    {
        return $this->hasMany(CashWithdrawal::class, 'save_reference_id');
    }

    public function loan_schedule()
    {
        return $this->hasMany(LoanCalculate::class, 'disbursement_id');
    }

    public function getGroupNameIDAttribute()
    {
        return (!empty($this->group_loans)) ? $this->group_loans->group_code . ' - ' . $this->group_loans->group_name : false;
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
    public function addButtonCustom()
    {
        $b = '';
        $his_id = \App\Models\LoanTransfer::where('loan_id', $this->id)->first();
        if (companyReportPart() == 'company.mkt' && optional($his_id)->old_branch > 0) {
            return $b . '
            <a href="' . url("/admin/his_transfer?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>

            <a href="' . url("/admin/loan-transfer/create?is_frame=1&loan_id=" . $this->id . "&disburse_number={$this->disbursement_number}") . '" data-remote="false" data-toggle="modal" data-target="#show-create-deposit" class="btn btn-xs btn-success"><i class="fa fa-exchange"></i> Transfer</a>';
        } else {
            return '<a href="' . url("/admin/loan-transfer/create?is_frame=1&loan_id=" . $this->id . "&disburse_number={$this->disbursement_number}") . '" data-remote="false" data-toggle="modal" data-target="#show-create-deposit" class="btn btn-xs btn-success"><i class="fa fa-exchange"></i> Transfer</a>';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();
        /*
                static::addGlobalScope('disbursement_status', function (Builder $builder) {
                    $builder->where('disbursement_status','Pending');
                });*/

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


    }
}
