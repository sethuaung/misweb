<table style="width: 100%;border-collapse: collapse;border: none" border="0">
    <tr>
        <td  style="text-align: right;vertical-align: top; width: 20%;">
            <?php
            $m = getSetting();
            $logo = getSettingKey('logo',$m);
            ?>
            <div style="float: left;">
                <img style="height: 100px;" src="{{asset($logo)}}" class="image-logo">
            </div>
        </td>
        <td style="text-align: center;">
            <?php
            $m = getSetting();
            $company = getSettingKey('company-name',$m);
            ?>
            <b style="font-size: 20px;">{{$company}}</b><br>
                {{--<b style="font-size: 20px;">Teacher Loan</b><br>--}}
                <b style="font-size: 20px;">{{$report_name}}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="30px"></td>
    </tr>
</table>

