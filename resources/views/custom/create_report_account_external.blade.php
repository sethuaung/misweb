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
					@if(companyReportPart() == 'company.mkt')
						<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
						<!-- Product -->
                    	@if (_can('profit-lost-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-profit-loss') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss</span></a></li>@endif
						@if (_can('balance-sheet-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-balance-sheet') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Balance Sheet</span></a></li>@endif
					@elseif(companyReportPart() == 'company.quicken')
						<li><a href="javascript:void(0)" data-href="{{ url('report/frd-report') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Report</span></a></li>
						@if (_can('profit-lost-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-profit-loss') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss</span></a></li>@endif
						@if (_can('balance-sheet-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-balance-sheet') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Balance Sheet</span></a></li>@endif
						<li><a href="javascript:void(0)" data-href="{{ url('report/external-portfolio-outreach') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>PAR</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/ten-largest-loan') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Ten Largest Loan</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/ten-largest-deposit') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Deposit Saving</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/market-conduct') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Market Conduct</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/prudential-indicators') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Prudential Indicators</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/monthly-progress') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Monthly Progress</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/saving-report') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Saving Report</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/donor') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Donor</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/staff-information') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Staff Information</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/deposit-taking') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Deposit Taking</span></a></li>
						<li><a href="javascript:void(0)" data-href="{{ url('report/female-client') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Female Client</span></a></li>
					@else
						<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
					<!-- Product -->
{{--                    @if (_can('account-list-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-account-list') }}" class="report-url"><i class='fa fa-tag'></i> <span>Account List</span></a></li>@endif--}}
{{--                    @if (_can('trail-balance-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-trial-balance') }}" class="report-url"><i class='fa fa-tag'></i> <span>Trial Balance</span></a></li>@endif--}}
						@if (_can('profit-lost-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-profit-loss') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss</span></a></li>@endif
						@if (_can('profit-lost-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-profit-loss-mkt') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss MKT</span></a></li>@endif
						{{--@if (_can('profit-lost-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-detail') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss Detail</span></a></li>@endif--}}
					{{-- @if (_can('profit-lost-by-job-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-by-job') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss By Job</span></a></li>@endif
						@if (_can('profit-lost-by-class-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-by-class') }}" class="report-url"><i class='fa fa-tag'></i> <span>Profit Loss By Class</span></a></li>@endif--}}
						@if (_can('balance-sheet-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-balance-sheet') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Balance Sheet</span></a></li>@endif
						@if (_can('balance-sheet-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-balance-sheet-mkt') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Balance Sheet MKT</span></a></li>@endif
						@if (_can('portfolio-outreach-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-portfolio-outreach') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Portfolio And Outreach </span></a></li>@endif
						@if (_can('ten-largest-loan-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-ten-largest-loan') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Ten Largest Loans </span></a></li>@endif
						@if (_can('ten-largest-deposit-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-ten-largest-deposit') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Ten Largest Depositors </span></a></li>@endif
						@if (_can('market-conduct-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-market-conduct') }}" class="report-url"><i class='fa fa-tag'></i> <span>Market Conduct Indicator </span></a></li>@endif
						@if (_can('external-prudential-indicator'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-prudential-indicator') }}" class="report-url"><i class='fa fa-tag'></i> <span>Prudential Indicator </span></a></li>@endif


						@if (_can('monthly-progress-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-monthly-progress') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Monthly Progress  Report</span></a></li>@endif
						@if (_can('saving-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-saving') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Saving Report</span></a></li>@endif
						@if (_can('donor-lender-information-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-donor-lender-information') }}" class="report-url"><i class='fa fa-tag'></i> <span>Donor Lender Information </span></a></li>@endif
						@if (_can('staff-information-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-staff-information') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Staff Information </span></a></li>@endif
						@if (_can('loan-type-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-loan-type') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Loan Type </span></a></li>@endif
						@if (_can('section-wise-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-section-wise') }}" class="report-url" data-date-only="1"><i class='fa fa-tag'></i> <span>Sector Wise </span></a></li>@endif
						@if (_can('section-wise-outstanding-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-section-wise-outstanding') }}" class="report-url"><i class='fa fa-tag'></i> <span>Sector Wise Outstanding </span></a></li>@endif
						{{--@if (_can('transaction-detail-by-account-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/transaction-detail-by-account') }}" class="report-url"><i class='fa fa-tag'></i> <span>Transaction Detail by Account</span></a></li>@endif--}}
						{{--@if (_can('cash-statement-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/external-cash-statement') }}" class="report-url"><i class='fa fa-tag'></i> <span>Cash Statement</span></a></li>@endif--}}
						{{--@if (_can('cash-statement-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement-detail') }}" class="report-url"><i class='fa fa-tag'></i> <span>Cash Statement Detail</span></a></li>@endif--}}
						<!-- ======================================= -->
						{{-- <li class="header">Other menus</li> --}}
					@endif
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
