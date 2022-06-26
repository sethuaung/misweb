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
</head>
<body>
<div class="container-fluid">
    @if($row != null)
        <div id="content" style="width: 1000px;margin: auto" >
            <div class="blog"  style="margin-top: 20px">
                <h1 style="text-align: center">Loan Deposit</h1>
                <table style="width: 100%;margin-top: auto">
                    <tr>
                        <td style="padding: 5px"><b>Applicant Number</b>:{{optional($row->loan_disbursement)->disbursement_number}}</td>
                        <td style="padding: 5px;text-align: right"><b>Client Name</b>:{{optional($row->client)->name}}</td>
                        <td style="padding: 5px;text-align: right"><b>Deposit Amount</b>:{{$row->total_deposit}}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;"><b>Compulsory Amount</b>:{{$row->compulsory_saving_amount}}</td>
                        <td style="padding: 5px;text-align: right"><b>Loan Request</b>:{{optional($row->loan_disbursement)->loan_amount}}</td>
                    </tr>
                </table>
                <table style="width: 100%;border-collapse: collapse;border: 1px solid black" >
                    @if($row->deposit_service_charge != null)
                        @foreach($row->deposit_service_charge as $item)
                            <tr style="border: 1px solid black">
                                <?php
                                $loan_charge = \App\Models\LoanCharge::find($item->service_charge_id);
                                ?>
                                <td style="padding: 5px;border: 1px solid black"><b>Service Charge</b>:{{optional($loan_charge)->name}}</td>
                                <td style="padding: 5px;border: 1px solid black"><b>Service Amount</b>:{{$item->service_charge_amount}}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    @endif

       <div class="content">
           <a onclick="history.go(-1);return false;" href="#" class="btn btn-primary"><< Back</a>
           <button  class="btn btn-success " id="print" > Print</button>
       </div>


</div>
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
