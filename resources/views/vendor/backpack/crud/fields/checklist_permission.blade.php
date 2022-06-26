<!-- select2 -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->getModel(); ?>


        @php
            $m  = $field['model']::all();
        @endphp
        @if($m != null)
            @php
                $gg  = $m->groupBy('group_name');
                $iii = 0;
            @endphp
            @foreach ($gg as $g => $mg)
                <h5 style="color: red;cursor: pointer;" class="gg-click" data-id="{{$iii}}">{{ ucfirst($g) }}</h5>
                <div class="row">
                    @foreach ($mg as $connected_entity_entry)
                        <div class="col-sm-4">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" class="gg-check-{{$iii}}"
                                  name="{{ $field['name'] }}[]"
                                  value="{{ $connected_entity_entry->getKey() }}"

                                  @if( ( old( $field["name"] ) && in_array($connected_entity_entry->getKey(), old( $field["name"])) ) || (isset($field['value']) && in_array($connected_entity_entry->getKey(), $field['value']->pluck($connected_entity_entry->getKeyName(), $connected_entity_entry->getKeyName())->toArray())))
                                         checked = "checked"
                                  @endif > {!! ucfirst(str_replace('-',' ',$connected_entity_entry->{$field['attribute']})) !!}
                              </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php $iii++; @endphp
            @endforeach
        @endif


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
