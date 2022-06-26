<div class="print-payment">
    <div id="DivIdToPrintPop">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">


        <style type="text/css">
            #DivIdToPrintPop{
                background-color: #ffffff;
                padding: 30px;
                color:black;
            }

            .my_font{
                font-size:13px;
                font-family: "Poppins" !important;
            }

            td {
                /*padding-top: 5px;*/
            }
            #data{
                margin-top:5px;

            }

            #data td{
                font-size:13px;
                font-weight:normal;
                /*padding:5px;*/
            }

            th{
                /*padding:5px;*/
                font-size:13px;
            }

            .bor{
                border:1px solid black;
            }

            @media print {
                @page { margin: 0; }
            }

            #data {
                border-collapse: collapse;
            }
        </style>

<?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);
?>
@if($row != null)
    <div id="logo" style="text-align: center">
        <img src="{{ asset($logo) }}" width="130"/>
    </div>


    <div class="my_font" style="padding-top:5px;text-align: center">
        <p class="cash" style="">Loan Repayment <br>
        <p class="date">DATE: {{ optional($row)->payment_date != null ? date('d-m-Y', strtotime(optional($row)->payment_date)):''}}<br>
        {{$row->payment_number}}</p>
    </div>

    <table id="data" width="100%" class="my_font schedule" style="border: 0px solid black;">
        <tbody>
        <tr style="background-color:#009900;color:black;">
            <td class="bor" style="text-align: center;">Client Name</td>
            <td class="bor">{{optional(optional($row)->client_name)->name}}</td>
        </tr>

        <tr>
            <td class="bor" style="text-align: center;">NRC No</td>
            <td class="bor">{{optional(optional($row)->client_name)->client_number}}</td>
        </tr>

        <tr>
            <td class="bor" style="text-align: center;">Penalty Fees</td>
            <td class="bor">{{number_format($row->penalty_amount),2}}</td>
        </tr>

        <tr>
            @php

                if(isset($payment_total)){
                    $total = $payment_total;
                }else{
                    $total = optional($schedule)->principal_p + optional($schedule)->interest_p;
                }
            @endphp
            <td class="bor" style="text-align: center;">Total Coll;Amount</td>
            <td class="bor">{{number_format($total),2}}</td>
        </tr>

        </tbody>
    </table>

    <?php 
        if(isset($payment_total)){
            $total_word = App\Helpers\S::convert_number_to_words($row->payment);
        }else{
            $total_word = App\Helpers\S::convert_number_to_words(optional($schedule)->principal_p + optional($schedule)->interest_p);
        }
    ?>

    <div class="my_font" style="font-size:13px; padding-top: 5px; text-transform: uppercase">AMOUNT IN WORDS : {{$total_word}}</div>

    <div class="my_font col-sm-12">
        <table style="border: 0;">
            <tr>
                <td style="padding:10px;border: 0;text-align: right;"><b>ငွေပေးသူ:</b> </td>
                <td style="border: 0;">.................................................</td>
            </tr>
            <tr>
                <td style="padding:10px;border: 0; text-align: right;">ဖုန်း:</td>
                <td style="border: 0;">.................................................</td>
            </tr>
            <tr>
                <td style="padding:10px;border: 0;text-align: right;">ငွေလက်ခံသူ</td>
                <td style="border: 0;">.................................................</td>
            </tr>
            <tr>
                <td style="padding:10px;border: 0;text-align: right;">Cashier:</td>
                <td style="border: 0;">.................................................</td>
            </tr>
        </table>
    </div>
</div>
    <div class="my_font action" style="float: right;">
        <button class="my_font" type="button" onclick="printDiv() " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">PRINT</button>
    </div>
@endif
</div>
<script>
        function printDiv() {

            var DivIdToPrintPop = document.getElementById('DivIdToPrintPop');//DivIdToPrintPop

            if(DivIdToPrintPop != null) {
                var newWin = window.open('', 'Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">' + DivIdToPrintPop.innerHTML);

                newWin.document.close();

                setTimeout(function () {
                    newWin.close();
                }, 10);
            }
        }
</script>
