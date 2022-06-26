@extends('backpack::layout')
@push('after_styles')
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
    <link href="{{ asset('vendor/adminlte/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    {{--<link rel="stylesheet" href="{{ asset('js/MonthPicker.css') }}">--}}

    <link href="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endpush

@section('content')

@endsection


@push('after_scripts')
@if(companyReportPart() != 'company.mkt')
{{--    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/MonthPicker.js') }}"></script>--}}

    <script src="{{ asset('vendor/adminlte/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/moment/moment.min.js') }}"></script>

    <script src="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    {{--<script src="{{ asset('js/jquery.maskedinput.js') }}"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>--}}
    <script src="{{ asset('vendor/adminlte/plugins/jquery-maskedinput/jquery.maskedinput.min.js') }}"></script>

    <script src="{{asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>


@endif
@endpush
