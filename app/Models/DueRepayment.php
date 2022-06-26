<?php

namespace App\Models;

use App\Models\LoanCalculate;
use Illuminate\Database\Eloquent\Builder;

class DueRepayment extends Loan
{

    public function addButtonCustom()
    {

        $loan_id = $this->id;
        $last_payment_id = LoanPayment2::where('disbursement_id', $loan_id)->max('id');
        $schedule_backup = ScheduleBackup::where('loan_id', $loan_id)->first();
        $b = '';
        /*if($loan_id>0 && $schedule_backup != null){
            $b = '<a href="'.url("/api/delete-payment?payment_id={$last_payment_id}").'" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-danger">Roll Back</a>';
        }*/
        if (companyReportPart() == 'company.moeyan') {
            return $b . '
        <a href="' . url("/admin/client_pop?client_id={$this->client_id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-primary"><i class="fa fa-user"></i></a>
        <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
        <a href="' . url("/admin/history_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-money"></i></a>
        ';
        } else {
            return $b . '
        <a href="' . url("/admin/client_pop?client_id={$this->client_id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-primary"><i class="fa fa-user"></i></a>
        <a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
        <a href="' . url("/admin/payment_pop?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-money"></i></a>';
        };

    }

    public function loan_schedule()
    {
        return $this->hasMany(LoanCalculate::class, 'disbursement_id');
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
            $builder->where('disbursement_status', 'Activated');
        });
    }
}
