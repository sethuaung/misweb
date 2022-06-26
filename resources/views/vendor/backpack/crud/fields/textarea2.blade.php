<!-- textarea -->
@php
    $r_id = rand(1,9999);
    $location_group = isset($field['location_group'])?strtolower($field['location_group']):'location_group';
    $f_id = isset($field['name'])? str_replace(']','',str_replace('[','',$field['name'])) :'location_group_id';
@endphp
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <textarea address="{{$location_group}}"
              name="{{ $field['name'] }}"
            @include('crud::inc.field_attributes')

    >{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
