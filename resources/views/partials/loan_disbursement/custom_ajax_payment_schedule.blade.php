<?php
    $_e = isset($entry)?$entry:null;
    $repayment_cal = \App\Models\LoanCalculate::where('disbursement_id',optional($_e)->id)->orderBy('id')->get();
?>
<table class="table-data" id="table-data" style="margin-top: 20px;">
    <thead>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Date</th>
        <th colspan="3">Repayment</th>
        <th rowspan="2">Balance</th>
    </tr>
    <tr>
        <th>Principal</th>
        <th>Interest</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody class="payment-schedule">
    <?php
    $total_principle = 0;
    $total_interest = 0;
    $total_payment = 0;
    ?>
        @if($repayment_cal != null)
            @foreach($repayment_cal as $r)
                <tr>
                    <td>{{$r->no}}</td>
                    <td>{{\Carbon\Carbon::parse($r->date_s)->format('Y-m-d')}}</td>
                    <td style="text-align: right;">{{number_format($r->principal_s,-2)}}</td>
                    <td style="text-align: right;">{{number_format($r->interest_s,-2)}}</td>
                    <td style="text-align: right;">{{number_format($r->total_s,-2)}}</td>
                    <td style="text-align: right;">{{number_format($r->balance_s,-2)}}</td>
                </tr>
                <?php
                $total_principle += $r->principal_s;
                $total_interest += $r->interest_s;
                $total_payment += $r->total_s;
                ?>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right;"><b>Total: </b></td>
                <td style="text-align: right;"><b>{{numb_format($total_principle,0)}}</b></td>
                <td style="text-align: right;"><b>{{numb_format($total_interest,0)}}</b></td>
                <td style="text-align: right;"><b>{{numb_format($total_payment,0)}}</b></td>
                <td style="text-align: right;"></td>
            </tr>
        @endif
    </tbody>
</table>

@if ($_e != null)
    <br>
    <a href="{{backpack_url('regenerate-schedule')}}/{{optional($_e)->id}}" class="btn btn-success">Regenerate</a>
@endif



@push('crud_fields_styles')
    <style>

        .table-data
        {
            width:100%;
        }

        .table-data,.table-data  th, .table-data  td
        {
            border-collapse:collapse;
            border: 1px solid #a8a8a8;
        }

        .table-data  th
        {
            text-align: center;
            padding: 5px;
        }

        .table-data  td
        {
            padding: 5px;
        }

        .table-data  tbody > tr:nth-child(odd)
        {
            background-color: #f4f4f4;
            color: #606060;
        }
    </style>
@endpush
