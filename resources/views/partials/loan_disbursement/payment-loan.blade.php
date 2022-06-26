@extends('backpack::layout')

@section('header')
    <style>
        .dataTables_scrollBody{
            min-height: 230px;
        }
    </style>
    <section class="content-header">
        <h1>
            <span class="text-capitalize head-font-size">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.list') }}</li>
        </ol>
    </section>
@endsection

@section('content')
@if($errors->any())
    @foreach ($errors->all() as $message)
    <div class="alert alert-warning">
        <strong>Sorry !</strong> {{$message}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach
@endif
@if(Session::has('message'))
<div class="alert alert-success" >
        <strong>{{Session::get('message')}}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remark for Loan canceled</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="GET" action="{{url('/admin/cancel-activated')}}">
        @csrf
          <div class="form-group">
            <input class ="id" type="hidden" name="id" id="id">
            <label for="remark" class="col-form-label">Remark:</label>
            <input class="form-control" type="name" name="remark" id="remark">
        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Okay</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>


{{-- Change Date Modal Box start here--}}
<div class="modal fade" id="changedate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Change Date</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="success_message"></div>
          <div class="form-group">
            <input class ="date_id" type="hidden" name="id" id="id">
            @if(companyReportPart() == 'company.mkt')
            <div class="col-md-4">
                <label class="col-form-label">Disbursement Date:</label><br>
                <select class="form-control" id="repayment_date">
                <option disabled selected>select</option>
                    <option value="yes" >Yes</option>
                    <option value="no">No</option>
                </select>
                </div>
                <div class="col-md-4">
                <label class="col-form-label">First Installment Date:</label><br>
                <select class="form-control" id="first_date">
                    <option disabled selected>select</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Deposit Date:</label><br>
                    <select class="form-control" id="deposit_date">
                        <option disabled selected>select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    </div>
                <div id="second_box" class="col-md-12">
                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" id="re_date" class="form-control" readonly style="margin-top:48px;">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                <label for="repaymentDate" class="col-form-label">Disbursement Date Change:</label>
                <input class="form-control" type="date" name="repaymentDate" id="repaymentDate">
                </div>
                </div>
                <div id="first_box" class="col-md-12">
                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" id="fr_date" class="form-control" readonly style="margin-top:48px;">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                <label for="firstDate" class="col-form-label">First Installment Date Change:</label>
                <input class="form-control" type="date" name="firstDate" id="firstDate">
                </div>
                </div>
                <div id="third_box" class="col-md-12">
                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                        <input type="text" id="de_date" class="form-control" readonly style="margin-top:48px;">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                    <label for="DepositDate" class="col-form-label">Deposit Date Change:</label>
                    <input class="form-control" type="date" name="DepositDate" id="DepositDate">
                    </div>
                </div>
                <div id="depositDate" class="col-md-12">
                    <input type="text"  class="form-control" readonly style="margin-top:48px;" value="No Deposit Date">
                </div>
            @else
            <div class="col-md-6">
                <label class="col-form-label">Disbursement Date:</label><br>
                <select class="form-control" id="repayment_date">
                <option disabled selected>select</option>
                    <option value="yes" >Yes</option>
                    <option value="no">No</option>
                </select>
                </div>
                <div class="col-md-6">
                <label class="col-form-label">First Installment Date:</label><br>
                <select class="form-control" id="first_date">
                    <option disabled selected>select</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                </div>
                <div id="second_box" class="col-md-12">
                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" id="re_date" class="form-control" readonly style="margin-top:48px;">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                <label for="repaymentDate" class="col-form-label">Disbursement Date Change:</label>
                <input class="form-control" type="date" name="repaymentDate" id="repaymentDate">
                </div>
                </div>
                <div id="first_box" class="col-md-12">
                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" id="fr_date" class="form-control" readonly style="margin-top:48px;">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                <label for="firstDate" class="col-form-label">First Installment Date Change:</label>
                <input class="form-control" type="date" name="firstDate" id="firstDate">
                </div>
                </div>
            @endif
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-top:20px;" id="close_button">Close</button>
        <button type="submit" class="btn btn-primary" style="margin-top:20px;" id="change">Change</button>
        </div>
    </div>
  </div>
</div>
{{-- Change Date Modal Box end here--}}






    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">
            <div class="">
                @php
                    $Url = explode('/', Request::url());
                   // dd($crud->filters->toArray());
                @endphp
                <div class="row m-b-12">
                    @if (!in_array('disbursependingapproval', $Url))
                        <div class="col-xs-10">
                            @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
                                <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

                                    @include('crud::inc.button_stack', ['stack' => 'top'])

                                </div>
                            @endif

                        </div>
                    @endif
                    @if (in_array('disbursependingapproval', $Url) && companyReportPart() == 'company.moeyan')
                        <div class="col-xs-10">
                        </div>
                    @endif
                 {{--   @if (companyReportPart() != 'company.quicken' && companyReportPart() != 'company.mkt')
                    <div class="col-xs-6">
                    </div>
                    @endif--}}
{{--                    @if (in_array('disburseawaiting', $Url) && (companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('loanoutstanding', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('loandisbursement', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('disburseclosed', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('disbursedeclined', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('disbursecanceled', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6"></div>--}}{{--
                    @endif
                    @if (in_array('disbursewrittenoff', $Url) &&(companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
--}}{{--                    <div class="col-xs-6">--}}{{--
                    </div>
                    @endif--}}
                    @if (in_array('disbursependingapproval', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt'))
                    @php
                    $date =\Carbon\Carbon::now()->format('d-m-Y');
                    @endphp


                        <form action="{{route('approve_all')}}" method = "GET">
                            @csrf
                            <div class="col-md-3">
                                <input type="text" id="time" class="form-control" name="approve_date" placeholder="Select Approved Date" required>
                            </div>

                            <input type="hidden" id="approve_id" name="approve_id" required>
                            <div class="col-md-7">
                                <button class="btn btn-success" type="submit" value="submit"><span class="fa fa-check-circle-o"></span> Approve Selected</button>
                            </div>
                        </form>


                    @endif
                    @if (in_array('disburseawaiting', $Url) && (companyReportPart() == 'company.quicken'))
                        @php
                        $date =\Carbon\Carbon::now()->format('d-m-Y');
                        @endphp
                        <form action="{{route('active_all')}}" method = "GET">
                        @csrf
                        <div class="col-md-3">
                            <input type="text" id="time" class="form-control" name="active_date" placeholder="Select Date" required>
                        </div>
                        <input type="hidden" id="approve_id" name="approve_id" required>
                            <div class="col-md-7">

                            <button class="btn btn-success" type="submit" value="submit">Disburse</button>
                            </div>

                        </form>
                    @endif
                    <div class="col-xs-2">
                        <div id="datatable_search_stack" class="pull-right"></div>
                    </div>
                </div>

                {{-- Backpack List Filters --}}
                @if ($crud->filtersEnabled())
                    @include('crud::inc.filters_navbar')
                @endif

                <div class="overflow-hidden">

                    <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
                        <thead>
                            <tr>
                                {{-- Table columns --}}
                                @foreach ($crud->columns as $column)
                                    <th
                                    data-orderable="{{ var_export($column['orderable'], true) }}"
                                    data-priority="{{ $column['priority'] }}"
                                    data-visible-in-modal="{{ (isset($column['visibleInModal']) && $column['visibleInModal'] == false) ? 'false' : 'true' }}"
                                    data-visible="{{ !isset($column['visibleInTable']) ? 'true' : (($column['visibleInTable'] == false) ? 'false' : 'true') }}"
                                    data-visible-in-export="{{ (isset($column['visibleInExport']) && $column['visibleInExport'] == false) ? 'false' : 'true' }}"
                                    >
                                    {!! $column['label'] !!}
                                </th>
                            @endforeach

                            @if ( $crud->buttons->where('stack', 'line')->count() )
                                <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tr style="background-color:#ddd;">
                        <td colspan="8">Total</td>
                        <td id="service_total"></td>
                        <td id="compulsory_total"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="termperiod_total"></td>
                        <td id="loan_amount_total"></td>
                        <td id="principle_total"></td>
                        <td id="interest_repay_total"></td>
                        <td id="principle_outstanding_total"></td>
                        <td id="interest_outstanding_total"></td>
                        <td id="term_total"></td>
                        <td id="cycle_total"></td>
                        <td ></td>
                        <td></td>

                    </tr>
                    <tfoot>
                        <tr>
                            @foreach ($crud->columns as $column)
                                <th>{!! $column['label'] !!}</th>
                            @endforeach

                            @if ( $crud->buttons->where('stack', 'line')->count() )
                                <th>{{ trans('backpack::crud.actions') }}</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>

                {{-- <p id="waveTotal"></p> --}}

                @if ( $crud->buttons->where('stack', 'bottom')->count() )
                    <div id="bottom_buttons" class="hidden-print">
{{--                        @include('crud::inc.button_stack', ['stack' => 'bottom'])--}}

                        <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
                    </div>
                @endif

            </div><!-- /.box-body -->

        </div><!-- /.box -->
    </div>

</div>

    {{-- <div id="waveTotal"></div> --}}

@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')


<script>

</script>
    <div class="modal fade" id="show-detail-modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">

                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="show-create-deposit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 80%;">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{_t('Loan Deposit')}}</h4>
                </div>
                <div class="">
                    <iframe class="is-iframe modal-body" width="100%" height="500" src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="show-write-off-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-x" style="width: 80%;">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title transfer-title" id="myModalLabel">
                </h4>
            </div>
            <div class="">
                <iframe class="is-iframe modal-body" width="100%" height="500" src="" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    @include('crud::inc.datatables_logic')

{{--    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></scrixpt>--}}
{{--    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>--}}
{{--    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>--}}

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')

    <script>

        $(function(){
            $('#time').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });


        // if (typeof printDiv !== 'undefined') {

        function printDiv() {

            var divToPrint = document.getElementById('DivIdToPrintPop');

            if(divToPrint != null) {
                var newWin = window.open('', 'Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

                newWin.document.close();

                setTimeout(function () {
                    newWin.close();
                }, 10);
            }
        }
        // }
    </script>
    <script>
        // Insert the sum of a column into the columns footer, for the visible
        // data on each draw
        // var table = $('#crudTable').DataTable();
       // $("#wave_total").html( $('#crudTable').column( 3 ).data().sum());
        jQuery(document).ready(function($) {

            $("#show-create-deposit").on("show.bs.modal", function(e) {
              // var loan_id = $('.deposit').data('loan_id');
               var link = $(e.relatedTarget);
                $(".is-iframe").prop('src',link.attr("href"));
            });
            $("#show-create-deposit").on("hidden.bs.modal", function(e) {

                $(".is-iframe").prop('src','');
                window.location.reload();
            });

            $("#show-detail-modal").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);

                $(this).find(".modal-body").load(link.attr("href"));
                //$(".modal-body").find('iframe').prop('src',link);
            });

            $("#show-detail-modal").on('hidden.bs.modal', function () {
                //$(this).find(".modal-body").html('...');
                $(".modal-body").find('iframe').prop('src','');
            });
            $("#show-write-off-modal").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);
                $(".is-iframe").prop('src',link.attr("href"));
            });
            $("#show-write-off-modal").on("hidden.bs.modal", function(e) {

                $(".is-iframe").prop('src','');
                window.location.reload();
            });


            jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
                return this.flatten().reduce( function ( a, b ) {
                    if ( typeof a === 'string' ) {
                        // a = a.replace(/[^\d.-]/g, '') * 1;
                        a = a.replace(/[^\d.-]/g, '') * 1;
                    }
                    if ( typeof b === 'string' ) {
                        b = b.replace(/[^\d.-]/g, '') * 1;
                    }
                    // console.log(a, b);
                    return a + b;
                }, 0 );
            } );
            // var table = $('#crudTable').DataTable();
            crud.table.on('draw.dt', function () {
                // console.log('Redraw occurred at: ' + new Date().getTime());
              $("#service_total").html(crud.table.column(8).data().sum());
              $("#compulsory_total").html(crud.table.column(9).data().sum());
              $("#termperiod_total").html(crud.table.column(19).data().sum());
              $("#loan_amount_total").html(crud.table.column(20).data().sum());
              $("#principle_total").html(crud.table.column(21).data().sum());
              $("#interest_repay_total").html(crud.table.column(22).data().sum());
              $("#principle_outstanding_total").html(crud.table.column(23).data().sum());
              $("#interest_outstanding_total").html(crud.table.column(24).data().sum());
              //$("#term_total").html(crud.table.column(18).data().sum());
              $("#cycle_total").html(crud.table.column(26).data().sum());
              // console.log(crud.table.column(9).data());
            });
    });

    </script>
    <script>
         $('#close_button').click(function(){
            window.location.reload();
         })
    </script>
    <script>

    $('#exampleModal').on('show.bs.modal', function (event) {

  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('Remark for Loan canceled')
  //modal.find('.modal-body input').val(recipient)
  modal.find('.id').val(recipient)
})
$('#changedate').on('show.bs.modal', function (event) {

 var button = $(event.relatedTarget) // Button that triggered the modal
 var recipient = button.data('whatever') // Extract info from data-* attributes
 var repayment_date = button.data('re_date')
 var first_date = button.data('fr_date')
 var deposit_date = button.data('de_date')
 // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
 // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
 var modal = $(this)
 modal.find('.modal-title').text('Change Date')
 //modal.find('.modal-body input').val(recipient)
 modal.find('.date_id').val(recipient)
 modal.find('#re_date').val(repayment_date)
 modal.find('#fr_date').val(first_date)
 modal.find('#de_date').val(deposit_date)
})
$(document).ready(function(){
    $('#first_box').hide();
    $('#second_box').hide();
    $('#third_box').hide();
    $('#depositDate').hide();
    $("#first_date").change(function() {
        var first_date = $("#first_date option:selected").val();
        if(first_date == 'yes'){
            $('#first_box').show();
        }
        else
        {
            $('#first_box').hide();
        };
        });
        $("#repayment_date").change(function() {
        var repayment_date = $("#repayment_date option:selected").val();
        if(repayment_date == 'yes'){
            $('#second_box').show();
        }
        else
        {
            $('#second_box').hide();
        };
        });
        $("#deposit_date").change(function() {
        var deposit_date = $("#deposit_date option:selected").val();
        var de_date = $('#de_date').val();
        if(deposit_date == 'yes'){
            if(de_date != ''){
            $('#third_box').show();
            $('#depositDate').hide();
            }
            else{
                $('#depositDate').show();
            }
        }
        else
        {
            $('#depositDate').hide();
            $('#third_box').hide();
        };
        });
        $('#change').click(function(){
            var firstDate = $('#firstDate').val();
            var id = $('.date_id').val();
            var repaymentDate = $('#repaymentDate').val();
            var DepositDate = $('#DepositDate').val();
            if(firstDate != '' || repaymentDate != '' || DepositDate != ''){
                $.ajax({
                   method : "GET",
                    url: "{{route('first_date')}}",
                    data: {firstDate:firstDate,repaymentDate:repaymentDate,id:id,DepositDate:DepositDate},
                    success:function(data){
                    if(data == "Paid Loan"){
                        $('#success_message').fadeIn().html("<div class='alert alert-danger'>"+data+"!</div>");
                            setTimeout(function() {
                                $('#success_message').fadeOut("slow");
                        }, 2000 );
                    }
                    else if(data == "No Deposit Date"){
                        $('#success_message').fadeIn().html("<div class='alert alert-danger'>"+data+"!</div>");
                            setTimeout(function() {
                                $('#success_message').fadeOut("slow");
                        }, 2000 );
                    }
                    else{
                        $('#success_message').fadeIn().html("<div class='alert alert-success'>Date Changed Successfully!</div>");
                            setTimeout(function() {
                                $('#success_message').fadeOut("slow");
                        }, 2000 );
                    }
                    }
                });
            }
            else{
                alert('Choose Date');
            };
        })

});

    </script>
{{--@if (companyReportPart() == 'company.quicken')--}}
@push('after_styles')
<link href="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('after_scripts')
<script src="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
@endpush
{{--@endif--}}
@endsection
