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
                    @if (_can('account-list-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/account-list') }}" class="report-url" id="account_list"><i class='fa fa-tag'></i> <span>Account List</span></a></li>@endif
                    @if (_can('trail-balance-report'))<li><a href="javascript:void(0)" data-href="{{ companyReportPart() == 'company.moeyan' ? url('report/trial-balance-moeyan') : url('report/trial-balance')}}" class="report-url" id="trial_balance"><i class='fa fa-tag'></i> <span>Trial Balance</span></a></li>@endif
                    @if (_can('profit-lost-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss') }}" class="report-url" id="profit_loss"><i class='fa fa-tag'></i> <span>Profit Loss</span></a></li>@endif


                    {{--@if (_can('profit-lost-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-detail') }}" class="report-url" id="profit_loss_d"><i class='fa fa-tag'></i> <span>Profit Loss Detail</span></a></li>@endif--}}
                   {{-- @if (_can('profit-lost-by-job-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-by-job') }}" class="report-url" id="profit_loss_j"><i class='fa fa-tag'></i> <span>Profit Loss By Job</span></a></li>@endif
                    @if (_can('profit-lost-by-class-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/profit-loss-by-class') }}" class="report-url" id="profit_loss_c"><i class='fa fa-tag'></i> <span>Profit Loss By Class</span></a></li>@endif--}}
                    @if (_can('balance-sheet-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/balance-sheet') }}" class="report-url" id="balance_sheet"><i class='fa fa-tag'></i> <span>Balance Sheet</span></a></li>@endif
                    {{--@if (_can('transaction-detail-by-account-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/transaction-detail-by-account') }}" class="report-url" id="transaction_detail_acc"><i class='fa fa-tag'></i> <span>Transaction Detail by Account</span></a></li>@endif--}}

					@if (companyReportPart() == 'company.mkt')
						{{--@if (_can('cash-statement-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement') }}" class="report-url" id="cash_book"><i class='fa fa-tag'></i> <span>Cash Book</span></a></li>@endif--}}
						{{--@if (_can('cash-statement-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement-detail') }}" class="report-url" id="cash_statement_detail"><i class='fa fa-tag'></i> <span>Cash Book Detail</span></a></li>@endif--}}
						{{--@if (_can('cash-transaction'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-transaction') }}" class="report-url" id="cash_transaction"><i class='fa fa-tag'></i> <span>Cash Transaction</span></a></li>@endif--}}
						@if (_can('cash-book-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-book') }}" class="report-url" id="cash_book_summary"><i class='fa fa-tag'></i> <span>Cash Book Summary</span></a></li>@endif
						@if (_can('cash-book-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-book-detail') }}" class="report-url" id="cash_book_detail"><i class='fa fa-tag'></i> <span>Cash Book Detail</span></a></li>@endif

					@else
						@if (companyReportPart() == 'company.moeyan')
							@if (_can('cash-statement-report-moeyan'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement-moeyan') }}" class="report-url" id="cash_book"><i class='fa fa-tag'></i> <span>Cash Book</span></a></li>@endif
							@if (_can('cash-statement-detail-report-moeyan'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement-detail-moeyan') }}" class="report-url" id="cash_book_detail"><i class='fa fa-tag'></i> <span>Cash Book Detail</span></a></li>@endif

						@else
							@if (_can('cash-statement-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement') }}" class="report-url" id="cash_book"><i class='fa fa-tag'></i> <span>Cash Book</span></a></li>@endif
							@if (_can('cash-statement-detail-report'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-statement-detail') }}" class="report-url" id="cash_book_detail"><i class='fa fa-tag'></i> <span>Cash Book Detail</span></a></li>@endif

						@endif

						@if (_can('cash-transaction'))<li><a href="javascript:void(0)" data-href="{{ url('report/cash-transaction') }}" class="report-url" id="cash_transaction"><i class='fa fa-tag'></i> <span>Cash Transaction</span></a></li>@endif
					@endif

					@if (companyReportPart() == 'company.moeyan')
						@if (_can('general-leger-moeyan'))<li><a href="javascript:void(0)" data-href="{{ url('report/general-leger-moeyan') }}" class="report-url" id="generalLeger"><i class='fa fa-tag'></i> <span>General Ledger</span></a></li>@endif
					@else
						@if (_can('general-leger'))<li><a href="javascript:void(0)" data-href="{{ url('report/general-leger') }}" class="report-url" id="generalLeger"><i class='fa fa-tag'></i> <span>General Ledger</span></a></li>@endif
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
