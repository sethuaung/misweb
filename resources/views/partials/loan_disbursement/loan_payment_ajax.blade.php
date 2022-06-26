<div id="service_charge">
    <?php
         $payment_pending_id = request()->payment_pending_id??0;
         $payment_tem = \App\Models\LoanPaymentTem::find($payment_pending_id);
         $loan = \App\Models\Loan::find(optional($payment_tem)->disbursement_id);
         $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',optional($payment_tem)->disbursement_id)->get();
         $type = request()->type??'';

    ?>
 @if($charges != null)
    @foreach($charges as $r)
        <?php
                $amt = 0;
                if($r->charge_option==1){
                    $amt = $r->amount;
                }else {
                    $amt = (optional($loan)->loan_amount * $r->amount)/100;
                }
         ?>
        <div class="col-sm-3" style="margin: 0;padding: 0;">
            <label>{{$r->name}}</label>
            <input value="{{$amt}}" type="text" name="service_charge[{{$r->id}}]" class="form-control service-charge" placeholder="Enter ...">
        </div>

   @endforeach
  @endif


</div>
<?php
    $_e = isset($entry)?$entry:null;
?>
@push('crud_fields_scripts')
    <script>
        $(function () {
            $('[name="penalty_amount"]').on('keyup',function () {
                get_payment();
            });
            $('[name="other_payment"]').on('keyup',function () {
                get_payment();
            });
            $('[name="principle"]').on('keyup',function () {
                get_payment();
            });
            $('[name="interest"]').on('keyup',function () {
                get_payment();
            });
            $('[name="payment"]').on('keyup',function () {
                var payment = $(this).val()-0;
                var total_payment = $('[name="total_payment"]').val()-0;
                var owed_balance = total_payment - payment;
                if(owed_balance >=0) {
                    $('[name="owed_balance"]').val(round(owed_balance, 2));
                }else{
                    $(this).val(total_payment);
                    $('[name="owed_balance"]').val(0);
                }
            });

            $('body').on('change', '.disbursement_id', function () {

                var disbursement_id = $('[name="disbursement_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get--loand-disbursement/')}}'+ '/' + disbursement_id,
                    data: {
                        disbursement_id: disbursement_id,
                    },
                    success: function (res) {
                        var str = '';
                        if(res.interst_method >0 || res.flexible == 1){
                            console.log(res);
                            $('[name="principle"]').removeAttr("readonly");
                            $('[name="interest"]').removeAttr("readonly");
                            $('[name="payment"]').attr("readonly", "readonly");
                            $('[name="principle"]').val(res.principal_s);
                            $('[name="interest"]').val(res.interest_s);
                            $('[name="total_payment"]').val((res.principle_s+res.interest_s));

                        }else{
                            $('[name="interest"]').attr("readonly", "readonly");
                            $('[name="principle"]').attr("readonly", "readonly");
                            $('[name="principle"]').val(res.principal_s);
                            $('[name="interest"]').val(res.interest_s);
                            $('[name="total_payment"]').val(res.total_p);


                        }

                        $('[name="client_name"]').val(res.client);
                        $('[name="client_id"]').val(res.client_id);
                        $('[name="client_number"]').val(res.client_number);
                        $('[name="penalty_amount"]').val(res. penalty_s);

                        $('[name="payment"]').val(res.total_p);

                        $('[name="owed_balance"]').val(res.owed_balance);
                        $('[name="principle_balance"]').val(res.princilpale_balance);
                        $('[name="compulsory_saving"]').val(res.compulsory);
                        $('[name="disbursement_detail_id"]').val(res.disbursement_detail_id);
                        $.each(res.charges,function (index,element) {
                            var amt = 0;
                            if(element["charge_option"]==1){
                                amt = element['amount'];
                            }else {
                                amt = (res.total_disburse * element['amount'])/100;
                            }
                             str += '<div class="form-group col-sm-3" style="margin: 0;padding: 0;">\n' +
                                '<label>'+element["name"]+'</label>\n' +
                                '<input value="'+amt+'" type="text" name="service_charge['+element['id']+']" class="form-control service-charge" placeholder="Enter ...">\n' +
                                '</div>';
                        });
                        $('#service_charge').html(str);
                        get_payment();
                    }


                });

                get_payment();
            });
            @if($type=='payment_tem')
            var penalty_amount = $('[name="penalty_amount"]').val()-0;
            var principle = $('[name="principle"]').val()-0;
            var interest = $('[name="interest"]').val()-0;
            var old_owed = 0;// $('[name="old_owed"]').val()-0;
            var other_payment = $('[name="other_payment"]').val()-0;
            var compulsory_saving = $('[name="compulsory_saving"]') ? $('[name="compulsory_saving"]').val()-0:0;
            compulsory_saving = compulsory_saving>0?compulsory_saving:0;

            var charge =0;
            $('.service-charge').each(function () {
                var v = $(this).val()-0;
                if(v>0){
                    charge += v;
                }
            });
               var payment = 0;
                payment = penalty_amount + principle + interest + old_owed + other_payment + compulsory_saving +charge;
                $('[name="payment"]').val(round(payment,2));
                $('[name="total_payment"]').val(round(payment,2));
            @endif

        });
        function get_payment() {

            var penalty_amount = $('[name="penalty_amount"]').val()-0;
            var principle = $('[name="principle"]').val()-0;
            var interest = $('[name="interest"]').val()-0;
            var old_owed = 0;// $('[name="old_owed"]').val()-0;
            var other_payment = $('[name="other_payment"]').val()-0;
            var compulsory_saving = $('[name="compulsory_saving"]') ? $('[name="compulsory_saving"]').val()-0:0;
            compulsory_saving = compulsory_saving>0?compulsory_saving:0;

            var charge =0;
            $('.service-charge').each(function () {
                var v = $(this).val()-0;
                if(v>0){
                    charge += v;
                }
            });

            var payment = 0;
            payment = penalty_amount + principle + interest + old_owed + other_payment + compulsory_saving +charge;

            $('[name="payment"]').val(round(payment,2));
            $('[name="total_payment"]').val(round(payment,2));
        }
        @if(optional($_e) != null)
        $(function () {
            $('[name="client_id"]').trigger('change');
            $('[name="branch_id"]').trigger("change");
        });
        @endif


    </script>
@endpush
