<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-primary">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title" id="payModalLabel"> {{_t('Payment')}} </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <!-------------------
                        ##---- Payment ------
                        ##------------------>
                        <div class="row">
                            @if (companyReportPart() == 'company.myanmarelottery')
                                <div class="col-xs-8">
                                    <div class="form-group">
                                        <label for="p-amount">{{_t('Amount')}}</label>
                                        <input readonly value="{{optional($_e)->paid}}" name="paid" type="text" id="p-amount" number="number" class="pa form-control kb-pad h_total_amt">
                                    </div>
                                </div>
                            @else
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="p-amount">{{_t('Amount (USD)')}}</label>
                                        <input readonly value="{{optional($_e)->paid}}" name="paid" type="text" id="p-amount" number="number" class="pa form-control kb-pad h_total_amt">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="p-amount-kh">{{companyReportPart() == "company.theng_hok_ing"? _t('Amount (RIEL)') :_t('Amount ( KHR )')}}</label>
                                        <?php
                                        $kh_currency= \App\Models\Currency::where('currency_code','KHR')
                                            ->orWhere('currency_name','KHR')
                                            ->orWhere('currency_symbol','៛')
                                            ->first();
                                        $total_khr=0;
                                        if ($kh_currency != null){

                                            $total_khr=(optional($_e)->paid*1)*($kh_currency->exchange_rate*1);
                                        }

                                        ?>
                                        <input readonly value="{{$total_khr}}" name="paid_kh" type="text" id="p-amount-kh" number="number" class="pa form-control kb-pad h_total_amt_kh">
                                    </div>
                                </div>
                            @endif

                            <!--------------------------
                            ##---- Payment Method ------
                            ##------------------------->
                            <?php
                            $paid_by = optional($_e)->paid_by;
                            ?>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="paid_by">{{_t('Paying by')}}</label>
                                    <select id="paid_by" name="paid_by" class="form-control paid_by" style="width:100%;" tabindex="-1" aria-hidden="true">
                                        <option {{$paid_by == 'cash'?'selected':''}} value="cash">Cash</option>
                                        <option {{$paid_by == 'CC'?'selected':''}} value="CC">Credit Card</option>
                                        <option {{$paid_by == 'cheque'?'selected':''}} value="cheque">Cheque</option>
                                        <option {{$paid_by == 'gift_card'?'selected':''}} value="gift_card">Gift Card</option>
                                        <option {{$paid_by == 'stripe'?'selected':''}} value="stripe">Stripe</option>
                                        <option {{$paid_by == 'other'?'selected':''}} value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!---------------------------------
                        ##---- Payment Method Detail ------
                        ##-------------------------------->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group gc" style="display: none;">
                                    <label for="gift_card_no">Gift Card No</label>
                                    <input name="gift_card_no" value="{{optional($_e)->gift_card_no}}" type="text" id="gift_card_no" class="pa form-control kb-pad gift_card_no gift_card_input p-clear">
                                    <div id="gc_details"></div>
                                </div>
                                <div class="pcc" style="display:none;">
                                    <div class="form-group">
                                        <input type="text" value="{{optional($_e)->swipe_card}}" name="swipe_card" id="swipe" class="form-control swipe swipe_input p-clear" placeholder="Swipe card here then write security code manually">
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" value="{{optional($_e)->card_no}}" name="card_no" id="pcc_no" class="form-control kb-pad p-clear" placeholder="Credit Card No">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">

                                                <input type="text" value="{{optional($_e)->holder_name}}" name="holder_name" id="pcc_holder" class="form-control kb-text p-clear" placeholder="Holder Name">
                                            </div>
                                        </div>
                                        <?php
                                        $card_type = optional($_e)->card_type;
                                        ?>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <select id="pcc_type" name="card_type" class="form-control pcc_type select2 p-clear" placeholder="Card Type" tabindex="-1" aria-hidden="true">
                                                    <option {{$card_type == 'Visa'?'selected':''}} value="Visa">Visa</option>
                                                    <option {{$card_type == 'MasterCard'?'selected':''}} value="MasterCard">MasterCard</option>
                                                    <option {{$card_type == 'Amex'?'selected':''}} value="Amex">Amex</option>
                                                    <option {{$card_type == 'Discover'?'selected':''}} value="">Discover</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="text" value="{{optional($_e)->card_month}}" name="card_month" id="pcc_month" class="form-control kb-pad p-clear" placeholder="Month">
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="text" value="{{optional($_e)->card_year}}" name="card_year" id="pcc_year" class="form-control kb-pad p-clear" placeholder="Year">
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="text" value="{{optional($_e)->card_cvv}}" name="card_cvv" id="pcc_cvv2" class="form-control kb-pad p-clear" placeholder="CVV2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pcheque" style="display:none;">
                                    <div class="form-group"><label for="cheque_no">Cheque No</label>
                                        <input type="text" value="{{optional($_e)->cheque_no}}" name="cheque_no" id="cheque_no" class="form-control cheque_no kb-text p-clear">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row" style="background: rgba(120, 207, 253, 0.22); margin: 0px;box-shadow: 1px 0px 2px 1px #CCC; padding: 5px;">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="p-amount">{{_t('Paid By Name')}}</label>
                                    <input value="{{optional($_e)->paid_by_name}}" name="paid_by_name" type="text" id="p-amount" class="form-control paid_by_name">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{{_t('Received Name')}}</label>
                                    <input value="{{optional($_e)->received_name}}" name="received_name" type="text"  class="form-control received_name">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="p-amount">{{_t('Phone')}}</label>
                                    <input value="{{optional($_e)->phone}}" name="phone" type="text" class="pa form-control phone">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <label>Account</label>
                                <div class="form-group">
                                    <select id="pcc_type" name="cash_acc_id" class="form-control pcc_type select2 p-clear" placeholder="Card Type" tabindex="-1" aria-hidden="true">
                                        <?php
                                        $acc = \App\Models\AccountChart::where('section_id',10)->get();

                                        ?>

                                        @if($acc !=null)
                                            @foreach($acc as $a)
                                                <option {{optional($_e)->cash_acc_id == $a->id ? "selected":''}} value="{{$a->id}}">{{$a->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group"><label for="payment_note">Payment Note</label>
                                    <textarea id="payment_note" value="{{optional($_e)->payment_note}}" name="payment_note" class="form-control payment_note kb-text p-clear"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------------------------
                    ##----- Quick Payment ------
                    ##------------------------->
                   {{-- <div class="col-xs-3 text-center">
                        <div class="btn-group btn-group-vertical" style="width:100%;">
                            <button type="button" class="btn btn-info btn-block total_amt quick-amt" id="quick-payment" data-amt="0">0</button>
                            <button type="button" class="btn btn-block btn-warning quick-cash" data-amt="10">10</button>
                            <button type="button" class="btn btn-block btn-warning quick-cash" data-amt="20">20</button>
                            <button type="button" class="btn btn-block btn-warning quick-cash" data-amt="50">50</button>
                            <button type="button" class="btn btn-block btn-warning quick-cash" data-amt="100">100</button>
                            <button type="button" class="btn btn-block btn-danger" id="clear-cash-notes">Clear</button>
                        </div>
                    </div>--}}

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"> Close </button>
                <button class="btn btn-info" id="submit-sale">Submit</button>
            </div>
        </div>
    </div>
</div>
@push('crud_fields_styles')
    <style>
        .font16{
            font-size:15px;
            margin-bottom:10px;
        }
        .font16, .table-calendar .table td {
            font-weight: 700;
        }
        .modal-success .table-bordered > tbody > tr > td {
            background: #FFF;
            color: #333;
            border: 1px solid #00a65a;
        }
        #quick-payment .badge, .quick-cash .badge {
            position: absolute;
            top: 1px;
            right: 1px;
            padding: 1px 3px
        }
    </style>
@endpush
@push('crud_fields_scripts')
    <script>
        $(function(){
            $("#submit-sale").on('click',function() {
                $(this).hide();
            });
        });
        var ccc = 0;
        $(document).ready(function(){
            //##################### Change Payment Method ##################//
            $('.paid_by').on('change', function () {

                if(ccc > 0){
                    $('.p-clear').val("");
                }

                ccc++;

                var paid_by = $(this).val();

                if(paid_by == "cash" || paid_by == "other"){
                    $('.pcash').slideDown();
                    $('.gc').slideUp();
                    $('.pcheque').slideUp();
                    $('.pcc').slideUp();
                    $('.amount').focus();
                } else if(paid_by == "CC" || paid_by == "stripe") {
                    $('.pcash').slideUp();
                    $('.gc').slideUp();
                    $('.pcheque').slideUp();
                    $('.pcc').slideDown();
                    $('.swipe_input').focus();
                } else if(paid_by == "cheque") {
                    $('.pcash').slideUp();
                    $('.gc').slideUp();
                    $('.pcheque').slideDown();
                    $('.pcc').slideUp();
                    $('.cheque_no').focus();
                } else {
                    $('.pcash').slideUp();
                    $('.gc').slideDown();
                    $('.pcheque').slideUp();
                    $('.pcc').slideUp();
                    $('.gift_card_no').focus();
                }
            });

            @if($paid_by != null)
            $('.paid_by').val('{{$paid_by}}');
            $('.paid_by').trigger('change');
            @endif
            //######################## Quick Payment #######################//
            $(document).on('click', '.quick-cash', function () {
                var $quick_cash = $(this);
                var note_count  = $quick_cash.find('span');
                $('#quick-payment').find('.badge').remove();
                if (note_count.length == 0) {
                    $quick_cash.append('<span class="badge">1</span>');
                } else {
                    note_count.text(parseInt(note_count.text()) + 1);
                }
                calculate_payment();
            });

            $(document).on('click', '#quick-payment', function () {
                var $quick_cash = $(this);
                $('.quick-cash').find('.badge').remove();
                $quick_cash.find('.badge').remove();
                $quick_cash.append('<span class="badge">1</span>');
                calculate_payment();
            });

            //###################### Remove Payment ######################//
            $(document).on('click', '#clear-cash-notes', function () {
                $('.quick-cash').find('.badge').remove();
                $('#quick-payment').find('.badge').remove();
                $('.p-amount').val('0').focus();
                calculate_payment();
            });

            $(document).on('show.bs.modal', '.modal', function () {
                calculate_payment({{optional($_e)->paid}});
            });
            $(document).on('hidden.bs.modal', '.modal', function () {
                calculate_payment();
                $('.quick-cash').find('.badge').remove();
                $('#quick-payment').find('.badge').remove();
            });

            $(document).on('keyup', '.p-amount', function () {
                var paid = $(this).val();
                calculate_payment(paid);
            });

        });

        function calculate_payment(amt_paid = 0){
            var grand_total = round2($('.h_total_amt').val() - 0,6);
            var paid        = amt_paid?amt_paid:calQuickAmt();
            var balance     = round2(grand_total - paid, 6);
            $('#total_paying').text(paid);
            $('.p-amount').val(paid);
            $('#balance').text(balance);
            $('.p-balance').val(balance);
            $('.h_balance').val(balance);
        }

        function calQuickAmt() {
            var t = 0;
            $('.badge').each(function () {
                var n = $(this).text() - 0;
                var p = $(this).parent();
                var amt = p.data('amt') - 0;

                if(n > 0 && amt >0){
                    t += round2(n * amt,6) ;
                }

            });

            return t;
        }
        function round2(value, exp) {
            value = value -0;
            return  Math.round(value * 10000) / 10000;
        }

    </script>
@endpush
