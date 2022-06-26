
<?php $base=asset('vendor/adminlte') ?>
<link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
<link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Theme style -->
<link rel="stylesheet"
      href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<style>
    .pg-d url{
        display: block !important;
    }
</style>

<table class="table table-bordered" style="width: 100%;background-color: white">
    <thead>
    <tr>
        <th>CenterID-Name</th>
        <th>Group ID</th>
        <th>Group Name</th>
        <th>Payment Date</th>
        <th>Term No</th>
        <th>Loan Product</th>
        {{--<th>Loan Repayment</th>--}}
        <th>Compulsory</th>
        <th>Principle</th>
        <th>Interest</th>
        <th>Total Payment</th>
        <th><input type="checkbox" id="check_all_group"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $t_loan_repayment = 0;
    $t_other_payment = 0;
    $t_payment = 0;
    $t_principle=0;
    $t_interest=0;
    ?>

    @if($g_pending != null)
        @php
            $group_mem = $g_pending->groupBy('group_loan_id');
        @endphp
        @foreach($group_mem as $g_id => $rows)
            <?php

            $group = \App\Models\GroupLoan::find($g_id);
            $rand_id = rand(1,1000).time().rand(1,1000);
            $center = null;
            if($group != null){
                $center = \App\Models\CenterLeader::find($group->center_id);
            }
            //$total += $row->amount;

            $t_line_loan_repayment = 0;
            $t_line_other_payment = 0;
            $t_line_payment = 0;
            $t_line_compulsory = 0;
            $t_line_charge =0;
            $pay_date = '';
            $pay_term = '';
            $loan_product= '';
            $principle=0;
            $interest=0;
            $principle2=0;
            $interest2=0;

            foreach ($rows as $row){

                $line_loan_repayment = 0;
                $line_other_payment = 0;
                $line_payment = 0;
                $line_compulsory = 0;
                $line_charge =0;
                $principle=0;
                $interest=0;
                //============ compulsory saving==================
                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$row->id)->first();

                if($compulsory != null){

                    if($compulsory->compulsory_product_type_id == 3){

                        if($compulsory->charge_option == 1){
                            $line_compulsory = $compulsory->saving_amount;
                        }elseif($compulsory->charge_option == 2){
                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                        }
                    }
                    if(($compulsory->compulsory_product_type_id == 4) && ($last_no%2==0)){

                        if($compulsory->charge_option == 1){
                            $line_compulsory = $compulsory->saving_amount;
                        }elseif($compulsory->charge_option == 2){
                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                        }
                    }
                    if($compulsory->compulsory_product_type_id == 5 && ($last_no%3==0)){
                        if($compulsory->charge_option == 1){
                            $line_compulsory = $compulsory->saving_amount;
                        }elseif($compulsory->charge_option == 2){
                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                        }
                    }
                    if($compulsory->compulsory_product_type_id == 6 && ($last_no%6==0)){
                        if($compulsory->charge_option == 1){
                            $line_compulsory = $compulsory->saving_amount;
                        }elseif($compulsory->charge_option == 2){
                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                        }
                    }

                }

                //==============Total Repayment=========================

                $loan_d = \App\Models\LoanCalculate::where('disbursement_id',$row->id)
                    ->where('total_p','=',0)/*->whereDate('date_s','>=',$start_date)
                    ->whereDate('date_s','<=',$end_date)*/->where('payment_status','pending')->first();
                $last_no = 0;
                $pay_date = \Carbon\Carbon::parse($loan_d->date_s)->format('d-m-Y');
                $pay_term = $loan_d->no;
                if($loan_d != null){
                    $principal_s = $loan_d->principal_s??0;
                    $interest_s = $loan_d->interest_s??0;
                    $penalty_s = $loan_d->penalty_s??0;

                    $line_loan_repayment = $principal_s + $interest_s + $penalty_s;

                    if($line_loan_repayment >0){
                        $t_line_loan_repayment += $line_loan_repayment;
                    }
                    $last_no = $loan_d->no;
                }

                //============Service Charge==================
                $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('status','Yes')->where('loan_id',$row->id)->get();
                if($charges != null){
                    foreach ($charges as $c){
                        $amt_charge = $c->amount;
                        $line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                    }
                }

                if($line_compulsory>0){
                    $t_line_compulsory += $line_compulsory;
                }

                if($line_charge >0){
                    $t_line_charge += $line_charge;
                }
                $t_line_other_payment = $t_line_compulsory + $t_line_charge;
                $t_line_payment = $t_line_loan_repayment + $t_line_other_payment;

                $l_product=\App\Models\LoanProduct::find($row->loan_production_id);
                $loan_product=optional($l_product)->name;

                //find principle and interest
                $principle+=\App\Models\LoanCalculate::where('disbursement_id',$row->id)
                    ->whereDate('date_s','=',date('Y-m-d'))
                    ->where('payment_status','!=','paid')
                    ->sum('principal_s');
                $interest+=\App\Models\LoanCalculate::where('disbursement_id',$row->id)
                    ->whereDate('date_s','=',date('Y-m-d'))
                    ->where('payment_status','!=','paid')
                    ->sum('interest_s');

                $principle2+=$principle;
                $interest2+=$interest;

            }

            $t_loan_repayment += $t_line_loan_repayment;
            $t_other_payment += $t_line_other_payment;
            $t_payment += $t_line_payment;

            $t_principle+=$principle2;
            $t_interest+=$interest2;

            ?>

            <tr id="p-{{$rand_id}}">
                <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                <td>{{optional($group)->group_code}}</td>
                <td>{{optional($group)->group_name}}</td>
                <td>{{$pay_date}}</td>
                <td>{{$pay_term}}</td>
                <td>{{$loan_product}}</td>
{{--                <td>{{ $t_line_loan_repayment }}</td>--}}
                <td>{{ $t_line_other_payment }}</td>
                <td>{{$principle2}}</td>
                <td>{{$interest2}}</td>
                <td>{{$t_line_payment}}</td>
                <td>

                    <a href="{{url("admin/list-member-repayment?group_loan_id={$g_id}&rand_id={$rand_id}")}}"
                       data-remote="false" data-toggle="modal" data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                    <input type="checkbox" data-payment="{{$t_line_payment}}" class="form-check-input c-checked-group" name="approve_check[{{$rand_id}}]" value="{{$g_id}}"/>
                    <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$g_id}}">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr>
        <td colspan="6" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
{{--        <td>{{$t_loan_repayment}}</td>--}}
        <td>{{$t_other_payment}}</td>
        <td>{{$t_principle}}</td>
        <td>{{$t_interest}}</td>
        <td>{{$t_payment}}</td>
        <td></td>
    </tr>
    </tfoot>
</table>

<script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>




