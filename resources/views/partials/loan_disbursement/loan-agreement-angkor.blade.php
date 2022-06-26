<?php 
    $loan = \App\Models\Loan::find($row->contract_id);
    // dd($loan->loan_product->code);
    
    $group_loans = \App\Models\Loan::where('group_loan_id', $loan->group_loan_id)->get();
    $group_leader = array();

    foreach($group_loans as $loan){
        if($loan->you_are_a_group_leader = "YES"){
            $group_leader = $loan;
        }
    }
    // dd($client);
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>{{optional($row)->reference}}</title>
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
        <div>
            <p style="text-align:center;" class="small-letter"> <b>လိုင်စင်ရ အသေးစား ငွေရေးကြေးရေး လုပ်ငန်း</b></p>
            <p style="text-align:center;line-height: 0;" class="small-letter"> <b>အုပ်စု ချေးငွေစာချုပ်</b></p>
        </div>

        <div style=" height:100px; padding-left:15px;font-size:10px;line-height: 18px;">
            <table width="100%">
                <tr>
                    <td style="width:18%;">ငွေထုတ်ပေးသည့် ရက်စွဲ<br>(Disbursement Date)</td>
                    <td style="width:15%;vertical-align: top;">:<b>{{ $row->paid_disbursement_date != null ? date('d-m-Y', strtotime($row->paid_disbursement_date)) : ''}}</b></td>
                    <td style="width:13%">ချေးငွေကာလ<br>(Loan term)</td>
                    <?php 
                    $term_month =  floor($loan->loan_term_value/30);
                    $term_day = ($loan->loan_term_value) - ($term_month * 30);
                    ?>
                    <td style="width:15%;vertical-align: top;">:<b><?= ($term_month > 0 ? $term_month.' လ ' : '') ?>
                        <?= ($term_day > 0 ? $term_day.' ရက် ' : '') ?>
                    </b></td>
                    <td style="width:18%">စတင်ပေးချေရမည်နေ့ရက်<br>(First Repayment Date)</td>
                    <td style="width:15%;vertical-align: top;">: <b>{{ $loan->first_installment_date != null ? date('d-m-Y', strtotime($loan->first_installment_date)) : ''}}</b> </td>
                </tr>
                <tr>
                    <td style="width:18%">အဖွဲ့ဝင်အရေအတွက်<br>(Number of member)</td>
                    <td style="width:15%;vertical-align: top;">: <b>{{count($group_loans)}}</b></td>
                    <td style="width:13%">ပေးချေသည့်ပုံစံ<br>(Repayment Interval)</td>
                    <td style="width:15%;vertical-align: top;">:<b> {{$loan->repayment_term}}</b></td>
                        <td style="width:18%">စာချုပ်အမျိုးအစား<br>(Contract Type)</td>
                        <td style="width:15%;vertical-align: top;">: <b>{{$loan->loan_product->name}}</b></td>
                    </tr>
                    <tr>
                        <td style="width:18%">ဝိုင်းကြီးချုပ်နံပါတ်<br>(Group No. )</td>
                        <td style="width:15%;vertical-align: top;">:<b>{{$loan->group_loans->group_code}}</b></td>
                        <td style="width:13%">အတိုးနှုန်း<br>(Interest Rate) </td>
                        <td style="width:15%;vertical-align: top;">:<b>{{$loan->interest_rate}} %</b></td>
                        <td style="width:18%">ချေးငွေအရာရှိ ID<br>(Credit Officer ID)</td>
                        <td style="width:15%;vertical-align: top;">: <b>{{$loan->officer_name->name}}</b></td>
                    </tr>

                    <tr>
                        <td style="width:18%; font-family:Pyidaungsu !important;">လိပ်စာ<br>(Leader/Borrower Address )</td>
                        <td style="vertical-align: top;" colspan="5">:<b>{{$group_leader->client_name->address1}}</b> </td>
                        </tr>
            </table>
        </div>
                <br><br>
                <div style=" width:100%;min-height:100px; padding-top:15px; padding-left:10px;">
                    <p>
                    ရုံးလိပ်စာ(Office Address ) :အမှတ် ၅၆၆ /ခ ရာဇဌာနီ လမ်း ပေါင်းလောင်း (၃ ) ရပ်ကွက် ပျဉ်းမနားမြို့ နေပြည်တော်။<br> Ph - ၀၉၄၂၃၃၃၃၈၅၅
                    </p>

                    <p>
                    ချေးငွေ မပေးသွင်းနိုင်ပါက ဝိုင်းကြီးချုပ်စနစ်ဖြင့်  အဖွဲ့ အမှတ် {{$loan->group_loans->group_code}} ၏ အဖွဲ့ဝင်များနှင့်ပူးတွဲ ငွေချေးသူတို့က အညီအမျှ တာဝန်ယူဖြေရှင်းပေးမည် ဖြစ်ကြောင်းလက်မှတ်ရေးထိုး ပါသည်။
                    </p>
                </div>

                <div style=" padding-left:10px; padding-right:10px;">
                    <table border="1" style="max-width:100%; font-size:11px;">
                        <tr>
                        <th style="width:5%"><span>(စဉ်)</span><br>No</th>
                            <th style="width:10%"><span>( ချေးငွေ    ID)</span><br>Loan ID</th>
                            <th style="width:20%"><span>(ငွေချေးသူအမည်)</span><br>Borrower Name</th>
                            <th style="width:15%"><span>(မှတ်ပုံတင်အမှတ်)</span><br> NRC No </th>
                            <th style="width:10%"><span>( ချေးငွေပမာဏ    )</span><br>Loan Amount</th>
                            <th style="width:10%"><span>( လက်မှတ်)</span><br>Signature</th>
                        </tr>
                        <?php
                        $i = 1;
                        foreach($group_loans as $loan){
                            ?>
                            <tr>
                                <td style="padding:5px">{{ $i}}</td>
                                <td style="padding:5px">{{$loan->disbursement_number}}</td>
                                <td style="padding:5px">{{$loan->client_name->name}}</td>
                                <td style="padding:5px">{{$loan->client_name->nrc_number}}</td>
                                <td style="padding:5px">{{number_format($loan->loan_amount)}}</td>
                                <td style="padding:5px"> </td>

                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </table>
                </div>

                <div>
                    <p style="text-align:center; padding-top:15px;" class="small-letter"> </p>
                </div>
                <div class="rules">
                    <div class="rules_header" style="text-align: center; padding-top:15px;"><b>အထူးစည်းမျဉ်းများ</b></div>
                    <div class="rules_body">
                        <ol>
                            <li style="word-wrap: break-word;padding: 15px;">
                                လူကြီးမင်း၏ ချေးငွေလျှောက်လွှာအတွက် ကျေးဇူးတင်ရှိပြီး <?php echo $company ?> ထံမှ ချေးငွေထုတ်ယူသော အချိန်မှစ၍ ချေးငွေပြန်ဆပ်သည့်အချိန်ထိ ၎င်းစည်းမျဉ်း စည်းကမ်းများကိုနားလည်ရန် နှင့် ၎င်းစည်းမျဉ်းစည်းကမ်းတွင် ပါရှိသည့်အတိုင်း ပေးထားသော ကတိများကို စောင့်ထိန်းရန် အရေးကြီးပါသည်။ ကိုယ်ရေးအချက်အလက်များ နှင့်ပတ်သက်သော သတင်းများကို အချိန်နှင့်တပြေးညီအသိပေးရန် ကတိပြုပြီး အကယ်၍ အပြောင်းအလဲရှိပါက ချက်ချင်း  {{$company}}  ထံသို့အကြောင်းကြားရန် လိုအပ်ပါသည်။
                            </li>
                            <li style="word-wrap: break-word;padding: 15px;">
                               ချေးငွေ အတိုးနှုန်းမှာတစ်လလျှင် ၂.၃% အတိုးနှုန်း ဖြစ်ပီးချေးငွေလက်ကျန်စာရင်းအပေါ်တွင်လတစ်လ၏ ရက်ပေါင်း ပေါ်မူတည်ပြီး အတိုးတွက်ချက်ပါမည်။ စောလျင်စွာ (သို့) နောက်ကျစွာ (သို့) ပိုများသောပမာဏများ (သို့) ပိုနည်းသောပမာဏများကိုကျွနု်ပ် တို့၏ ခွင့်ပြုချက် မပါရှိပဲ ပြန်လည်ပေးချေခြင်းမပြုလုပ်ရပါ။ပ် တို့၏ ခွင့်ပြုချက် မပါရှိပဲ ပြန်လည်ပေးချေခြင်းမပြုလုပ်ရပါ။
                            </li>
                            <li style="word-wrap: break-word;padding: 15px;">
                              ထုတ်ချေးငွေ၏  ၁%  ကိုစီမံခန့်ခွဲရန် အခကြေးငွေ ဝန်ဆောင်ခ  အနေဖြင့်လည်းကောင်း  ၊ဝ.၅%  ကို လူမှုထောက်ပံ့ရေးရန်ပုံငွေ အဖြစ်လည်းကောင်း  ၊  ချေးငွေ၏  ၅%ကို မဖြစ်မနေစုဆောင်းငွေ အဖြစ်လည်ကောင်း  ချေးငွေထုတ်သည့်နေ့တွင် တစ်ကြိမ်ကောက်ခံမည်ဖြစ်သည်။  လူမှုထောက်ပံ့ရေးရန်ပုံငွေ ပေးသွင်းခြင်းဖြင့် {{$company}}  နှင့် အဖွဲ့ဝင်ဖြစ်နေစဉ်အတွင်း  ငွေချေးသူသေဆုံးပါက  ချေးငွေများကိုပယ်ဖျက်ပေးရန် အထောက်အကူပြုပါသည်။ {{$company}} တွင် ချေးထားသောချေးငွေများ မရှိတော့သောအခါ နှင့် {{$company}} ၏ အဖွဲ့ဝင်အဖြစ်မှ နှုတ်ထွက်သောအခါတွင် မဖြစ်မနေစုဆောင်းငွေအားလုံးကို ထုတ်ယူနိုင်မည် ဖြစ်သည်။ စုငွေအတိုးနှုန်းမှာ ၁.၁၇%ဖစ်ပါသည်။
                            <li style="word-wrap: break-word;padding: 15px;">
                                ပြန်လည်ပေးဆပ်ရမည့် ငွေအားလုံးကို ပေးဆပ်ရမည့်နေ့တွင်  နေ့လည်(၁၂)နာရီထက် နောက်မကျဘဲ ပေးသွင်းရပါမည်။ ထိုနေ့တွင် နေ့လည်(၁၂)နာရီထက်နောက်ကျ၍ ပေးသွင်းပါက နောက်ကျ   ပေးသွင်းသည်ဟု သတ်မှတ်ပြီး   နောက်ကျကြေးပေးသွင်းရမည် ဖြစ်သည်။၁ ရက်ထက်  နောက်ကျပါက နောက်ကျကြေး ပေးသွင်းရမည်ဖြစ်ပြီး ထိုနောက်ကျကြေးသည် တစ်ကြိမ်ပြန်ဆပ်ငွေပမာဏ၏   ၁%  ဖြစ်ပြီး  ၎င်းကိုနောက်ကျသည့်အခါတိုင်း နောက်ကျကြေးအဖြစ် ပေးသွင်းရပါမည်။ ငွေပြန်ဆပ်ရန် ပျက်ကွက်ခဲ့လျှင် နောက်ကျကြေးအပြင်  အခြားအရေးယူမှုများကို ပြုလုပ်မည်ကို ထုတ်ချေးသူဘက်က  သိရှိရန်လိုအပ်ပါသည်။
                            </li>
                            <li style="word-wrap: break-word;padding: 15px;">
                                အကယ်၍ အဖွဲ့ဝင် တစ်ဦးဦး အချိန်မှီပြန်လည်ပေးဆပ်ရန် ပျက်ကွက်ခြင်း၊  နောက်ကျခြင်း များဖြစ်ပေါ်ပါက ဝိုင်းကြီးချုပ်၏ စည်းကမ်းအရ ကျန်သော ဝိုင်းကြီးချုပ် အဖွဲ့ဝင်များ  အညီအမျှ  တာဝန်ယူ  ဖြေရှင်းပေးရမည်  ဖြစ်ပီး {{$company}}  ထံသို့  ငွေကို  ချက်ချင်းပြန်လည်ပေးချေရန် တာဝန်ရှိပါသည်။ သင်သည်ချေးငွေအဖွဲ့ဝင် ဖြစ်နေစဉ်ကာလအတွင်း    ဥပဒေ    (သို့) စည်းမျဉ်းစည်းကမ်းများ    ပြောင်းလဲခြင်းများရှိပါက    (သို့မဟုတ်)    ဥပဒေ    (သို့) စည်းမျဉ်းစည်းကမ်းအသစ်များ  ထွက်  ရှိပါက {{$company}}  မှဥပဒေ  နှင့်အညီ စည်းမျဉ်းစည်းကမ်း အပြောင်းအလဲ ပြုလုပ်မည်ကိုလည်း သဘောတူ ပါသည်။
                            </li>
                            <li style="word-wrap: break-word;padding: 15px;">
                            <?php echo $company ?> ထံမှငွေချေးယူသူသည် ရရှိသည့် ချေးငွေကို ချေးငွေစာချုပ်တွင် ဖော်ပြထားသော လုပ်ငန်းများ လုပ်ကိုင်ရန်အတွက်သာ အသုံးပြုရမည် ဖြြစ်ပီး ပြည်ထောင်စု သမ္မတ မြန်မာနိုင်ငံတော်၏ တည်ဆဲဥပဒေများအရ တားမြစ်ထားသည့် အခြားသော လုပ်ဆောင်မှု များအတွက် အသုံးမပြုရန် လိုအပ်ပါသည်။
                            </li>
                            <li style="word-wrap: break-word;padding: 15px;">
                                ငွေချေးသူအနေဖြင့်  စာချုပ်ပါ အချက်များ အားလုံးကို အတိအကျ လိုက်နာ ဆောင်ရွက်ရန်  လိုအပ်ပြီး  အကယ် ၍ စာချုပ်ပါ အချက်များက်ု ချိုးဖောက်လျှင်  ပြည်ထောင်စုသမ္မတ မြန်မာနိုင်ငံတော် ၏တည်ဆဲ ဥပဒေများအရ အရေးယူ ဆောင်ရွက်ခံရမည် ဖြစ်ပီး တရားစွဲဆိုမှု(သို့)  ဖြေရှင်းမှု့နှင့် စပ်လျဉ်းသည့် ကုန်ကျစရိတ် စုစုပေါင်းကို ပျက်ကွက်သူ(သို့) ချိုးဖောက်သူဖက်မှ ကျခံရမည် ဖြစ်သည် -စသည့် စည်းမျဉ်းစည်းကမ်း အချက်များကို  ကောင်းစွာ လိုက်နာ ဆောင်ရွက်မည် ဖြစ်ကြောင်းကို မိမိတို့၏ သဘောဆန္ဒအရ  လက်မှတ်ရေးထိုးပါသည်
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px; padding-left:10px;">
                    <div style="padding-left:50px;">
                        <p>ငွေချေးသူ၏ ဘယ်လက်မလက်ဗွေ <br> ငွေချေးသူ  </p><br>
                    </div>
                    <?php
                    $i = 1;
                    foreach($group_loans as $loan){?>
                    <div class="col-md-3 col-sm-3 col-xs-3" style="display: inline;padding-left:50px;">

                        <div style="width: 100px;height: 110px;border:2px solid black;">
                        </div><br>
                        <div style="line-height: 0px;">
                            {{$i}} {{$loan->client_name->name}}
                        </div><br>
                        <div class="row" style=""><label>Amount:</label>{{number_format($loan->loan_amount)}}
                        </div>
                    </div>
                    <?php $i+=1;} ?>
                </div>
