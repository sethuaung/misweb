<?php
$disbursement_id = request()->id;
$disburse = optional(\App\Models\Loan::find($disbursement_id));
$total_disburse = $disburse->loan_amount??0;
$charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',$disbursement_id)->get();
?>
@if($charges != null)
    @foreach ($charges as $c)
    <?php
    $amt_charge = $c->amount;
    $line_charge = ($c->charge_option == 1?$amt_charge:(($total_disburse*$amt_charge)/100));
    ?>
        <div class="col-sm-3">
            <label>{{$c->name}}</label>
            <input class="form-control" value="{{$line_charge}}">
        </div>

    @endforeach
@endif
