<!-- select2 -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <div class="row">
        @foreach ($field['option'] as $k => $v)
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               name="{{ $field['name'] }}[]"
                               value="{{ $k }}"

                               @if( (old( $field["name"]) && in_array($k, old( $field["name"])) ) ||  (isset($field['value']) && in_array($k, $field['value'])))
                               checked="checked"
                            @endif > {!! $v !!}
                    </label>
                </div>
            </div>
        @endforeach


    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
