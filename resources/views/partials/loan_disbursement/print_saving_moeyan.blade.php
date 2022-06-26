<div id="DivIdToPrintPop">

    <link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">


<style>
    table {
        border-collapse: collapse;
        margin:0 auto;
        margin-top:10px;
        padding-left: 20px;
    }
    td{
        padding-top:20px;
        font-size:14px;
        border: 0px solid rgba(188, 188, 188, 0.96);
    }

    body{
        font-size: 13px;
        text-align: center;
    }

    @media print {
        @page { margin: 0; }
    }

</style>
    <?php
    $debit_total = 0;
    $credit_total = 0;
    ?>

@if($saving != null)
    <div class="container w-75">
        <div>
            <h2><span style="font-size:13px; margin:0 auto;" ><b>STAR MOE YAN MICROFINANCE COMPANY LIMITED</b></span><br></h2>
            <h2><span style="font-size:13px; margin:0 auto;" ><b>{{ $saving->tran_type=="deposit"?'Saving Deposit':'Saving Withdrawal' }}</b></span><br></h2>
        </div>
        <table style="width: 40%;" border="0">
            <tr>
                <td>Vr No:</td>
                <td style="text-align: right;">{{ $saving->reference }}</td>
            </tr>
            <tr>
                <td>Date:</td>
                <td style="text-align: right;">{{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Customer Name:</td>
                <td style="text-align: right;">{{ optional($client)->name?$client->name:$client->name_other }} </td>
            </tr>
            <tr>
                <td>NRC-Number:</td>
                <td style="text-align: right;">{{ optional($client)->nrc_number }} </td>
            </tr>
            <tr>
                @php
                    $saving_product = \App\Models\SavingProduct::find(optional($saving)->saving_product_id);
                @endphp
                <td>Savings Product:</td>
                <td style="text-align: right;">{{ $saving_product->name }} </td>
            </tr>
            <tr>
                @php
                    $saving_number = \App\Models\Saving::find(optional($saving)->saving_id);
                @endphp
                <td>Savings Number:</td>
                <td style="text-align: right;">{{ $saving_number->saving_number }} </td>
            </tr>
            <tr>
                <td>Kyats:</td>
                <td style="text-align: right; border:1px solid black;"><span style="padding: 1px">{{ $saving->amount > 0 ? $saving->amount:(-$saving->amount) }}</span> </td>
            </tr>
            <tr>
                <td>စာသားဖြင့်:</td>
                <td style="text-align: right;">{{ App\Helpers\S::convert_number_to_words($saving->amount > 0 ? $saving->amount:(-$saving->amount)) }}</td>
            </tr>
            <tr>
                <td>ငွေလက်ခံသူ:</td>
                <td style="text-align: right;">_______________</td>
            </tr>
            <tr>
                <td>Name:</td>
                <td style="text-align: right;">{{ optional($client)->name?$client->name:$client->name_other }} </td>
            </tr>
            <tr>
                <td>NRC Number:</td>
                <td style="text-align: right;">_______________</td>
            </tr>
            <tr>
                <td>ငွေထုတ်သူ:</td>
                <td style="text-align: right;">_______________</td>
            </tr>
            <tr>
                <td>Cashier:</td>
                <td style="text-align: right;">_______________</td>
            </tr>
        </table>
    </div>
</div>
@endif

<script src="{{asset('')}}vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script>

    $(document).ready(function(){
        setTimeout(function () { window.print(); }, 100);
        window.onload = function () { setTimeout(function () { window.close(); }, 500); }
    });
</script>