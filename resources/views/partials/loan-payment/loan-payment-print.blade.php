<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Payment</title>
</head>
<body>



<div class="print-payment">
    <div id="DivIdToPrintPop">
        <style>
            table {
                border-collapse: collapse;
            }

            .border th, .border td {
                border: 1px solid rgba(188, 188, 188, 0.96);
                padding: 5px;
            }

            .right {
                text-align: right;
            }

            .my-table tr td {
                font-size: 12px;
                padding: 10px;
            }
            .print-payment{
                background-color: #fff;
                height: 842px;
            }
        </style>
        <?php
        $m = getSetting();
        $logo = getSettingKey('logo',$m);
        $company = getSettingKey('company-name',$m);
        ?>
        @if($row != null)
            <table style="width: 100%" class="my-table">
                <tr>
                    <td>
                        <img src="{{ asset($logo) }}" width="130"/>
                    </td>

                    <td style="font-size:20px; text-align: center; font-weight: bold;">
                        {{$company}} <br>
                        Payment<br>

                    </td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 30px;"  class="my-table">
                <tbody>
                <tr>
                    <td><b>Payment Number</b></td>
                    <td>{{$row->payment_number}}</td>
                    <td><b>Client Name</b></td>
                    <td>{{optional(optional($row)->client_name)->name}}</td>
                </tr>
                <tr>
                    <td><b>Receipt No</b></td>
                    <td>{{$row->receipt_no}}</td>
                    <td><b>Date</b></td>
                    <td>{{\Carbon\Carbon::parse($row->payment_date)->format('Y-m-d')}}</td>
                </tr>
                <tr>
                    <td><b>Over Days</b></td>
                    <td>{{$row->over_days}}</td>
                    <td><b>Penalty Amount</b></td>
                    <td>{{number_format($row->penalty_amount),2}}</td>
                </tr>
                <tr>
                    <td><b>Principle</b></td>
                    <td>{{number_format($row->penalty_amount),2}}</td>
                    <td><b>Payment Method</b></td>
                    <td>{{$row->payment_method}}</td>
                </tr>
                <tr>
                    <td><b>Old Owed</b></td>
                    <td>{{number_format($row->old_owed),2}}</td>
                    <td><b>Other Pay</b></td>
                    <td>{{number_format($row->other_payment),2}}</td>
                </tr>
                <tr>
                    <td><b>Total Payment</b></td>
                    <td>{{number_format($row->total_payment),2}}</td>
                    <td><b>Payment</b></td>
                    <td>{{number_format($row->payment),2}}</td>
                </tr>
                <tr>
                    <td><b>Owed Balance</b></td>
                    <td>{{number_format($row->owed_balance),2}}</td>
                    <td><b>Principle Balance</b></td>
                    <td>{{number_format($row->principle_balance),2}}</td>
                </tr>


                </tbody>
            </table>
    </div>
    <div class="action" style="float: right;">
        <button type="button " onclick="printDiv()" style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">PRINT</button>
    </div>
    @endif
</div>


<script src="{{asset('vendor/adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>


<script>
    // if (typeof printDiv !== 'undefined') {


        function printDiv() {
            var DivIdToPrintPop = document.getElementById('DivIdToPrintPop');//DivIdToPrintPop

            if(DivIdToPrintPop != null) {
                var newWin = window.open('', 'Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">' + DivIdToPrintPop.innerHTML + '</body></html>');

                newWin.document.close();

                setTimeout(function () {
                    newWin.close();
                }, 10);
            }
        }


    // }
</script>



</body>
</html>

