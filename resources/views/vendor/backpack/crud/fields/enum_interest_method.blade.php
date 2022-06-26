<!-- enum -->
@php
    $arr = [
             'effective-rate' => 'Effective Rate',
             'effective-flate-rate' => 'Effective Flate Rate',
             ];

    if(companyReportPart() == 'company.mkt'){
        $arr = [
             'flat-rate' => 'Flat Rate',
             'declining-rate' => 'Decline Rate',
             'declining-flate-rate' => 'Decline Flate Rate',
             'declining-balance-equal-installments' => 'Decline Flate payment',
             'interest-only' => 'Decline Interest only',
             ];
    }
    if(companyReportPart() == 'company.quicken'){
        $arr = [
             'effective-rate' => 'Effective Rate',
             'effective-flate-rate' => 'Effective Flate Rate',
             ];
    }
    if(companyReportPart() == 'company.moeyan'){
        $arr = [
             'moeyan-effective-rate' => 'Effective Rate (MoeYan)',
             'moeyan-effective-flate-rate' => 'Flate Payment (MoeYan)',
             'moeyan-flexible-rate' => 'Flexible Rate (MoeYan)',
             ];
    }


@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->model; ?>
    <select
        name="{{ $field['name'] }}"
        @include('crud::inc.field_attributes')
        >

        @if ($entity_model::isColumnNullable($field['name']))

        @endif

            @if (count($arr))
                @foreach ($arr as $possible_value => $v)
                    <option value="{{ $possible_value }}"
                        @if (( old(square_brackets_to_dots($field['name'])) &&  old(square_brackets_to_dots($field['name'])) == $possible_value) || (isset($field['value']) && $field['value']==$possible_value))
                            selected
                        @endif
                    >{{ $v }}</option>
                @endforeach
            @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
