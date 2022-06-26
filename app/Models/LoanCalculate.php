<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LoanCalculate extends Model
{
    // protected $table = 'loan_disbursement_calculate';

    //protected $table = '';
    public $table = 'loan_disbursement_calculate';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loan_disbursement_calculate_' . $_REQUEST['branch_id'] : 'loan_disbursement_calculate_' . session('s_branch_id');
        }

    }
//    public function __construct($branch_id='')
//    {
//        parent::__construct();
//
//        if(companyReportPart()=='company.mkt') {
//            if (empty($branch_id)) {
//                if(isset($_REQUEST['branch_id'])){
//                    if(is_array($_REQUEST['branch_id'])){
//                        $branch_id = session('s_branch_id');
//                    }else{
//                        $branch_id = $_REQUEST['branch_id'];
//                    }
//
//                }else {
//                    $branch_id = session('s_branch_id');
//                }
//                $tablename = 'loan_disbursement_calculate_' . $branch_id;
//
//
//            } else {
//                $tablename = 'loan_disbursement_calculate_' . $branch_id;
//            }
//        }else{
//            $tablename = 'loan_disbursement_calculate';
//        }
//
//        $this->setTable($tablename);
//    }

    public function disbursements()
    {
        return $this->belongsTo(Loan::class, 'disbursement_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });
    }
}
