<!-- field_type_name -->
<style media="screen">
.acc-icon{
    padding: 10px 2px;
    border: none;
    font-size: 20px;
}
option{
    font-size: 17px;
}
</style>

<div @include('crud::inc.field_wrapper_attributes') id ="acc_range">
    <label>{!! $field['label'] !!}</label>
    <div class="input-group">
        <table style="width: 100%;">
            <tr>
                <td>
                    <select class="form-control" name="account[1]" id="account_1">
                            <option value="0">Account</option>
                    </select>
                </td>
                <td class="acc-icon"> <i class="fa fa-arrows-h"></i> </td>
                <td>
                    <select class="form-control" name="account[2]" id="account_2">
                            <option value="0">Account</option>
                    </select>
                </td>
            </tr>
        </table>

    </div>

    {{-- <input
        type="text"
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')
        > --}}

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
        <!-- no styles -->
    @endpush


    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script type="text/javascript">
        $( document ).ready(function() {

            search_range = $("#search_range").val();

            $('#acc_range').hide();
            account_chart('#account_1');
            account_chart('#account_2');
            

            $("#search_range").change(function(){
                // console.log($("#search_range").val());
                if($(this).val() == "range"){
                    $('#acc_range').show();
                    $('.acc_normal').hide();
                }else{
                    $('#acc_range').hide();
                    $('.acc_normal').show();
                }
            })

            function account_chart(field) { // get nrc prefix by state id
                $.ajax({
                    url : '/api/acc-chart?range='+1,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        // console.log(data);
                        $.each(data, function(key,value){
                            $(field).append('<option value="'+ value.code +'">'+ value.title +'</option>');
                        });
                    }
                });
            }

        });
        </script>
    @endpush
@endif


{{-- Note: most of the times you'll want to use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load CSS/JS once, even though there are multiple instances of it. --}}
