<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <!-- no scripts -->
        {{--<script src="https://unpkg.com/knayi-myscript@latest/dist/knayi-myscript.min.js"></script>--}}
        <script src="{{ asset('vendor/adminlte') }}/plugins/unpkg/unpkg.min.js" ></script>

        <script type="text/javascript">
        $( document ).ready(function() {

            $( "#name_other" ).keyup(function() {
                var name_other = $( "#name_other" ).val();
                if (name_other) {
                    var result = knayi.fontDetect(name_other);
                    /*console.log(result);
                    if (result == 'zawgyi') {
                        alert( "Only Unicode is Allowed" );
                        $( "#name_other" ).val('');
                    }*/
                }
            });
        });
        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
