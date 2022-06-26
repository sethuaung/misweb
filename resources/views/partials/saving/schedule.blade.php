<table style="width: 100%;">
    <thead>
    <h3 style="text-align:center;">မိုးယံစုဘူး Schedule</h3>
    <tr>
        <th style="text-align:left;">To</th>
        <th style="text-align:right;" colspan="2">Date : {{\Carbon\Carbon::parse($first_payment_date)->format('d/m/y')}}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td style="text-align:right;" colspan="2">serial no. SF-00001</td>
    </tr>
    <tr>
        <td>></td>
        <td style="font-weight: bold;">Name : U Aye Thein</td>
        <td style="width: 60%;"></td>
    </tr>
    <tr>
        <td>></td>
        <td style="font-weight: bold;">Agreement No : NKN-061309</td>
        <td style="width: 60%;"></td>
    </tr>
    <tr>
        <td colspan="2">14/ဟသတ(နိုင်)010002 / 13/နခန(နိုင်)061309</td>
    </tr>
    <tr>
        <td>074-60551 / 09-47010074</td>
    </tr>
    <tr>
        <td>အေြကာင်းအရာ ။</td>
        <td>။ စုဘူး စုရမည့် အစီအစဥ်ဇယား</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2">ေအာက်ပါ စုဘူး အစီအစဥ်ဇယားအတိုင်း လစဥ် စုေငွသွင်းရန် လိုအပ်ပါသည်။
            ။</td>
    </tr>
    </tbody>
</table>
<h6 style="font-weight: bold;">Information</h6>
<table border="1px solid black" style=" border-collapse: collapse; width:80%; margin-left:20px;">
    <tr>
        <td>ေမျာ်မှန်းေငွ</td>
        <td>{{number_format($saving_amount??0,-2)}} MMK</td>
        <td>ပထမဆုံးလ စုရမည့်ရက်</td>
        <td>{{\Carbon\Carbon::parse($first_payment_date)->format('d/m/y')}}</td>
    </tr>
    <tr>
        <td>စုလိုေသာနှစ်</td>
        <td>{{$term_value/12}} {{$term_value/12>1?'Years':'Year'}}</td>
        <td>ေနာက်ဆုံးလ စုရမည့်ရက်</td>
        <td>#REF!</td>
    </tr>
    <tr>
        <td>လစဥ် စုရမည့်ေငွ</td>
        <td>{{number_format($saving_amount??0,-2)}} MMK</td>
        <td>စုဘူး အြပည့်ထုတ်နိုင်သည့်ရက်</td>
        <td>{{\Carbon\Carbon::parse($last_payment_date)->format('d/m/y')}}</td>
    </tr>
</table>
<table border="1px solid black" style=" border-collapse: collapse; width:100%; margin-top:20px;">
    <thead>
        <tr>
            <th></th>
            <th colspan="3" style="text-align: center;">အရင်း</th>
            <th colspan="3" style="text-align: center;">အတိုးကာလ</th>
            <th style="text-align: center;">အတိုးနှုန်း</th>
            <th></th>
            <th style="text-align: center;">အတိုးရ ေငွ</th>
        </tr>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">ေန့စွဲ</th>
            <th style="text-align: center;">လစဥ် စုရမည့်ေငွ</th>
            <th style="text-align: center;">လက်ကျန် ေငွ</th>
            <th style="text-align: center;">မှ</th>
            <th style="text-align: center;">သို့</th>
            <th style="text-align: center;">လ</th>
            <th style="text-align: center;">တစ်နှစ်နှုန်း</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
        </tr>
    </thead>
    <?php
        $total = 0;

    ?>
    @if($schedules != null)
       @foreach($schedules as $s)

    @if($s['no'] == 0)
        <tr style="background: lightgreen">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">Int</td>
            <td style="text-align: center;">{{number_format($s['interest'])}}</td>
            <td style="text-align: center;">{{number_format($s['compound'])}}</td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">1</td>
            <td style="text-align: center;">{{$interest_rate}}%</td>
            <td></td>
            <td style="text-align: center;"></td>
        </tr>
    @else
        <tr>
            <td style="text-align: center;">{{$s['no']}}</td>
            <td style="text-align: center;">{{\Carbon\Carbon::parse($s['date'])->format('d-m-Y')}}</td>
            <td style="text-align: center;">{{number_format($s['principle'])}}</td>
            <td style="text-align: center;">{{number_format($s['compound'])}}</td>
            <td style="text-align: center;">{{\Carbon\Carbon::parse($s['from_date'])->format('d-m-Y')}}</td>
            <td style="text-align: center;">{{\Carbon\Carbon::parse($s['to_date'])->format('d-m-Y')}}</td>

            <td style="text-align: center;">1</td>
            <td style="text-align: center;">{{$interest_rate}}%</td>
            <td></td>
            <td style="text-align: center;">{{$s['interest']}}</td>
        </tr>

    @endif

        @endforeach
    <?php
        $total = last($schedules);
    ?>
    <tr style=" border-right-style:hidden; border-left-style: hidden;">
        <td style="border-bottom:hidden;"></td>
        <td style="border-left-style:hidden;border-bottom:hidden;"></td>
        <td style="text-align: center; font-weight: bold; border-left-style:hidden;">Total</td>
        <td style="text-align: right; font-weight: bold; border-left-style:hidden; border-right-style:hidden;">{{numb_format($total['compound'],0)}}</td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
    </tr>
    <tr style=" border-right-style:hidden; border-left-style: hidden;">
        <td style="border-bottom:hidden;"></td>
        <td style="border-left-style:hidden;border-bottom:hidden;"></td>
        <td style="text-align: center; font-weight: bold; border-left-style:hidden;"></td>
        <td style="text-align: right; font-weight: bold; border-left-style:hidden; border-right-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
        <td style="border-bottom:hidden;border-left-style:hidden;"></td>
    </tr>
    @endif
</table>
<p style="text-align: center;">အထက်ပါ စုဘူး အစီအစဥ်ဇယားအတိုင်း လစဥ် စုေငွသွင်းရန် လိုအပ်ပါသည်။  ။</p>
<table style="width: 100%;">
    <tr>
        <td><span style="font-weight: bold;">>></span></td>
        <td style="text-align: center; width: 60%;"><span style="font-weight: bold;">Star Moe Yan Microfinance</span> သို့ သွင်းမည် ဟုေြပာြပီး <span style="font-weight: bold;">"စုသည့်သူ၏ အမည်"</span> နှင့်</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">Moe Yan မှ ေပးေသာ <span style="font-weight: bold;">"ကုဒ်နံပါတ် Agreement number"</span> တို့ကို တိကျမှန်ကန်စွာ ေရးြဖည့်ရပါမည်။</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">KBZ Bank Acc No. - 04510304502701901</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">CB Bank Acc No. - 0010100500041173</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">AYA Bank Acc No. - 0211103010000281</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">Yoma Bank Acc No. - 000310263000325</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">UAB Bank Acc No. - 016010100011135</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 80%; text-align: center;">KBZ, CB, AYA Mobile Banking - My Service > Payment > Bill Payment > Star Moe Yan Microfinance</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">မိုးယံစုဘူး စည်းမျဥ်းစည်းကမ်းများ </td>
    </tr>
    <tr>
        <td style="width: 1%;">&#10003;</td>
        <td style="width: 60%;">စုဘူးနှစ်ြပည့်သည်အထိ လစဥ် ေငွစုရမည်ြဖစ်ပါသည်။</td>
    </tr>
    <tr>
        <td>&#10003;</td>
        <td>လစဥ်လဆန်း(၇)ရက် ေန့သည် စုေငွသွင်းရမည့် ေနာက်ဆုံးရက် ြဖစ်ပါသည်။</td>
    </tr>
    <tr>
        <td>&#10003;</td>
        <td>စုဘူး မြပည့်မီ ထုတ်ယူပါက <span>&ensp;&nbsp;၁။အတိုးနှုန်း၁၀% ြဖင့်ထုတ်နိုင်ပါသည်။</span></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;၂။ Coupon ြဖင့် Discount ရရှိထားပါက Discount ေငွကို ြပန်နုတ်ထားမည် ြဖစ်ပါသည်။</td>
        <td></td>
    </tr>
</table>
<table border="3px solid black" style=" border-collapse: collapse; width:100%; margin-top:15px;margin-bottom: 10px">
    <tr border="3px solid black" style="height:250px;" valign="top">
        <td style="border: 3px solid black" >စာရင်းြပုစုသူ</td>
        <td style="border: 3px solid black">Customer</td>
        <td style="border: 3px solid black">အတည်ြပုသူ</td>
    </tr>
</table>
<p style="text-align: center;">Star Moe Yan Microfinance မှ လှိုက်လဲစွာ ြကိုဆိုေနပါသည်။ ဆက်သွယ်ရန်: 09-260414371</p>
