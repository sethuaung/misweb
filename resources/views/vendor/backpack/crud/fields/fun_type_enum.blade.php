<!-- enum -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->model; ?>
    <select
        name="{{ $field['name'] }}"
        @include('crud::inc.field_attributes')
        >
        <option value="-">-</option>
        @if ($entity_model::isColumnNullable($field['name']))

        @endif

            @if (count($entity_model::getPossibleEnumValues($field['name'])))
                @foreach ($entity_model::getPossibleEnumValues($field['name']) as $possible_value)
                    <option value="{{ $possible_value }}"
                        @if (( old(square_brackets_to_dots($field['name'])) &&  old(square_brackets_to_dots($field['name'])) == $possible_value) || (isset($field['value']) && $field['value']==$possible_value))
                            selected
                        @endif
                    >{{ $possible_value }}</option>
                @endforeach
            @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
