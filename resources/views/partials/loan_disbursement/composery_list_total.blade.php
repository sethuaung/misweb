@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
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
                        <tr style="background-color:darkgray;">
                            <td colspan="6">Total</td>
                            <td id="saving_amount_total"></td>
                            <td ></td>
                            <td id="interest_rate_total"></td>
                            <td id=""></td>
                            <td id="deposits_total"></td>
                            <td id="interest_total"></td>
                            <td id="cash_withdrawal_total"></td>
                            <td id="available_balance_total"></td>
                            <td ></td>
                            <td ></td>
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
                            @include('crud::inc.button_stack', ['stack' => 'bottom'])

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
    @include('crud::inc.datatables_logic')

    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
    <script>

        $("#show-detail-modal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);

            $(this).find(".modal-body").load(link.attr("href"));
        });

        $("#show-detail-modal").on('hidden.bs.modal', function () {
            $(this).find(".modal-body").html('...');
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
            jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
                return this.flatten().reduce( function ( a, b ) {
                    if ( typeof a === 'string' ) {
                        // a = a.replace(/[^\d.-]/g, '') * 1;
                        a = a.replace(/[^\d.-]/g, '') * 1;
                    }
                    if ( typeof b === 'string' ) {
                        b = b.replace(/[^\d.-]/g, '') * 1;
                    }
                    //console.log(a, b);
                    return a + b;
                }, 0 );
            } );
            // var table = $('#crudTable').DataTable();
            crud.table.on('draw.dt', function () {
                //console.log(crud.table.column(8).data().sum());
                // console.log('Redraw occurred at: ' + new Date().getTime());
                // $("#overday_total").html(crud.table.column(4).data().sum());
                $("#saving_amount_total").html(crud.table.column(5).data().sum());
                $("#interest_rate_total").html(crud.table.column(8).data().sum());
                $("#deposits_total").html(crud.table.column(10).data().sum());
                $("#interest_total").html(crud.table.column(11).data().sum());
                $("#cash_withdrawal_total").html(crud.table.column(12).data().sum());
                $("#available_balance_total").html(crud.table.column(13).data().sum());



                // console.log(crud.table.column(9).data());
            });
        });
    </script>

    {{-- <script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js" charset="utf-8"></script>
    <script type="text/javascript">
    $( document ).ready(function() {
        $('#crudTable').DataTable( {
            drawCallback: function () {
                var api = this.api();
                $( api.table().footer() ).html(
                    api.column( 9, {page:'current'} ).data().sum()
                );
            }
        } );
    });
    </script> --}}

    {{-- <script src="{{ asset('js/FileSaver.min.js') }}"></script> --}}

    <script type="text/javascript">
        $( "#excel" ).click(function() {
            var query = {
                'branch_id' : $('#filter_branch_id').val(),
                'center_id' : $('#filter_center_id').val(),
                'loan_officer_id' : $('#filter_loan_officer_id').val(),
                'from_to' : $('#daterangepicker-from-to').val(),
                'applicant_number_id' : $('#filter_applicant_number_id').val(),
                'client_name': $('#text-filter-client-name').val(),
                'nrc_number': $('#text-filter-nrc-number').val(),
                'search': crud.table.search(),
            }
            //console.log(query);
            var url = "{{URL::to('/admin/report/payment-deposits/excel')}}?" + $.param(query)
            window.location = url;

            // $.ajax({
            //     type : 'get',
            //     url : '{{URL::to('/admin/report/payment-deposits/excel')}}',
            //     responseType: 'blob', // important
            //     data:{
            //         'branch_id': $('#filter_branch_id').val(),
            //         'client_name': $('#text-filter-client-name').val(),
            //     }
            // }).then((response) => {
            //     // console.log(response.file);
            //     // const url = window.URL.createObjectURL(new Blob([response.file]));
            //     // const link = document.createElement('a');
            //     // link.setAttribute('download', 'file.xlsx');
            //     // document.body.appendChild(link);
            //     // link.click();
            //     // var blob = new Blob([response.file], {type: "application/xlsx"});
            //     // saveAs(blob, 'Test.xlsx');
            // });
        });
    </script>
@endsection
