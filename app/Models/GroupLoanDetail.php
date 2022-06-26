<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLoanDetail extends Model
{
    //


    protected $table = 'group_loan_details';


    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
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
    }
}
