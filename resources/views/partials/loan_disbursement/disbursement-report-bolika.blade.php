<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <?php $base=asset('vendor/adminlte') ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {{--<link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">--}}
</head>
<body>

<div id="content">
    <?php
    $uid = time() . rand(1, 9999) . rand(1, 9999);
    ?>

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
        }
        #popup{{  $uid }} {
            background-color: white;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 9999;
            display: none;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;
            outline: 0;
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4)
        }
        #iframe{{  $uid }} {
            border: 0;
            width: 100%;
            height: 100%;
            /*margin: 30px auto;*/
            position: relative;
            display: block;
            box-shadow: 0 2px 3px rgba(0,0,0,0.125);
        }

        #page{{  $uid }} { height: 100%; }
    </style>

    @if($row != null)
        <?php
            $loan = \App\Models\Loan::find($row->contract_id);
            $client = \App\Models\Client::find($loan->client_id);
            $user = \App\User::find($row->updated_by);

            $currency = \App\Models\Currency::find(optional($loan)->currency_id);
        ?>
        <table width="100%">
            <thead>
            <tr>
                <h1 style="text-align: center;">Disbursement Receipt</h1>
            </tr>
            </thead>
        </table>
        <table width="100%">
            <tr >
                <td style="padding: 10px;">Received From: {{$user->name}}</td>
                <td style="padding: 10px;">To: {{optional($client)->name}}</td>
            </tr>
            <tr>
                <td style="padding: 10px;">Application Number: {{optional($loan)->disbursement_number}}</td>
                <td style="padding: 10px;">Disbursement Date: {{$row->paid_disbursement_date}}</td>
            </tr>
            <tr style="padding: 10px">
                <td style="padding: 10px;">Amount: {{numb_format($row->cash_pay,0)}} {{optional($currency)->currency_symbol}}</td>
                <td></td>
            </tr>

            <tr style="padding: 10px">

            </tr>

            <tr style="padding: 10px">
                <td style="padding: 10px;">Account</td>
                <td>Client</td>
            </tr>

        </table>
</div>
<table width="100%">
    <tr style="text-align: right;">
        <td>
            <span><button id="print" style="padding: 7px 10px; background: rgba(130,149,111,0.41)" >PRINT</button> </span>
            <span><a href="{{url('admin/my-paid-disbursement/create')}}" style="padding: 5px 10px; background: #c9cccf; text-decoration: none;" >Back</a> </span>
        </td>
    </tr>
</table>
@endif
<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>

<script>
    $(function () {
        document.getElementById("print").addEventListener("click", function() {
            var printContents = document.getElementById('content').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    })
</script>
</body>


</html>


