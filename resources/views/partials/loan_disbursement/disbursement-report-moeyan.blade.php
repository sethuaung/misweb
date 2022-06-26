<?php
        $loan = \App\Models\Loan::find($row->contract_id);
        $client = \App\Models\Client::find($loan->client_id);
        $user = \App\User::find($row->updated_by);
    ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $loan->disbursement_number ? $loan->disbursement_number : 'N/A'; ?></title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet">
    <style type="text/css">
        .container-fluid {
            font-family: 'Zawgyi-One', Times New Roman;
        }

        #logo img {
            width: 200px;
            margin-left: 10%;
            opacity: 0.8;
        }

        td {
            padding-top: 5px;
        }
    </style>
    <?php
$m = getSetting();
$logo = getSettingKey('logo', $m);
$company = getSettingKey('company-name', $m);
?>
</head>

<body>
    <div id="DivIdToPrintPop" class="container-fluid">
        <div class="row">
            <div class="col-md-12" style="padding:0;width: 100%;padding-bottom: 40px;">
                <div class="header" style="width:100%;">
                    <div class="brand-name" style="width:60%;margin-left:12%;">
                        <div id="logo">
                            <span>
                            <img src="{{asset($logo)}}" width="200"/>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="header" style="width:100%; clear: both;">
                    <div style="text-align:center;padding-bottom: 15px;">
                        <!-- <div style="background-color: black;margin-top: 5px;">
                       <span style="font-size:12px;font-family:Zawgyi-One;color: white;">No(401-411), Ground Floor, Between 27<sup>th</sup>  28<sup>th</sup> Street, Bogyoke Aung San Road, Pabedan Township,
                           Yangon. <br> Tel : 09-797002155, <br> 09-797002156, 09-797002157. Email:moeyanmfi@moeyantrade.com
                       </span>
                   </div> -->

                        <span style="font-size:17px;color:black;font-family:Zawgyi-One;font-weight: bold;">Cash Payment Vouncher<br>
                            <!-- <span style="font-size: 16px;">ေငြေပးေျပစာ</span> -->
                        </span>
                    </div>
                    <div class="col-md-offset-9 col-md-2" style="font-size: 13px;">
                        <span style="float: right;"><b>Vr No:</b> <b>{{optional($row)->reference}} </b></span>
                        <br>
                        <span style="float: right;"><b>Date:</b> <b>{{ $loan->status_note_date_activated != null ? date('d-m-Y', strtotime($loan->status_note_date_activated)) : ''}}</b></span>
                        <br>
                    </div>
                    <div style="padding-top: 5px;">
                        <center>
                            <table width="100%">
                                <tr>
                                    <td style="text-align: right;font-size:12px">Customer Name:</td>
                                    <td colspan="3" style="padding: 10px 5px;font-size: 12px;"><b>{{optional($client)->name}}</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;font-size:12px">NRC-number:</td>
                                    <td colspan="3" style="padding: 10px 5px;font-size: 12px;"><b>{{optional($client)->client_number}}</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;font-size:14px">အကြောင်းအရာ</td>
                                    <td colspan="3" style="padding: 10px 5px;font-size: 12px;"><b>ချေးငွေထုတ်ပေးခြင်း</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;font-size:12px">Kyats:</td>
                                    <td colspan="3" style="border:1px solid black;padding: 10px 5px;"><b> {{number_format($loan->loan_amount,0)}}</b></td>
                                </tr>
                                <?php $total_word = App\Helpers\S::convert_number_to_words($loan->loan_amount);?>
                                <tr>
                                    <td style="text-align: right;font-size:12px"> စာသားဖြင့်:</td>
                                    <td colspan="3" style="padding: 10px 5px; text-transform: uppercase"><b>{{$total_word}}</b></td>

                                </tr>
                            </table>
                        </center>
                    </div>
                    <div class="col-sm-12">
                        <table style="border: 0;">
                            <tr>
                                <td style="padding:10px;border: 0; font-size: 11px;text-align: right;"> ငွေလက်ခံသူ </td>
                                <td style="border: 0;font-size: 11px;">....................................</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;border: 0;font-size: 11px;text-align: right;">Name:</td>
                                <td style="border: 0;font-size: 11px;">{{optional($client)->name}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;border: 0;font-size: 11px;text-align: right;">NRC-number:</td>
                                <td style="border: 0;font-size: 11px;">....................................</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;border: 0; font-size: 13px;text-align: right;">ငွေပေးသူ:</td>
                                <td style="border: 0;font-size: 11px;">....................................</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;border: 0;font-size: 11px;text-align: right;">Cashier:</td>
                                <td style="border: 0;font-size: 11px;">....................................</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script>
$(document).ready(function(){
    window.print();
});
</script>
</body>