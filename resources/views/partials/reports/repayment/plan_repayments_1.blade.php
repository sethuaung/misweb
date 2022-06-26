@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row m-t-20">
            <div class="col-md-12">
                <!-- Default box -->
                {{ Form::open(['method' => 'GET', 'url' => 'admin/report/plan-repayments']) }}
                    {{-- {{ csrf_field() }} --}}
                    <div class="col-md-12">
                        <div class="row display-flex-wrap">
                            <!-- load the view from the application if it exists, otherwise load the one in the package -->
                            <div class="box col-md-12 padding-10 p-t-20">

                                <div class="form-group col-md-3 ">
                                    <label>Branch ID</label>
                                    <select name="search_branch" style="width: 100%" id="select2_ajax_branch_id" class="form-control select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option value="">Choose</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" @if($branch->id == old('search_branch', $search_branch)) selected @endif>{{$branch->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>Loan Officer Name</label>
                                    <select name="search_branch" style="width: 100%" id="select2_ajax_branch_id" class="form-control select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option value="">Choose</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" @if($branch->id == old('search_branch', $search_branch)) selected @endif>{{$branch->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                    <div class="form-group col-md-3 ">
                                        <input type="hidden" name="start_date" value="">
                                        <label>Start Date</label>
                                        <div class="input-group date">
                                            <input data-bs-datepicker="{&quot;todayBtn&quot;:true,&quot;format&quot;:&quot;yyyy-mm-dd&quot;}" type="text" class="form-control">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- load the view from type and view_namespace attribute if set -->
                                    <!-- bootstrap datepicker input -->
                                    <div class="form-group col-md-3 ">
                                        <input type="hidden" name="end_date" value="">
                                        <label>End Date</label>
                                        <div class="input-group date">
                                            <input data-bs-datepicker="{&quot;todayBtn&quot;:true,&quot;format&quot;:&quot;yyyy-mm-dd&quot;}" type="text" class="form-control">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- load the view from type and view_namespace attribute if set -->

                                    <!-- view field -->

                                    <div class="form-group col-xs-12">
                                        <div style="text-align: center">
                                            <button type="submit" class="btn btn-app search-data">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <button type="button" class="btn btn-app print-data">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                            <button type="button" class="btn btn-app" onclick="exportTableToExcel('table-data', 'Data-15-05-2019')">
                                                <i class="fa fa-file-excel-o"></i> Excel
                                            </button>
                                        </div>
                                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        <div class="row">
            @if($rows != null)
                @php
                $i = 0;
                @endphp
                <table class="table table-bordered" style="width: 100%;background-color: white" id="plan-repayments-table">
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
                    </tbody>
                </table>
                {{$rows->links()}}
            @endif
        </div>
    </div>
</div>
</div>
</div>




@endsection

@section('extra_scripts')
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#select2_ajax_branch_id').select2();
    });
    </script>

@endsection
