<!-- text input -->
<div>

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
                type="text"
                ng-model="item.{{ $field['name'] }}"
                @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif

        @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


@if (!$crud->child_resource_included['text'])

    @push('crud_fields_styles')
        <style>
            .table input[type='text']
        </style>
    @endpush

    <?php $crud->child_resource_included['text'] = true; ?>
@endif