<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SaleDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sale_details';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['product_id', 'sale_id', 'class_id', 'job_id', 'serial_id', 'line_warehouse_id',
        'line_tax_id', 'line_unit_id', 'line_spec_id', 'line_qty', 'line_qty_delivery', 'line_qty_remain',
        'unit_discount', 'extra_discount', 'unit_cost', 'line_discount_amount', 'line_tax_amount', 'net_unit_cost',
        'unit_tax', 'line_amount', 'sale_status', 'parent_id', 'branch_id', 'user_id', 'updated_by', 'average_cost',
        'p_line_cost', 'p_line_net_cost', 'profit', 'margin', 'note', 'pre_delivery_id', 'delivery_date', 'due_date',
        'invoice_status', 'round_id', 'job_status', 'job_qty', 'receive_status', 'gas_weight,old_number,new_number'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function tax()
    {
        return $this->belongsTo(TaxRate::class, 'line_tax_id');
    }

    public function warehouse()
    {

        return $this->belongsTo('App\Models\Warehouse', 'line_warehouse_id');
    }

    public function unit()
    {

        return $this->belongsTo('App\Models\Unit', 'line_unit_id');
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {

        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function spec()
    {

        return $this->belongsTo('App\Models\ProductSpecCategory', 'line_spec_id');
    }

    public function sale_location_lot()
    {
        /*return $this->hasMany(SaleDetailLocationLot::class,'sale_detail_id');*/
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function addButtonCustom1()
    {
        return '
               <div class="btn-group">
                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action
                     <span class="caret"></span>
                </button>
                <div class="dropdown-menu" style="right: 0;left: auto">
                     <a href = ' . url("/admin/ct_add_job?id={$this->id}") . ' class="dropdown-item btn btn-info" data-remote="false" data-toggle="modal" data-target="#ct_job_pop" style="display: block;width: 100%;padding: .25rem 1.5rem;clear: both;font-weight: 400;color: #212529;text-align: inherit;white-space: nowrap;background-color: transparent;border: 0;"><i class="fa fa-plus-square"></i>Add Jobs</a>
                     <a href = ' . url("/admin/ct_edit_job?id={$this->id}") . ' class="dropdown-item btn btn-info" data-remote="false" data-toggle="modal" data-target="#ct_job_pop" style="display: block;width: 100%;padding: .25rem 1.5rem;clear: both;font-weight: 400;color: #212529;text-align: inherit;white-space: nowrap;background-color: transparent;border: 0;"><i class="fa  fa-file-text-o"></i>Edit Jobs</a>
                     <a href = ' . url("") . ' class="dropdown-item btn btn-info"  style="display: block;width: 100%;padding: .25rem 1.5rem;clear: both;font-weight: 400;color: #212529;text-align: inherit;white-space: nowrap;background-color: transparent;border: 0;"><i class="fa  fa-cut"></i>Delete Jobs</a>

                </div>
             </div>
       ';

    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
    }

    public static function job_reort($start_date = null, $end_date = null, $reference = null, $receive_status = null, $customer_id = null)
    {
        return SaleDetail::
        LeftJoin('products', 'products.id', '=', 'sale_details.product_id')
            ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->selectRaw('sale_details.*,
         sale_details.product_id,
         sale_details.sale_id,
         sale_details.class_id,
         sale_details.job_id,
         sale_details.line_warehouse_id,
         sale_details.line_tax_id,
         sale_details.line_unit_id,
         sale_details.line_spec_id,
         sale_details.line_qty,
         sale_details.line_qty_delivery,
         sale_details.line_qty_remain,
         sale_details.unit_discount,
         sale_details.extra_discount,
         sale_details.unit_cost,
         sale_details.line_discount_amount,
         sale_details.line_tax_amount,
         sale_details.net_unit_cost,
         sale_details.line_amount,
         sale_details.sale_status,
         sale_details.parent_id,
         sale_details.branch_id,
         sale_details.user_id,
         sale_details.updated_by,
         sale_details.average_cost,
         sale_details.p_line_cost,
         sale_details.p_line_net_cost,
         sale_details.profit,
         sale_details.pre_delivery_id,
         sale_details.delivery_date,
         sale_details.due_date,
         sale_details.invoice_status,
         sale_details.job_status,
         sale_details.job_qty,
         sale_details.created_at,
         sale_details.receive_status,
         sales.customer_id,
         sales.reference_no


        ')->where(function ($query) use ($start_date) {
                if ($start_date != null) {
                    return $query->whereDate('sale_details.created_at', '>=', $start_date);

                }
            })->where('sales.sale_type', 'order')
            ->where(function ($query) use ($end_date) {
                if ($end_date != null) {
                    return $query->whereDate('sale_details.created_at', '<=', $end_date);

                }
            })->where(function ($query) use ($reference) {
                if ($reference != null) {
//                     return $query->where('substr(sales.reference_no, -4)','like', '%'.$reference.'%');
//                     return $query->where(('substr(sales.reference_no, 1, 3)'), '=' , 511);
                    return $query->orWhere('sales.order_number', 'LIKE', '%' . $reference . '%');
//                     return $query->whereRaw('SUBSTRING(sales.order_number, -4 ,4) = '.$reference);

                }
            })->where(function ($query) use ($receive_status) {
                if ($receive_status == 'received' && $receive_status != 'null') {
                    return $query->where('sale_details.receive_status', $receive_status);

                } else if ($receive_status == 'pending' && $receive_status != 'null') {
                    return $query->where('sale_details.receive_status', null);

                }
            })
            ->where(function ($query) use ($customer_id) {
                if ($customer_id != null && $customer_id != 'null') {
                    return $query->where('sales.customer_id', $customer_id);

                }
            })
            ->Where('sale_details.job_status', '!=', 'none')
            ->orderBy('sale_details.created_at', 'desc')
            ->paginate(12);

    }
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
