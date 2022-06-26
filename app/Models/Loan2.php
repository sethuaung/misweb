<?php

namespace App\Models;

use App\Helpers\MFS;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class Loan2 extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'loans';
    public $table = 'loans';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }

    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['branch_id', 'center_leader_id', 'center_code_id',
        'loan_officer_id', 'transaction_type_id', 'currency_id',
        'client_id', 'loan_application_date', 'first_installment_date',
        'loan_production_id', 'loan_amount', 'loan_term_value', 'loan_term',
        'repayment_term', 'interest_rate_period', 'interest_rate',
        'loan_objective_id', 'figure_print_id', 'reason_no_figure_print',
        'guarantor_id', 'relationship_member', 'disbursement_status',
        'disbursement_number', 'group_loan_id', 'disbursement_name'];


    public function group_loans()
    {
        return $this->belongsTo('App\Models\GroupLoan', 'group_loan_id');
    }
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function loanid()
    {
        return $this->hasMany(CenterLeader::class, 'disbursement_id');
    }

    public static function saveDetail($request, $disbursement)
    {
        $disbursement_id = $disbursement->id;
        $charge_id = $request->charge_id;
        $loan_charge_id = $request->loan_charge_id;

        $name = $request->name;
        $amount = $request->amount;
        $charge_option = $request->charge_option;
        $charge_type = $request->charge_type;
        $status = $request->status;


        $compulsory_id = $request->compulsory_id;
        $loan_compulsory_id = $request->loan_compulsory_id;
        $product_name = $request->product_name;
        $saving_amount = $request->saving_amount;
        $c_charge_option = $request->c_charge_option;
        $compulsory_product_type_id = $request->compulsory_product_type_id;
        $compound_interest = $request->compound_interest;
        $c_status = $request->c_status;

        $c_interest_rate = $request->c_interest_rate;
        if ($charge_id != null) {
            foreach ($charge_id as $key => $value) {
                $d_id = isset($loan_charge_id[$key]) ? $loan_charge_id[$key] : 0;
                $m = null;
                if ($value > 0) {
                    if ($d_id > 0) {
                        $m = LoanCharge::find($d_id);
                    } else {
                        $m = new LoanCharge();
                    }
                    $m->loan_id = $disbursement_id;
                    $m->charge_id = $value;
                    $m->name = isset($name[$key]) ? $name[$key] : '';
                    $m->amount = isset($amount[$key]) ? $amount[$key] : 0;
                    $m->charge_option = isset($charge_option[$key]) ? $charge_option[$key] : 0;
                    $m->charge_type = isset($charge_type[$key]) ? $charge_type[$key] : 0;
                    $m->status = isset($status[$key]) ? $status[$key] : 'yes';
                    $m->save();
                }
            }
        }
        if ($compulsory_id > 0) {
            $c = null;
            if ($loan_compulsory_id > 0) {
                $c = LoanCompulsory::find($loan_compulsory_id);
            } else {
                $c = new LoanCompulsory();
            }
            $c->loan_id = $disbursement_id;
            $c->compulsory_id = $compulsory_id;
            $c->product_name = $product_name;
            $c->saving_amount = $saving_amount;
            $c->charge_option = $c_charge_option;
            $c->interest_rate = $c_interest_rate;
            $c->compound_interest = $compound_interest;
            $c->compulsory_product_type_id = $compulsory_product_type_id;
            $c->status = $c_status;
            $c->save();

        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
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


    public function withdrawal_cash()
    {
        return $this->hasMany(CashWithdrawal::class, 'save_reference_id');
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
    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public function addButtonCustom()
    {

        return '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '"
data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
            $setting = getSetting();
            $s_setting = getSettingKey('loan', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $row->seq = $last_seq;
            //$row->disbursement_number = getAutoRef($last_seq, $arr_setting);
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });
    }
}
