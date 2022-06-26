@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <!-- Theme style -->

@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <h1>
                <span class="text-capitalize">Plan Repayments Report</span>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ backpack_url('/dashboard') }}">Admin</a></li>
                <li><a href="{{ backpack_url('/report/plan-repayments') }}" class="text-capitalize">Plan Repayments</a></li>
            </ol>
        </section>
        <br>

        {{-- <div class="row m-t-20">
            <div class="col-md-12">
                <!-- Default box -->
                <form method="post" action="http://localhost:8000/admin/report-accounting">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <div class="row display-flex-wrap">
                            <div class="box col-md-12 padding-10 p-t-20">
                                <!-- load the view from type and view_namespace attribute if set -->

                                <!-- select2 from ajax multiple -->

                                <div class="form-group col-md-5 ">
                                    <label>Account</label>
                                    <select name="acc_chart_id[]" style="width: 100%" id="select2_ajax_multiple_acc_chart_id" class="form-control select2-hidden-accessible" multiple="" tabindex="-1" aria-hidden="true">
                                        <option value="1">Fixed Assets</option></select><span class="select2 select2-container select2-container--bootstrap select2-container--below" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-selection__choice" title="Fixed Assets"><span class="select2-selection__choice__remove" role="presentation">×</span>Fixed Assets</li><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                    <!-- include field specific select2 js-->
                                    <!-- load the view from type and view_namespace attribute if set -->
                                    <!-- bootstrap datepicker input -->

                                    <div class="form-group col-md-5 ">
                                        <input type="hidden" name="month" value="2019-05-13">
                                        <label>Month</label>
                                        <div class="input-group date">
                                            <input data-bs-datepicker="{&quot;todayBtn&quot;:true,&quot;format&quot;:&quot;yyyy-mm&quot;}" type="text" class="form-control">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- load the view from type and view_namespace attribute if set -->

                                    <!-- select from array -->
                                    <div class="form-group col-md-2 ">
                                        <label>Show Zero</label>
                                        <select name="show_zero" class="form-control js-example-basic-single">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <!-- load the view from type and view_namespace attribute if set -->

                                    <!-- bootstrap daterange picker input -->


                                    <div class="form-group col-md-12 ">
                                        <input class="datepicker-range-start" type="hidden" name="start_date" value="2015-03-28 00:00:00">
                                        <input class="datepicker-range-end" type="hidden" name="end_date" value="2019-05-13 23:59:59">
                                        <label>Select Date Range</label>
                                        <div class="input-group date">
                                            <input data-bs-daterangepicker="{&quot;locale&quot;:{&quot;format&quot;:&quot;DD\/MM\/YYYY&quot;}}" type="text" class="form-control">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- load the view from type and view_namespace attribute if set -->

                                    <!-- view field -->

                                    <div class="form-group col-xs-12">
                                        <div style="text-align: center">
                                            <button type="button" class="btn btn-app search-data">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <button type="button" class="btn btn-app print-data">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                            <button type="button" class="btn btn-app" onclick="exportTableToExcel('table-data', 'Data-13-05-2019')">
                                                <i class="fa fa-file-excel-o"></i> Excel
                                            </button>
                                        </div>
                                        <div class="show-report"></div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </form>
                </div>
            </div> --}}
        <div class="row">
            {{-- <input type="text" id="myInputTextField"> --}}

            {{-- {!! $dataTable->table()  !!} --}}

            {!! $dataTable->table([], true) !!}
            {{-- <table class="table table-bordered" style="width: 100%;background-color: white" id="plan-repayments-table">
                <thead>
                <tr>
                    <th>Loan Number</th>
                    <th>Client Name</th>
                    <th>NRC</th>
                    <th>Phone</th>
                    <th>Due Date</th>
                    <th>Branch</th>
                    <th>Center</th>
                    <th>CO Name</th>
                    <th>Installment Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @if($rows != null)
                    @foreach($rows as $row)
                       <tr>
                           <td>{{ $row->disbursement_number }}</td>
                           <td>{{ optional($row->client_name)->name }}</td>
                           <td>{{ optional($row->client_name)->nrc_number }}</td>
                           <td>{{ optional($row->client_name)->primary_phone_number.', '.optional($row->client_name)->alternate_phone_number }}</td>
                           <td>@if (!$row->loan_schedule->isEmpty()) {{ $row->loan_schedule[0]->date_s }} @endif</td>
                           <td>{{ optional($row->branch_name)->title }}</td>
                           <td>{{ optional($row->center_leader_name)->title }}</td>
                           <td>{{ optional($row->officer_name)->name }}</td>
                           <td>@if (!$row->loan_schedule->isEmpty()) {{ $row->loan_schedule[0]->principal_s }} @endif</td>
                           <td>{{ $row->id }}</td>
                       </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            {{$rows->links()}} --}}
        </div>
    </div>




@endsection

@section('extra_scripts')
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    </script>

    {!! $dataTable->scripts() !!}

    {{-- <script type="text/javascript">
    oTable = $('#dataTableBuilder').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
        console.log($(this).val());
        oTable.search($(this).val()).draw() ;
    })
    </script> --}}

    {{-- <script>
    $(function() {
        $('#plan-repayments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('admin/report/plan-repayments-data') }}',
            columns: [
                { data: 'disbursement_number', name: 'disbursement_number' },
            ],
            dom: 'Bfrtip',
            buttons: ['export', 'print', 'reset', 'reload'],
        });
    });
</script> --}}
@endsection
