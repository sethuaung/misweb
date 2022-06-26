@php
$css = isset($filter->options['css'])?$filter->options['css']:null;
$js = isset($filter->options['js'])?$filter->options['js']:null;
@endphp

@push('crud_list_styles')
	@if($css != null)
		@include($css)
	@endif
@endpush


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
	@if($js != null)
		@include($js)
	@endif
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}