@push('crud_fields_styles')

@endpush

@push('crud_fields_scripts')

    <script>
        $(function () {
            $('[name="penalty_amount"]').on('keyup',function () {
                get_payment();
            });
            $('[name="other_payment"]').on('keyup',function () {
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
            get_payment();
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
            $('[type="submit"]').on('click',function () {
                $(this).hide();
            });
        }
    </script>
    <script>
        $(document).ready(function(){
            var pre_repayment = $('[id="pre_repayment"]').val();
            var late_repayment = $('[id="late_repayment"]').val();
            if(pre_repayment == 1 || late_repayment == 1){
                $('#interest').attr('readonly', false);
                $('#principle').attr('readonly', false);
            }
            else{
                $('#interest').attr('readonly', true); 
                $('#principle').attr('readonly', true);
            }
        })
    </script>

    <script>
        $('#pre_repayment').change(function(){
            var pre_repayment = $('[id="pre_repayment"]').val();
            //console.log(pre_repayment);
            if(pre_repayment == 1){
                $('#interest').attr('readonly', false);
            }
            else{
                $('#interest').attr('readonly', true); 
            }
        })
        $('#late_repayment').change(function(){
            var late_repayment = $('[id="late_repayment"]').val();
            //console.log(pre_repayment);
            if(late_repayment == 1){
                $('#interest').attr('readonly', false);
                $('#principle').attr('readonly', false);
            }
            else{
                $('#interest').attr('readonly', true); 
                $('#principle').attr('readonly', true);
            }
        })
    </script>
    <script>
        $('#interest').keyup(function(){
            //console.log("nyi nyi");
           var interest = $('[id="interest"]').val();
           var principle = $('[id="principle"]').val();
           var total = parseInt(interest) + parseInt(principle);
           $('#total_payment').val(total);
           $('#payment').val(total);
        })
        $(function(){
           var h_principleBalance = $('[id="principle_balance"]').val();
           var principle_balance = $('[id="principle_balance"]').val();
           var h_principle = $('[id="principle"]').val();
           

           $('#principle').keyup(function(){

           var interest = $('[id="interest"]').val();
           var principle = $('[id="principle"]').val();
           var total = parseInt(interest) + parseInt(principle);
           $('#payment').val(total);
           $('#total_payment').val(total);

           if( parseInt(principle) > parseInt(h_principle)){
            var plus_balance = parseInt(principle) - parseInt(h_principle);
            var balance = parseInt(principle_balance) - plus_balance;
            $('#principle_balance').val(balance);
           }else{
            var minus_balance = parseInt(h_principle) - parseInt(principle);
            var balance = parseInt(principle_balance) + minus_balance;
            $('#principle_balance').val(balance);
           }
        })
        });
    </script>
@endpush
