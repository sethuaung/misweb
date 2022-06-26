
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
        <th>Total Loans</th>
        <th>Total Charge</th>
        <th>Total Compulsory</th>
        <th>Total</th>
        <th><input type="checkbox" id="check_all_group"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;

    $total_loan = 0;
    $total_charge = 0;
    $total_compulsory = 0;
    ?>
    @if($g_pending != null)
        @php
            $group_mem = $g_pending->groupBy('group_loan_id');
        @endphp
        @foreach($group_mem as $g_id => $rows)
            <?php

            $group = \App\Models\GroupLoan::find($g_id);
            $rand_id = rand(1, 1000) . time() . rand(1, 1000);
            $center = null;
            if ($group != null) {
                $center = \App\Models\CenterLeader::find($group->center_id);
            }
            //$total += $row->amount;
            $total_line_loan = 0;
            $total_line_charge = 0;
            $total_line_compulsory = 0;
            foreach ($rows as $row) {
                $total_line_loan += $row->loan_amount;

                $charges = \App\Models\LoanCharge::where('charge_type', 2)->where('status','Yes')->where('loan_id', $row->id)->get();
                if ($charges != null) {
                    foreach ($charges as $c) {
                        $amt_charge = $c->amount;
                        $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                    }
                }

                $compulsory = \App\Models\LoanCompulsory::where('compulsory_product_type_id', 2)->where('loan_id', $row->id)->first();

                if ($compulsory != null) {
                    $amt_compulsory = $compulsory->saving_amount;
                    $total_line_compulsory += ($compulsory->charge_option == 1 ? $amt_compulsory : (($row->loan_amount * $amt_compulsory) / 100));

                }
            }

            $total_line = $total_line_loan - $total_line_charge - $total_line_compulsory;

            $total_loan += $total_line_loan;
            $total_charge += $total_line_charge;
            $total_compulsory += $total_line_compulsory;

            ?>

            <tr id="p-{{$rand_id}}">
                <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                <td>{{optional($group)->group_code}}</td>
                <td>{{optional($group)->group_name}}</td>
                <td>{{ $total_line_loan }}</td>
                <td>{{ $total_line_charge }}</td>
                <td>{{ $total_line_compulsory }}</td>
                <td>{{$total_line}}</td>
                <td>

                    <a href="{{url("admin/list-member-dirseburse?group_loan_id={$g_id}&rand_id={$rand_id}")}}"
                       data-remote="false" data-toggle="modal"
                       data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i
                                class="fa fa-eye"></i></a>
                    <input type="checkbox" class="form-check-input c-checked-group"
                           data-payment="{{$total_line}}" name="approve_check[{{$rand_id}}]"
                           value="{{$row->group_loan_id}}"/>
                    <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$row->group_loan_id}}">
                    <input type="hidden" name="center_id[{{$rand_id}}]" value="{{$center->id}}">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
        <td>{{$total_loan}}</td>
        <td>{{$total_charge}}</td>
        <td>{{$total_compulsory}}</td>
        <td>{{$total_loan-$total_compulsory-$total_charge}}</td>
        <td></td>
    </tr>
    </tfoot>
</table>

<script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>




