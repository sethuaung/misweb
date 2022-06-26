<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLoanDetailM extends Model
{
    //


    protected $table = 'group_loan_details';


    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

}
