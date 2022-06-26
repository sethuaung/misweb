
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li hidden><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i><span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li><a href="{{ backpack_url('dashboard_mkt') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>

@if(_can('create-client') || _can('list-client') || _can('list-client-center-leader') || _can('create-guarantor') || _can('list-survey') || _can('list-ownership') || _can('list-ownership-farmland') || _can('list-import-client'))
    <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>MANAGE CLIENT</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">



            @if (_can('list-authorize-client-pending'))  <li><a href='{{ backpack_url('authorize-client-pending') }}'><i class="fa fa-plus-square-o"></i> <span>Authorize Client</span></a></li>@endif
            @if (_can('list-approve-client-pending'))  <li><a href='{{ backpack_url('approve-client-pending') }}'><i class="fa fa-plus-square-o"></i> <span>Approve Client</span></a></li>@endif
            @if (_can('create-client'))  <li><a href='{{ backpack_url('client/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Client</span></a></li>@endif

            <li class="treeview">
                <a href="#"><i class="fa fa-user"></i> <span>Client List</span> <i class="fa fa-angle-left pull-right"></i></a>

                <ul class="treeview-menu">

                    @if (_can('list-client'))<li><a href="{{ backpack_url('client') }}"><i class="fa fa-user"></i> All Client List</a></li>@endif
                    @if (_can('list-client'))<li><a href="{{ backpack_url('active-member-client') }}"><i class="fa fa-user"></i> Active Member</a></li>@endif
                    @if (_can('list-client'))<li><a href="{{ backpack_url('drop_out-member-client') }}"><i class="fa fa-user"></i> Drop-out Member</a></li>@endif
                    @if (_can('list-client'))<li><a href="{{ backpack_url('rejoin-member-client') }}"><i class="fa fa-user"></i> Rejoin Member</a></li>@endif
                    @if (_can('list-client'))<li><a href="{{ backpack_url('substitute-member-client') }}"><i class="fa fa-user"></i> Substitute Member</a></li>@endif
                    
                    @if (_can('list-client'))<li><a href="{{ backpack_url('dead-member-client') }}"><i class="fa fa-user"></i> Dead Member</a></li>@endif

                </ul>
            </li>
            @if (_can('list-client')) <li><a href='{{ backpack_url('reject-client') }}'><i class="fa fa-user"></i> <span>Reject Client</span></a>@endif
            @if (_can('list-client-center-leader')) <li><a href='{{ backpack_url('clientcenterleader') }}'><i class="fa fa-plus-square-o"></i> <span>Center Leader</span></a>@endif
            @if(companyReportPart() == 'company.moeyan')
                <li><a href='{{ backpack_url('inspector/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Inspector</span></a></li>
                <li><a href='{{ backpack_url('inspector') }}'><i class="fa fa-plus-square-o"></i> <span>Inspector List</span></a></li>
            @endif
            @if (_can('create-guarantor'))<li><a href='{{ backpack_url('guarantor/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Guarantor</span></a></li>@endif
            @if (_can('list-guarantor'))<li><a href='{{ backpack_url('guarantor') }}'><i class="fa fa-plus-square-o"></i> <span>Guarantor List</span></a></li>@endif
            <li><a href='{{ backpack_url('/add-address') }}'><i class="fa fa-plus-square-o"></i> <span>Add Address</span></a></li>
            <li class="sep-p"><hr></li>

            @if (_can('list-nrc-prefix'))<li><a href="{{ backpack_url('nrc-prefix') }}"><i class='fa fa-tag'></i> <span>NRC Prefix</span></a></li>@endif

            @if (_can('list-survey'))<li><a href="{{ backpack_url('survey') }}"><i class='fa fa-tag'></i> <span>Survey</span></a></li>@endif
            @if (_can('list-ownership'))<li><a href="{{ backpack_url('ownership') }}"><i class='fa fa-tag'></i> <span>Ownership</span></a></li>@endif
            @if (_can('list-ownership-farmland'))<li><a href="{{ backpack_url('ownershipfarmland') }}"><i class='fa fa-tag'></i> <span>Ownership Farmland</span></a></li>@endif
            @if(_can('list-customer-group'))<li><a href="{{ backpack_url('customergroup') }}"><i class='fa fa-tag'></i> <span>Customer Type</span></a></li>@endif
            @if(_can('list-import-client'))<li><a href="{{ backpack_url('import-client/create') }}"><i class='fa fa-download'></i> <span>Import Client</span></a></li>@endif
        </ul>
    </li>
@endif

@if(_can('create-loan-calculator') || _can('create-loan-disbursement') || _can('list-loan-group') || _can('create-loan-calculator') || _can('ist-loan-group-deposit') || _can('list-group-disbursement') || _can('list-group-payment'))
    <li class="treeview">
        <a href="#"><i class="fa fa-cog"></i> <span>MANAGE LOAN</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">

            @if (_can('create-loan-calculator')) <li><a href='{{ backpack_url('loan-calculator/create') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Calculator</span></a></li>@endif
            @if (_can('create-loan-disbursement'))<li><a href="{{ backpack_url('loandisbursement/create') }}"><i class='fa fa-tag'></i> <span>Create Loan</span></a></li>@endif
            @if(_can('list-loan-group'))   <li><a href="{{ backpack_url('grouploan') }}"><i class="fa fa-plus-square-o"></i> <span>Add Group Loan</span></a></li>@endif

            @if (_can('list-loan-group'))
                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Group Loan</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('create-loan-calculator')) <li><a href='{{ backpack_url('group-loan-approve') }}'><i class="fa fa-plus-square-o"></i> <span>Group Pending Approval</span></a></li>@endif
                        @if (_can('list-loan-group-deposit'))<li><a href='{{ backpack_url('group-loan-deposit') }}'><i class="fa fa-plus-square-o"></i> <span>Group Deposit</span></a></li>@endif
                        @if (_can('list-group-disbursement'))<li><a href='{{ backpack_url('group-dirseburse') }}'><i class="fa fa-plus-square-o"></i> <span>Group Disburse</span></a></li>@endif
                        @if (_can('list-group-payment'))<li><a href='{{ backpack_url('group-repayment-new') }}'><i class="fa fa-plus-square-o"></i> <span>Group Repayment</span></a></li>@endif
                        @if (_can('list-due-group-payment'))<li><a href='{{ backpack_url('group-due-repayment') }}'><i class="fa fa-plus-square-o"></i> <span>Group Due Repayment</span></a></li>@endif
                        {{--@if (_can('list-group-due-repayment'))<li><a href='{{ backpack_url('group-due-repayment') }}'><i class="fa fa-plus-square-o"></i> <span>Group Due Repayment</span></a></li>@endif--}}
                    </ul>
                </li>
            @endif

            @if (_can('list-loan-disbursement'))<li><a href="{{ backpack_url('loandisbursement') }}"><i class='fa fa-tag'></i> <span>View All Loan</span><span class="pull-right-container"><span class="label label-info pull-right">View all loans</span></span></a></li>@endif
            @if (_can('list-loan-disbursement'))<li><a href="{{ backpack_url('loan-disbursement-branch') }}"><i class='fa fa-tag'></i> <span>Loan Change Branch</span></a></li>@endif
            @if (_can('list-disbursement-pending-approval'))<li><a href="{{ backpack_url('disbursependingapproval') }}"><i class='fa fa-tag'></i> <span>Pending Approval</span><span class="pull-right-container"><span class="label label-warning pull-right">Pending</span></span></a></li>@endif
            @if (_can('list-disbursement-awaiting'))<li><a href="{{ backpack_url('disburseawaiting') }}"><i class='fa fa-tag'></i> <span>Loan Approved</span><span class="pull-right-container"><span class="label label-success pull-right">Approved</span></span></a></li>@endif
            @if (_can('list-loan-outstanding')) <li><a href="{{ backpack_url('loanoutstanding') }}"><i class='fa fa-tag'></i> <span>Loan Activated</span><span class="pull-right-container"><span class="label label-success pull-right">Activated</span></span></a></li>@endif
            @if (_can('list-disbursement-closed'))<li><a href="{{ backpack_url('disburseclosed') }}"><i class='fa fa-tag'></i> <span>Loan Completed</span><span class="pull-right-container"><span class="label label-success pull-right">Closed</span></span></a></li>@endif
            @if (_can('list-disbursement-decline'))<li><a href="{{ backpack_url('disbursedeclined') }}"><i class='fa fa-tag'></i> <span>Loan Declined</span><span class="pull-right-container"><span class="label label-danger pull-right">Declined</span></span></a></li>@endif
            @if (_can('list-disbursement-decline'))<li><a href="{{ backpack_url('disbursecanceled') }}"><i class='fa fa-tag'></i> <span>Loan Canceled</span><span class="pull-right-container"><span class="label label-danger pull-right">Canceled</span></span></a></li>@endif
            {{--@if (_can('list-disbursement-withdrawn'))<li><a href="{{ backpack_url('disbursewithdrawn') }}"><i class='fa fa-tag'></i> <span>Loan Withdrawn</span><span class="pull-right-container"><span class="label label-danger pull-right">{{\App\Models\DisburseWithdrawn::count('id')}}</span></span></a></li>@endif--}}
            @if (_can('list-disbursement-write-off'))<li><a href="{{ backpack_url('disbursewrittenoff') }}"><i class='fa fa-tag'></i> <span>Loan Write Off</span><span class="pull-right-container"><span class="label label-danger pull-right">Write-off</span></span></a></li>@endif
            <li><a href="{{ backpack_url('prepaidloan') }}"><i class='fa fa-tag'></i> <span>Prepaid Loan</span><span class="pull-right-container"><span class="label label-primary pull-right">Prepaid</span></span></a></li>



            {{--@if (_can('create-disbursement-deposit'))<li><a href='{{ backpack_url('loandisburesementdepositu/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Deposits</span></a></li>@endif--}}
            {{--@if (_can('list-disbursement-deposit-u'))<li><a href='{{ backpack_url('loandisburesementdepositu') }}'><i class="fa fa-plus-square-o"></i> <span>List Deposits</span></a></li>@endif--}}
            {{--@if (_can('list-disbursement-pending'))<li><a href='{{ backpack_url('disbursementpending') }}'><i class="fa fa-plus-square-o"></i> <span>List Pending Disbursement</span></a></li>@endif--}}
            {{--@if (_can('create-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Disbursement</span></a></li>@endif--}}
            {{--@if (_can('list-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>List Disbursement</span></a></li>@endif--}}
            {{--@if (_can('list-loan-outstanding'))<li><a href='{{ backpack_url('loanoutstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Add Repayment</span></a></li>@endif--}}



            {{--<li><a href='{{ backpack_url('working-status') }}'><i class='fa fa-tag'></i> <span>Working Status</span></a></li>--}}

            {{--<li><a href='{{ backpack_url('default-saving-deposit') }}'><i class='fa fa-tag'></i> <span>Default Saving Deposit</span></a></li>--}}
            {{--<li><a href='{{ backpack_url('default-saving-interest') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest</span></a></li>--}}


            {{--<li><a href='{{ backpack_url('default-saving-interest-payable') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest Payable</span></a></li>--}}
            {{--<li><a href='{{ backpack_url('default-saving-withdrawal') }}'><i class='fa fa-tag'></i> <span>Default Saving Withdrawal</span></a></li>--}}
            {{--<li><a href='{{ backpack_url('default-saving-inter-withdrawal') }}'><i class='fa fa-tag'></i> <span>Default Saving Interest Withdrawal</span></a></li>--}}

            <li class="sep-p"><hr></li>

            @if (_can('list-loan-objective')) <li><a href="{{ backpack_url('loanobjective') }}"><i class='fa fa-tag'></i> <span>Loan Objective</span></a></li>@endif
            @if (_can('list-transaction-type'))<li><a href="{{ backpack_url('transactiontype') }}"><i class='fa fa-tag'></i> <span>Transaction Type</span></a></li>@endif
            @if(_can('list-import-loan'))<li><a href="{{ backpack_url('import-loan/create') }}"><i class='fa fa-download'></i> <span>Import Loans</span></a></li>@endif

        </ul>
    </li>
@endif


{{-- <li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE LEASING</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('product') }}"><i class='fa fa-tag'></i> <span>Products</span></a></li>
        <li><a href="{{ backpack_url('loan-item') }}"><i class='fa fa-tag'></i> <span>Loan Products</span></a></li>
        <li><a href="{{ backpack_url('loan-item-pending') }}"><i class='fa fa-tag'></i> <span>Loan Pending</span></a></li>
        <li><a href="{{ backpack_url('loan-item-approved') }}"><i class='fa fa-tag'></i> <span>Loan Approved</span></a></li>
        <li><a href="{{ backpack_url('list-deposit-loan-item') }}"><i class='fa fa-tag'></i> <span>Loan Deposits</span></a></li>
        <li><a href="{{ backpack_url('list-disbursement-loan-item') }}"><i class='fa fa-tag'></i> <span>Loan Disbursements</span></a></li>
        <li><a href="{{ backpack_url('loan-item-activated') }}"><i class='fa fa-tag'></i> <span>Loan Activated</span></a></li>
    </ul>
</li> --}}
@if(_can('list-deposit-pending') || _can('create-loan-disbursement-deposit-u') || _can('list-loan-outstanding') || _can('list-list-loan-repayment') || _can('list-paid-support-fund'))
    <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>MANAGE PAYMENT</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">

            @if (_can('list-deposit-pending'))
                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Deposit Payment</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-deposit-pending'))<li><a href='{{ backpack_url('depositpending') }}'><i class="fa fa-plus-square-o"></i> <span>Pending Deposits</span></a></li>@endif
                        @if (_can('create-loan-disbursement-deposit-u'))<li><a href='{{ backpack_url('loandisburesementdepositu/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Deposit</span></a></li>@endif
                        @if (_can('list-pending-deposit'))<li><a href='{{ backpack_url('loandisburesementdepositu') }}'><i class="fa fa-plus-square-o"></i> <span>List Deposits</span></a></li>@endif

                    </ul>
                </li>
            @endif

            @if (_can('list-disbursement-pending'))
                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Paid Disbursement</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-disbursement-pending'))<li><a href='{{ backpack_url('disbursementpending') }}'><i class="fa fa-plus-square-o"></i> <span>Pending Disbursements</span></a></li>@endif
                        @if (_can('list-disbursement-deposit-u'))<li><a href='{{ backpack_url('my-paid-disbursement/create') }}'><i class="fa fa-plus-square-o"></i> <span>Add Disbursement</span></a></li>@endif
                        @if (_can('list-my-paid-disbursement'))<li><a href='{{ backpack_url('my-paid-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>List Disbursements</span></a></li>@endif
                    </ul>
                </li>
            @endif



            @if (_can('list-authorize-loan-repayment'))<li><a href="{{ backpack_url('authorize-loan-payment') }}"><i class='fa fa-tag'></i> <span>Authorize Repayments</span></a></li>@endif
            @if (_can('list-approve-loan-repayment'))<li><a href="{{ backpack_url('approve-loan-payment') }}"><i class='fa fa-tag'></i> <span>Approve Repayments</span></a></li>@endif
            @if (_can('list-loan-repayment'))<li><a href="{{ url('admin/due-repayment-list') }}"><i class='fa fa-tag'></i> <span>Due Repayments</span></a></li>@endif
            @if (_can('list-loan-repayment'))<li><a href="{{ url('admin/late-repayment-list') }}"><i class='fa fa-tag'></i> <span>Late Repayments</span></a></li>@endif
            @if (_can('addloanrepayment'))<li><a href='{{ backpack_url('addloanrepayment') }}'><i class="fa fa-plus-square-o"></i> <span>Add Loan Repayment</span></a></li>@endif
            @if (_can('loan-repayments'))<li><a href='{{ backpack_url('/report/loan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>List Repayments</span></a></li>@endif
            @if(_can('list-import-loan-repaymebt'))<li><a href="{{ backpack_url('import-loan-repayment/create') }}"><i class='fa fa-download'></i> <span>Import Loan Repayments</span></a></li>@endif
            {{--@if (_can('list-list-loan-repayment'))
                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Loan Repayment</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-loan-outstanding'))<li><a href='{{ backpack_url('loanoutstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Add Repayment</span></a></li>@endif
                        @if (_can('list-list-loan-repayment'))<li><a href="{{ url('admin/due-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Due Repayments</span></a></li>@endif
                        @if (_can('list-list-loan-repayment'))<li><a href="{{ url('admin/late-repayment-list') }}"><i class='fa fa-tag'></i> <span>List Late Repayments</span></a></li>@endif
                        @if (_can('list-list-loan-repayment'))<li><a href="{{ url('api/loan-repayment-list') }}"><i class='fa fa-tag'></i> <span>Loan Repayment</span></a></li>@endif
                    </ul>
                </li>
            @endif--}}
            @if (_can('list-paid-support-fund'))<li><a href="{{ url('admin/paid-support-fund') }}"><i class='fa fa-tag'></i> <span>Paid Support Funds</span></a></li>@endif
            <li><a href="{{ url('admin/comming-repayment') }}"><i class='fa fa-plus-square-o'></i> <span>Coming Repayments</span></a></li>
        </ul>
    </li>
@endif

@if(_can('list-compulsory-saving-list')|| _can('list-compulsory-saving-active') || _can('list-compulsory-saving-completed') || _can('list-compulsory-saving-transaction') || _can('list-cash-withdrawal'))
    <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>COMPULSORY SAVING</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">

            @if (_can('list-compulsory-saving-list'))<li><a href='{{ backpack_url('compulsorysavinglist') }}'><i class='fa fa-tag'></i> <span>Compulsories List</span></a></li>@endif
            @if(_can('list-compulsory-saving-active'))<li><a href='{{ backpack_url('compulsorysavingactive') }}'><i class='fa fa-tag'></i> <span>Compulsory Active</span></a></li>@endif
            @if(_can('list-compulsory-saving-completed'))<li><a href='{{ backpack_url('compulsorysavingcompleted') }}'><i class='fa fa-tag'></i> <span>Compulsory Completed</span></a></li>@endif
            @if(_can('list-compulsory-saving-transaction'))<li><a href='{{ backpack_url('compulsory-saving-transaction') }}'><i class='fa fa-tag'></i> <span>Compulsory Transactions</span></a></li>@endif
            @if(_can('list-cash-withdrawal'))<li><a href='{{ backpack_url('cashwithdrawal') }}'><i class='fa fa-tag'></i> <span>Cash Withdrawals</span></a></li>@endif
            <li><a href="{{ backpack_url('import-compulsory-saving/create') }}"><i class='fa fa-download'></i> <span>Import  Saving Withdrawal</span></a></li>


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


@if(_can('list-loan-pending-transfer'))
    <li class="treeview">
        <a href="#"><i class="fa fa-bookmark"></i> <span>MANAGE TRANSFER</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(_can('list-loan-pending-transfer'))<li><a href='{{ backpack_url('loan-pending-transfer') }}'><i class='fa fa-tag'></i> <span>Loans Transfer</span></a></li>@endif
            {{-- @if(_can('list-transfer'))<li><a href='{{ backpack_url('loan-transfer') }}'><i class='fa fa-tag'></i> <span>Loan Transfer</span></a></li>@endif--}}
        </ul>
    </li>
@endif

@if(_can('list-loan-product') || _can('list-compulsory-product') || _can('list-charge'))
    <li class="treeview">
        <a href="#"><i class="fa fa-money"></i> <span>MANAGE PRODUCT</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(_can('list-loan-product'))<li><a href='{{ backpack_url('loan-product') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Products</span></a></li>@endif
            @if(_can('list-compulsory-product'))<li><a href='{{ backpack_url('saving-product') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Products</span></a></li>@endif
            @if(_can('list-compulsory-product'))<li><a href='{{ backpack_url('compulsory-product') }}'><i class='fa fa-tag'></i> <span>Compulsory Products</span></a></li>@endif
            @if(_can('list-charge'))<li><a href='{{ backpack_url('charge') }}'><i class='fa fa-tag'></i> <span>Service Charges</span></a></li>@endif

            {{--<li><a href='{{ backpack_url('compulsory-product-type') }}'><i class="fa fa-plus-square-o"></i> <span>Compulsory Product Type</span></a></li>--}}
        </ul>
    </li>

@endif
<!-- Account -->

<!-- Product -->
{{--

@if(_can('list-asset') || _can('list-asset-type'))
<li class="treeview">
    <a href="#"><i class="fa fa-bicycle"></i> <span>ASSET</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(_can('list-asset'))<li><a href='{{ backpack_url('asset') }}'><i class="fa fa-plus-square-o"></i> <span>Asset</span></a></li>@endif
        @if(_can('list-asset-type'))<li><a href='{{ backpack_url('asset-type') }}'><i class="fa fa-plus-square-o"></i> <span>Asset Type</span></a></li>@endif
    </ul>
</li>

@endif

--}}

{{--

@if(_can('list-collateral-type'))
<li class="treeview">
    <a href="#"><i class="fa fa-list"></i> <span>COLLATERAL</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(_can('list-collateral-type'))<li><a href="{{ backpack_url('collateraltype') }}"><i class="fa fa-plus-square-o"></i><span>Collateral Type</span></a></li>@endif
    </ul>
</li>
@endif

--}}

@if(_can('list-general-journal') || _can('list-transfer') || _can('list-journal-expense') || _can('list-account-chart') || _can('list-close-all') || _can('list-acc-class') || _can('list-acc-class') || _can('list-job') || _can('list-currency'))
    <li class="treeview">
        <a href="#"><i class="fa fa-book"></i> <span>MANAGE ACCOUNTING</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(_can('list-general-journal'))<li><a href="{{ backpack_url('general-journal') }}"><i class="fa fa-plus-square-o"></i><span>{{_t('General Journal')}}</span></a></li>@endif

            @if(_can('list-transfer'))<li><a href="{{ backpack_url('transfer') }}"><i class='fa fa-tag'></i> <span>Transfers</span></a></li>@endif

            @if(_can('list-journal-expense'))<li><a href="{{ backpack_url('expense') }}"><i class="fa fa-plus-square-o"></i> <span>
                        @if(companyReportPart()=='company.moeyan') Cash Out @else Expense @endif</span></a></li>
            @endif
            @if(_can('list-other-income'))<li><a href="{{ backpack_url('journal-profit') }}"><i class="fa fa-plus-square-o"></i> <span>
                        @if(companyReportPart()=='company.moeyan') Cash In @else Other income @endif</span></a></li>

            @endif
            @if(_can('list-account-chart'))<li><a href='{{ backpack_url('account-chart') }}'><i class="fa fa-plus-square-o"></i><span>Chart of Account</span></a></li>@endif
            {{--@if(_can('list-account-chart'))<li><a href='{{ backpack_url('account-chart-external') }}'><i class="fa fa-plus-square-o"></i><span>External Chart of Account</span></a></li>@endif--}}
            {{--@if(_can('list-close-all'))<li><a href="{{ backpack_url('close-all') }}"><i class='fa fa-tag'></i> <span>Close Date</span></a></li>@endif--}}
            <li class="sep-p"><hr></li>

            {{--@if(_can('list-acc-class'))<li><a href='{{ backpack_url('acc-class') }}'><i class="fa fa-plus-square-o"></i> <span>Class</span></a></li>@endif
            @if(_can('list-job'))<li><a href='{{ backpack_url('job') }}'><i class="fa fa-plus-square-o"></i> <span>Job</span></a></li>@endif--}}
            @if(_can('list-currency'))<li><a href='{{ backpack_url('currency') }}'><i class="fa fa-plus-square-o"></i><span>Currency</span></a></li>@endif
            @if(_can('list-import-journal'))<li><a href='{{ backpack_url('import-journal') }}'><i class="fa fa-plus-square-o"></i><span>Import Journal / Expense</span></a></li>@endif
            {{-- <li><a href='{{ backpack_url('currency') }}'><i class='fa fa-tag'></i> <span>Currency</span></a></li>--}}
            {{--            @if(_can('list-frd-account-setting'))<li><a href='{{ backpack_url('frd-account-setting/create') }}'><i class='fa fa-plus-square-o'></i><span>FRD Account Setting</span></a></li>@endif--}}
            @if(_can('list-frd-account'))<li><a href='{{ backpack_url('frd-account') }}'><i class='fa fa-plus-square-o'></i><span>FRD Accounts</span></a></li>@endif
        </ul>
    </li>
@endif
@if(_can('list-capital'))
    <li class="treeview">
        <a href="#"><i class="fa fa-bookmark"></i> <span>MANAGE CAPITAL</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(_can('list-capital'))<li><a href='{{ backpack_url('capital') }}'><i class='fa fa-tag'></i> <span>Add Capital</span></a></li>@endif
            @if(_can('list-capital'))<li><a href='{{ backpack_url('capital-withdraw') }}'><i class='fa fa-tag'></i> <span>Withdraw Capital</span></a></li>@endif
            @if(_can('list-shareholder'))<li><a href='{{ backpack_url('shareholder') }}'><i class='fa fa-tag'></i> <span>Shareholder</span></a></li>@endif

        </ul>
    </li>
@endif
@if(_can('list-report-accounting') || _can('list-report-sale') || _can('list-report-customer') || _can('list-report-payment'))
    <li class="treeview">
        <a href="#"><i class="fa fa-list"></i> <span>REPORTS</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            {{--<li><a href="{{ backpack_url('') }}"><i class="fa fa-circle-o"></i> <span>Borrows Report</span></a></li>--}}
            @if(_can('list-report-summary')) <li><a href='{{ backpack_url('summary-report') }}'><i class='fa fa-tag'></i><span>Summary Report</span></a></li>@endif
            @if(_can('list-report-accounting')) <li><a href='{{ backpack_url('report-accounting') }}'><i class='fa fa-tag'></i><span>Accounting Report</span></a></li>@endif

            @if(_can('list-report-accounting-external')) <li><a href='{{ backpack_url('report-account-external') }}'><i class='fa fa-tag'></i><span>Accounting FRD Report</span></a></li>@endif
            {{--        @if(_can('list-report-loan')) <li><a href='{{ backpack_url('report-loan') }}'><i class='fa fa-tag'></i> <span>Loan Report</span></a></li>@endif--}}
            {{--        @if(_can('list-report-customer'))<li><a href='{{ backpack_url('report-customer') }}'><i class='fa fa-tag'></i> <span>Client Report</span></a></li>@endif--}}
            {{--        @if(_can('list-report-payment'))<li><a href='{{ backpack_url('report-payment') }}'><i class='fa fa-tag'></i> <span>Loan Payment Report</span></a></li>@endif--}}
            {{--        @if(_can('list-report-payment'))<li><a href='{{ backpack_url('report-repayment') }}'><i class='fa fa-tag'></i> <span>Payment Reports</span></a></li>@endif --}}

            @if(_can('group-loan')) <li><a href='{{ backpack_url('group-report') }}'><i class='fa fa-tag'></i><span>Group Loan Report</span></a></li>@endif

            <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Loan Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href='{{ backpack_url('/report/client-information') }}'><i class="fa fa-plus-square-o"></i> <span>Customer Info</span></a></li>
                    <li><a href='{{ backpack_url('/report/loan-outstanding') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Outstanding</span></a></li>
                    <li><a href='{{ backpack_url('/report/prepaid-loan') }}'><i class="fa fa-plus-square-o"></i> <span>Prepaid Loan</span></a></li>
                    <li><a href='{{ backpack_url('/report/dead-member-report') }}'><i class="fa fa-plus-square-o"></i> <span>Dead Member Report</span></a></li>
                </ul>
            </li>

            @if (_can('list-report-payment'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Payment Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        {{--@if (_can('plan-repayments')) <li><a href='{{ backpack_url('/report/plan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Repayments</span></a></li>@endif--}}
                        @if (_can('plan-due-repayments')) <li><a href='{{ backpack_url('/report/plan-due-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Due Repayments</span></a></li>@endif
                        @if (_can('plan-late-repayments'))<li><a href='{{ backpack_url('/report/plan-late-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Late Repayments</span></a></li>@endif
                        @if (_can('payment-deposits'))<li><a href='{{ backpack_url('/report/payment-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Payment Deposits</span></a></li>@endif
                        @if (_can('loan-disbursements'))<li><a href='{{ backpack_url('/report/loan-disbursements') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Disbursements</span></a></li>@endif
                        @if (_can('loan-repayments'))<li><a href='{{ backpack_url('/report/loan-repayments') }}'><i class="fa fa-plus-square-o"></i> <span>Loan Repayments</span></a></li>@endif
                        @if (_can('plan-disbursements'))<li><a href='{{ backpack_url('/report/plan-disbursements') }}'><i class="fa fa-plus-square-o"></i> <span>Plan Disbursements</span></a></li>@endif

                    </ul>
                </li>
            @endif

            {{--@if (_can('list-report-saving'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('saving-report')) <li><a href='{{ backpack_url('/report/saving-report') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Report</span></a></li>@endif
                        @if (_can('saving-deposit')) <li><a href='{{ backpack_url('/report/saving-deposit') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>@endif
                        @if (_can('saving-withdrawal'))<li><a href='{{ backpack_url('/report/saving-withdrawal') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawal</span></a></li>@endif
                        @if (_can('saving-accrued-interest'))<li><a href='{{ backpack_url('/report/saving-accrued-interest') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Accrued Interest</span></a></li>@endif
                    </ul>
                </li>
            @endif--}}
            @if (_can('list-saving-deposit'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-report-saving')) <li><a href="{{backpack_url('/savings-report')}}?type=normal"><i class="fa fa-plus-square-o"></i> <span>Saving Report</span></a></li>@endif
                        @if (_can('list-report-saving')) <li><a href="{{backpack_url('/savings-report')}}?type=customer"><i class="fa fa-plus-square-o"></i> <span>Customer Saving Report</span></a></li>@endif
                        @if (_can('list-report-saving')) <li><a href='{{ backpack_url('/interest-cal-report') }}'><i class="fa fa-plus-square-o"></i> <span>Interest Calculation</span></a></li>@endif
                        @if (_can('list-saving-deposit')) <li><a href='{{ backpack_url('/report/saving-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>@endif
                        @if (_can('list-saving-withdrawal'))<li><a href='{{ backpack_url('/report/saving-withdrawals') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawals</span></a></li>@endif
                        @if (_can('list-saving-interest'))<li><a href='{{ backpack_url('/report/saving-interests') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Interests</span></a></li>@endif
                    </ul>
                </li>
            @endif


        @if (_can('list-report-saving') || _can('list-compulsory-saving-deposit') || _can('list-compulsory-saving-withdrawal') || _can('list-compulsory-saving-accrued-interest'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Compulsory Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href='{{ backpack_url('/report/saving-report') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Report</span></a></li>
                        <li><a href='{{ backpack_url('/report/compulsory-saving-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>
                        <li><a href='{{ backpack_url('/report/compulsory-saving-withdrawals') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawals</span></a></li>
                        <li><a href='{{ backpack_url('/report/com-saving-accrued-interests') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Accrued Interests</span></a></li>
                    </ul>
                </li>
            @endif

           {{-- @if (_can('list-saving-deposit'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Saving Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('list-saving-deposit')) <li><a href='{{ backpack_url('/report/saving-deposits') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Deposits</span></a></li>@endif
                        @if (_can('list-saving-withdrawal'))<li><a href='{{ backpack_url('/report/saving-withdrawals') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Withdrawals</span></a></li>@endif
                        --}}{{--                        @if (backpack_user()->can('saving-accrued-interest'))<li><a href='{{ backpack_url('/report/saving-accrued-interests') }}'><i class="fa fa-plus-square-o"></i> <span>Saving Accrued Interests</span></a></li>@endif--}}{{--
                    </ul>
                </li>
            @endif--}}

            @if (_can('list-report-officer'))
                <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Staff Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if (_can('officer-disbursement')) <li><a href='{{ backpack_url('/report/officer-disbursement') }}'><i class="fa fa-plus-square-o"></i> <span>Disbursement By C.O</span></a></li>@endif
                        @if (_can('officer-collection'))<li><a href='{{ backpack_url('/report/officer-collection') }}'><i class="fa fa-plus-square-o"></i> <span>C.O Collections</span></a></li>@endif
                        @if (_can('officer-transaction'))<li><a href='{{ backpack_url('/report/officer-transaction') }}'><i class="fa fa-plus-square-o"></i> <span>C.O Transactions</span></a></li>@endif
                        @if (_can('staff-performance'))<li><a href='{{ backpack_url('/report/staff-performance') }}'><i class="fa fa-plus-square-o"></i> <span>Staff Performance</span></a></li>@endif
                    </ul>
                </li>
            @endif
        </ul>
    </li>

@endif

@if( _can('list-user') || _can('list-role') || _can('list-permission'))
    <li class="treeview">
        <a href="#"><i class="fa fa-group"></i> <span>MANAGE USER</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">


            @if(_can('list-user'))<li><a href="{{ backpack_url('user') }}"><i class="fa fa-tag"></i> <span>List Users</span></a></li>@endif
            @if(_can('list-role'))<li><a href="{{ backpack_url('role') }}"><i class="fa fa-tag"></i> <span>Role Permission</span></a></li>@endif
            {{--@if(_can('list-permission'))<li><a href="{{ backpack_url('permission') }}"><i class="fa fa-tag"></i> <span>Permissions</span></a></li>@endif--}}
        </ul>
    </li>
@endif
{{-- @if(_can('list-setting')) --}}
    <li class="treeview">
        <a href="#"><i class="fa fa-cog"></i> <span>GENERAL SETTING</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            {{--<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-tag"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>--}}
            @if('list-setting') <li><a href='{{ backpack_url('setting') }}'><i class='fa fa-tag'></i> <span>Settings</span></a></li>@endif


            @if(_can('list-branch'))<li><a href="{{ backpack_url('branch') }}"><i class='fa fa-tag'></i> <span>Branches</span></a></li>@endif
            <li><a href="{{ backpack_url('centerleader') }}"><i class='fa fa-tag'></i> <span>Centers</span></a></li>
            @if(_can('list-holiday-schedule'))<li><a href="{{ backpack_url('holidayschedule') }}"><i class='fa fa-tag'></i> <span>Set Holiday</span></a></li>@endif
            @if(_can('list-audit'))<li><a href="{{ backpack_url('audit') }}"><i class='fa fa-area-chart'></i> <span>Audit Trails</span></a></li>@endif

        </ul>
    </li>
{{-- @endif --}}
