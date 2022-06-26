@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize" style="font-size: 19px">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
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

    <?php
        $client_id = isset($_REQUEST['client_id'])?$_REQUEST['client_id']:0;
        $branch_id = isset($_REQUEST['branch_id'])?$_REQUEST['branch_id']:0;
        $saving_id = isset($_REQUEST['saving_id'])?$_REQUEST['saving_id']:0;


        //        dd($branch_id);
        $trans = \App\Models\SavingTransaction::where(function ($q) use ($client_id){
                if ($client_id*1>0){
                    $q->where('client_id',$client_id);
                }
            })
            ->where(function ($q) use ($branch_id){
                if ($branch_id*1>0){
                    $q->where('branch_id',$branch_id);
                }
            })
            ->where(function ($q) use ($saving_id){
                if ($saving_id*1>0){
                    $q->where('saving_id',$saving_id);
                }
            })
            ->where('tran_type','!=','accrue-interest')
            ->where('tran_type','!=','compound-interest')
            ->groupBy('saving_id')
            ->orderBy('saving_id','DESC')
            ->paginate(10);

//        dd($trans);
    ?>

    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">
            <div class="">

                <div class="row m-b-10">
                    <div class="col-xs-6">
                        @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
                            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

                                @include('crud::inc.button_stack', ['stack' => 'top'])

                            </div>
                        @endif
                        @php
                            // dd($crud->filters->toArray());
                        @endphp
{{--                        <a href="#" class="btn btn-dark" type="button" id="excel"><i class="fa fa-download" aria-hidden="true"></i> Excel</a>--}}
                        {{-- <a href="{!!route('payment-deposits.excel' , ['search' => $crud->filters->toArray()])!!}" class="btn btn-dark" type="button"><i class="fa fa-download" aria-hidden="true"></i> Excel</a> --}}
                    </div>
                    <div class="col-xs-6">
                        <div id="datatable_search_stack" class="pull-right"></div>
                    </div>
                </div>

                {{-- Backpack List Filters --}}
                @if ($crud->filtersEnabled())
                    @include('crud::inc.filters_navbar')
                @endif

                <style>
                    .header-search{
                        background: #fff;
                        height: 100%;
                        padding: 20px;
                        margin: 10px 0 10px 0;
                        border-radius: 8px;

                    }


                </style>

                <div class="header-search ">
                    <div class="row">
                        <form method="GET" id="form-search">

                            <div class="form-group col-lg-3">
                                <label>Client</label>
                                <select class="form-control js-client-id" name="client_id" id="client_id">
                                    @if(isset($_REQUEST['client_id']))
                                        <?php
                                        $client_id = \App\Models\ClientU::find($_REQUEST['client_id']);
                                        ?>
                                        @if($client_id != null)
                                            <option selected="selected" value="{{$client_id->id}}">{{$client_id->client_number}}-{{$client_id->name??$client_id->name_other}}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Branch</label>
                                <select class="form-control js-branch-id" name="branch_id" id="branch_id">
                                    @if(isset($_REQUEST['branch_id']))
                                        <?php
                                        $branch = \App\Models\Branch::find($_REQUEST['branch_id']);
                                        ?>
                                        @if($branch != null)
                                            <option selected="selected" value="{{$branch->id}}">{{$branch->title}}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Saving ID</label>
                                <select class="form-control js-saving-id" name="saving_id" id="saving_id">
                                    @if(isset($_REQUEST['saving_id']))
                                        <?php
                                        $saving = \App\Models\Saving::find($_REQUEST['saving_id']);
                                        ?>
                                        @if($saving != null)
                                            <option selected="selected" value="{{$saving->id}}">{{$saving->saving_number}}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Reference</label>
                                <input name="reference_no" placeholder="Reference No" id="reference_no" class="form-control" type="text" value="{{ isset($_REQUEST['reference_no'])? $_REQUEST['reference_no']:''}}">
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Start Date</label>
                                <input name="start_date" id="start_date" autocomplete="off" class="form-control radius-all" type="text" value="{{ isset($_REQUEST['start_date'])? $_REQUEST['start_date']:''}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label>To Date</label>
                                <input name="end_date" id="end_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['end_date'])? $_REQUEST['end_date']:''}}">
                            </div>

                            <div ><a href="{{backpack_url('saving-transaction')}}" id="remove_filters_button" class="{{ count(Request::input()) != 0 ? '' : 'hidden' }}"><i class="fa fa-eraser"></i> {{ trans('backpack::crud.remove_filters') }}</a></div>


                        </form>
                    </div>

                </div><!-- /.container-fluid -->

                <br>


                <div class="overflow-hidden shadow" style="border-radius: 8px !important; background: #fff;">
                    <table  class="box table table-striped table-hover display responsive nowrap " style="border-radius: 8px;box-shadow: none" >
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Debit Amount</th>
                            <th>Credit Amount</th>
                            <th>Balance</th>
                        </tr>
                        {{-- <tr>
                             --}}{{-- Table columns --}}{{--
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
                     </tr>--}}
                        </thead>
                        <tbody>

                        @foreach($trans as $t)
                            <?php
                            $reference_no = isset($_REQUEST['reference_no'])?$_REQUEST['reference_no']:null;
                            $start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
                            $end_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;

                            $transaction=\App\Models\SavingTransaction::where('saving_id', $t->saving_id)
                                ->where(function ($q) use ($reference_no){
                                    if ($reference_no != null){
                                        $q->where('reference',$reference_no);
                                    }
                                })
                                ->where(function ($q) use ($start_date, $end_date) {
                                    if ($start_date != null && $end_date == null) {
                                        $q->whereDate('date','<=',$start_date);
                                    } else if ($start_date != null && $end_date != null) {
                                        $q->whereDate('date','>=',$start_date)
                                            ->whereDate('date','<=',$end_date);
                                    }

                                })
                                ->where('tran_type','!=','accrue-interest')
                                ->where('tran_type','!=','compound-interest')
                                ->get();
//                            dd($transaction);
                            $client = \App\Models\Client::find($t->client_id);
                            $saving = \App\Models\Saving::find($t->saving_id);
                            $total_debit=0;
                            $total_credit=0;
                            $last_id= \App\Models\SavingTransaction::where('saving_id', $t->saving_id)->max('id');
                            $last_tran = \App\Models\SavingTransaction::find($last_id);

                            ?>

                            @if($transaction->count()>0)

                            <tr>
                                <td colspan="3"><b>Account >> {{optional($saving)->saving_number}} - {{optional($client)->name}}</b></td>
                                <td colspan="1"><b>Beginning Account Balance <span class="fa fa-caret-right"></span></b></td>
                                <td><b>0.00</b></td>
                                <td><b>0.00</b></td>
                                <td><b>0.00</b></td>
                                <td colspan="2"></td>
                            </tr>



                            @foreach($transaction as $r)
                                <?php
                                    if ($r->tran_type == 'deposit'){
                                        $total_debit+=$r->amount;
                                    }

                                    if ($r->tran_type == 'withdrawal'){
                                        $total_credit+=$r->amount;
                                    }
                                ?>
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{optional($r)->date!=null?\Carbon\Carbon::parse(optional($r)->date)->format('d M Y'):''}}</td>
                                    <td>{{$r->reference}}</td>
                                    <td style="text-transform: capitalize">
                                        @if($r->tran_type == 'deposit')
                                            <span class="btn-sm btn-success" style="padding: 2px 10px;">{{$r->tran_type}}</span>
                                        @elseif($r->tran_type == 'withdrawal')
                                            <span class="btn-sm btn-danger" style="padding: 2px 10px;">{{$r->tran_type}}</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($r->tran_type == 'deposit')
                                            {{numb_format($r->amount??0,0)}}
                                        @else
                                            {{numb_format(0,0)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($r->tran_type == 'withdrawal')
                                            {{numb_format(-$r->amount??0,0)}}
                                        @else
                                            {{numb_format(0,0)}}
                                        @endif
                                    </td>
                                    <td>{{numb_format($r->available_balance??0,0)}}</td>

                                    <?php

                                        $check_last_transaction = \App\Models\SavingTransaction::where('saving_id',$r->saving_id)
                                            ->orderBy('id','DESC')->first();

                                    ?>
                                    @if (optional($check_last_transaction)->id == $r->id)
                                        <td><a data-id="{{$r->id}}" href="javascript:void(0)" class="btn btn-xs btn-danger rollback" ><i class="fa fa-minus-circle"></i> Rollback</a></td>
                                    @else
                                        <td></td>
                                    @endif

                                @if(companyReportPart() == "company.moeyan")
                                    <td><a target="_blank" href="{{url('admin/saving-download/'.$r->id)}}" class="btn btn-xs btn-primary" ><i class="fa fa-print"></i></a></td>
                                    @endif
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="3"></td>
                                <td><b>Ending Account Balance <span class="fa fa-caret-right"></span></b></td>
                                <td><b>{{numb_format($total_debit,0)}}</b></td>
                                <td><b>{{numb_format(-$total_credit,0)}}</b></td>
                                <td><b>{{numb_format(optional($last_tran)->available_balance??0,0)}}</b></td>
                                <td colspan="2"></td>
                            </tr>

                            @endif

                        @endforeach
                        </tbody>
                        {{--<tr style="background-color:darkgray;">
                            <td colspan="6">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="loan_amount_total"></td>
                            @if(companyReportPart() == "company.quicken")
                                <td id="principle_target_total"></td>
                                <td id="interest_target_total"></td>
                            @endif
                            <td id="principle_repay_total"></td>
                            <td id="interest_repay_total"></td>
                            <td id="principle_outstanding_total"></td>
                            <td id="interest_outstanding_total"></td>
                            <td id="total_interest_total"></td>
                            <td></td>

                        </tr>--}}
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
              {{--  @if ( $crud->buttons->where('stack', 'bottom')->count() )
                    <div id="bottom_buttons" class="hidden-print">
                        @include('crud::inc.button_stack', ['stack' => 'bottom'])
                        <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
                    </div>
                @endif--}}

                    <?php
                        $branch_id=isset($_REQUEST['branch_id']) ? $_REQUEST['branch_id'] : 0;
                    ?>



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
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
    <style type="text/css">
        .select2-selection__clear::after {
            content: ' {{ trans('backpack::crud.clear') }}';
        }
    </style>
@endsection

@section('after_scripts')
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
    <input type="hidden" value="{{companyReportPart()}}" id="company">

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
{{--    @include('crud::inc.datatables_logic')--}}

{{--    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>--}}
{{--    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>--}}
{{--    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>--}}

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
    <script>



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


            /*jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
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
            } );*/
            // var table = $('#crudTable').DataTable();
            // crud.table.on('draw.dt', function () {
                // console.log('Redraw occurred at: ' + new Date().getTime());
             // $("#loan_amount_total").html(crud.table.column(10).data().sum());
            //  var company = $('#company').val();

          /*    if(company == "company.quicken"){
                $("#principle_target_total").html(crud.table.column(11).data().sum());
                $("#interest_target_total").html(crud.table.column(12).data().sum());
                $("#principle_repay_total").html(crud.table.column(13).data().sum());
                $("#interest_repay_total").html(crud.table.column(14).data().sum());
                $("#principle_outstanding_total").html(crud.table.column(15).data().sum());
                $("#interest_outstanding_total").html(crud.table.column(16).data().sum());
                $("#total_interest_total").html(crud.table.column(17).data().sum());
              }
              else{
                $("#principle_repay_total").html(crud.table.column(11).data().sum());
                $("#interest_repay_total").html(crud.table.column(12).data().sum());
                $("#principle_outstanding_total").html(crud.table.column(13).data().sum());
                $("#interest_outstanding_total").html(crud.table.column(14).data().sum());
                $("#total_interest_total").html(crud.table.column(15).data().sum());
              }*/
            // });
    });
    </script>

    @push('after_scripts')
        <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

        <script>
            $(function () {
                $('.js-client-id').select2({
                    theme: 'bootstrap',
                    multiple: false,
                    allowClear: true,
                    placeholder: "-",

                    ajax: {
                        url: '{{url("api/get-client")}}',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                            };
                        },
                        processResults: function (data, params) {
                            // Tranforms the top-level key of the response object from 'items' to 'results'
                            params.page = params.page || 1;
                            var result = {
                                results: $.map(data.data, function (item) {

                                    return {
                                        text: item["client_number"],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };
                            return result;
                        }
                    }
                }).on('select2:unselecting', function(e) {
                    $(this).val('').trigger('change');
                    // console.log('cleared! '+$(this).val());
                    e.preventDefault();
                });

                $('.js-branch-id').select2({
                    theme: 'bootstrap',
                    multiple: false,
                    allowClear: true,
                    placeholder: "-",

                    ajax: {
                        url: '{{url("api/get-branch")}}',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                            };
                        },
                        processResults: function (data, params) {
                            // Tranforms the top-level key of the response object from 'items' to 'results'
                            params.page = params.page || 1;
                            var result = {
                                results: $.map(data.data, function (item) {

                                    return {
                                        text: item["title"],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };
                            return result;
                        }
                    }
                }).on('select2:unselecting', function(e) {
                    $(this).val('').trigger('change');
                    // console.log('cleared! '+$(this).val());
                    e.preventDefault();
                });

                $('.js-saving-id').select2({
                    theme: 'bootstrap',
                    multiple: false,
                    allowClear: true,
                    placeholder: "-",

                    ajax: {
                        url: '{{url("api/get-saving")}}',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                            };
                        },
                        processResults: function (data, params) {
                            // Tranforms the top-level key of the response object from 'items' to 'results'
                            params.page = params.page || 1;
                            var result = {
                                results: $.map(data.data, function (item) {

                                    return {
                                        text: item["saving_number"],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };
                            return result;
                        }
                    }
                }).on('select2:unselecting', function(e) {
                    $(this).val('').trigger('change');
                    // console.log('cleared! '+$(this).val());
                    e.preventDefault();
                });

                $('#start_date').datepicker({
                        format: 'yyyy-mm-dd',

                    }
                ).change(function(){
                    //Change code!
                    var end_date = $('#end_date').val();
                    if(end_date){
                        $('#form-search').submit();
                    }
                });

                $('#end_date').datepicker({
                        format: 'yyyy-mm-dd',
                    }
                ).change(function(){
                    //Change code!
                    var start_date = $('#start_date').val();
                    if(start_date){
                        $('#form-search').submit();
                    }
                });

                $('#client_id').on("change", function () {

                    $('#form-search').submit();
                });


                $('#branch_id').on("change", function () {

                    $('#form-search').submit();
                });

                $('#saving_id').on("change", function () {

                    $('#form-search').submit();
                });

                $('#reference_no').on("change", function () {

                    $('#form-search').submit();
                });



                $('.rollback').on("click", function () {
                    var id = $(this).data('id');

                    swal({
                        title: "Are you sure?",
                        text: "Once rollback, you will not be able to recover this!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {



                                $.ajax({
                                    type: 'POST',
                                    url: '{{url('admin/remove-saving-transaction')}}',
                                    async: false,
                                    data: {
                                        id: id,
                                    },
                                    success: function (res) {
                                        // console.log(res);
                                     /*   setTimeout(function(){
                                        }, 100);*/

                                        window.location.reload();

                                    }
                                });

                                swal("Poof! Rollback has been succeed!", {
                                    icon: "success",
                                });
                            } else {
                                // swal("Your imaginary file is safe!");
                            }
                        });


                });





            })
        </script>
    @endpush


@endsection
