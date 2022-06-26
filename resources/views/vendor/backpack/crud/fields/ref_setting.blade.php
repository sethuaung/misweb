<!-- text input -->
@php
    $v =  old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' ));

    $val_arr = ($v != '' && $v != null)?json_decode($v,true):[];

    $pad = 5;
    $prefix = '';
    $use_date = 1;

    // dd((json_clean_decode($v)));

    if($val_arr != null){
        if(count($val_arr) > 0){
            $pad = isset($val_arr['pad'])?$val_arr['pad']:5;
            //$date = isset($option['date'])?$option['date']:null;
            $prefix = isset($val_arr['prefix'])?$val_arr['prefix']:'';
            $use_date = isset($val_arr['use_date'])?$val_arr['use_date']:1;
        }
    }

@endphp
<input type="hidden" name="{{$field['name']}}" class="{{$field['name']}}">

<div class="form-group col-md-4">
    <label>Prefix</label>
    <input type="text" value="{{$prefix}}" class="form-control prefix">
</div>

<div class="form-group col-md-4">
    <label>Length</label>
    <input type="text" number="number" value="{{$pad}}" class="form-control pad">
</div>

<div class="form-group col-md-4">
    <label>Use Date</label>
    <select type="text"  class="form-control use_date">
        <option {{ $use_date==1?'selected':'' }}  value="1">Yes</option>
        <option {{ $use_date==0?'selected':'' }} value="0">No</option>
    </select>
</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <script>

            $(function () {
                gen_{{$field['name']}}();

                $('.use_date').on('change',function () {
                    gen_{{$field['name']}}();
                });

                $('.pad,.prefix').on('keyup',function () {
                    gen_{{$field['name']}}();
                });


            });

            function gen_{{$field['name']}}() {
                var prefix = $('.prefix').val();
                var pad = $('.pad').val();
                var use_date = $('.use_date').val();

                var s = "{\"prefix\":\""+ prefix +"\",\"pad\":\""+ pad +"\",\"use_date\":\"" + use_date + "\" }";

                $('.{{$field['name']}}').val(s);
            }
        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}