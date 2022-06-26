<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;

class ApproveLoanItem extends LoanItem
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'loans';
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


    public function addButtonCustom1()
    {

        $available = 0;
        //return $this->disbursement_status;
        $loan_c = LoanCharge::where('loan_id', $this->id)->where('status', 'Yes')->where('charge_type', 1)->count();

        $loan_comp = LoanCompulsory::where('loan_id', $this->id)->where('compulsory_product_type_id', 1)->where('status', 'Yes')->count();


        $compulsory = LoanCompulsory::where('loan_id', $this->id)->where('status', 'Yes')->first();

        $m = Loan::find($this->id);
        //$balance =  $compulsory->balance>0 ?$compulsory->balance:0;
        $balance = 0;
        if ($m != null) {

            $balance = CompulsorySavingTransaction::where('customer_id', optional($m)->client_id)
                //->where('train_type_ref','deposit')
                ->sum('amount');
        }

        if ($compulsory != null) {
            $calculate_interest = $compulsory->calculate_interest > 0 ? $compulsory->calculate_interest : 0;

            $available = $balance + $calculate_interest;
        }


        if ($loan_c > 0 || $loan_comp > 0) {
            if ($this->deposit_paid == 'No') {
                return '
                        <a  class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')" href="' . url("/admin/cancel-activated?id={$this->id}") . '"  ><i class="fa fa-minus-circle"></i> Cancel</a>
                <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>'
                    . ' <a href="' . url("/admin/loan-item-deposit/create?is_frame=1&loan_id={$this->id}") . '" class="btn btn-xs btn-info" data-remote="false"  data-toggle="modal" data-target="#show-create-deposit">Deposit</a>';
                //. '<a href="' . url("/admin/cashwithdrawal/create?is_frame=1&saving_id={$this->id}") . '"class="btn btn-xs btn-info data-remote="false" data-toggle="modal" data-target="#show-detail-modal">cash withdrawal</a>';
            }
        }

        if ($this->disbursement_status != "Activated") {
            if ($available > 0) {
                return '
        <a  class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')" href="' . url("/admin/cancel-activated?id={$this->id}") . '"  ><i class="fa fa-minus-circle"></i> Cancel</a>
                <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
                <a href="' . url("/admin/loan-item-disbursement/create?is_frame=1&loan_id={$this->id}") . '"class="btn btn-xs btn-info" data-remote="false" data-toggle="modal" data-target="#show-create-deposit">Disbursement</a>';
            }
            return '
        <a  class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')" href="' . url("/admin/cancel-activated?id={$this->id}") . '"  ><i class="fa fa-minus-circle"></i> Cancel</a>
                    <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
                    <a href="' . url("/admin/loan-item-disbursement/create?is_frame=1&loan_id={$this->id}") . '"class="btn btn-xs btn-info" data-remote="false" data-toggle="modal" data-target="#show-create-deposit">Disbursement</a>
            ';
        }

        return '';
    }


    //enum('Pending-Approval', 'Awaiting-Disbursement', 'Loan-Declined', 'Loan-Withdrawn', 'Loan-Written-Off', 'Loan-Closed')

    //  . '<a class="btn btn-xs btn-info deposit" data-remote="false" data-loan_id="' . $this->id . '" data-loan_id="" data-toggle="modal" data-target="#show-create-deposit">Deposit</a>'


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
            $builder->where('disbursement_status', 'Approved')
                ->where('product_id', '>', 0);
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
