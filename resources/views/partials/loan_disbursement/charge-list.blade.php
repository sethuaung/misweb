<?php
$loan_product = isset($loan_product)?$loan_product:'';
$charges = optional($loan_product)->loan_products_charge;
$compulsory  = optional($loan_product)->compulsory_product;
$charge_option= array(
    '1' => 'Fixed Amount',
    '2' => 'Of Loan amount',
    /*'3' => 'Of Principle amount',
    '4' => 'Of Interest amount',
    '5' => 'Of Principle + Interest amount',
    '6' => 'Of Remaining Balance',*/
);
$charge_type= array(
    '1' => 'Deposit Before Disbursement',
    '2' => 'Deduct from loan disbursment',
    '3' => 'Every Repayments',
    /*'4' => 'Every 2 terms Repayments',
    '5' => 'Every 3 terms Repayments',
    '6' => 'Every 6 terms Repayments',*/
);

$status = array(
    'Yes' => 'Yes',
    'No' => 'No',
);



$compound_interest=array(
    '0' => 'None',
    '1' => 'Every Months',
    '2' => 'Every 2 Months',
    '3' => 'Every 3 Months',
    '6' => 'Every 6 Months',
    '12' => 'Every 12 Months',
    '13' => 'At End Of the Year',
);

$compulsory_product_type = array(
    '1' => 'Deposit Before Disbursement',
    '2' => 'Deduct from loan Disbursement',
    '3' => 'Every Repayments',
    '4' => 'Every 2 terms Repayments',
    '5' => 'Every 3 terms Repayments',
    '6' => 'Every 6 terms Repayments'
);

$override = [
    //'' => ' ',
    'yes' => 'Yes',
    'no' => 'No',
];


?>

@if($charges != null)
    <h3>Charge</h3>
    <table class="table table-bordered" id="table-data" style="margin-top: 20px;width: 100%; overflow-x: auto;">
        <thead>
        <tr>
            <th>Service Name</th>
            <th>Service Amount</th>
            <th>Charge Option</th>
            <th>Charge Type</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($charges as $c)
            <?php
            $rand_id = rand(1,1000).time().rand(1,1000);
            ?>
            <tr>
                <td>
                    <input type="hidden" class="form-control" name="charge_id[{{$rand_id}}]" value="{{optional($c)->id}}">
                    <input type="hidden" class="form-control" name="loan_charge_id[{{$rand_id}}]" value="">
                    <input class="form-control" name="name[{{$rand_id}}]" value="{{$c->name}}">

                </td>
                <td><input class="form-control" name="amount[{{$rand_id}}]" value="{{$c->amount}}"></td>
                <td>
                    {{--{{$c->charge_option}}<input class="form-control" name="name[]">--}}
                    <select class="form-control" name="charge_option[{{$rand_id}}]">
                        @foreach($charge_option as $ke => $va)
                            <option value="{{$ke}}" {{optional($c)->charge_option==$ke?'selected="selected"' : ''}}>{{$va}}</option>
                        @endforeach
                    </select>

                </td>
                <td>

                    {{--{{$c->charge_type}}<input class="form-control" name="name[]">--}}

                    <select class="form-control" name="charge_type[{{$rand_id}}]">
                        @foreach($charge_type as $ke_ => $va_)
                            <option value="{{$ke_}}" {{optional($c)->charge_type==$ke_?'selected="selected"' : ''}}>{{$va_}}</option>
                        @endforeach
                    </select>

                </td>
                <td>
                    <select class="form-control" name="status[{{$rand_id}}]">
                        @foreach($status as $ke1 => $va1)
                            <option value="{{$ke1}}" {{optional($c)->status==$ke1?'selected="selected"' : ''}}>{{$va1}}</option>
                        @endforeach
                    </select>


                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
<br>
@if($compulsory!=null)
    <h3>Compulsory Saving</h3>
    <table class="table table-bordered" id="table-data" style="margin-top: 20px;width: 100%; overflow-x: auto;">
        <thead>
        <tr>
            <th>Saving Name</th>
            <th>Saving Amount</th>
            <th>Charge Option</th>
            <th>Monthly Interest</th>
            <th>Product Type</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody id="compulsory-list">
        <tr>
            <td>
                <input type="hidden" class="form-control" name="compulsory_id" value="{{optional($compulsory)->id}}">
                <input type="hidden" class="form-control" name="compound_interest" value="{{optional($compulsory)->compound_interest}}">
                <input type="hidden" class="form-control" name="override_cycle" value="{{optional($compulsory)->override_cycle}}">
                <input type="hidden" class="form-control" name="loan_compulsory_id" value="">
                <input class="form-control" name="product_name" value="{{optional($compulsory)->product_name}}">
            </td>
            <td>
                <input class="form-control" name="saving_amount" value="{{optional($compulsory)->saving_amount}}">

            </td>
            <td>
                <select class="form-control" name="c_charge_option">
                    @foreach($charge_option as $ke => $va)
                        <option value="{{$ke}}" {{optional($compulsory)->charge_option==$ke?'selected="selected"' : ''}}>{{$va}}</option>
                    @endforeach
                </select>

            </td>
            <td>
                <input class="form-control" name="c_interest_rate" value="{{optional($compulsory)->interest_rate}}">
            </td>
            <td>
                <select class="form-control" name="compulsory_product_type_id">
                    @foreach($compulsory_product_type as $ke => $va)
                        <option value="{{$ke}}" {{optional($compulsory)->compulsory_product_type_id==$ke?'selected="selected"' : ''}}>{{$va}}</option>
                    @endforeach
                </select>

            </td>


            <td>

                <select class="form-control" name="c_status">
                    @foreach($status as $ke1 => $va1)
                        <option value="{{$ke1}}" {{optional($compulsory)->status==$ke1?'selected="selected"' : ''}}>{{$va1}}</option>
                    @endforeach
                </select>

            </td>

        </tr>
        </tbody>
    </table>
@endif





