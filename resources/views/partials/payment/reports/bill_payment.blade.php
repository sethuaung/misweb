<?php $_e = isset($entry) ? $entry : null;

$kh_currency= \App\Models\Currency::where('currency_code','KHR')
    ->orWhere('currency_name','KHR')
    ->orWhere('currency_symbol','៛')
    ->first();

$m = getSetting();

$class = getSettingKey('show-class', $m);
$job = getSettingKey('show-job', $m);
$round_s = getSettingKey('default-round', $m);

?>
@extends('payment.layout')

@section('title',_t('bill payment'))
@section('after_styles')
{{--    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css"--}}
{{--          rel="stylesheet" type="text/css" />--}}
@endsection

@section('main-header')
    <nav class="navbar navbar-static-top" role="navigation">

        <div class="navbar-custom-menu pull-left">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/admin/payment') }}"><i class="fa fa-backward"></i> <span>Back</span></a></li>
            </ul>
        </div>
    </nav>
@endsection
@section('sidebar')

@endsection
@section('content')
    <form method="post" action="{{url('admin/payment-list')}}">
        {!! csrf_field() !!}
        <?php
        $sup = isset($supplier_id) ? \App\Models\Supply::find($supplier_id) : null;

        ?>
        <input type="hidden" name="supplier_id" value="{{$supplier_id}}">
        <div class="col-md-12">
            <!-- DIRECT CHAT PRIMARY -->
            <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{_t('Supplier Information')}}</h3>

                </div>
                <!-- /.box-header -->
                @if($sup != null)
                    <div class="box-body">
                        <div class="row" style="padding: 10px 50px">
                            <div class="col-md-6">
                                <p>{{_t('Company Name')}}: <b class="p-company">{{$sup->company}}</b></p>
                                <p>{{_t('Full Name')}}: <b class="p-name">{{$sup->name}}</b></p>
                            </div>
                            <div class="col-md-6">
                                <p>{{_t('Main Phone')}}: <b class="p-phone">{{$sup->phone}}</b></p>
                                <p>{{_t('Address')}}: <b class="p-address">{{$sup->address}}</b></p>
                            </div>
                            <div class="col-md-12 btn-supplier-checked">

                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!--/.direct-chat -->
        </div>
        <div class="col-md-12">
            <!-- DIRECT CHAT PRIMARY -->
            <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">

                                <label>{{_t('Date')}}</label>
                                <div class="input-group date">
                                    <input class="form-control" name="payment_date" value="{{date('Y-m-d')}}"
                                           type="text" data-bs-datetimepicker="">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{_t('Reference No')}}</label>
                                <input type="text" class="form-control p_reference"
                                       value="{{\App\Models\Payment::getSeqRef()}}" name="reference_no">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{_t('Currency')}}</label>

                                <input type="hidden" class="form-control p_reference" name="currency_id"
                                       value="{{optional(\App\Models\Supply::find($supplier_id))->currencies->id}}">
                                <input type="text" disabled="disabled" class="form-control p_reference"
                                       value="{{optional(\App\Models\Supply::find($supplier_id))->currencies->currency_name}}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{_t('Branch')}}</label>
                            <select name="branch_id" class="form-control select line_class_id" title="{{_t('branch')}}">
                                <?php
                                $branches = \App\Models\Branch::all();
                                ?>
                                @if($branches != null)
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->title}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        @if (companyReportPart() == 'company.myanmarelottery_account')
                            <div class="form-group col-md-4">
                                <label class="control-label">{{_t('Round')}}</label>
                                <select name="round_id" class="form-control select" title="{{_t('Round')}}">
                                    <?php

                                    $round = \App\Models\Round::where('id',$round_s)->first();
                                    $round_all = \App\Models\Round::where('status','Active')->get();


                                    ?>
                                    @if($round != null)
                                        <option value="{{$round->id}}" selected>{{$round->name}}</option>
                                    @else
                                        @if($round_all != null)
                                            @foreach($round_all as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>
                        @endif


                        @if ($class == 'Yes')
                        <div class="form-group col-md-4">
                            <label class="control-label">{{_t('Classes')}}</label>
                            <select name="class_id" class="form-control select line_class_id" title="{{_t('class')}}">
                                <option value="0"></option>
                                {!! \App\Models\AccClass::getAccClass() !!}
                            </select>
                        </div>
                        @endif

                        @if ($job == 'Yes')
                        <div class="form-group col-md-4">
                            <label class="control-label">{{_t('Job')}}</label>
                            <select name="job_id" class="form-control select line_job_id" title="{{_t('job')}}">
                                <option value="0"></option>
                                {!! \App\Models\Job::getJob() !!}
                            </select>
                        </div>

                        @endif
                    </div>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row" style="padding: 10px 50px">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input class="checked_all" type="checkbox">
                                    </label>
                                </th>
                                <th>Date</th>
                                <th nowrap>{{_t('REF NO')}}</th>
                                @if(isLogCode() >0 )
                                    <th>លេខឡូតិ</th>
                                    {{--<th>Items</th>--}}
                                @endif
                                <th>{{_t('Transaction')}}</th>
                                <th>{{_t('AMT DUE')}}</th>
                                <th>{{_t('DISC USED')}}</th>
                                <th>{{_t('CREDITS USED')}}</th>
                                <th>{{_t('AMT TO PAY')}}</th>
                                <th>{{_t('Discount & Credit')}}</th>
                                <th>{{_t('View')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ap_trans as $row)
                                <?php

                                $rand_id = rand(1, 1000) . time() . rand(1, 1000);

                                $date = '';
                                $reference = '';
                                if($row->train_type_ref == 'open'){
                                    $open = \App\Models\OpenSupplierBalance::find($row->tran_id_ref);
                                    $date = optional($open)->open_date;
                                    $reference = optional($open)->reference;
                                }else{
                                    $po = \App\Models\Purchase::find($row->tran_id_ref);
                                    $date = optional($po)->p_date;
                                    $reference = optional($po)->bill_number;
                                }


                                if (isLogCode() > 0) {
                                    $pd = \App\Models\PurchaseDetail::where('purchase_id', $po->id)->get();

                                } else {
                                    $pd = null;

                                }
                                /*dd($po, $pd);*/

                                ?>
                                <tr id="id-{{$rand_id}}">
                                    <td class="center">
                                        <label>
                                            <input data-id="{{$rand_id}}" name="check_detail[{{$rand_id}}]"
                                                   data-balance="{{$row->balance}}" value="{{$rand_id}}" type="checkbox"
                                                   class="checkbox">
                                        </label>
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($date)->format('Y-m-d')}}</td>
                                    <td class="center">
                                        <span>{{$reference}}</span>
                                        <input type="hidden" name="d_reference_no[{{$rand_id}}]"
                                               value="{{$row->tran_id_ref}}"/>
                                    </td>
                                    @if(isLogCode() >0)
                                        <td>
                                            @if($pd != null)
                                                @forelse( $pd as$k=> $p)
                                                    <?php
                                                    $inv = \App\Models\Products\Inventory::find($p->product_id);
                                                    /*dd($inv);*/
                                                    ?>
                                                    @if($inv!=null)
                                                        @if( $inv->product_type == "inventory")
                                                            <span>{{optional($inv)->product_name??optional($inv)->product_name_in_kh??'-'}}{{$k=1?',':''}}&nbsp;</span>
                                                        @endif
                                                    @endif
                                                @empty
                                                    -
                                                @endforelse
                                            @endif
                                        </td>
                                    @endif
                                    <td nowrap>
                                        <span>{{$row->train_type_ref}}</span>
                                        <input type="hidden" name="d_transaction[{{$rand_id}}]"
                                               value="{{$row->train_type_ref}}"/>
                                    </td>
                                    <td nowrap class="right">
                                        <span>{{$row->balance}}</span>
                                        <input name="amount_used[{{$rand_id}}]" class="p_h_balance" type="hidden"
                                               value="{{$row->balance}}"/>
                                    </td>
                                    <td nowrap class="right">
                                        <span class="s-discount">0</span>
                                        <input data-id="{{$rand_id}}" name="discount_used[{{$rand_id}}]" type="hidden"
                                               class="h_discount">
                                    </td>
                                    <td class="right credit">
                                        <span class="s-credit"></span>
                                        <input data-id="{{$rand_id}}" name="credit_used[{{$rand_id}}]" type="hidden"
                                               class="h_credit">
                                    </td>
                                    <td class="right"><input data-id="{{$rand_id}}" name="amount_to_pay[{{$rand_id}}]"

                                                             data-validation="number" data-validation-allowing="range[0;{{$row->balance}}],float"
                                                             class="amount" type="text" value="0.00"></td>
                                    <td>
                                        <a data-id="{{$rand_id}}" href="javascript:void(0)" disabled="disabled"
                                           data-remote="false" data-toggle="modal"
                                           data-target="#show-discount-detail-{{$rand_id}}"><i class="fa fa-money"
                                                                                               style="color: green; font-size: 25px;"></i></a>
                                        <div class="modal fade" id="show-discount-detail-{{$rand_id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Discount and
                                                            Credits</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('partials.payment.reports.discount_pop',['rand_id'=>$rand_id,'credits'=>$credits])
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <a href="{{url("report/bill-list-pop/{$row->tran_id_ref}")}}" data-remote="false" data-toggle="modal"
                                           data-target="#show-purchase-detail" class="btn btn-default btn-xs"><span><i class="fa fa-eye"></i></span></a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="right">
                                    Total
                                </td>
                                <td class="right">
                                    <span class="total_balance"></span>
                                    <input type="hidden" name="total_amount" class="h_total_balance"/>
                                </td>
                                <td class="right">
                                    <span class="total_dis"></span>
                                    <input type="hidden" name="total_discount" class="h_total_dis">
                                </td>
                                <td class="right">
                                    <span class="total_credit"></span>
                                    <input type="hidden" name="total_credit" class="h_total_credit">
                                </td>
                                <td class="right">
                                    <span class="total_amt"></span>
                                    <input type="hidden" name="total_amount_to_used" class="h_total_amt">
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        @include('partials.payment_method',['_e'=>$_e])

                    </div>

                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="input-group">
                            <button type="button" class="btn btn-primary add_payment" data-toggle="modal"
                                    data-target="#payModal">Pay Selected Bills
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.direct-chat -->

        </div>
    </form>
    <div class="modal fade" id="show-purchase-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Purchase</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default glyphicon glyphicon-print"></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
    <script src="{{asset('js')}}/jquery.form-validator.min.js"></script>

    <script>
        $("#show-purchase-detail").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("href"));
        });
        $(function () {
            $('[data-bs-datetimepicker]').each(function () {

                var $fake = $(this),
                    $field = $fake.parents('.form-group').find('input[type="hidden"]'),
                    $suptomConfig = $.extend({
                        format: 'YYYY-MM-DD',
                        defaultDate: $field.val(),
                        @if(isset($field['allows_null']) && $field['allows_null'])
                        showClear: true,
                        @endif
                    }, $fake.data('bs-datetimepicker'));

                $suptomConfig.locale = $suptomConfig['language'];
                delete ($suptomConfig['language']);
                $picker = $fake.datetimepicker($suptomConfig);

                $fake.on('keydown', function (e) {
                    e.preventDefault();
                    return false;
                });

                $picker.on('dp.change', function (e) {
                    var sqlDate = e.date ? e.date.format('YYYY-MM-DD') : null;
                    $field.val(sqlDate);
                });

            });

            $('.checked_all').on('click', function () {
                if (this.checked) {
                    $('.checkbox').each(function () { //loop through each checkbox
                        $(this).prop('checked', true);
                        $(this).trigger('change');
                    });
                    calculate_amount();
                } else {
                    $('.checkbox').each(function () { //loop through each checkbox
                        $(this).prop('checked', false); //uncheck

                    });
                    calculate_amount();
                }
            });

            $('.checkbox').on('change', function () {

                var id = $(this).data('id');
                var tr = $('#id-' + id);

                if (this.checked) {
                    var disc = tr.find('.pop-discount').val() - 0;
                    var balance = $(this).data('balance') - 0;
                    var amount_pay = balance - disc;
                    tr.find('.amount').val(amount_pay);
                } else {
                    tr.find('.amount').val('0.00');
                    tr.find('.pop-discount').val('0.00');

                }
                calculate_amount();
            });


            $('.pop-discount').on('keyup', function () {
                var id = $(this).data('id');
                var tr = $('#id-' + id);
                line_cal(tr);
            });

            $('.amount').on('keydown', function () {
                $.validate({});
                calculate_amount();

            });
            $('.add_payment').on('click', function () {
                $.validate({});
                calculate_amount();

            });

            //$('#credit').hide();

            $('.a-discount-pop').on('click', function () {
                var p = $(this).parent().parent();
                p.find('.discount-pop-sub').show();
                p.find('.credit-pop-sub').hide();

            });

            $('.a-credit-pop').on('click', function () {
                var p = $(this).parent().parent();
                p.find('.discount-pop-sub').hide();
                p.find('.credit-pop-sub').show();
            });

            $('.p_checked_all').on('click', function () {
                if (this.checked) {
                    $('.credit_checkbox').each(function () { //loop through each checkbox
                        $(this).prop('checked', true); //check
                    });
                } else {
                    $('.credit_checkbox').each(function () { //loop through each checkbox
                        $(this).prop('checked', false); //uncheck
                    });
                }
            });
            $('.credit_checkbox').on('change', function () {
                var main_id = $(this).data('main_id');
                var main_tr = $('#main-' + main_id);
                var credit_id = $(this).data('id');
                var credit_tr = $('#credit-' + credit_id);
                var tr = main_tr.find(credit_tr);

                if (this.checked) {
                    var credit_amt = tr.find('.h_credit_amount').val() - 0;
                    tr.find('.credit_amt_used').val(credit_amt);

                } else {
                    tr.find('.credit_amt_used').val("0.00");

                }
                line_cal_credit(tr, main_tr);
                calculate_amount(tr, main_tr);

                var t_tr = $('#id-' + main_id);
                var credit_amt = main_tr.find('.h_total_credit_amt_used').val();

                t_tr.find('.s-credit').text(credit_amt);
                t_tr.find('.h_credit').val(credit_amt);

                calculate_amount();
                line_cal(t_tr);

            });

            $('.credit_amt_used').on('change', function () {
                // alert('hehe');
                var main_id = $(this).data('main_id');
                var main_tr = $('#main-' + main_id);
                var credit_id = $(this).data('id');
                var credit_tr = $('#credit-' + credit_id);
                var tr = main_tr.find(credit_tr);
                var t_tr = $('#id-' + main_id);

                $.validate({});

                main_tr.find('.credit_amt_used ').validate(function(valid, elem) {
                    // console.log('Element ' + elem.name + ' is ' + (valid ? 'valid' : 'invalid'));
                    // console.log(valid);
                    if (!valid ){
                        console.log($('#show-discount-detail-'+main_id));
                        main_tr.find('.credit_amt_used').val('0.00');
                    }
                });

                line_cal_credit(tr, main_tr);
                total_cal_credit(tr, main_tr);
                calculate_amount();


                var credit_amt = main_tr.find('.h_total_credit_amt_used').val();
                t_tr.find('.s-credit').text(credit_amt);
                t_tr.find('.h_credit').val(credit_amt);
                line_cal(t_tr);

            });



            $('.add_payment').on('click', function () {

                var get_currency = '<?php echo optional(\App\Models\Supply::find($supplier_id))->currencies->currency_name;?>';
                var get_currency_ex = '<?php echo optional(\App\Models\Supply::find($supplier_id))->currencies->exchange_rate;?>';
                var usd_exchange_rate = '<?php echo optional(\App\Models\Currency::where('currency_code', 'USD')
                    ->orwhere('currency_name', 'USD')
                    ->orwhere('currency_symbol', '$')->first())->exchange_rate;?>';
                var total_amt = $('.h_total_amt').val() - 0;
                var h_credit = $('.h_credit').val() - 0;
                var total_credit = $('.h_total_credit').val() - 0;

                if (total_amt == null){
                    total_amt=0;
                }
                if (total_credit == null){
                    total_credit=0;
                }
                if (get_currency == 'KHR' || get_currency == 'Riel'  ) {
                    var amount_to_usd = parseFloat((total_amt) * (usd_exchange_rate ? usd_exchange_rate : 1) / (get_currency_ex ? get_currency_ex : 1)).toFixed(2);
                    $('#p-amount').val('');
                    $('#p-amount-kh').val('');
                    $('#p-amount').val(amount_to_usd);
                    $('#p-amount-kh').val(total_amt.toLocaleString("en"));
                }
                if (total_amt == 0 && total_credit == 0) {
                    // if (h_credit == 0){
                        alert('no amount selected');
                        return false;
                    // }

                }
            });

            calculate_amount();
            //total_cal_credit();

        });

        function line_cal(tr) {
            var ch = tr.find('.checkbox');
            //alert(tr);
            if (ch.is(':checked')) {
                var disc = tr.find('.pop-discount').val() - 0;
                var credit = tr.find('.h_credit').val() - 0;
                var balance = ch.data('balance') - 0;
                var amount_pay = balance - disc - credit;
                tr.find('.amount').val(amount_pay);
                tr.find('.s-discount').text(disc);
                tr.find('.h_discount').val(disc);
            } else {
                tr.find('.amount').val('0.00');
                tr.find('.pop-discount').val('0.00');
                tr.find('.s-discount').text('');
                tr.find('.h_discount').val('0.00');
            }
            calculate_amount();

        }

        function currencyFormat(num) {
            return num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }

        function calculate_amount() {

            var total_amount = 0;


            $(".checkbox:not(:checked)").each(function () {
                var tr = $(this).parent().parent().parent();
                tr.find('.amount').val(0);
                tr.find('.s-discount').text(0);
                tr.find('.h_discount').val(0);
                tr.find('.s-credit').text(0);
                tr.find('.h_credit').val(0);
                tr.find('.credit_checkbox').each(function () {
                    $(this).prop("checked", false);
                });
            });

            $(".amount").each(function () {

                var amount = $(this).val() - 0;

                if (amount > 0) {
                    total_amount += amount;
                }
            });

            $('.total_amt').text(total_amount);
            $('.h_total_amt').val(total_amount);

            var kh_exchange_rate= '{{optional($kh_currency)->exchange_rate}}'*1;
            var total_kh= (kh_exchange_rate)*(total_amount*1);
            $('.h_total_amt_kh').val(currencyFormat(total_kh));

            //=================================

            var _total_balance = 0;
            var total_balance = 0;
            var h_total_amt = $('.h_total_amt').val() - 0;

            $('.p_h_balance').each(function () {
                var balance = $(this).val() - 0;

               /* console.log('balance == ' + balance);*/
                _total_balance += balance;

            });



            //====================
            var total_discount = 0;

            $('.pop-discount').each(function () {
                var disc = $(this).val() - 0;
                //console.log(disc);
                if(disc>0) {
                    total_discount += disc;
                }
            });
            $('.total_dis').text(total_discount);
            $('.h_total_dis').val(total_discount);
            //=====================

            var total_credit = 0;
            $('.h_credit').each(function () {
                var credit = $(this).val() - 0;
                total_credit += credit;
            });

            $('.total_credit').text(total_credit);
            $('.h_total_credit').val(total_credit);

            total_balance = _total_balance - h_total_amt-total_discount-total_credit;

            $('.total_balance').text(total_balance);
            $('.h_total_balance').val(total_balance);
        }

        function line_cal_credit(tr, main_tr) {
            var total_credit_balance = 0;
            var ch = tr.find('.credit_checkbox');
            //alert(tr);
            if (ch.is(':checked')) {
                var amount = tr.find('.h_credit_amount').val() - 0;
                var amt_used = tr.find('.credit_amt_used').val() - 0;
                total_credit_balance = amount - amt_used;
                tr.find('.credit_balance').text(total_credit_balance);
                tr.find('.h_credit_balance').val(total_credit_balance);
                tr.find('.s-credit').text(amt_used);
                tr.find('.h_credit').val(amt_used);

            } else {
                var amount = tr.find('.h_credit_amount').val() - 0;
                var amt_used = tr.find('.credit_amt_used').val() - 0;
                total_credit_balance = amount - amt_used;
                tr.find('.credit_balance').text(total_credit_balance);
                tr.find('.h_credit_balance').val(total_credit_balance);
                tr.find('.credit_amt_used').val('0.00');
                tr.find('.s-credit').text('');
                tr.find('.h_credit').val('0.00');
                total_cal_credit(tr, main_tr);
            }
            line_cal(tr);
            total_cal_credit(tr, main_tr);
        }

        function total_cal_credit(tr, main_tr) {
            var total_credit_amount = 0;
            main_tr.find('.h_credit_amount').each(function () {
                var credit_amount = $(this).val() - 0;
                total_credit_amount += credit_amount;
            });
            main_tr.find('.total_credit_amount').text(total_credit_amount);

            var total_credit_amt_used = 0;
            main_tr.find('.credit_amt_used').each(function () {
                var amt_used = $(this).val() - 0;
                total_credit_amt_used += amt_used;
            });
            main_tr.find('.total_credit_amt_used').text(total_credit_amt_used);
            main_tr.find('.h_total_credit_amt_used').val(total_credit_amt_used);

            var total_credit_balance = 0;
            main_tr.find('.h_credit_balance').each(function () {
                var balance = $(this).val() - 0;
                total_credit_balance += balance;
            });
            main_tr.find('.total_credit_balance').text(total_credit_balance);
            main_tr.find('.h_total_credit_balance').text(total_credit_balance);

        }

    </script>
@endsection
