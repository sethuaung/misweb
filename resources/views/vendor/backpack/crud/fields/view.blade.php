<!-- view field -->

<div @include('crud::inc.field_wrapper_attributes') >
  @include($field['view'], compact('crud', 'field'))
</div>
