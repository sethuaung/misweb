<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class ApproveClientPending extends ClientPending
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

        return '<a href="' . url("admin/client/create/?client_pending_id={$this->id}&create_client=request_to_client") . '" class="btn btn-xs btn-success"><span class="fa fa-plus-circle"></span> Approve</a>';
    }
}
