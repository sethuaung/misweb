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
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
</head>
<body>
<?php

?>
<div class="container">
    <div class="row" style="margin-top: 20px">
        <form action="{{url('admin/customer_loan_deposit')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <table class="table table-bordered" style="width: 100%">
                <thead>
                <tr>
                    <th>Applicant No</th>
                    <th>Applicant Name</th>
                    <th>Applicant ID</th>
                    <th>Total Loans</th>
                    <th>Total Charge</th>
                    <th>Total Compulsory</th>
                    <th>Total</th>
                    <th>
                        <input type="checkbox" value="" id="check_all" class="c-checked-group">
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php
                $total =  0;
                $total_line_loan = 0;


                ?>
                @if($loan != null)

                    @foreach($loan as $row)

                        <?php
                              /// dd($row);
                        $client = optional(\App\Models\Client::find($row->client_id));
                        $rand_id = rand(1,9999).time().rand(1,9999);

                        $total_line_deposit =0;
                        $total_line_charge = 0;
                        $total_line_compulsory = 0;
                        $charges = \App\Models\LoanCharge::where('charge_type', 1)->where('status','Yes')->where('loan_id',$row->id)->get();
                        if($charges != null){
                            foreach ($charges as $c){
                                $amt_charge = $c->amount;
                                $total_line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                            }
                        }

                        $compulsory = \App\Models\LoanCompulsory::where('compulsory_product_type_id', 1)->where('loan_id',$row->id)->first();

                        if($compulsory != null){
                            $amt_compulsory = $compulsory->saving_amount;
                            $total_line_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($row->loan_amount*$amt_compulsory)/100));

                        }
                        ?>


{{--                        {{dd($row->id)}}--}}

                        <tr>
                            <td>{{$row->disbursement_number}}</td>
                            <td>{{$client->name}}</td>
                            <td>{{$client->client_number}}</td>
                            <td>{{$row->loan_amount}}</td>
                            <td>{{$total_line_charge}}</td>
                            <td>{{$total_line_compulsory}}</td>
                            <td>{{$total_line_compulsory+$total_line_charge}}</td>
                            <td>
                                <input type="checkbox" data-id="{{$rand_id}}" data-payment="{{$total_line_compulsory+$total_line_charge}}" name="checked[{{$rand_id}}]" value="{{$row->id}}" class="c-checked">
                                <input type="hidden" name="loan_id[{{$rand_id}}]" value="{{$row->id}}">

                                <input type="hidden" name="client_id[{{$rand_id}}]" value="{{$row->client_id}}"/>
                                <input type="hidden" name="total_loan[{{$rand_id}}]" value="{{$row->loan_amount}}"/>
                                <input type="hidden" name="total_charge[{{$rand_id}}]" value="{{$total_line_charge}}"/>
                                <input type="hidden" name="total_compulsory[{{$rand_id}}]" value="{{$total_line_compulsory}}"/>
                                <input type="hidden" name="total[{{$rand_id}}]" value="{{$total_line_compulsory+$total_line_charge}}"/>


                            </td>
                        </tr>

                        <?php
                        //$total += $row->loan_amount;
                        $total_line_deposit =$total_line_compulsory+$total_line_charge;
                        $total += $total_line_deposit;

                        ?>



                    @endforeach
                @endif
                <tr>
                    <td style="text-align: right" colspan="6">Total</td>
                    <td >{{$total}}</td>
                </tr>
                </tbody>
            </table>





            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Note</label>
                        <input class="form-control" name="approve_note">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Reference</label>
                        <input class="form-control" name="referent_no" value="{{\App\Models\PaidDisbursement::getSeqRef('group_deposit')}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Cash Payment</label>
                        <input type="text" class="form-control cash-topay" name="" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Date</label>
                        <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                        <span></span>
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

                <div class="col-sm-4">
                    <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Deposit" />
                </div>
            </div>
        </form>
    </div>
</div>


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
</body>
</html>
