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


					<!-- Product -->


					<li class="treeview">
						<a href="#"><i class="fa fa-shopping-cart"></i> <span>Purchase Order</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="javascript:void(0)" data-href="{{ url('report/purchase-order') }}" class="report-url"><i class='fa fa-tag'></i> <span>Order List</span></a></li>
							<li><a href="javascript:void(0)" data-href="{{ url('report/purchase-order-detail') }}" class="report-url"><i class='fa fa-tag'></i> <span>Order List Detail</span></a></li>
							<li><a href="javascript:void(0)" data-href="{{ url('report/purchase-order-by-item') }}" class="report-url"><i class='fa fa-tag'></i> <span>Order By Item</span></a></li>
						</ul>
					</li>


					<li class="treeview">
						<a href="#"><i class="fa fa-shopping-cart"></i> <span>Bill</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="javascript:void(0)" data-href="{{ url('report/bill') }}" class="report-url"><i class='fa fa-tag'></i> <span>Bill by Supply</span></a></li>
							<li><a href="javascript:void(0)" data-href="{{ url('report/bill-by-supply-summary') }}" class="report-url"><i class='fa fa-tag'></i> <span>Bill by Supply Summary</span></a></li>
							<li><a href="javascript:void(0)" data-href="{{ url('report/bill-detail') }}" class="report-url"><i class='fa fa-tag'></i> <span>Bill by Supply Detail</span></a></li>

							<li><a href="javascript:void(0)" data-href="{{ url('report/bill-by-item') }}" class="report-url"><i class='fa fa-tag'></i> <span>Bill By Item</span></a></li>
							<li><a href="javascript:void(0)" data-href="{{ url('report/bill-by-item-summary') }}" class="report-url"><i class='fa fa-tag'></i> <span>Bill By Item Summary</span></a></li>
						</ul>
					</li>



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
