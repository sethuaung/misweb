<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class ApproveLoanPaymentTem extends LoanPaymentTem
{
    use CrudTrait;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 'approved');
        });
    }

    public function addButtonCustom()
    {

        return '<a href="' . url("admin/addloanrepayment/create/?payment_pending_id={$this->id}&type=payment_tem") . '" class="btn btn-xs btn-success"><span class="fa fa-check-circle-o"></span> Approve To Loan Repayment</a>';
    }

}
