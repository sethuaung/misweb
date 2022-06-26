<div id="DivIdToPrintPop">
<style>
    table {
        border-collapse: collapse;
        margin:0 auto;
        margin-top:30px;
    }

    .border td {
        border: 1px solid rgba(188, 188, 188, 0.96);
    }

    .right {
        text-align: right;
    }

    tr td {
        font-size: 13px;
    }

    thead th{
        text-align:center;
        font-size:16px;
        background-color:#3c8dbc;
        padding: 5px;
        border: 1px solid rgba(188, 188, 188, 0.96);
    }

    body{
        font-size: 13px;
        text-align: center;
    }

    .info{
        text-align: left;
    }

</style>
    <?php
    $debit_total = 0;
    $credit_total = 0;
    ?>

@if($reference_no != null)
    <div class="container">
        <div>
            <span style="font-size:13px; margin:0 auto;" ><b>STAR MOE YAN MICROFINANCE COMPANY LIMITED</b></span><br>
            <span style="font-size:13px; margin:0 auto;" ><b>Journal Vouncher</b></span><br>
        </div><br>

        <table style="width: 100%;font-size:13px;">
            <tr>
                <td><b>Reference No</b> <span style="padding-left: 20px;">: {{$reference_no}}</span></td>

                <td></td>
                <td></td>

                <td><b>Currency</b><span style="padding-left: 20px;">: {{$currency}}</span></td>
            </tr>

            <tr>
                <td><b>Branch</b><span style="padding-left: 56px;">: {{$branch->title}}</span></td>
            </tr>
        </table>
        <table style="width: 100%;">
            <thead>
                <th>Date</th>
                <th>Chart Account</th>
                <th>Note</th>
                <th>Debit</th>
                <th>Credit</th>
            </thead>


            <tbody class="border">
                @foreach($acc_ids as $acc_id)
                @php
                    $acc_chart = \App\Models\AccountChart::find($acc_id->acc_chart_id);
                    $debit = $acc_id->dr??0;
                    $debit_total += $debit;
                    $credit = $acc_id->cr??0;
                    $credit_total += $credit;
                @endphp
                    <tr style="background: #fffbe8;">
                        <td style="text-align:center;">{{$date}}</td>
                        <td style="text-align:center;">{{$acc_chart->code . "-" . $acc_chart->name}}</td>
                        <td style="text-align:center;">{{$note}}</td>
                        <td style="text-align:center;">{{number_format($debit)}}</td>
                        <td style="text-align:center;">{{number_format($credit)}}</td>
                    </tr>
                @endforeach  
                <tr style="background: #fffbe8;">
                    <td></td>
                    <td></td>
                    <td style="text-align:center;">Total</td>
                    <td style="text-align:center;">{{number_format($debit_total)}}</td>
                    <td style="text-align:center;">{{number_format($credit_total)}}</td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;font-size:13px;">
            <tr>
                <td style="text-align: left;"><b>Prepared By</b></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b>Approved By</b></td>
            </tr>
        </table>
    </div>
</div>
@endif
<script>
    window.print();
</script>