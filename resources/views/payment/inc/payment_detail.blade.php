<div class="col-md-12">
    <!-- DIRECT CHAT PRIMARY -->
    <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Supplier Information</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="padding: 10px 50px">
                <div class="col-md-6">
                    <p>Company Name: <b class="p-company"></b></p>
                    <p>Full Name:  <b class="p-name"></b></p>
                </div>
                <div class="col-md-6">
                    <p>Main Phone: <b class="p-phone"></b></p>
                    <p>Address:  <b class="p-address"></b></p>
                </div>
                <div class="col-md-12 btn-supplier-checked">

                </div>
            </div>
        </div>
    </div>
    <!--/.direct-chat -->
</div>


<div class="col-md-12">
    <!-- DIRECT CHAT PRIMARY -->
    <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
            <div class="row">
                    <div class="form-group col-md-4">
                        <label>Transaction</label>
                            <select class="form-control tran-type">
                                <option value="">{{ _t('All') }}</option>
                                <option value="order">{{ _t('order') }}</option>
                                <option value="bill-pending">{{ _t('Bill Pending') }}</option>
                                <option value="bill-complete">{{ _t('Bill Complete') }}</option>
                                <option value="bill-received">{{ _t('bill-received') }}</option>
                                <option value="purchase-return">{{ _t('purchase-return') }}</option>
                                <option value="deposit">{{ _t('deposit') }}</option>
{{--                                <option value="open">{{ _t('open') }}</option>--}}
                                <option value="payment">{{ _t('payment') }}</option>
                            </select>
                    </div>
                {{--<div class="col-md-4">
                    --}}{{--<div class="form-group">
                        <input class="datepicker-range-start " type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
                        <input class="datepicker-range-end" type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                        <label>Date</label>
                        <div class="input-group date">
                            <input class="form-control"
                                    data-bs-daterangepicker='{"timePicker":false,"locale":{"format":"YYYY-MM-DD"}}'
                                    type="text">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div>--}}{{--

                </div>--}}
                <div class="form-group col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="text" name="start_date" class="form-control start_date" id="start_date" value="{{\Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
                </div>
                <div class="form-group col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="text" name="end_date" autocomplete="off" class="form-control end_date" id="end_date"  value="{{date('Y-m-d')}}">
                </div>
                <div class="col-md-2" style="margin-top: 27px;">
                    <button class="btn btn-primary btn-search">Search</button>
                </div>
            </div>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="padding: 10px 50px">
                <div class="col-m-12 show-transaction"></div>
            </div>
        </div>
    </div>
    <!--/.direct-chat -->
</div>

@push('crud_fields_scripts')
    <link rel="stylesheet" href="{{ asset('/vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    {{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.42/css/bootstrap-datetimepicker.min.css" />--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">

    <script src="{{ asset('/vendor/adminlte/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('/vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
    {{--<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.42/js/bootstrap-datetimepicker.min.js"></script>--}}
    <script type="text/javascript" src="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script>
        jQuery(document).ready(function($){

            $('[data-bs-daterangepicker]').each(function(){

                var $fake = $(this),
                    $start = $fake.parents('.form-group').find('.datepicker-range-start'),
                    $end = $fake.parents('.form-group').find('.datepicker-range-end'),
                    $customConfig = $.extend({
                        format: 'dd/mm/yyyy',
                        autoApply: true,
                        startDate: moment($start.val()),
                        endDate: moment($end.val())
                    }, $fake.data('bs-daterangepicker'));

                $fake.daterangepicker($customConfig);
                $picker = $fake.data('daterangepicker');

                $fake.on('keydown', function(e){
                    e.preventDefault();
                    return false;
                });

                $fake.on('apply.daterangepicker hide.daterangepicker', function(e, picker){
                    $start.val( picker.startDate.format('YYYY-MM-DD') );
                    $end.val( picker.endDate.format('YYYY-MM-DD') );

                    var tran_type = $('.tran-type').val();
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    getSupplyTransaction(supply_id,tran_type,start_date,end_date);
                });

            });

            $( ".start_date" ).datepicker({
                format : 'yyyy-mm-dd'
            });
            $( ".from_date" ).datepicker({
                format : 'yyyy-mm-dd'
            });

            $(".tran-type").on('change',function () {
                var tran_type = $('.tran-type').val();
                var start_date = $('[name="start_date"]').val();
                var end_date = $('[name="end_date"]').val();
                getSupplyTransaction(supply_id,tran_type,start_date,end_date);
            });
            $('.btn-search').on('click',function () {
                var tran_type = $('.tran-type').val();
                var start_date = $('[name="start_date"]').val();
                var end_date = $('[name="end_date"]').val();
                getSupplyTransaction(supply_id,tran_type,start_date,end_date);
            });
            /*$("#show-discount-detail").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);
                $(this).find(".modal-body").load(link.attr("href"));
            });*/
        });
    </script>
@endpush()
