<!-- select2 from ajax -->
@php
    $r_id = rand(1,9999);

    $state = null;
    $district = null;
    $township = null;
    $village = null;
    $ward = null;
    $house_number = null;
    $address = null;

    if(isset($entry)){

        if($entry != null){
            $state = isset($entry->{$field['state_name']})?$entry->{$field['state_name']} : null;
            $district = isset($entry->{$field['district_name']})?$entry->{$field['district_name']} : null;
            $township = isset($entry->{$field['township_name']})?$entry->{$field['township_name']} : null;
            $village = isset($entry->{$field['village_name']})?$entry->{$field['village_name']} : null;
            $ward = isset($entry->{$field['ward_name']})?$entry->{$field['ward_name']} : null;
            $house_number = isset($entry->{$field['house_number_name']})?$entry->{$field['house_number_name']} : '';
            $address = isset($entry->{$field['address_name']})?$entry->{$field['address_name']} : '';

        }
    }


    $f_id1 = isset($field['state_name'])? str_replace(']','',str_replace('[','',$field['state_name'])) :'state_name';
    $f_id2 = isset($field['district_name'])? str_replace(']','',str_replace('[','',$field['district_name'])) :'district_name';
    $f_id3 = isset($field['township_name'])? str_replace(']','',str_replace('[','',$field['township_name'])) :'township_name';
    $f_id4 = isset($field['village_name'])? str_replace(']','',str_replace('[','',$field['village_name'])) :'village_name';
    $f_id5 = isset($field['ward_name'])? str_replace(']','',str_replace('[','',$field['ward_name'])) :'ward_name';

    $old_value_state = old($field['state_name']) ? old($field['state_name']) : ($state != null ? $state : (isset($field['state_default']) ? $field['state_default'] : false ));
    $old_value_district = old($field['district_name']) ? old($field['district_name']) : ($district != null ? $district : (isset($field['district_default']) ? $field['district_default'] : false ));
    $old_value_township = old($field['township_name']) ? old($field['township_name']) : ($township != null ? $township : (isset($field['township_default']) ? $field['township_default'] : false ));
    $old_value_village = old($field['village_name']) ? old($field['village_name']) : ($village != null ? $village : (isset($field['village_default']) ? $field['village_default'] : false ));
    $old_value_ward = old($field['ward_name']) ? old($field['ward_name']) : ($ward != null ? $ward : (isset($field['ward_default']) ? $field['ward_default'] : false ));
    $old_value_house_number = old(square_brackets_to_dots($field['house_number_name'])) ?? $house_number ?? $field['house_number_default'] ?? '';
    $old_value_address = old(square_brackets_to_dots($field['address_name'])) ?? $address ?? $field['address_name_default'] ?? '';
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('State') }} / {{_t('Region')}}</label>
    <select
            name="{{ $field['state_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id1.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_state)
            @php
                $item = \App\Address::find($old_value_state);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('District') }} / {{_t('Division')}}</label>
    <select
            name="{{ $field['district_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id2.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_district)
            @php
                $item = \App\Address::find($old_value_district);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>


<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Township') }}</label>
    <select
            name="{{ $field['township_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id3.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_township)
            @php
                $item = \App\Address::find($old_value_township);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Town') }} / {{ _t('Village') }} / {{ _t('Group Village') }}</label>
    <select
            name="{{ $field['village_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id4.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_village)
            @php
                $item = \App\Address::find($old_value_village);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>


<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Ward') }} / {{ _t('Small Village') }}</label>
    <select
            name="{{ $field['ward_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id5.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_ward)
            @php
                $item = \App\Address::find($old_value_ward);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('House Number') }}</label>
    <input id="house_number_name{{ $r_id }}"
            type="text"
            name="{{ $field['house_number_name'] }}"
            value="{{ $old_value_house_number }}"
            @include('crud::inc.field_attributes')
    >
</div>

<div class="form-group col-md-12" >
    <label>{{ _t('Address') }}</label>
    <input  id="address_name{{ $r_id }}"
            type="text"
            name="{{ $field['address_name'] }}"
            value="{{ $old_value_address }}"
            @include('crud::inc.field_attributes')
    >
</div>



{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
        <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')

    <script>
        if(!String.prototype.trim) {
            String.prototype.trim = function () {
                return this.replace(/^\s+|\s+$/g,'');
            };
        }
        function getAddressAu{{ $r_id }}() {

            var house_number_name = $('#house_number_name{{ $r_id }}').val();
            var v1 = $('#select2_ajax_{{ $f_id1.$r_id }} option:selected').text();
            var v2 = $('#select2_ajax_{{ $f_id2.$r_id }} option:selected').text();
            var v3 = $('#select2_ajax_{{ $f_id3.$r_id }} option:selected').text();
            var v4 = $('#select2_ajax_{{ $f_id4.$r_id }} option:selected').text();
            var v5 = $('#select2_ajax_{{ $f_id5.$r_id }} option:selected').text();

            var full_address = house_number_name + ' ' + v5.trim() + ' ' + v4.trim() + ' ' +
                v3.trim() + ' ' + v2.trim() + ' ' + v1.trim() ;


            $('#address_name{{ $r_id }}').val(full_address);
        }
    </script>
    <script>

        jQuery(document).ready(function($) {

            $('#house_number_name{{ $r_id }}').on('change',function () {
                getAddressAu{{ $r_id }}();
            });

            $("#select2_ajax_{{ $f_id1.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "",
                        minimumInputLength: "0",
                        ajax: {
                            url: "{{ url('api/myanmar-address-state') }}",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {

                                        var textField = "name";
                                        return {
                                            text: item[textField] + ' / '+ item["description"],
                                            id: item["code"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {
                        $("#select2_ajax_{{ $f_id2.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id3.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');

                        getAddressAu{{ $r_id }}();
                    });
                }
            });


            $("#select2_ajax_{{ $f_id2.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "",
                        minimumInputLength: "0",
                        ajax: {
                            url: "{{ url('api/myanmar-address-district') }}",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page,
                                    state_id : $("#select2_ajax_{{ $f_id1.$r_id }}").val()
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {

                                        var textField = "name";
                                        return {
                                            text: item[textField] + ' / '+ item["description"],
                                            id: item["code"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {
                        $("#select2_ajax_{{ $f_id3.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');


                        getAddressAu{{ $r_id }}();

                    });
                }
            });


            $("#select2_ajax_{{ $f_id3.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "",
                        minimumInputLength: "0",
                        ajax: {
                            url: "{{ url('api/myanmar-address-township') }}",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page,
                                    district_id: $("#select2_ajax_{{ $f_id2.$r_id }}").val()
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {

                                        var textField = "name";
                                        return {
                                            text: item[textField] + ' / '+ item["description"],
                                            id: item["code"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {
                        $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                        $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');


                        getAddressAu{{ $r_id }}();

                    });
                }
            });


            $("#select2_ajax_{{ $f_id4.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "",
                        minimumInputLength: "0",
                        ajax: {
                            url: "{{ url('api/myanmar-address-village') }}",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page,
                                    township_id: $("#select2_ajax_{{ $f_id3.$r_id }}").val()
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {

                                        var textField = "name";
                                        return {
                                            text: item[textField] + ' / '+ item["description"],
                                            id: item["code"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {
                        $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');


                        getAddressAu{{ $r_id }}();


                    });
                }
            });


            $("#select2_ajax_{{ $f_id5.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "",
                        minimumInputLength: "0",
                        ajax: {
                            url: "{{ url('api/myanmar-address-ward') }}",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page,
                                    village_id: $("#select2_ajax_{{ $f_id4.$r_id }}").val()
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {

                                        var textField = "name";
                                        return {
                                            text: item[textField] + ' / '+ item["description"],
                                            id: item["code"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {

                        getAddressAu{{ $r_id }}();

                    });
                }
            });


        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
