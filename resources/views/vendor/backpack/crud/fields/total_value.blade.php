<!-- text input -->


<?php
   $total_value = 0;
   $total_qty = 0;
   $total = 0;

   $id = isset($entry->id)?$entry->id:0;
   $currency_id = isset($entry->currency_id)?$entry->currency_id:0;



   if (isset($id) && isset($currency_id)) {
       $total_value=\App\Helpers\S::getAvgCost($id,$currency_id);
       $total_qty = \App\Models\StockMovement::where('product_id',$id)->sum('qty_cal');


       $total = $total_value * $total_qty;
   }

?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>

        <input readonly type="text"
            value="{{$total}}"
            @include('crud::inc.field_attributes')
        >

</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    {{-- @push('crud_fields_scripts')
        <!-- no scripts -->
    @endpush --}}


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
