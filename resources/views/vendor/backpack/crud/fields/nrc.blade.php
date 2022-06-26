<!-- field_type_name -->
<style media="screen">
.nrc-icon{
    padding: 6px 2px;
    border: none;
    font-size: 20px;
}
</style>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @php
    if (!empty($crud->entry->nrc_type)) {
        if ($crud->entry->nrc_type == 'New Format') {
            if (!empty($field['value'])) {
                $a = explode('/', $field['value']);
                $nrc_old[1] = isset($a[0])?$a[0]:'';
                $b = explode('(', isset($a[1])?$a[1]:'');
                $nrc_old[2] = isset($b[0])?$b[0]:'';
                $c = explode(')', isset($b[1])?$b[1]:'');
                $nrc_old[3] = isset($c[0])?$c[0]:'';
                $nrc_old[4] = isset($c[1])?$c[1]:'';
            }
        }
    }

    if (!empty($crud->entry->nrc_type)) {
        if ($crud->entry->nrc_type == 'Old Format' ) {
            if (!empty($field['value'])) {
                $nrc_full = $field['value'];
            }
        }
    }

    $nrc_1 = old('nrc_old.1') ? old('nrc_old.1') : ((!empty($nrc_old[1])) ? $nrc_old[1] : '');
    $nrc_2 = old('nrc_old.2') ? old('nrc_old.2') : ((!empty($nrc_old[2])) ? $nrc_old[2] : '');
    $nrc_3 = old('nrc_old.3') ? old('nrc_old.3') : ((!empty($nrc_old[3])) ? $nrc_old[3] : '');
    $nrc_4 = old('nrc_old.4') ? old('nrc_old.4') : ((!empty($nrc_old[4])) ? $nrc_old[4] : '');
    $nrc_full = old('nrc_new') ? old('nrc_new') : ((!empty($crud->entry->nrc_number_new)) ? $crud->entry->nrc_number_new : '');
    @endphp

    <div class="input-group">
        <table width="100%" >
            <tr>
                @php
                    // $state_prefix_array = array('၁','၂','၃','၄','၅','၆','၇','၈','၉','၁၀','၁၁','၁၂','၁၃','၁၄');
                    $state_prefix_array = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14');
                @endphp
                <td width="15%">
                    <select class="form-control radius-all" name="nrc_old[1]" id="nrc_1" style="padding:0;font-size: 13px;">
                        {{-- @for ($i=1; $i < 15; $i++)
                            <option value="{{$i}}"
                                    @if ($nrc_1 == $i)
                                    selected
                                    @endif
                            >{{$i}}</option>
                        @endfor --}}
                        @foreach ($state_prefix_array as $key => $value)
                            <option value="{{$value}}"
                                    @if ($nrc_1 == $value)
                                    selected
                                    @endif
                            >{{$value}}</option>
                        @endforeach
                    </select>
                </td>
                <td style="width: 5px;"><span class="input-group-addon nrc-icon"  style="font-size: 10px;">/</span></td>
                <td width="25%">
                    {{-- @php
                        $nrc_prefix_array = array('TANGANA' => 'Taungoo', 'BAAHNA' => 'PhaAn');
                    @endphp --}}
                    <select class="form-control radius-all" name="nrc_old[2]" id="nrc_2" style="padding:0;font-size: 13px;margin: 0;">
                        {{-- <option value=""></option> --}}
                        {{-- @foreach ($nrc_prefix_array as $key => $value)
                            <option value="{{$key}}"
                                    @if ($nrc_2 == $key)
                                    selected
                                    @endif
                            >{{$key}}</option>
                        @endforeach --}}
                    </select>
                </td>
                <td style="width: 5px;"><span class="input-group-addon nrc-icon"  style="font-size: 13px;">(</span></td>
                <td width="15%">
                    @php
                        // $prefix_array = array('နိုင်');
                        if(companyReportPart() == 'company.moeyan'){
                            $prefix_array = array('N', 'E', 'P', 'T', 'Y', 'S');
                        }
                        else{
                            $prefix_array = array('C');
                        }
                    @endphp
                    <select  class="form-control radius-all" name="nrc_old[3]" id="nrc_3" style="padding:0;font-size: 13px;">
                        @foreach ($prefix_array as $prefix)
                            <option value="{{$prefix}}"
                                    @if ($nrc_3 == $prefix)
                                    selected
                                    @endif
                            >{{$prefix}}</option>
                        @endforeach
                    </select>
                </td>
                <td style="width: 5px;"><span class="input-group-addon nrc-icon" style="font-size: 13px;">)</span></td>
                <td >
                    <input  number="number" name="nrc_old[4]" value="{{ $nrc_4 }}" class="form-control radius-all" style="font-size: 11px;padding: 0;margin: 0;text-align: center;"
                            onKeyPress="if(this.value.length == 6) return false;" id="nrc_4">
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

            $("#client_number_moeyan").prop('readonly', true);
            var nrc_state = ('<?php echo $nrc_1 ?>') ? '<?php echo $nrc_1 ?>' :
            ('<?php echo old('nrc_old.1') ?>') ? ('<?php echo old('nrc_old.1') ?>') : '1';
            var nrc_prefix = ('<?php echo $nrc_2 ?>') ? '<?php echo $nrc_2 ?>' :
            ('<?php echo old('nrc_old.2') ?>') ? ('<?php echo old('nrc_old.2') ?>') : 'BAMANA';
            var nrc_nature = ('<?php echo $nrc_3 ?>') ? '<?php echo $nrc_3 ?>' :
            ('<?php echo old('nrc_old.3') ?>') ? ('<?php echo old('nrc_old.3') ?>') : '';
            var nrc_code = ('<?php echo $nrc_4 ?>') ? '<?php echo $nrc_4 ?>' :
            ('<?php echo old('nrc_old.4') ?>') ? ('<?php echo old('nrc_old.4') ?>') : '';
            var nrc_full = ('<?php echo $nrc_full ?>') ? '<?php echo $nrc_full ?>' :
            ('<?php echo old('nrc_number_new') ?>') ? ('<?php echo old('nrc_number_new') ?>') : '';


            nrcType(nrc_state, nrc_prefix, nrc_nature, nrc_code, nrc_full);

          /*  var guar_url = encodeURI('/api/guarantor-check/' + nrc_full);
            $.ajax({
                url : guar_url,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    data != null ? $("#guarantor").val(1) : $("#guarantor").val(0);
                }
            });*/

            $( "#nrc_type" ).change(function() {
                nrcType(nrc_state, nrc_prefix, nrc_code, nrc_full);
            });

            $('select[name="nrc_old[1]"]').on('change',function(){
                var statePrefix = $(this).val();
                if(statePrefix)
                {
                    nrcPrefixDefault(statePrefix);
                }
                else
                {
                    $('select[name="state"]').empty();
                }
            });

            $('#nrc_new').change(function(){
                var current = window.location.href;
                var type = current.substring(32, current.indexOf('/create'));
                var url = encodeURI('/api/nrc-check/' + $(this).val() + '/' + type);
                $.ajax({
                    url : url,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        if(data){
                            alert("This nrc number with a "+type+" already exists");
                            $("#nrc_new").val("");
                        }
                    }
                });
            });

            //$( ".nrc_new" ).hide();
            //$( ".nrc_old" ).hide();
            nrcPrefixDefault(nrc_state, nrc_prefix, nrc_nature);

            var company = $('#company_part').val();

            if(company == "company.moeyan"){
                $("#nrc_type").val('New Format');
                $("#nrc_type").attr('disabled', true);
                $("#nrc_type").css('cursor', 'text');
            }

            $('#nrc_2, #nrc_4').on('change', function () {
                var nrc_pre = $("#nrc_2").val().replace(/[a-z]/g, '');
                var nrc_num =  nrc_pre + $("#nrc_4").val();
                $("#client_number_moeyan").val(nrc_num);
            });

            function nrcPrefixDefault(statePrefix = null, valueSelected = null, nrc_nature = null) { // get nrc prefix by state id
                $.ajax({
                    url : '/api/nrc-prefix/' +statePrefix,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        $('select[name="nrc_old[2]"]').empty();
                        $.each(data, function(key,value){
                            $('select[name="nrc_old[2]"]').append('<option value="'+ value +'">'+ value +'</option>');
                            // $("#nrc_2 option[value='မညန']").prop('selected', true);
                            if (valueSelected) {
                                // console.log(valueSelected);
                                $("#nrc_2 option[value='"+valueSelected+"']").prop('selected', true);
                            }
                        });
                    }
                });

                $("#nrc_3 option[value='"+nrc_nature+"']").prop('selected', true);
            }

            function nrcType(nrc_state = null, nrc_prefix = null, nrc_nature = null, nrc_code = null, nrc_full = null) { // nrc old and new format change
                var nrc_type = $( "#nrc_type" ).val();
                //alert(nrc_type);
                if (nrc_type != 'New Format') {

                    $( ".nrc_new" ).hide();
                    $( ".nrc_old" ).show();


                    $( "#nrc_1" ).val(); $( "#nrc_2" ).val(''); $( "#nrc_3" ).val(''); $( "#nrc_4" ).val('');
                    $( "#nrc_new" ).val(nrc_full);
                    //$('[name=nrc_number_new]').show();
                }else{

                    $( ".nrc_new" ).show();
                    $( ".nrc_old" ).hide();

                    $( "#nrc_new" ).val('');
                    $( "#nrc_1" ).val(nrc_state); $( "#nrc_2" ).val('');

                    <?php if(companyReportPart() == 'company.moeyan'){?>
                    $( "#nrc_3" ).val('N');
                    <?php } else {?>
                    $( "#nrc_3" ).val('C');
                    <?php }?>

                    $( "#nrc_4" ).val(nrc_code);
                    nrcPrefixDefault(nrc_state, nrc_prefix, nrc_nature);

                    /*$('[name=nrc_number_new]').hide();
                    $('[name=nrc_number_new]').val('');*/
                }
            }
        });
        </script>
    @endpush
@endif


{{-- Note: most of the times you'll want to use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load CSS/JS once, even though there are multiple instances of it. --}}
