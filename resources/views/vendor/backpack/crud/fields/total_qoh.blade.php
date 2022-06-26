<!-- text input -->


<?php
   $total_qty = 0;
   $qoh = '';
  $id = isset($entry->id)?$entry->id:0;
   if (isset($id)) {
       $total_qty = \App\Models\StockMovement::where('product_id',$id)->sum('qty_cal');

       $qoh=convertUnit($id,$total_qty);
   }

?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>

        <input readonly type="text"
            value="{{$qoh}}"
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
