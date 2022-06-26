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
@section('header')
@endsection
@section('content')

    <div class="row">
        @if(_can('form-serch'))
            <div class="col-md-8 col-xs-12">
                <form  action="{{url('api/dashboard_search')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">

                        <div class="input-group input-group-sm">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="reservation">
                            <input type="hidden" id="end-date" name="end_date" value="">
                            <span class="input-group-btn">
                                  <input type="submit" class="btn btn-info btn-round radius-all" value="{{_t("Search")}}">
                            </span>
                        </div>
                    </div>
                </form>

            </div>
        @endif
    </div>

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


    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/amcharts.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/serial.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/pie.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/light.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/export.js" type="text/javascript"></script>

    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/amcharts.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/serial.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/pie.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/themes/light.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/plugins/export/export.min.js" type="text/javascript"></script>--}}

{{--<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>--}}

    <!-- include select2 js-->
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

    <script src="{{asset('vendor/adminlte/bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>

        jQuery(document).ready(function($){
            $('[data-bs-month]').each(function() {
                $(this).MonthPicker({
                    Button: false,
                    MonthFormat: 'yy-mm' ,
                    ShowAnim: 'slideDown',UseInputMask: true ,
                    OnAfterChooseMonth :function (e) {
                        //console.log($(this).val());
                    }
                });
            });
        });
    </script>


    {{--range date--}}
    @push('after_scripts')

        <script type="text/javascript">
            $(function () {

                $('#reservation').datepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });


            });
        </script>
    @endpush
    {{--range date--}}

@endif
@endpush
