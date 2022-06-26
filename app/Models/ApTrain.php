<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApTrain extends Model
{
    protected $table = 'ap_trains';

    public function suppliers()
    {
        return $this->belongsTo(Supply::class, 'supplier_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
