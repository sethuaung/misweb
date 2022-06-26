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
                        <tr style="background-color:#dcdcdc;">
                            <td colspan="6">Total</td>
                            <td  id="total"></td>
                            <td id="discount"></td>
                            <td colspan="4"></td>

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
    <div class="modal fade" id="ticket_close_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {{--                <div class="modal-header">--}}
                {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--                    <h4 class="modal-title" id="myModalLabel">Detail</h4>--}}
                {{--                </div>--}}
                <div class="modal-body modal-ticket_close_register-body">

                </div>
                {{--                <div class="modal-footer">--}}
                {{--                    <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>--}}
                {{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    {{--<div class="modal fade" id="show-detail-modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    </div>--}}

    @include('crud::inc.datatables_logic')

    {{--    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>--}}
    {{--    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>--}}
    {{--    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>--}}

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
    <script>
        $("#ticket_close_register").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-ticket_close_register-body ").load(link.attr("href"));
        });
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
                    // console.log(a, b);
                    return a + b;
                }, 0 );
            } );
            // var table = $('#crudTable').DataTable();


            crud.table.on('draw.dt', function () {
                // console.log('Redraw occurred at: ' + new Date().getTime());
                // $("#overday_total").html(crud.table.column(4).data().sum());
                // $("#total").html($.number(crud.table.column(6).data().sum(),0));
                $("#total").html(crud.table.column(6).data().sum());
                // $("#discount").html(crud.table.column(5).data().sum());

                // console.log(crud.table.column(9).data());
            });
        });
    </script>








@endsection
