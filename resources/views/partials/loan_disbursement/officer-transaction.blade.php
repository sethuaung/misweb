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
{{--                        <a href="#" class="btn btn-dark" type="button" id="excel"><i class="fa fa-download" aria-hidden="true"></i> Excel</a>--}}
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
                        <td colspan="4">Total</td>
                        <td id="loan_amount_total"></td>
                        <td></td>
                        <td></td>
                        <td id="principle_total"></td>
                        <td id="interest_total"></td>
                        <td id="other_payment_total"></td>
                        <td id="penalty_amount_total"></td>
                        <td id="total_payment_total"></td>
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
    @include('crud::inc.datatables_logic')

    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')

    <script>
        jQuery(document).ready(function($) {
            jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
                return this.flatten().reduce( function ( a, b ) {
                    if ( typeof a === 'string' ) {
                        a = a.replace(/[^\d.-]/g, '') * 1;
                    }
                    if ( typeof b === 'string' ) {
                        b = b.replace(/[^\d.-]/g, '') * 1;
                    }
                    return a + b;
                }, 0 );
            } );

            crud.table.on('draw.dt', function () {
              $("#loan_amount_total").html(crud.table.column(4).data().sum());
              $("#principle_total").html(crud.table.column(7).data().sum());
              $("#interest_total").html(crud.table.column(8).data().sum());
              $("#other_payment_total").html(crud.table.column(9).data().sum());
              $("#penalty_amount_total").html(crud.table.column(10).data().sum());
              $("#total_payment_total").html(crud.table.column(11).data().sum());
            });
    });

    $( "#excel" ).click(function() {
        var query = {
            'branch_id' : $('#filter_branch_id').val(),
            'center_id' : $('#filter_center_id').val(),
            'loan_officer_id' : $('#filter_loan_officer_id').val(),
            'from_to' : $('#daterangepicker-from-to').val(),
            'search': crud.table.search(),
        }

        var url = "{{URL::to('/admin/report/officer-transaction/excel')}}?" + $.param(query)
        window.location = url;
    });
    </script>
@endsection
