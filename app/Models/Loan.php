<?php

namespace App\Models;

use App\Helpers\MFS;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class Loan extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    // = 'loans_1';
    public $table = 'loans';
    public $incrementing = true;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->incrementing = false;
            $this->table = (isset($_REQUEST['branch_id'])) && (!is_array($_REQUEST['branch_id'])) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }

    // public function __construct($bid='')
//    {
    //      $tablename = 'loans_'.$_REQUEST['branch_id'];

    /*   parent::__construct();

//        if(companyReportPart()=='company.mkt') {
//            if (empty($bid)) {
//
//                if(isset($_REQUEST['branch_id'])){
//                    if(is_array($_REQUEST['branch_id'])){
//                        $bid = session('s_branch_id');
//                    }else{
//                        $bid = $_REQUEST['branch_id'];
//                    }
//
//                }else {
//                    $bid = session('s_branch_id');
//                }
//
//                $tablename = 'loans_' . $bid;
//
//
//            }
//            else {
//
//                $tablename = 'loans_1';
//            }
//        }
      // $this->setTable($tablename);
       //$this->table = 'loans_1';
       $this->setTable('loans_1');
   }*/

    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['branch_id', 'center_leader_id', 'center_code_id',
        'loan_officer_id', 'transaction_type_id', 'currency_id',
        'client_id', 'loan_application_date', 'first_installment_date',
        'loan_production_id', 'loan_amount', 'loan_term_value', 'loan_term',
        'repayment_term', 'interest_rate_period', 'interest_rate',
        'loan_objective_id', 'figure_print_id', 'reason_no_figure_print',
        'guarantor_id', 'relationship_member',
        'interest_method', 'inspector1_id', 'inspector2_id',
        'disbursement_number', 'group_loan_id', 'disbursement_name', 'deposit_paid', 'you_are_a_group_leader', 'you_are_a_center_leader',
        'plan_disbursement_date', 'guarantor2_id', 'remark', 'excel', 'product_id', 'business_proposal'];


    public function group_loans()
    {
        return $this->belongsTo('App\Models\GroupLoan', 'group_loan_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function saveDetail($request, $disbursement)
    {

        $disbursement_id = $disbursement->id;
        $loan_nomber = $disbursement->disbursement_number;
        $client_id = $disbursement->client_id;
        $charge_id = $request->charge_id;
        $loan_charge_id = $request->loan_charge_id;
        $branch_id = $disbursement->branch_id;
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
        $override_cycle = $request->override_cycle;
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
            if (companyReportPart() == 'company.mkt') {
                if ($loan_compulsory_id > 0) {
                    $c = LoanCompulsory::find($loan_compulsory_id);
                    $savong_no = $c->compulsory_number;
                } else {
                    $c = new LoanCompulsory();
                    $savong_no = LoanCompulsory::getSeqRef('compulsory');
                }
            } else {
                if ($loan_compulsory_id > 0) {
                    $c = LoanCompulsory::find($loan_compulsory_id);
                    $savong_no = $c->compulsory_number;
                } else {
                    $loan = Loan::find($disbursement_id);
                    $c = new LoanCompulsory();
                    $savong_no = $loan->disbursement_number;
                }

            }

            //dd($savong_no);

            $saving_client = LoanCompulsory::where('client_id', $client_id)->where('override_cycle', 'yes')->where('compulsory_id', $compulsory_id)->first();

            if ($saving_client and $override_cycle == "yes") {
                $saving_client->loan_id = $disbursement_id;
                $saving_client->client_id = $client_id;
                $saving_client->saving_amount = $saving_amount;
                $saving_client->charge_option = $c_charge_option;
                $saving_client->interest_rate = $c_interest_rate;
                $saving_client->compound_interest = $compound_interest;
                $saving_client->override_cycle = $override_cycle;
                $saving_client->compulsory_product_type_id = $compulsory_product_type_id;
                $saving_client->branch_id = $branch_id;
                $saving_client->save();
            } else {
                //dd($savong_no);
                $c->loan_id = $disbursement_id;
                $c->client_id = $client_id;
                $c->compulsory_id = $compulsory_id;
                $c->product_name = $product_name;
                $c->saving_amount = $saving_amount;
                $c->charge_option = $c_charge_option;
                $c->interest_rate = $c_interest_rate;
                $c->compound_interest = $compound_interest;
                $c->override_cycle = $override_cycle;
                $c->compulsory_number = $savong_no;
                $c->compulsory_product_type_id = $compulsory_product_type_id;
                $c->status = $c_status;
                $c->branch_id = $branch_id;
                $c->save();
            }
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


    public function center_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function client_number()
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

    public function inspector_name()
    {
        return $this->belongsTo(Inspector::class, 'inspector_id');
    }

    public function inspector2_name()
    {
        return $this->belongsTo(Inspector::class, 'inspector2_id');
    }

    public function pre_repayment()
    {
        return $this->hasMany(LoanPayment::class, 'disbursement_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public static function boot()
    {
        parent::boot();

        /* static::addGlobalScope('loans', function (Builder $builder) {
             $builder->where(function ($q){
                 return $q->whereIn('purchase_type', ['bill-only','bill-only-from-order','bill-and-received','bill-and-received-from-order','bill-only-from-received']);
             });
         });*/
        if (companyReportPart() != 'company.mkt') {
            static::addGlobalScope(getLoanTable() . '.branch_id', function (Builder $builder) {
                $u = optional(auth()->user());

                $branch_id = [];
                if (optional($u)->branches != null) {

                    foreach (optional($u)->branches as $b) {
                        $branch_id[$b->id] = $b->id;
                    }
                }
                //dd(auth()->user());
                $builder->where(function ($q) use ($u, $branch_id) {
                    if ($branch_id != null) {
                        if ($u->id != 1 && $branch_id != null) {
                            return $q->whereIn(getLoanTable() . '.branch_id', $branch_id);
                        }
                    }
                });
            });

        }


        static::creating(function ($obj) {

            $client_id = $obj->client_id;
            $group_loan_id = $obj->group_loan_id;
            $m = null;
            if (companyReportPart() == "company.quicken") {
                $client = Client::find($client_id);
                $client->condition = "Have Loan";
                $client->save();
            }

            GroupLoanDetail::where('loan_id', $obj->id)->delete();
            if ($group_loan_id != null) {
                if ($group_loan_id > 0 && $client_id > 0) {

                    $m = new GroupLoanDetail();
                    $m->client_id = $client_id;
                    $m->group_loan_id = $group_loan_id;
                    $m->loan_id = $obj->id;
                    $m->save();
                }
            }
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
            $setting = getSetting();
            $s_setting = getSettingKey('loan', $setting);
            $rand = time() . floor(rand(1000, 9999));
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $obj->seq = $last_seq;
            $obj->disbursement_number = getAutoRef($last_seq, $arr_setting);
            $userid = auth()->user()->id;
            $obj->created_by = $userid;
            $obj->updated_by = $userid;
            if (companyReportPart() == 'company.mkt') {
                $obj->id = $rand;
                $obj->disbursement_number = $rand;
            }
        });

        static::created(function ($obj) { // before delete() method call this
            $date = $obj->loan_application_date;
            $first_date_payment = $obj->first_installment_date;
            $loan_product = LoanProduct::find($obj->loan_production_id);
            $interest_method = optional($loan_product)->interest_method;
            $principal_amount = $obj->loan_amount;
            $loan_duration = $obj->loan_term_value;
            $loan_duration_unit = $obj->loan_term;
            $repayment_cycle = $obj->repayment_term;
            $loan_interest = $obj->interest_rate;
            $loan_interest_unit = $obj->interest_rate_period;
            $i = 1;
            $monthly_base = optional($loan_product)->monthly_base ?? 'No';

            if (companyReportPart() == 'company.quicken') {
                $client = Client::find($obj->client_id);
                $client->condition = "Have Loan";
                $client->save();
            }

            $repayment = $monthly_base == 'No' ? MFS::getRepaymentSchedule($date, $first_date_payment, $interest_method,
                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit) :
                MFS::getRepaymentSchedule2($monthly_base, $date, $first_date_payment, $interest_method,
                    $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit);
            //dd($repayment);
            if ($repayment != null) {
                if (is_array($repayment)) {
                    foreach ($repayment as $r) {
                        $d_cal = new LoanCalculate();

                        $d_cal->no = $i++;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $obj->id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $r['principal'];
                        $d_cal->interest_s = $r['interest'];
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $r['payment'];
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->branch_id = $obj->branch_id;
                        $d_cal->group_id = $obj->group_loan_id;
                        $d_cal->center_id = $obj->center_leader_id;
                        $d_cal->loan_product_id = $obj->loan_production_id;
                        $d_cal->save();
                    }
                }
            }
        });

        static::updating(function ($obj) { // before delete() method call this
            $d_cal = LoanCalculate::where('total_p', '>', 0)->where('disbursement_id', $obj->id)->first();
            if ($d_cal != null) {
                return false;
            }


            $userid = auth()->user()->id;
            $obj->updated_by = $userid;
        });

        static::updated(function ($obj) {
            $client_id = $obj->client_id;
            $group_loan_id = $obj->group_loan_id;
            $m = null;
            GroupLoanDetail::where('loan_id', $obj->id)->delete();
            if ($group_loan_id != null) {
                if ($group_loan_id > 0 && $client_id > 0) {

                    $m = new GroupLoanDetail();
                    $m->client_id = $client_id;
                    $m->group_loan_id = $group_loan_id;
                    $m->loan_id = $obj->id;
                    $m->save();
                }
            }

            LoanCalculate::where('disbursement_id', $obj->id)->delete();
            $date = $obj->loan_application_date;
            $first_date_payment = $obj->first_installment_date;
            $loan_product = LoanProduct::find($obj->loan_production_id);
            $interest_method = optional($loan_product)->interest_method;
            $principal_amount = $obj->loan_amount;
            $loan_duration = $obj->loan_term_value;
            $loan_duration_unit = $obj->loan_term;
            $repayment_cycle = $obj->repayment_term;
            $loan_interest = $obj->interest_rate;
            $loan_interest_unit = $obj->interest_rate_period;
            $i = 1;

            /*$repayment = MFS::getRepaymentSchedule($date, $first_date_payment, $interest_method,
                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit);*/
            $monthly_base = optional($loan_product)->monthly_base ?? 'No';
            $repayment = $monthly_base == 'No' ? MFS::getRepaymentSchedule($date, $first_date_payment, $interest_method,
                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit) :
                MFS::getRepaymentSchedule2($monthly_base, $date, $first_date_payment, $interest_method,
                    $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit);

            //dd($repayment);
            if ($repayment != null) {
                if (is_array($repayment)) {
                    foreach ($repayment as $r) {
                        $d_cal = new LoanCalculate();

                        $d_cal->no = $i++;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $obj->id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $r['principal'];
                        $d_cal->interest_s = $r['interest'];
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $r['payment'];
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->branch_id = $obj->branch_id;
                        $d_cal->group_id = $obj->group_loan_id;
                        $d_cal->center_id = $obj->center_leader_id;
                        $d_cal->loan_product_id = $obj->loan_production_id;
                        $d_cal->save();
                    }
                }
            }
        });

        static::deleted(function ($obj) {
            LoanCalculate::where('total_p', '>', 0)->where('disbursement_id', $obj->id)->delete();
        });
    }


    public function addButtonCustomLoanOutstanding()
    {
        $loan_id = $this->id;
        //$last_payment_id = LoanPayment2::where('disbursement_id',$loan_id)->max('id');
        //$schedule_backup = ScheduleBackup::where('loan_id',$loan_id)->first();
        $b = array();
        $client = "";
        $deposit = \App\Models\LoanDeposit::where('applicant_number_id', $loan_id)->first();

        if (companyReportPart() == 'company.moeyan') {
            array_push($b, '<a href="' . url("/admin/print-disbursement?is_pop=1&disbursement_id={$this->id}") . '" style="color:#0e455e;border-radius:5px;"><i class="fa fa-file-text"></i>Receipt</a>');
            $client = '<a href="' . url("/admin/client_pop?client_id={$this->client_id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-user"></i></a>
            <a href="' . url("/admin/history_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-money"></i></a> ';
        }

        if (companyReportPart() == 'company.angkor') {
            array_push($b, '<a href="' . url("/admin/print-disbursement?is_pop=1&disbursement_id={$this->id}") . '" style="color:#0e455e;border-radius:5px;"><i class="fa fa-file-text"></i>Received Form</a>');
            array_push($b, '<a href="' . url("/admin/print-contract?is_pop=1&disbursement_id={$this->id}") . '" style="color:#0e455e;border-radius:5px;"><i class="fa fa-file-text"></i>Agreement Form</a>');
        }
        if (companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.mkt') {
            array_push($b,
                '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-eye"></i>Schedule Form</a>',
                '<a href="' . url("/admin/payment_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-plus-circle"></i>Add Payment</a>',
                '<a href="' . url("/admin/loan-write-off/create?is_frame=1&loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-write-off-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-plus-circle"></i>Write Off</a>',
                '<a data-whatever="' . $this->id . '" data-re_date="' . $this->status_note_date_activated . '" data-fr_date="' . $this->first_installment_date . '" data-de_date="' . optional($deposit)->loan_deposit_date . '" data-toggle="modal" data-target="#changedate" class="btn" style="color:#0e455e;border-radius:5px;"><i class="fa fa-calendar"></i>Change Date</a>',
                '<a data-whatever="' . $this->id . '" data-toggle="modal" data-target="#exampleModal" class="btn btn-danger cancel-loan" style="color:#ffffff;border-radius:5px;"></i>Cancel Loan</a>'
            );
        } else {
            array_push($b,
                '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-eye"></i>Schedule Form</a>',
                '<a href="' . url("/admin/payment_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-plus-circle"></i>Add Payment</a>',
                '<a href="' . url("/admin/loan-write-off/create?is_frame=1&loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-write-off-modal" style="color:#0e455e;border-radius:5px;"><i class="fa fa-plus-circle"></i>Write Off</a>',
                '<a data-whatever="' . $this->id . '" data-toggle="modal" data-target="#exampleModal" class="btn btn-danger" style="color:#0e455e;border-radius:5px;"></i>Cancel Loan</a>'
            );
        }


        return $client . S::getActionButton($b);

        /*if($loan_id>0 && $schedule_backup != null){
            $b = '<a href="'.url("/api/list-last-payment?payment_id={$last_payment_id}").'" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-danger">Roll Back</a>';
        }*/
    }


    public function addButtonCustomLoanClosed()
    {
        $loan_id = $this->id;
        $last_payment_id = LoanPayment2::where('disbursement_id', $loan_id)->max('id');
        $schedule_backup = ScheduleBackup::where('loan_id', $loan_id)->first();
        $schedule = LoanCalculate::where('disbursement_id', $loan_id)->get();
        $b = '';
        /*if($loan_id>0 && $schedule_backup != null){
            $b = '<a href="'.url("/api/list-last-payment?payment_id={$last_payment_id}").'" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-danger">Roll Back</a>';
        }*/

        if (_can('delete-loan-payment') && $schedule->count() > 1 && companyReportPart() == "company.moeyan") {
            $b = '<a class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')" href="' . url("/admin/cancel-completed?id={$this->id}") . '"  ><i class="fa fa-minus-circle"></i> Delete Last Payment</a>';
        }

        return $b . '

        <a  class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')" href="' . url("/admin/cancel-activated?id={$this->id}") . '"  ><i class="fa fa-minus-circle"></i> Cancel</a>
        <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
        <a href="' . url("/admin/payment_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-money"></i></a>
        ';

    }


}


