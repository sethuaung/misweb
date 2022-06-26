<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class DisbursementPending extends Model
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


    public static function boot()
    {
        parent::boot();

        $arr = [];

        if (companyReportPart() == "company.mkt") {
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status', 'Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        } else {
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status', 'Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }

        if ($charge != null) {

            foreach ($charge as $r) {
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($compulsory != null) {

            foreach ($compulsory as $r) {
                $arr[$r->loan_id] = $r->loan_id;
            }
        }

        static::addGlobalScope(getLoanTable() . '.deposit_paid', function (Builder $builder) use ($arr) {
            $builder->where(function ($q) use ($arr) {
                $q->orWhere(function ($qq) use ($arr) {
                    $qq->whereIn(getLoanTable() . '.id', $arr)->where(getLoanTable() . '.deposit_paid', 'Yes');
                })->orWhere(function ($qq) use ($arr) {
                    $qq->whereNotIn(getLoanTable() . '.id', $arr);
                });
            });

        });

        static::addGlobalScope(getLoanTable() . '.disbursement_status', function (Builder $builder) {
            $builder->where(getLoanTable() . '.disbursement_status', 'Approved');
        });

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
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];


    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function leader_name()
    {
        return $this->belongsTo(User::class, 'center_leader_id');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    public function officer_name()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }


    public function guarantor_name()
    {
        return $this->belongsTo(Guarantor::class, 'guarantor_id');
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

    public function center_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function withdrawal_cash()
    {
        return $this->hasMany(CashWithdrawal::class, 'save_reference_id');
    }

    public function group_loans()
    {
        return $this->belongsTo('App\Models\GroupLoan', 'group_loan_id');
    }


    public function addButtonCustom1()
    {

        //return  '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

        return '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>'
            . '<a href="' . url("/admin/paiddisbursement/create?is_frame=1&loan_id={$this->id}") . '"class="btn btn-xs btn-info data-remote="false" data-toggle="modal" data-target="#show-detail-modal">Disbursement</a>';
    }

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
