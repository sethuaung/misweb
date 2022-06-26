@if(companyReportPart() == 'company.bolika' || companyReportPart() == 'company.active_people'
|| companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.mkt'
)
    @include('vendor.backpack.base.inc.'.companyReportPart())
@else
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i><span>{{ trans('backpack::base.dashboard') }}</span></a></li>

@if(backpack_user()->can('create-client') || backpack_user()->can('list-client') || backpack_user()->can('list-client-center-leader') || backpack_user()->can('create-guarantor') || backpack_user()->can('list-survey') || backpack_user()->can('list-ownership') || backpack_user()->can('list-ownership-farmland') || backpack_user()->can('list-import-client'))
<li class="treeview">
    <a href="#"><i class="fa fa-users"></i> <span>MANAGE CLIENT</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">


        @if(backpack_user()->can('create-client'))  <li><a href='{{ backpack_url('client/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Client</span></a></li>@endif
        @if(backpack_user()->can('list-client'))  <li><a href='{{ backpack_url('client') }}'><i class="fa fa-plus-square-o"></i> <span>Client List</span></a></li>@endif
        @if(backpack_user()->can('list-client-center-leader')) <li><a href='{{ backpack_url('clientcenterleader') }}'><i class="fa fa-plus-square-o"></i> <span>Center Leader</span></a>@endif
        @if(companyReportPart() == 'company.moeyan')
          <li><a href='{{ backpack_url('inspector/create') }}'><i class="fa fa-plus-square-o"></i><span>Add Inspector</span></a></li>
          <li><a href='{{ backpack_url('inspector') }}'><i class="fa fa-plus-square-o"></i><span>Inspector List</span></a></li>
        @endif
        @if(backpack_user()->can('create-guarantor'))<li><a href='{{ backpack_url('guarantor/create') }}'><i class="fa fa-plus-square-o"></i><span>Add Guarantor</span></a></li>@endif
        @if(backpack_user()->can('list-guarantor'))<li><a href='{{ backpack_url('guarantor') }}'><i class="fa fa-plus-square-o"></i><span>Guarantor List</span></a></li>@endif
        <li><a href='{{ backpack_url('/add-address') }}'><i class="fa fa-plus-square-o"></i> <span>Add Address</span></a></li>
        <li class="sep-p"><hr></li>

        @if(backpack_user()->can('list-nrc-prefix'))<li><a href="{{ backpack_url('nrc-prefix') }}"><i class='fa fa-tag'></i> <span>NRC Prefix</span></a></li>@endif
        @if(backpack_user()->can('list-survey'))<li><a href="{{ backpack_url('survey') }}"><i class='fa fa-tag'></i> <span>Survey</span></a></li>@endif
        @if(backpack_user()->can('list-ownership'))<li><a href="{{ backpack_url('ownership') }}"><i class='fa fa-tag'></i> <span>Ownership</span></a></li>@endif
        @if(backpack_user()->can('list-ownership-farmland'))<li><a href="{{ backpack_url('ownershipfarmland') }}"><i class='fa fa-tag'></i><span>Ownership Farmland</span></a></li>@endif
        @if(backpack_user()->can('list-customer-group'))<li><a href="{{ backpack_url('customergroup') }}"><i class='fa fa-tag'></i> <span>CUSTOMER TYPE</span></a></li>@endif
        @if(backpack_user()->can('list-import-client'))<li><a href="{{ backpack_url('import-client/create') }}"><i class='fa fa-download'></i> <span>Import Client</span></a></li>@endif
    </ul>
</li>
@endif
@if(backpack_user()->can('create-loan-calculator') || backpack_user()->can('create-loan-disbursement') || backpack_user()->can('list-loan-group') || backpack_user()->can('create-loan-calculator') || backpack_user()->can('ist-loan-group-deposit') || backpack_user()->can('list-group-disbursement') || backpack_user()->can('list-group-payment'))
<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE LOAN</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        @if(backpack_user()->can('create-loan-calculator')) <li><a href='{{ backpack_url('loan-calculator/create') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Calculator</span></a></li>@endif
        @if(backpack_user()->can('create-loan-disbursement'))<li><a href="{{ backpack_url('loandisbursement/create') }}"><i class='fa fa-tag'></i><span>Create Loan</span></a></li>@endif
        @if(backpack_user()->can('list-loan-group'))   <li><a href="{{ backpack_url('grouploan') }}"><i class="fa fa-plus-square-o"></i> <span>Add Group Loan</span></a></li>@endif

            @if(backpack_user()->can('list-loan-group'))
                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Group Loan</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if(backpack_user()->can('create-loan-calculator')) <li><a href='{{ backpack_url('group-loan-approve') }}'><i class="fa fa-plus-square-o"></i> <span>Group Pending Approve</span></a></li>@endif
                        @if(backpack_user()->can('list-loan-group-deposit'))<li><a href='{{ backpack_url('group-loan-deposit') }}'><i class="fa fa-plus-square-o"></i> <span>Group Deposit</span></a></li>@endif
                        @if(backpack_user()->can('list-group-disbursement'))<li><a href='{{ backpack_url('group-dirseburse') }}'><i class="fa fa-plus-square-o"></i> <span>Group Dirseburse</span></a></li>@endif
                        @if(backpack_user()->can('list-group-payment'))<li><a href='{{ backpack_url('group-repayment-new') }}'><i class="fa fa-plus-square-o"></i> <span>Group Repayment</span></a></li>@endif
                        @if(backpack_user()->can('list-due-group-payment'))<li><a href='{{ backpack_url('group-due-repayment') }}'><i class="fa fa-plus-square-o"></i> <span>Group Due Repayment</span></a></li>@endif
                        {{--@if(backpack_user()->can('list-group-due-repayment'))<li><a href='{{ backpack_url('group-due-repayment') }}'><i class="fa fa-plus-square-o"></i> <span>Group Due Repayment</span></a></li>@endif--}}
                    </ul>
                </li>
            @endif

        @if(backpack_user()->can('list-loan-disbursement'))<li><a href="{{ backpack_url('loandisbursement') }}"><i class='fa fa-tag'></i> <span>View All Loan</span><span class="pull-right-container"><span class="label label-info pull-right">{{\App\Models\Loan::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-loan-disbursement'))<li><a href="{{ backpack_url('loan-disbursement-branch') }}"><i class='fa fa-tag'></i> <span>Change Branch of Loan</span></a></li>@endif
        @if(backpack_user()->can('list-disbursement-pending-approval'))<li><a href="{{ backpack_url('disbursependingapproval') }}"><i class='fa fa-tag'></i><span>Pending Approval</span><span class="pull-right-container"><span class="label label-warning pull-right">{{\App\Models\DisbursePendingApproval::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-disbursement-awaiting'))<li><a href="{{ backpack_url('disburseawaiting') }}"><i class='fa fa-tag'></i> <span>Approved</span><span class="pull-right-container"><span class="label label-success pull-right">{{\App\Models\LoanAwaiting::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-loan-outstanding')) <li><a href="{{ backpack_url('loanoutstanding') }}"><i class='fa fa-tag'></i> <span>Activated</span><span class="pull-right-container"><span class="label label-success pull-right">{{\App\Models\LoanOutstanding::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-disbursement-closed'))<li><a href="{{ backpack_url('disburseclosed') }}"><i class='fa fa-tag'></i> <span>Loan Completed</span><span class="pull-right-container"><span class="label label-success pull-right">{{\App\Models\LoanClosed::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-disbursement-decline'))<li><a href="{{ backpack_url('disbursedeclined') }}"><i class='fa fa-tag'></i> <span>Loan Declined</span><span class="pull-right-container"><span class="label label-danger pull-right">{{\App\Models\LoanDeclined::count('id')}}</span></span></a></li>@endif
        @if(backpack_user()->can('list-disbursement-decline'))<li><a href="{{ backpack_url('disbursecanceled') }}"><i class='fa fa-tag'></i> <span>Loan Canceled</span><span class="pull-right-container"><span class="label label-danger pull-right">{{\App\Models\LoanCanceled::count('id')}}</span></span></a></li>@endif
        {{--@if(backpack_user()->can('list-disbursement-withdrawn'))<li><a href="{{ backpack_url('disbursewithdrawn') }}"><i class='fa fa-tag'></i> <span>Loan Withdrawn</span><span class="pull-right-container"><span class="label label-danger pull-right">{{\App\Models\DisburseWithdrawn::count('id')}}</span></span></a></li>@endif--}}
        @if(backpack_user()->can('list-disbursement-write-off'))<li><a href="{{ backpack_url('disbursewrittenoff') }}"><i class='fa fa-tag'></i><span>Loan Write Off</span><span class="pull-right-container"><span class="label label-danger pull-right">{{\App\Models\DisburseWrittenOff::count('id')}}</span></span></a></li>@endif



        {{--@if(backpack_user()->can('create-disbursement-deposit'))<li><a href='{{ backpack_url('loandisburesementdepositu/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Deposits</span></a></li>@endif--}}
        {{--@if(backpack_user()->can('list-disbursement-deposit-u'))<li><a href='{{ backpack_url('loandisburesementdepositu') }}'><i class="fa fa-plus-square-o"></i> <span>List Deposits</span></a></li>@endif--}}
        {{--@if(backpack_user()->can('list-disbursement-pending'))<li><a href='{{ backpack_url('disbursementpending') }}'><i class="fa fa-plus-square-o"></i> <span>List Pending Disbursement</span></a></li>@endif--}}
        {{--@if(backpack_user()->can('create-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Disbursement</span></a></li>@endif--}}
        {{--@if(backpack_user()->can('list-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>List Disbursement</span></a></li>@endif--}}
        {{--@if(backpack_user()->can('list-loan-outstanding'))<li><a href='{{ backpack_url('loanoutstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Add Repayment</span></a></li>@endif--}}



        {{--<li><a href='{{ backpack_url('working-status') }}'><i class='fa fa-tag'></i> <span>Working Status</span></a></li>--}}

        {{--<li><a href='{{ backpack_url('default-saving-deposit') }}'><i class='fa fa-tag'></i> <span>Default Saving Deposit</span></a></li>--}}
        {{--<li><a href='{{ backpack_url('default-saving-interest') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest</span></a></li>--}}


        {{--<li><a href='{{ backpack_url('default-saving-interest-payable') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest Payable</span></a></li>--}}
        {{--<li><a href='{{ backpack_url('default-saving-withdrawal') }}'><i class='fa fa-tag'></i> <span>Default Saving Withdrawal</span></a></li>--}}
        {{--<li><a href='{{ backpack_url('default-saving-inter-withdrawal') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest Withdrawal</span></a></li>--}}

        <li class="sep-p"><hr></li>

        @if(backpack_user()->can('list-loan-objective')) <li><a href="{{ backpack_url('loanobjective') }}"><i class='fa fa-tag'></i> <span>Loan Objective</span></a></li>@endif
        @if(backpack_user()->can('list-transaction-type'))<li><a href="{{ backpack_url('transactiontype') }}"><i class='fa fa-tag'></i><span>Transaction Type</span></a></li>@endif
        @if(backpack_user()->can('list-import-loan'))<li><a href="{{ backpack_url('import-loan/create') }}"><i class='fa fa-download'></i> <span>Import Loan</span></a></li>@endif

    </ul>
</li>
@endif
<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE LEASING</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        <li><a href="{{ backpack_url('loan-item') }}"><i class='fa fa-tag'></i> <span>Loan Product</span></a></li>
        <li><a href="{{ backpack_url('loan-item-pending') }}"><i class='fa fa-tag'></i> <span>Pending Loan</span></a></li>
        <li><a href="{{ backpack_url('loan-item-approved') }}"><i class='fa fa-tag'></i> <span>Approved Loan</span></a></li>
        <li><a href="{{ backpack_url('list-disbursement-loan-item') }}"><i class='fa fa-tag'></i> <span>Loan Disbursement</span></a></li>
        <li><a href="{{ backpack_url('loan-item-activated') }}"><i class='fa fa-tag'></i> <span>Activated Loan</span></a></li>
        <li><a href="{{ backpack_url('loan-item-complete') }}"><i class='fa fa-tag'></i> <span>Loan Completed</span></a></li>
        <li><a href="{{ backpack_url('loan-item-repayment') }}"><i class='fa fa-tag'></i> <span>Loan Repayment List</span></a></li>
    </ul>
</li>

@if(backpack_user()->can('list-deposit-pending') || backpack_user()->can('create-loan-disbursement-deposit-u') || backpack_user()->can('list-loan-outstanding') || backpack_user()->can('list-list-loan-repayment') || backpack_user()->can('list-paid-support-fund'))
    <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>MANAGE PAYMENT</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        @if(backpack_user()->can('list-deposit-pending'))
            <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Payment Deposit</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(backpack_user()->can('list-deposit-pending'))<li><a href='{{ backpack_url('depositpending') }}'><i class="fa fa-plus-square-o"></i> <span>List Pending Deposit</span></a></li>@endif
                    @if(backpack_user()->can('create-loan-disbursement-deposit-u'))<li><a href='{{ backpack_url('loandisburesementdepositu/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Deposits</span></a></li>@endif
                    @if(backpack_user()->can('list-pending-deposit'))<li><a href='{{ backpack_url('loandisburesementdepositu') }}'><i class="fa fa-plus-square-o"></i> <span>List Deposits</span></a></li>@endif

                </ul>
            </li>
        @endif

        @if(backpack_user()->can('list-disbursement-pending'))
            <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Paid Disbursement</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(backpack_user()->can('list-disbursement-pending'))<li><a href='{{ backpack_url('disbursementpending') }}'><i class="fa fa-plus-square-o"></i> <span>List Pending Disbursement</span></a></li>@endif
                    @if(backpack_user()->can('list-disbursement-deposit-u'))<li><a href='{{ backpack_url('my-paid-disbursement/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Disbursement</span></a></li>@endif
                    @if(backpack_user()->can('list-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>List Disbursement</span></a></li>@endif
                </ul>
            </li>
        @endif

        @if(backpack_user()->can('list-loan-repayment'))<li><a href="{{ url('admin/due-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Due Repayments</span></a></li>@endif
        @if(backpack_user()->can('list-loan-repayment'))<li><a href="{{ url('admin/late-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Late Repayments</span></a></li>@endif
        @if(backpack_user()->can('addloanrepayment'))<li><a href='{{ backpack_url('addloanrepayment') }}'><i class="fa fa-plus-square-o"></i> <span>Add Loan Repayment</span></a></li>@endif
        @if(backpack_user()->can('loan-repayments'))<li><a href='{{ backpack_url('/report/loan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>List Repayments</span></a></li>@endif
            @if(backpack_user()->can('list-import-loan-repaymebt'))<li><a href="{{ backpack_url('import-loan-repayment/create') }}"><i class='fa fa-download'></i> <span>Import Loan Repayment</span></a></li>@endif
        {{--@if(backpack_user()->can('list-list-loan-repayment'))
            <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Loan Repayment</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(backpack_user()->can('list-loan-outstanding'))<li><a href='{{ backpack_url('loanoutstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Add Repayment</span></a></li>@endif
                    @if(backpack_user()->can('list-list-loan-repayment'))<li><a href="{{ url('admin/due-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Due Repayments</span></a></li>@endif
                    @if(backpack_user()->can('list-list-loan-repayment'))<li><a href="{{ url('admin/late-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Late Repayments</span></a></li>@endif
                    @if(backpack_user()->can('list-list-loan-repayment'))<li><a href="{{ url('api/loan-repayment-list') }}"><i class='fa fa-tag'></i> <span>Loan Repayment</span></a></li>@endif
                </ul>
            </li>
        @endif--}}
            @if(backpack_user()->can('list-paid-support-fund'))<li><a href="{{ url('admin/paid-support-fund') }}"><i class='fa fa-tag'></i> <span>Paid Support Fund</span></a></li>@endif
            @if(companyReportPart() == 'company.mkt')
               <li><a href="{{ url('admin/comming-repayment') }}"><i class='fa fa-plus-square-o'></i> <span>Coming Repayments</span></a></li>
            @endif


        </ul>
    </li>
    @endif

@if(backpack_user()->can('list-compulsory-saving-list')|| backpack_user()->can('list-compulsory-saving-active') || backpack_user()->can('list-compulsory-saving-completed') || backpack_user()->can('list-compulsory-saving-transaction') || backpack_user()->can('list-cash-withdrawal'))
    <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>COMPULSORY SAVING</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">

            @if(backpack_user()->can('list-compulsory-saving-list'))<li><a href='{{ backpack_url('compulsorysavinglist') }}'><i class='fa fa-tag'></i> <span>Compulsory Save List</span></a></li>@endif
            @if(backpack_user()->can('list-compulsory-saving-active'))<li><a href='{{ backpack_url('compulsorysavingactive') }}'><i class='fa fa-tag'></i> <span>Compulsory Save Active</span></a></li>@endif
            @if(backpack_user()->can('list-compulsory-saving-completed'))<li><a href='{{ backpack_url('compulsorysavingcompleted') }}'><i class='fa fa-tag'></i> <span>Compulsory Save Completed</span></a></li>@endif
            @if(backpack_user()->can('list-compulsory-saving-transaction'))<li><a href='{{ backpack_url('compulsory-saving-transaction') }}'><i class='fa fa-tag'></i> <span>Saving Transactions</span></a></li>@endif
            @if(backpack_user()->can('list-cash-withdrawal'))<li><a href='{{ backpack_url('cashwithdrawal') }}'><i class='fa fa-tag'></i> <span>Cash Withdrawal</span></a></li>@endif



        </ul>
    </li>
    @endif


<li class="treeview">
    <a href="#"><i class="fa fa-users"></i> <span>MANAGE SAVING</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        {{--        <li><a href='{{ backpack_url('saving-calculator/create') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Calculator</span></a></li>--}}
        {{--        @if (backpack_user()->can('list-open-saving-account'))--}}
        <li><a href='{{ backpack_url('open-saving-account/create') }}'><i class="fa fa-plus-square-o"></i> <span>Open Saving Account</span></a></li>
        {{--        @endif--}}
        {{--        <li><a href='{{ backpack_url('/') }}'><i class='fa fa-tag'></i> <span>Pending Account</span></a></li>--}}
        {{--        @if (backpack_user()->can('list-saving-activated'))--}}
        <li><a href='{{ backpack_url('/saving-activated') }}'><i class='fa fa-tag'></i> <span>Activated Account</span></a></li>
        {{--        @endif--}}
        {{--        <li><a href='{{ backpack_url('/') }}'><i class='fa fa-tag'></i> <span>Due Deposit List</span></a></li>--}}
        {{--        @if (backpack_user()->can('list-saving-deposit'))--}}
        <li><a href='{{ backpack_url('/saving-deposit/create') }}'><i class='fa fa-tag'></i> <span>Add Deposit</span></a></li>
        {{--        @endif--}}

        {{--        @if (backpack_user()->can('list-saving-withdrawal'))--}}
        <li><a href='{{ backpack_url('/saving-withdrawal/create') }}'><i class='fa fa-tag'></i> <span>Add Withdrawal</span></a></li>
        {{--        @endif--}}

        {{--        @if (backpack_user()->can('list-saving-transaction'))--}}
        <li><a href='{{ backpack_url('/saving-transaction') }}'><i class='fa fa-tag'></i> <span>Transaction List</span></a></li>
        {{--        @endif--}}
    </ul>
</li>


    @if(backpack_user()->can('list-loan-pending-transfer'))
        <li class="treeview">
            <a href="#"><i class="fa fa-bookmark"></i> <span>MANAGE TRANSFER</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                @if(backpack_user()->can('list-loan-pending-transfer'))<li><a href='{{ backpack_url('loan-pending-transfer') }}'><i class='fa fa-tag'></i> <span>Loans Transfer</span></a></li>@endif
                {{-- @if(backpack_user()->can('list-transfer'))<li><a href='{{ backpack_url('loan-transfer') }}'><i class='fa fa-tag'></i> <span>Loan Transfer</span></a></li>@endif--}}
            </ul>
        </li>
    @endif

    @if(backpack_user()->can('list-loan-product') || backpack_user()->can('list-compulsory-product') || backpack_user()->can('list-charge'))
    <li class="treeview">
        <a href="#"><i class="fa fa-money"></i> <span>MANAGE PRODUCT</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(backpack_user()->can('list-loan-product'))<li><a href='{{ backpack_url('loan-product') }}'><i class="fa fa-plus-square-o"></i><span>Loan Product</span></a></li>@endif
            @if(backpack_user()->can('list-compulsory-product'))<li><a href='{{ backpack_url('saving-product') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Product</span></a></li>@endif
            @if(backpack_user()->can('list-compulsory-product'))<li><a href='{{ backpack_url('compulsory-product') }}'><i class='fa fa-tag'></i> <span>Compulsory Product</span></a></li>@endif
            @if(backpack_user()->can('list-charge'))<li><a href='{{ backpack_url('charge') }}'><i class='fa fa-tag'></i> <span>Charge</span></a></li>@endif

            {{--<li><a href='{{ backpack_url('compulsory-product-type') }}'><i class="fa fa-plus-square-o"></i> <span>Compulsory Product Type</span></a></li>--}}
    </ul>
</li>

@endif
<!-- Account -->

<!-- Product -->
{{--

@if(backpack_user()->can('list-asset') || backpack_user()->can('list-asset-type'))
<li class="treeview">
    <a href="#"><i class="fa fa-bicycle"></i> <span>ASSET</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(backpack_user()->can('list-asset'))<li><a href='{{ backpack_url('asset') }}'><i class="fa fa-plus-square-o"></i> <span>Asset</span></a></li>@endif
        @if(backpack_user()->can('list-asset-type'))<li><a href='{{ backpack_url('asset-type') }}'><i class="fa fa-plus-square-o"></i> <span>Asset Type</span></a></li>@endif
    </ul>
</li>

@endif

--}}

{{--

@if(backpack_user()->can('list-collateral-type'))
<li class="treeview">
    <a href="#"><i class="fa fa-list"></i> <span>COLLATERAL</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(backpack_user()->can('list-collateral-type'))<li><a href="{{ backpack_url('collateraltype') }}"><i class="fa fa-plus-square-o"></i><span>Collateral Type</span></a></li>@endif
    </ul>
</li>
@endif

--}}

@if(backpack_user()->can('list-general-journal') || backpack_user()->can('list-transfer') || backpack_user()->can('list-journal-expense') || backpack_user()->can('list-account-chart') || backpack_user()->can('list-close-all') || backpack_user()->can('list-acc-class') || backpack_user()->can('list-acc-class') || backpack_user()->can('list-job') || backpack_user()->can('list-currency'))
<li class="treeview">
    <a href="#"><i class="fa fa-book"></i> <span>ACCOUNTING</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(backpack_user()->can('list-general-journal'))<li><a href="{{ backpack_url('general-journal') }}"><i class="fa fa-plus-square-o"></i><span>{{_t('General Journal')}}</span></a></li>@endif

        @if(backpack_user()->can('list-transfer'))<li><a href="{{ backpack_url('transfer') }}"><i class='fa fa-tag'></i> <span>Transfer</span></a></li>@endif

         @if(backpack_user()->can('list-journal-expense'))<li><a href="{{ backpack_url('expense') }}"><i class="fa fa-plus-square-o"></i> <span>
                        @if(companyReportPart()=='company.moeyan') Cash Out @else Expense @endif</span></a></li>
         @endif
         @if(backpack_user()->can('list-other-income'))<li><a href="{{ backpack_url('journal-profit') }}"><i class="fa fa-plus-square-o"></i> <span>
                        @if(companyReportPart()=='company.moeyan') Cash In @else Other income @endif</span></a></li>

         @endif
         @if(backpack_user()->can('list-account-chart'))<li><a href='{{ backpack_url('account-chart') }}'><i class="fa fa-plus-square-o"></i><span>Chart of Account</span></a></li>@endif
         {{--@if(backpack_user()->can('list-account-chart'))<li><a href='{{ backpack_url('account-chart-external') }}'><i class="fa fa-plus-square-o"></i><span>External Chart of Account</span></a></li>@endif--}}
         {{--@if(backpack_user()->can('list-close-all'))<li><a href="{{ backpack_url('close-all') }}"><i class='fa fa-tag'></i> <span>Close Date</span></a></li>@endif--}}
        <li class="sep-p"><hr></li>

        {{--@if(backpack_user()->can('list-acc-class'))<li><a href='{{ backpack_url('acc-class') }}'><i class="fa fa-plus-square-o"></i> <span>Class</span></a></li>@endif
        @if(backpack_user()->can('list-job'))<li><a href='{{ backpack_url('job') }}'><i class="fa fa-plus-square-o"></i> <span>Job</span></a></li>@endif--}}
        @if(backpack_user()->can('list-currency'))<li><a href='{{ backpack_url('currency') }}'><i class="fa fa-plus-square-o"></i><span>Currency</span></a></li>@endif
        @if(backpack_user()->can('list-import-journal'))<li><a href='{{ backpack_url('import-journal') }}'><i class="fa fa-plus-square-o"></i><span>Import Journal / Expense</span></a></li>@endif
        {{-- <li><a href='{{ backpack_url('currency') }}'><i class='fa fa-tag'></i> <span>Currency</span></a></li>--}}
        @if(backpack_user()->can('list-frd-account-setting'))<li><a href='{{ backpack_url('frd-account-setting/create') }}'><i class='fa fa-plus-square-o'></i><span>FRD Account Setting</span></a></li>@endif
        @if(backpack_user()->can('list-frd-account-setting2'))<li><a href='{{ backpack_url('frd-account-setting2/create') }}'><i class='fa fa-plus-square-o'></i><span>FRD Account Setting 2</span></a></li>@endif
    </ul>
</li>
@endif
@if(backpack_user()->can('list-capital'))
<li class="treeview">
    <a href="#"><i class="fa fa-bookmark"></i> <span>CAPITAL</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(backpack_user()->can('list-capital'))<li><a href='{{ backpack_url('capital') }}'><i class='fa fa-tag'></i> <span>Add Capital</span></a></li>@endif
        @if(backpack_user()->can('list-capital'))<li><a href='{{ backpack_url('capital-withdraw') }}'><i class='fa fa-tag'></i> <span>Withdraw Capital</span></a></li>@endif
        @if(backpack_user()->can('list-shareholder'))<li><a href='{{ backpack_url('shareholder') }}'><i class='fa fa-tag'></i> <span>Shareholder</span></a></li>@endif

    </ul>
</li>
@endif
@if(backpack_user()->can('list-report-accounting') || backpack_user()->can('list-report-sale') || backpack_user()->can('list-report-customer') || backpack_user()->can('list-report-payment'))
<li class="treeview">
    <a href="#"><i class="fa fa-list"></i> <span>REPORT</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        {{--<li><a href="{{ backpack_url('') }}"><i class="fa fa-circle-o"></i> <span>Borrows Report</span></a></li>--}}
        @if(backpack_user()->can('list-report-summary')) <li><a href='{{ backpack_url('summary-report') }}'><i class='fa fa-tag'></i><span>Summary Report</span></a></li>@endif
        @if(backpack_user()->can('list-report-accounting')) <li><a href='{{ backpack_url('report-accounting') }}'><i class='fa fa-tag'></i><span>Accounting Report</span></a></li>@endif

        @if(backpack_user()->can('list-report-accounting-external')) <li><a href='{{ backpack_url('report-account-external') }}'><i class='fa fa-tag'></i><span>Accounting FRD Report </span></a></li>@endif
{{--        @if(backpack_user()->can('list-report-loan')) <li><a href='{{ backpack_url('report-loan') }}'><i class='fa fa-tag'></i> <span>Loan Report</span></a></li>@endif--}}
{{--        @if(backpack_user()->can('list-report-customer'))<li><a href='{{ backpack_url('report-customer') }}'><i class='fa fa-tag'></i> <span>Client Report</span></a></li>@endif--}}
{{--        @if(backpack_user()->can('list-report-payment'))<li><a href='{{ backpack_url('report-payment') }}'><i class='fa fa-tag'></i> <span>Loan Payment Report</span></a></li>@endif--}}
{{--        @if(backpack_user()->can('list-report-payment'))<li><a href='{{ backpack_url('report-repayment') }}'><i class='fa fa-tag'></i> <span>Payment Reports</span></a></li>@endif --}}

        @if(backpack_user()->can('group-loan')) <li><a href='{{ backpack_url('group-report') }}'><i class='fa fa-tag'></i><span>Group Loan Report</span></a></li>@endif

            <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Loan Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href='{{ backpack_url('/report/client-information') }}'><i class="fa fa-plus-square-o"></i> <span>Customer Info</span></a></li>
                    <li><a href='{{ backpack_url('/report/loan-outstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Outstanding</span></a></li>
                </ul>
            </li>

            @if(backpack_user()->can('list-report-payment'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Payment Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        {{--@if(backpack_user()->can('plan-repayments')) <li><a href='{{ backpack_url('/report/plan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Repayments</span></a></li>@endif--}}
                        @if(backpack_user()->can('plan-due-repayments')) <li><a href='{{ backpack_url('/report/plan-due-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Due Repayments</span></a></li>@endif
                        @if(backpack_user()->can('plan-late-repayments'))<li><a href='{{ backpack_url('/report/plan-late-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Late Repayments</span></a></li>@endif
                        @if(backpack_user()->can('payment-deposits'))<li><a href='{{ backpack_url('/report/payment-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Payment Deposits</span></a></li>@endif
                        @if(backpack_user()->can('loan-disbursements'))<li><a href='{{ backpack_url('/report/loan-disbursements') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Disbursements</span></a></li>@endif
                        @if(backpack_user()->can('loan-repayments'))<li><a href='{{ backpack_url('/report/loan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Repayments</span></a></li>@endif
                        @if(backpack_user()->can('plan-disbursements'))<li><a href='{{ backpack_url('/report/plan-disbursements') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Disbursements</span></a></li>@endif

                    </ul>
                </li>
            @endif

            @if (_can('list-compulsory-saving-deposit') || _can('list-compulsory-saving-withdrawal') || _can('list-compulsory-saving-accrued-interest'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Compulsory Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-compulsory-saving-deposit')) <li><a href='{{ backpack_url('/report/compulsory-saving-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>@endif
                        @if (_can('list-compulsory-saving-withdrawal'))<li><a href='{{ backpack_url('/report/compulsory-saving-withdrawals') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawals</span></a></li>@endif
                        @if (_can('list-compulsory-saving-accrued-interest'))<li><a href='{{ backpack_url('/report/com-saving-accrued-interests') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Accrued Interests</span></a></li>@endif
                    </ul>
                </li>
            @endif

            @if (_can('list-saving-deposit'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-saving-deposit')) <li><a href='{{ backpack_url('/report/saving-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>@endif
                        @if (_can('list-saving-withdrawal'))<li><a href='{{ backpack_url('/report/saving-withdrawals') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawals</span></a></li>@endif
                        @if (_can('list-saving-interest'))<li><a href='{{ backpack_url('/report/saving-interests') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Interests</span></a></li>@endif
                    </ul>
                </li>
            @endif

            @if(backpack_user()->can('list-report-officer'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Officer Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if(backpack_user()->can('officer-disbursement')) <li><a href='{{ backpack_url('/report/officer-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>Disbursement By C.O</span></a></li>@endif
                        @if(backpack_user()->can('officer-collection'))<li><a href='{{ backpack_url('/report/officer-collection') }}'><i class="fa fa-plus-square-o"></i> <span>C.O Collections</span></a></li>@endif
                        @if(backpack_user()->can('officer-transaction'))<li><a href='{{ backpack_url('/report/officer-transaction') }}'><i class="fa fa-plus-square-o"></i> <span>C.O Transactions</span></a></li>@endif
                        @if(backpack_user()->can('staff-performance'))<li><a href='{{ backpack_url('/report/staff-performance') }}'><i class="fa fa-plus-square-o"></i> <span>Staff Performance</span></a></li>@endif
                    </ul>
                </li>
            @endif
    </ul>
</li>

@endif

@if( backpack_user()->can('list-user') || backpack_user()->can('list-role') || backpack_user()->can('list-permission'))
<li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>USER</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">


        @if(backpack_user()->can('list-user'))<li><a href="{{ backpack_url('user') }}"><i class="fa fa-tag"></i> <span>Users</span></a></li>@endif
        @if(backpack_user()->can('list-role'))<li><a href="{{ backpack_url('role') }}"><i class="fa fa-tag"></i> <span>Roles</span></a></li>@endif
        {{--@if(backpack_user()->can('list-permission'))<li><a href="{{ backpack_url('permission') }}"><i class="fa fa-tag"></i> <span>Permissions</span></a></li>@endif--}}
    </ul>
</li>
@endif
@if(backpack_user()->can('list-setting'))
<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>GENERAL SETTING</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        {{--<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-tag"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>--}}
       @if('list-setting') <li><a href='{{ backpack_url('setting') }}'><i class='fa fa-tag'></i> <span>Settings</span></a></li>@endif


        @if(backpack_user()->can('list-branch'))<li><a href="{{ backpack_url('branch') }}"><i class='fa fa-tag'></i> <span>BRANCH</span></a></li>@endif
        @if(backpack_user()->can('list-center-leader'))<li><a href="{{ backpack_url('centerleader') }}"><i class='fa fa-tag'></i> <span>CENTER</span></a></li>@endif
        @if(backpack_user()->can('list-holiday-schedule'))<li><a href="{{ backpack_url('holidayschedule') }}"><i class='fa fa-tag'></i> <span>HOLIDAY</span></a></li>@endif
        @if(backpack_user()->can('list-audit'))<li><a href="{{ backpack_url('audit') }}"><i class='fa fa-area-chart'></i> <span>AUDIT TRAIL</span></a></li>@endif

    </ul>
</li>
@endif
<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE INVENTORY</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href='{{ backpack_url('inventory') }}'><i class='fa fa-tag'></i> <span>Inventory</span></a></li>
        <li><a href='{{ backpack_url('product-category') }}'><i class='fa fa-tag'></i> <span>Category</span></a></li>
        <li><a href='{{ backpack_url('warehouse') }}'><i class='fa fa-tag'></i> <span>Warehouse</span></a></li>
        <li><a href='{{ backpack_url('unit') }}'><i class='fa fa-tag'></i> <span>Unit</span></a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE PURCHASE</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href='{{ backpack_url('purchase-order') }}'><i class='fa fa-tag'></i> <span>Purchase Order</span></a></li>
        <li><a href='{{ backpack_url('authorize-purchase-order') }}'><i class='fa fa-tag'></i> <span>Authorize Purchase Order</span></a></li>
        <li><a href='{{ backpack_url('approve-purchase-order') }}'><i class='fa fa-tag'></i> <span>Approved Purchase Order</span></a></li>
        <li><a href='{{ backpack_url('bill') }}'><i class='fa fa-tag'></i> <span>Enter Bill</span></a></li>
        <li><a href='{{ backpack_url('goods-received') }}'><i class='fa fa-tag'></i> <span>Goods Received</span></a></li>
        <li><a href='{{ backpack_url('purchase-return') }}'><i class='fa fa-tag'></i> <span>Purchase Return</span></a></li>
        <li><a href='{{ backpack_url('supply') }}'><i class='fa fa-tag'></i> <span>Supplier</span></a></li>
        <li><a href='{{ backpack_url('payment') }}'><i class='fa fa-tag'></i> <span>Supplier Payment</span></a></li>
        <li><a href='{{ backpack_url('payment-list') }}'><i class='fa fa-tag'></i> <span>Supplier Payment List</span></a></li>
    </ul>
</li>
@endif

