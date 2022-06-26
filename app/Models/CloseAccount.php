<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class CloseAccount extends CloseAll
{
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

        static::addGlobalScope('close_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('close_type', 'account');
            });
            $builder->withoutGlobalScope(CloseAll::class);
        });

    }
}
