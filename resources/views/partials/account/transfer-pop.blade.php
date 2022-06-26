<style>
   /* #show-detail-modal .modal-dialog{
        padding-left: 30%;
        padding-right: 30%;
        font-size: 19px;
        font-family: 'Bodoni MT'; 
    }

    table {
        border-collapse: collapse;
        font-size: 18px;
    }
    th{
        font-weight: bold;
        padding-right: 15px;
    }
    #border{
        border: 1px solid black;
        padding: 7px 10px;
    }*/

   body{
       font-size:13px;
       font-family: "Poppins" !important;
       text-align: center;
   }

   td {
       padding-top: 5px;
   }
   #data{
       margin-top:10px;

   }

   #data td{
       font-size:13px;
       font-weight:normal;
       padding:5px;
   }

   th{
       padding:5px;
       font-size:13px;
   }

   .bor{
       border:1px solid black;
   }

   @media print {
       @page { margin: 0; }
   }
</style>


<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">


<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

@if($row != null)
<?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);

    $from_acc = \App\Models\AccountChart::find($row->from_cash_account_id, ['name']);
    $to_acc = \App\Models\AccountChart::find($row->to_cash_account_id, ['name']);
    $transfer_by = \App\User::find($row->transfer_by_id, ['name']);
    $receive_by = \App\User::find($row->receive_by_id, ['name']);
?>
<div id="DivIdToPrintPop">

    <div class="container " style="padding-left: 35px;padding-top: 0px;margin-top:10px;margin-left: 0px">


    <table>
        <tr>
            <td><span style = "font-weight:bold; font-size:16px;">MoeYan Microfinance Ltd Transfer</span></td>
        </tr>
    </table>

    <br>

    <table style="">
        <tr>
            <th width="110">Reference No</th>
            <td>: {{$row->reference_no}}</td>
        </tr>
        <tr>
            <th>From Cash</th>
            <td>: {{ $from_acc->name }}</td>
        </tr>
        <tr>
            <th>To Cash</th>
            <td>: {{ $to_acc->name }}</td>
        </tr>
        <tr>
            <th>Transfer By</th>
            <td>: {{ $transfer_by->name }}</td>
        </tr>
        <tr>
            <th>Receive By</th>
            <td>: {{ $receive_by->name }}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>: {{ number_format($row->t_amount) }}</td>
        </tr>
        <tr>
            <th>Amount In Words :</th>
            <td style="text-transform:uppercase">: {{ App\Helpers\S::convert_number_to_words($row->t_amount) }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>: {{ $row->t_date }}</td>
        </tr>
        @if($row->description)
        <tr style="padding-top:10px;">
            <th>Description</th>
        </tr>
        <tr>
            <td colspan="3" border="1" id="border">{{ Illuminate\Mail\Markdown::parse($row->description) }}</td>
        </tr>
        @endif
        <tr>
            <th style="padding-top:10px;">ငွေပေးသူ:</th>
            <td style="padding-top:10px;">................................................</td>
        </tr>
        <tr>
            <th style="padding-top:10px;">ငွေလက်ခံသူ</th>
            <td style="padding-top:10px;">................................................</td>
        </tr>
        <tr>
            <th style="padding-top:10px;">အတည်ပြုသူ</th>
            <td style="padding-top:10px;">................................................</td>
        </tr>
    </table>

    </div>
</div>
<br>
<div class="tab-content">

    <script src="{{asset('')}}vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <script>

        $(document).ready(function(){
            setTimeout(function () { window.print(); }, 100);
            window.onload = function () { setTimeout(function () { window.close(); }, 500); }
        });
    </script>
@endif
