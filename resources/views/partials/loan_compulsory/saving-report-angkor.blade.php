<!DOCTYPE html>
<html>
<head>
    <title>Withdrawal</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Battambang|Moul" rel="stylesheet">
    <style type="text/css">
    @media print {
        .phone {color:red;}
    }
    html, body {
        height: 100%;
    }
    .contain-wrapper {
        width: 21cm;
        min-height: 29.7cm;
        padding: 2cm;
        margin: 1cm auto;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        font-family: Zawgyi-One,'Battambang', Times New Roman;
    }
    .ch-box{
        width:15px;height:15px;border:1px solid black;display:inline-block;
    }
    .small-letter{
        font-family:Zawgyi-One,khmer os muol;font-weight:bold;font-size:12px;
    }
    .chat table{
        border-collapse:collapse;
        width: 100%;
        margin-bottom:20px;
    }
    .chat table tr td{
        border:1px solid black;
    }
    .chat tr td {
        padding:10px;
    }
    .order-num{
        font-weight:bold;
    }
    #logo img{
        width:150px;
    }
    th{
        padding: 10px;
        vertical-align:center;
        text-align: center;
    }
    span{
        font-size:13px;
    }

</style>
<?php
$m = getSetting();
$logo = getSettingKey('logo', $m);
$company = getSettingKey('company-name', $m);
?>
</head>
<body>
    <div class="contain-wrapper" style="padding:0; margin:0 auto;">

        <div class="header" style=" text-align:center;">
            <div style=" " id="logo">
                <span>
                <img src="{{asset($logo)}}" width="200"/>
                </span>
            </div>
            <b>
                {{$company}}
            </b>

        </div>
        <div style=" width:100%;min-height:50px; padding-top:10px; padding-left:50px;">
            <p>
                ရုံးလိပ်စာ(Office Address):အမှတ် ၅၆၆/ခ ရာဇဌာနီလမ်း ပေါင်းလောင်း(၃)ရပ်ကွက် ပျဉ်းမနားမြို့နေပြည်တော်။
                <br>Ph - ၀၉၄၂၃၃၃၃၈၅၅
            </p>
        </div>

        <div>
            <div class="text-center">
                <h2>Saving Withdrawal Form</h2>
            </div>
            <table class="table table-bordered schedule" style="height:40px;border: 1px solid black;margin-top:5px;font-size:11px;width:90%;margin-left:45px">
                <tr style="background-color:#009900;color:white;">
                    <td rowspan="2" style="text-align: center;">စဉ်</td>
                    <td rowspan="2" style="text-align: center;">အမည်</td>
                    <td rowspan="2" style="text-align: center;">ထုတ်ယူသည့်<br>ရက်စွဲ</td>
                    <td rowspan="2" style="text-align: center;">ချေးငွေအမှတ်</td>
                    <td rowspan="2" style="text-align: center;">အဖွဲ့</td>
                    <td colspan="3" style="text-align: center;">စုဆောင်းငွေထုတ်ယူသည့်ပမာဏ</td>
                    <td rowspan="2" style="text-align: center;">ငွေလက်ခံရရှိသူ<br>လက်မှတ်</td>
                    <td rowspan="2" style="text-align: center;">မှတ်ချက်</td>
                </tr>
                <tr style="background-color:#009900;color:white;">
                    <td style="text-align: center;">အရင်း</td>
                    <td style="text-align: center;">အတိုး</td>
                    <td style="text-align: center;">စုစုပေါင်း</td>
                </tr>
                <?php
                $total = 0; $interest = 0; $principle = 0;
                $j = 1;

                foreach ($savings as $key => $saving) {?>
                    <tr class=" text-bold">
                        <td><b>{{$j}}</b></td>
                        <td style="white-space: nowrap;"><b>{{$saving->client_name->name}}</b></td>
                        <td><b>{{ $saving->status_note_date_activated != null ? date('d-m-Y', strtotime($saving->status_note_date_activated)) : ''}}</b></td>
                        <td><b>{{$saving->disbursement_number}}</b></td>
                        <td><b>{{optional($saving->group_loans)->group_code}}</b></td> 
                        <?php
                        $interest_amount = $saving->interest_repayment;
                        $principle_amount = $saving->principle_repayment;
                        $amount = $saving->loan_amount;
                        ?>
                        <td style="text-align: right;"><b>{{number_format($principle_amount)}}</b></td>
                        <td style="text-align: right;"><b>{{number_format($interest_amount)}}</b></td>
                        <td style="text-align: right;"><b>{{number_format($principle_amount + $interest_amount)}}</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php $j++; $interest += $saving->interest_repayment; $principle += $saving->principle_repayment; $total += $saving->loan_amount + $saving->interest_repayment;}?>
                    <tr>
                        <td></td>
                        <td colspan="4">စုစုပေါင်း</td>
                        <td style="text-align: right;"><b>{{number_format($principle)}}</b></td>
                        <td style="text-align: right;"><b>{{number_format($interest)}}</b></td>
                        <td style="text-align: right;"><b>{{number_format($total)}}</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <br><br>
                <div class="col-sm-12" style="display: contents;">
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            Paid by <br><br><br><br>
                            {{Auth()->user()->name}}
                            Nay Pyi Daw (Head Office)
                        </div>
                        <div class="col-sm-6 text-center">
                            Checked & Approved by <br><br><br><br>
                            
                            Aye Thandar Phyo <br>

                            Branch Manager <br>
                            Nay Pyi Daw (Head Office)
                        </div>
                    </div>
                </div>

                <div style="height:35px;" id="row_blank">
                </div>
            </div>
