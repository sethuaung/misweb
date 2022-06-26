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
                <span class="text-capitalize">Plan Late Repayments Report</span>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ backpack_url('/dashboard') }}">Admin</a></li>
                <li><a href="{{ backpack_url('/report/plan-late-repayments') }}" class="text-capitalize">Plan Late Repayments</a></li>
            </ol>
        </section>
        <br>
        <div class="row">
            {!! $dataTable->table([], true) !!}
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
@endsection
