<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Helpers\S;

class LoanOutstanding extends Loan
{
    public $table = 'loans';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }

    public function addButtonCustom()
    {
        $loan_id = $this->id;
        $last_payment_id = LoanPayment2::where('disbursement_id', $loan_id)->max('id');
        $schedule_backup = ScheduleBackup::where('loan_id', $loan_id)->first();
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
            $builder->whereIn('disbursement_status', ['Activated']);
        });
        //    if(companyReportPart() == 'company.mkt' && empty($_GET)){
        //         static::addGlobalScope('created_at', function (Builder $builder) {
        //             $month = date('m');
        //             $year = date('yy');
        //             $builder->whereMonth('created_at', [$month]);
        //             $builder->whereYear('created_at', [$year]);
        //         });
        // }

    }


}
