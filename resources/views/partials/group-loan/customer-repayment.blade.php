<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


    <?php $base=asset('vendor/adminlte') ?>
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<body>
    <?php

    ?>
<div class="container">
    <div class="row" style="margin-top: 20px">
       <form action="{{url('admin/list-member-repayment')}}" method="post">
           <input type="hidden" name="_token" value="{{csrf_token()}}">
           <table class="table table-bordered" style="width: 100%">
               <thead>
               <tr>
                   <th>Applicant No</th>
                   <th>Branch Name</th>
                   <th>Client ID</th>
                   <th>Client Name</th>
                   <th>Loan Product</th>
                   <th>Center ID</th>
                   <th>Group ID</th>
                   {{--<th>Loans Repay</th>--}}
                   <th>Compulsory</th>
                   <th>Principle</th>
                   <th>Interest</th>
                   <th>Total Payment</th>
                   <th>
                       <input type="checkbox" value="" id="check_all" class="c-checked-group">
                   </th>
               </tr>
               </thead>
               <tbody>


               @if($loan != null)
                   <?php
                   $rand_id = rand(1,1000).time().rand(1,1000);

                   //$total += $row->amount;
                   $t_loan_repayment = 0;
                   $t_other_payment = 0;
                   $t_payment = 0;
                   $t_principle=0;
                   $t_interest=0;

                   ?>
                   @foreach ($loan as $row)
                        <?php

                            $line_loan_repayment = 0;
                            $line_other_payment =0 ;
                            $line_compulsory = 0;
                            $line_charge = 0;
                            $line_payment = 0;
                            $principle=0;
                            $interest=0;
                            $principle=\App\Models\LoanCalculate::where('disbursement_id',$row->id)->sum('principal_s');
                            $interest=\App\Models\LoanCalculate::where('disbursement_id',$row->id)->sum('interest_s');

                            $t_principle+=$principle;
                            $t_interest+=$interest;

                        $client = \App\Models\Client::find($row->client_id);
                       $rand_id = rand(1,9999).time().rand(1,9999);

                       $loan_d = \App\Models\LoanCalculate::where('disbursement_id',$row->id)
                           ->whereNull('date_p')->orderBy('date_s','asc')->first();
                       $last_no = 0;
                       if($loan_d != null){
                           $principal_s = $loan_d->principal_s??0;
                           $interest_s = $loan_d->interest_s??0;
                           $penalty_s = $loan_d->penalty_s??0;

                           $line_loan_repayment = $principal_s + $interest_s + $penalty_s;

                           if($line_loan_repayment >0){
                               $t_loan_repayment += $line_loan_repayment;
                           }
                           $last_no = $loan_d->no;
                       }

                       //============ compulsory saving==================
                       $compulsory = \App\Models\LoanCompulsory::where('loan_id',$row->id)->first();
                       $disburse=\App\Models\Loan::find($row->id);
                       if($compulsory != null){

                           if($compulsory->compulsory_product_type_id == 3){

                               if($compulsory->charge_option == 1){
                                   $line_compulsory = $compulsory->saving_amount;
                               }elseif($compulsory->charge_option == 2){
                                   $line_compulsory = ($compulsory->saving_amount*optional($disburse)->loan_amount)/100;
                               }
                           }
                           if(($compulsory->compulsory_product_type_id == 4) && ($last_no%2==0)){

                               if($compulsory->charge_option == 1){
                                   $line_compulsory = $compulsory->saving_amount;
                               }elseif($compulsory->charge_option == 2){
                                   $line_compulsory = ($compulsory->saving_amount*optional($disburse)->loan_amount)/100;
                               }
                           }
                           if($compulsory->compulsory_product_type_id == 5 && ($last_no%3==0)){
                               if($compulsory->charge_option == 1){
                                   $line_compulsory = $compulsory->saving_amount;
                               }elseif($compulsory->charge_option == 2){
                                   $line_compulsory = ($compulsory->saving_amount*optional($disburse)->loan_amount)/100;
                               }
                           }
                           if($compulsory->compulsory_product_type_id == 6 && ($last_no%6==0)){
                               if($compulsory->charge_option == 1){
                                   $line_compulsory = $compulsory->saving_amount;
                               }elseif($compulsory->charge_option == 2){
                                   $line_compulsory = ($compulsory->saving_amount*optional($disburse)->loan_amount)/100;
                               }
                           }

                       }



                       //============Service Charge==================
                       $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',$row->id)->get();
                       if($charges != null){
                           foreach ($charges as $c){
                               $amt_charge = $c->amount;
                               $line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                           }
                       }
                        $line_other_payment = $line_compulsory + $line_charge;

                        $line_payment = $line_other_payment + $line_loan_repayment;

                        $t_other_payment += $line_other_payment;
                        $t_payment += $line_payment;
                        $l_product = \App\Models\LoanProduct::find($row->loan_production_id);

                   ?>
                   <tr>
                       <td>{{$row->disbursement_number}}</td>
                       <td>{{optional(\App\Models\BranchU::find($row->branch_id))->title}}</td>
                       <td>{{$client->client_number}}</td>
                       <td>{{$client->name_other}}</td>
                       <td>{{optional($l_product)->name}}</td>
                       <td>{{optional(\App\Models\CenterLeader::find($row->center_leader_id))->code}}</td>
                       <td>{{optional(\App\Models\GroupLoan2::find($row->group_loan_id))->group_code}}</td>
{{--                       <td>{{$line_loan_repayment}}</td>--}}
                       <td>{{$line_other_payment}}</td>
                       <td>{{$principle}}</td>
                       <td>{{$interest}}</td>
                       <td>{{$line_payment}}</td>
                       <td>
                           <input type="checkbox" data-id="{{$rand_id}}" data-payment="{{$line_payment}}" name="list_customer_checked[{{$rand_id}}]" value="{{$row->id}}" class="c-checked">
                           <input type="hidden" name="loan_id[{{$rand_id}}]" value="{{$row->id}}">
                       </td>
                   </tr>
                   @endforeach
               @endif
               <tr>
                   <td style="text-align: right" colspan="7">Total</td>

{{--                   <td style="text-align: right">{{$t_loan_repayment}}</td>--}}
                   <td style="text-align: right">{{$t_other_payment}}</td>
                   <td style="text-align: right">{{$t_principle}}</td>
                   <td style="text-align: right">{{$t_interest}}</td>
                   <td style="text-align: right">{{$t_payment}}</td>

                   <td></td>

               </tr>
               </tbody>
           </table>
           <div class="row">
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>Reference</label>
                       <input class="form-control" name="reference" value="{{\App\Models\PaidDisbursement::getSeqRef('group_repayment')}}">
                   </div>
               </div>
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>Note</label>
                       <input class="form-control" name="approve_note">
                   </div>
               </div>
               <div class="col-sm-2">
                   <div class="form-group">
                       <label>Date</label>
                       <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                       <span></span>
                   </div>
               </div>
               <div class="col-sm-2">
                   <div class="form-group">
                       <label>Cash Payment</label>
                       <input type="text" class="form-control cash-topay" name="" value="">
                   </div>
               </div>
               <div class="col-sm-2">
                   <label>Cash Out</label>
                   <select class="form-control" name="cash_out">
                        <?php
                            $acc_chart = \App\Models\AccountChart::all();
                        ?>
                       @if($acc_chart != null)
                           @foreach($acc_chart as $acc)
                               <option value="{{$acc->id}}">{{$acc->name}}</option>
                           @endforeach
                       @endif
                   </select>
               </div>
               <div class="col-sm-2">
                   <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Payment" />
               </div>
           </div>
       </form>
    </div>
</div>

<script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        // $('.select-province').select2();
        $('.c-checked-group').on('change',function(event) {

            if(this.checked) { // check select status
                $('.c-checked').each(function() {
                    this.checked = true;  //select all
                    sum_payment();

                });
            }else{
                $('.c-checked').each(function() {
                    this.checked = false; //deselect all
                    sum_payment();
                });
            }
        });
        $('.c-checked').on('change',function () {
            sum_payment();

        });
        $('.js-cash-out').select2({
            theme: 'bootstrap',
            multiple: false,
            ajax: {
                url: '{{url("api/account-cash")}}',
                dataType: 'json',
                quietMillis: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                },
                processResults: function (data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 1;
                    var result = {
                        results: $.map(data.data, function (item) {

                            return {
                                text: item["name"],
                                id: item["id"]
                            }
                        }),
                        pagination: {
                            more: data.current_page < data.last_page
                        }
                    };
                    return result;
                }
            }
        });

    });
    $(function () {
        $('.approve_date').datepicker({
                format: 'yyyy-mm-dd',
            }
        );
    });
    function sum_payment() {
        var total = 0;
        $('.c-checked').each(function () {

            if($(this).is(':checked')){
                var payment = $(this).data('payment');
                total += payment;
            }
        });
        $('.cash-topay').val(total);
    }
</script>
    <script>
        $(function () {
            // $('.select-province').select2();
            $('#check_all_group').on('change',function(event) {

                if(this.checked) { // check select status
                    $('.c-checked-group').each(function() {
                        this.checked = true;  //select all
                        sum_payment();

                    });
                }else{
                    $('.c-checked-group').each(function() {
                        this.checked = false; //deselect all

                        sum_payment();
                    });
                }
            });
            $('.c-checked-group').on('change',function () {
                sum_payment();
            });

        });
        $(function () {
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
        });
    </script>
</body>
</html>
