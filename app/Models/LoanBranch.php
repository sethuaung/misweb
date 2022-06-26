<?php

namespace App\Models;

use App\Helpers\MFS;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class LoanBranch extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    // = 'loans_1';
    protected $table = 'loans_27';
    // public $incrementing = true;
    /* public function __construct(array $attributes = array())
     {
         parent::__construct($attributes);
         if(companyReportPart()=='company.mkt') {
             $this->incrementing = false;
             $this->table = (isset($_REQUEST['branch_id'])) && (!is_array($_REQUEST['branch_id'])) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
         }

     }*/

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

    /*   protected $primaryKey = 'id';

       public $timestamps = true;
       protected $guarded = ['id'];
       protected $fillable = ['branch_id', 'center_leader_id', 'center_code_id',
           'loan_officer_id', 'transaction_type_id', 'currency_id',
           'client_id', 'loan_application_date', 'first_installment_date',
           'loan_production_id', 'loan_amount', 'loan_term_value', 'loan_term',
           'repayment_term', 'interest_rate_period', 'interest_rate',
           'loan_objective_id', 'figure_print_id', 'reason_no_figure_print',
           'guarantor_id', 'relationship_member',
           'interest_method','inspector1_id','inspector2_id',
           'disbursement_number','group_loan_id','disbursement_name','deposit_paid','you_are_a_group_leader','you_are_a_center_leader',
           'plan_disbursement_date','guarantor2_id','remark','excel','product_id','business_proposal'];
   */


}


