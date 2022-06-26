@extends('backpack::layout')

@section('header')

@endsection

@section('sidebar')

	@if (backpack_auth()->check())
		<!-- Left side column. contains the sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- Sidebar user panel -->
			@include('backpack::inc.sidebar_user_panel')

			<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu" data-widget="tree">
				{{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
				<!-- ================================================ -->
					<!-- ==== Recommended place for admin menu items ==== -->
					<!-- ================================================ -->

					<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
					@if (_can('sale-order-report') || _can('sale-order-pich-dara-report') || _can('sale-order-detail-report') || _can('sale-order-by-item-report'))
						<li class="treeview">
							<a href="#"><i class="fa fa-shopping-cart"></i> <span>{{_t('Client Report')}}</span> <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								@if (_can('customer-list-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/customer-list') }}" class="report-url"><i class='fa fa-tag'></i> <span>Client List</span></a></li>@endif
								@if (_can('sale-order-pich-dara-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/sale-order-pich-dara') }}" class="report-url"><i class='fa fa-tag'></i> <span>Saving List</span></a></li>@endif
							</ul>
						</li>
					@endif

					<!-- ======================================= -->
					{{-- <li class="header">Other menus</li> --}}
				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>
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

		    <div class="row display-flex-wrap">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif
		    </div><!-- /.box-body -->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection
