@extends('backpack::layout')

@section('header')
    @if(request()->is_frame >0)

    @else
	<section class="content-header">
	  <h1>
        <span class="text-capitalize  head-font-size">Add {!! str_replace('_',' ',$crud->getHeading() ?? $crud->entity_name) !!}</span>
        <small>
			&nbsp;&nbsp;&nbsp;&nbsp;
			@if ($crud->hasAccess('list'))
				<a href="{{ starts_with(URL::previous(), url($crud->route)) ? URL::previous() : url($crud->route) }}" class="hidden-print my-back"><i class="fa fa-angle-double-left"></i>  <span></span></a>
			@endif
		</small>


	  </h1>
	  {{--<ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>--}}
	</section>
    @endif
@endsection

@section('content')

<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="col-md-12">

		    <div class="row display-flex-wrap my-sha">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif
		    </div><!-- /.box-body -->
		    <div class="row">

                @include('crud::inc.form_save_buttons')

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection
