<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Market Conduct'])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>

@php
    if(companyReportPart() == "company.quicken"){
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        $acc_chart_id = ["22"];
        $branch_id = ["2"];
        //dd($start_date,$end_date,$branch_id,$acc_chart_id);
        $beg_leger = App\Models\ReportAccounting::getBeginGeneralLeger($start_date,$end_date, $branch_id, $acc_chart_id);
        if($beg_leger != null){
            foreach ($beg_leger as $b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_begin[$b->acc_chart_id] = $b->amt??0;
            }
        }
        
        if($branch_id == null){
            $branch_id = [1];
        }

        $gen_leger = App\Models\ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);

        

            if($gen_leger != null){

            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }

    }
   
@endphp
@foreach($arr_acc as $acc)
                <?php
                    $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
                    $ac_o = optional(\App\Models\AccountChart::find($acc));
                    //dd($begin);
                ?>
                <?php
                    $current = isset($arr_leger[$acc])?$arr_leger[$acc]:[];
                    $t_dr = 0;
                    $t_cr = 0;
                ?>
@endforeach
@php
    $current = $current??[];
    $begin = $begin??0;
@endphp
                @if(count($current)>0)
                    @foreach($current as $row)
                        <?php
                        $begin += (($row->dr??0)- ($row->cr??0));
                        
                        ?>
                    
                    @endforeach

                @endif
    @php

        $percentage_1 = $begin / $officer_activated;
        $percentage_2 = $loan_activated / $officer_activated;
        foreach($other_income as $income){
            //dd($income);
            $bal = $income->amt;
        }
        $bal = $bal??0;
    @endphp
    <table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="1154" style="min-width:100%;border-collapse:collapse;mso-yfti-tbllook:1184;
 mso-padding-alt:0in 5.4pt 0in 5.4pt">
        <tbody>
        <tr style="mso-yfti-irow:3;height:17.1pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt"></td>
            <td width="875" nowrap="" colspan="2" style="text-align: right;width:656.5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  283pt;mso-char-indent-count:50.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Date {{date("d/m/Y")}}</span></p>
            </td>
            {{-- <td width="100" nowrap="" style="width:75.05pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td> --}}
        </tr>
        <tr style="mso-yfti-irow:4;height:17.1pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt"></td>
            <td width="875" nowrap="" colspan="2" style="text-align: right;width:656.5pt;padding:0in 32.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  283pt;mso-char-indent-count:50.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Reporting Period:<o:p></o:p></span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:5;height:6.35pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.35pt"></td>
            <td width="694" nowrap="" valign="bottom" style="width:520.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.35pt"></td>
            <td width="182" nowrap="" valign="bottom" style="width:136.2pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.35pt"></td>
            <td width="100" nowrap="" style="width:75.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.35pt"></td>
        </tr>
        <tr style="mso-yfti-irow:6;height:19.8pt">
            <td width="178" nowrap="" style="width:133.8pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;height:19.8pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Area<o:p></o:p></span></b></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.8pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Indicator<o:p></o:p></span></b></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid black .5pt;padding:0in 5.4pt 0in 5.4pt;height:
  19.8pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></b></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:7;height:19.7pt">
            <td width="178" nowrap="" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Complaints<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number
  of Complaints Handled Internally<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"> - </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:8;height:19.7pt">
            <td width="178" nowrap="" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number of
  Complaints Referred to the Microfinance Supervisory Committee<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"> - </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:9;height:19.7pt">
            <td width="178" nowrap="" style="width:133.8pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number
  of Complaints Brought to Court<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"> - </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:10;height:19.7pt">
            <td width="178" rowspan="5" valign="top" style="width:133.8pt;border-top:none;
  border-left:solid windowtext 1.0pt;border-bottom:solid black 1.0pt;
  border-right:solid windowtext 1.0pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Case
  Load for Reviewing Over-Indebtedness<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Total
  Loan Outstanding<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{$begin}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:11;height:19.7pt">
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number
  of Active Clients<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{$loan_activated}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:12;height:19.7pt">
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number
  of Loan Officers <o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{$officer_activated}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:13;height:19.7pt">
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Total
  Loan Outstanding / Number of Loan Officers<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"><span style="mso-spacerun:yes">&nbsp; </span>{{round($percentage_1)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:14;height:19.7pt">
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Number
  of Active Clients / Number of Loan <span class="SpellE">Oficers</span><o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"><span style="mso-spacerun:yes">&nbsp; </span>{{round($percentage_2)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:15;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Appropriate
  Pricing<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Maximum
  Loan Interest Rate (%) (Monthly)<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;28%</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:16;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Commission
  and Fees Income<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;
  @if($bal < 0)
    {{-$bal}}
  @else
    {{$bal}}
  @endif
  
    </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:17;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Interest
  Income from Loans<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{$interest_collection}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:18;height:19.7pt">
            <td width="178" style="width:133.8pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Commission
  and Fees Income / Interest Income from Loans<o:p></o:p></span></p>
            </td>
            <td width="282" nowrap="" colspan="2" style="width:211.3pt;border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:19;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:solid windowtext 1.0pt;border-right:none;mso-border-left-alt:
  solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Licensed<o:p></o:p></span></b></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Operating<o:p></o:p></span></b></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:20;height:19.7pt">
            <td width="178" rowspan="2" valign="top" style="width:133.8pt;border-top:none;
  border-left:solid windowtext 1.0pt;border-bottom:none;border-right:solid windowtext 1.0pt;
  mso-border-left-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Area
  Expansion / Operating Area<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Total
  Branches<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 0</span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;1</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:21;height:19.7pt">
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Town<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 8 </span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 8 </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:22;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Township<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 8 </span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 8 </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:23;height:19.7pt">
            <td width="178" style="width:133.8pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Ward<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 57 </span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;34 </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:24;height:19.7pt">
            <td width="178" style="width:133.8pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="694" nowrap="" style="width:520.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Village<o:p></o:p></span></p>
            </td>
            <td width="182" nowrap="" style="width:136.2pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 809 </span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:19.7pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp; 16 </span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:25;height:7.65pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="694" nowrap="" valign="bottom" style="width:520.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="182" nowrap="" valign="bottom" style="width:136.2pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="100" nowrap="" valign="bottom" style="width:75.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
        </tr>
        <tr style="mso-yfti-irow:26;height:7.65pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="694" nowrap="" valign="bottom" style="width:520.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="182" nowrap="" valign="bottom" style="width:136.2pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
            <td width="100" nowrap="" valign="bottom" style="width:75.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:7.65pt"></td>
        </tr>
        <tr style="mso-yfti-irow:27;height:17.1pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt"></td>
            <td width="875" nowrap="" colspan="2" style="width:656.5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="text-align: right;margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  495.0pt;mso-char-indent-count:45.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Prepared by (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:28;height:6.9pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="694" nowrap="" style="width:520.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="182" nowrap="" valign="bottom" style="width:136.2pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="100" nowrap="" valign="bottom" style="width:75.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="mso-ascii-font-family:Calibri;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-hansi-font-family:Calibri;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:29;height:17.1pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt"></td>
            <td width="875" nowrap="" colspan="2" style="width:656.5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="text-align: right;margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  495.0pt;mso-char-indent-count:45.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Checked by
  (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:30;height:6.9pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="694" nowrap="" style="width:520.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="182" nowrap="" valign="bottom" style="width:136.2pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
            <td width="100" nowrap="" style="width:75.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:6.9pt"></td>
        </tr>
        <tr style="mso-yfti-irow:31;mso-yfti-lastrow:yes;height:17.1pt">
            <td width="178" nowrap="" valign="bottom" style="width:133.8pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt"></td>
            <td width="875" nowrap="" colspan="2" style="width:656.5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  495.0pt;mso-char-indent-count:45.0;line-height:normal;text-align: right;"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Approved by
  (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="100" nowrap="" style="width:75.05pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:17.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        </tbody></table>

</div>
