<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Helpers\S;

class PrePaid extends Loan
{
    public $table = 'loans';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }


    public function pre_repayment()
    {
        return $this->hasMany(LoanPayment::class, 'disbursement_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
