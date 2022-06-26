@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize head-font-size" >{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
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
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th>{!! $column['label'] !!}</th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

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

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte') }}/plugins/datatables/css/dataTables.bootstrap.min.css" >
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte') }}/plugins/datatables/css/fixedHeader.dataTables.min.css" >
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte') }}/plugins/datatables/css/responsive.bootstrap.min.css" >

  {{--<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
  {{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
  {{--<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">--}}

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

    <style>
        #crudTable tbody tr:hover{
            color: #0012ff !important;
            background-color: #fffdb5 !important;
        }
    </style>

{{--    testing vyrom--}}
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
    <div class="modal fade" id="show-saving-transaction-lists"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<div class="modal fade" id="repayment-date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Repayment Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="reload">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="repaymentMessage"></div>
          <input type="hidden" class="payment_id form-control">
          <div class="col-md-12 mb-3">
                <div class="col-md-6">
                    <label class="col-form-label">Old Date:</label>
                    <input type="text" class="date_id form-control" readonly>
                </div>
                <div class="col-md-6">
                <label for="repaymentDate" class="col-form-label">Repayment Date Change:</label>
                <input class="form-control" type="date" name="RepaymentDate" id="RepaymentDate" style="margin-bottom:20px;">
                </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary reload" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary repayment_change">Change</button>
      </div>
    </div>
  </div>
</div>
    <script>

        $("#show-detail-modal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);

            $(this).find(".modal-body").load(link.attr("href"));
        });
        $("#show-saving-transaction-lists").on("show.bs.modal", function(e) {
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
        $(function () {
            $("#show-create-deposit").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);
                $(".is-iframe").prop('src',link.attr("href"));
            });
            $("#show-create-deposit").on("hidden.bs.modal", function(e) {

                $(".is-iframe").prop('src','');
                window.location.reload();
            });

        });
$('#repayment-date').on('show.bs.modal', function (event) {

 var button = $(event.relatedTarget) // Button that triggered the modal
 var recipient = button.data('whatever') // Extract info from data-* attributes
 var repayment_date = button.data('re_date')
 var modal = $(this)
 modal.find('.modal-title').text('Repayment Date')
 //modal.find('.modal-body input').val(recipient)
 modal.find('.payment_id').val(recipient)
 repayment_date = repayment_date.split(' ')[0];
 repayment_date = repayment_date.split(/\D/g);
 repayment_date = [repayment_date[1],repayment_date[2],repayment_date[0] ].join("/");
 modal.find('.date_id').val(repayment_date)
});
  $(function(){
            $('.reload').click(function(){
              location.reload();
            });
            $('.repayment_change').click(function(){
                var old_date = $('.date_id').val();
                var payment_id = $('.payment_id').val();
                var repaymentDate = $('#RepaymentDate').val();
            if(repaymentDate != ""){
                $.ajax({
                   method : "GET",
                    url: "{{route('repayment_date')}}",
                    data: {repaymentDate:repaymentDate,old_date:old_date,payment_id:payment_id},
                    success:function(data){
                        if(data == "Error"){
                        $('#repaymentMessage').fadeIn().html("<div class='alert alert-danger'>"+data+"!</div>");
                            setTimeout(function() {
                                $('#repaymentMessage').fadeOut("slow");
                        }, 2000 );
                    }
                    else{
                        $('#repaymentMessage').fadeIn().html("<div class='alert alert-success'>Date Changed Successfully!</div>");
                            setTimeout(function() {
                                $('#repaymentMessage').fadeOut("slow");
                        }, 2000 );
                    }
                    }
                });
                }
                });
  })
    </script>
@endsection
