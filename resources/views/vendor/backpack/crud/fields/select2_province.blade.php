<!-- select2 from ajax -->
@php
    $r_id = rand(1,9999);
    $location_group = isset($field['location_group'])?strtolower($field['location_group']):'location_group';
    $f_id = isset($field['name'])? str_replace(']','',str_replace('[','',$field['name'])) :'location_group_id';
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : false ));
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <?php $entity_model = $crud->model; ?>

    <select provinces="{{$location_group}}"
            name="{{ $field['name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >

        @if ($old_value)
            @php
                $item = $connected_entity->find($old_value);
            @endphp
            @if ($item)
                <option value="{{ $item->getKey() }}" selected>
                    {{ $item->{$field['attribute']} }}
                </option>
            @endif
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
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
        jQuery(document).ready(function($) {
            // trigger select2 for each untriggered select2 box
            $("#select2_ajax_{{ $f_id.$r_id }}").each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "{{ $field['placeholder'] }}",
                        minimumInputLength: "{{ $field['minimum_input_length'] }}",
                        ajax: {
                            url: "{{ $field['data_source'] }}",
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

                                        var textField = "{{ $field['attribute'] }}";
                                        return {
                                            text: item[textField],
                                            id: item["{{ $connected_entity_key_name }}"]
                                        }
                                    }),
                                    more: data.current_page < data.last_page
                                };

                                return result;
                            },
                            cache: true
                        },
                    }).on('change',function () {
                        $('[districts="{{$location_group}}"]').val('').trigger("change");
                        $('[communes="{{$location_group}}"]').val('').trigger("change");
                        $('[villages="{{$location_group}}"]').val('').trigger("change");

                        var p = $('[provinces="{{$location_group}}"] option:selected').text();
                        var d = $('[districts="{{$location_group}}"] option:selected').text();
                        var c = $('[communes="{{$location_group}}"] option:selected').text();
                        var v = $('[villages="{{$location_group}}"] option:selected').text();

                        var house = $('[house="{{$location_group}}"]').val();
                        var street = $('[street="{{$location_group}}"]').val();

                        var pp =  $('[provinces="{{$location_group}}"]').val();
                        var dd =  $('[districts="{{$location_group}}"]').val();

                        var address = '';

                        if(pp == 12) {
                            address = '{{ trans('backpack::custom.house_no') }}'+house+' {{ trans('backpack::custom.street_no') }} '+street+'  ' + v + '   {{ trans('backpack::custom.commune_1') }} ' + c + ' {{ trans('backpack::custom.district_1') }} ' + d + ' {{ trans('backpack::custom.province') }}';
                        } else {
                            if(dd == 811){
                                address = '{{ trans('backpack::custom.house_no') }}'+house+' {{ trans('backpack::custom.street_no') }} '+street+' {{ trans('backpack::custom.village') }} ' + v + '   {{ trans('backpack::custom.commune_1') }} ' + c + ' {{ trans('backpack::custom.city_1') }} ';
                            }else {
                                address = '{{ trans('backpack::custom.house_no') }}'+house+' {{ trans('backpack::custom.street_no') }} '+street+' {{ trans('backpack::custom.village') }} ' + v + ' {{ trans('backpack::custom.commune_2') }} ' + c + ' {{ trans('backpack::custom.district_2') }} ' + d + ' {{ trans('backpack::custom.city_2') }} ' + p;
                            }
                        }

                        $('[address="{{$location_group}}"]').val(address);

                    });
                }
            });

        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
