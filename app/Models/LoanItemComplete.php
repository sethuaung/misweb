<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;

class LoanItemComplete extends Loan
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

//    public function addButtonCustom()
//    {
//
//        return '<a href="'.url("/admin/print_schedule?loan_id={$this->id}").'" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';
//
//    }

    public function addButtonCustom()
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

    //enum('Pending-Approval', 'Awaiting-Disbursement', 'Loan-Declined', 'Loan-Withdrawn', 'Loan-Written-Off', 'Loan-Closed')
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


        static::addGlobalScope('disbursement_status', function (Builder $builder) {
            $builder->where('disbursement_status', 'Closed')->where('product_id', '>', 0);
        });
    }


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
