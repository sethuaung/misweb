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
<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Prudential Indicator','use_date'=>1])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>

    
    <table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="1269" style="min-width:100%;border-collapse:collapse;mso-yfti-tbllook:1184;
 mso-padding-alt:0in 5.4pt 0in 5.4pt">
        <tbody>
        <tr style="mso-yfti-irow:4;height:20.15pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt"></td>
            <td width="948" nowrap="" colspan="2" style="width:711.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  300.0pt;    text-align: right;mso-char-indent-count:55.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Date (:dd-mmm-<span class="SpellE">yyyy</span>:)<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{date("d/m/Y")}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:5;height:20.15pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt"></td>
            <td width="948" nowrap="" colspan="2" style="width:711.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  299.0pt;text-align: right;    margin-right: 37px;mso-char-indent-count:55.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Reporting period:<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:6;height:10.5pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:10.5pt"></td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:10.5pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:10.5pt"></td>
            <td width="138" valign="bottom" style="width:103.6pt;border:none;border-bottom:
  solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:10.5pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:7;height:30.05pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;height:30.05pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">CAMEL Area<o:p></o:p></span></b></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:30.05pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Prudential Indicators<o:p></o:p></span></b></p>
            </td>
            <td width="417" style="width:312.95pt;border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:30.05pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span class="GramE"><b><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">FRD<span style="mso-spacerun:yes">&nbsp; </span>Standard</span></b></span><b><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM"> <o:p></o:p></span></b></p>
            </td>
            <td width="138" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:30.05pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></b></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:8;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Capital
  <o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Total
  Equity / Total <span class="GramE">Assets<span style="mso-spacerun:yes">&nbsp;
  </span>(</span>Solvency Ratio)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">&gt; 12%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{round($result1)}}%<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:9;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Assets
  Quality <o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Non-Performing
  Loans / Gross Loans <o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">NA<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;NA</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:10;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Current Loan<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">1%<o:p></o:p></span></p>
            </td>
            @php
                $one = $begin * 0.01;
            @endphp
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{number_format($one)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:11;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Sub-Standard (1-30) Days *NPL<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">10%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{number_format($total_outstanding_one * 0.1)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:12;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Watch (31 - 60) Days *NPL<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">50%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{number_format($total_outstanding_two * 0.5)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:13;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Doubtful (61 - 90) Days *NPL<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">75%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{number_format($total_outstanding_three * 0.75)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:14;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Loss <span class="GramE">( Over</span> 91 ) Days *NPL<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">100%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;{{number_format($total_outstanding_four * 1)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:15;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Rescheduled Loans (1 Time)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">50%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:16;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Loan
  Loss Reserve Amount for Rescheduled Loans (2 Time)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">100%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:17;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Liquidity<o:p></o:p></span></p>
            </td>
            <td width="531" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Cash
  and Balances in <span class="GramE">Banks<span style="mso-spacerun:yes">&nbsp;
  </span>/</span> Voluntary Savings (Liquidity Ratio)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">&gt;25%<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM"><span style="mso-spacerun:yes">&nbsp; </span><o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:18;height:33.1pt">
            <td width="182" style="width:136.7pt;border:solid windowtext 1.0pt;border-top:
  none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:33.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Foreign
  Exchange Position<o:p></o:p></span></p>
            </td>
            <td width="531" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:33.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Assets
  in Foreign Exchange - Liabilities in Foreign Exchange<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:33.1pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">0<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:33.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:19;height:25.0pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:25.0pt"></td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:25.0pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:25.0pt"></td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:25.0pt"></td>
        </tr>
        <tr style="mso-yfti-irow:20;height:24.1pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;height:24.1pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">CAMEL Area<o:p></o:p></span></b></p>
            </td>
            <td width="531" style="width:398.3pt;border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:24.1pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Prudential Indicators<o:p></o:p></span></b></p>
            </td>
            <td width="417" style="width:312.95pt;border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:24.1pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span class="GramE"><b><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">FRD<span style="mso-spacerun:yes">&nbsp; </span>Standard</span></b></span><b><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM"> <o:p></o:p></span></b></p>
            </td>
            <td width="138" style="width:103.6pt;border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:24.1pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><b><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">Year to Date<o:p></o:p></span></b></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:21;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Earnings
  (Annualized)<o:p></o:p></span></p>
            </td>
            <td width="531" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Net
  Income after Tax / Average Total Assets (ROA)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">NA<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{number_format($earnings_1)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:22;height:26.3pt">
            <td width="182" nowrap="" style="width:136.7pt;border-top:none;border-left:solid windowtext 1.0pt;
  border-bottom:none;border-right:solid windowtext 1.0pt;mso-border-left-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:26.3pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:26.3pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Net
  Income after Tax / Average Total Equity (ROE)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:26.3pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">NA<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:26.3pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{number_format($earnings_2)}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:23;height:25.0pt">
            <td width="182" nowrap="" style="width:136.7pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" style="width:398.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Operating
  Expense / Gross Income (OSS)<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" style="width:312.95pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  background:#DDD9C4;padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="center" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  color:black;mso-bidi-language:KHM">NA<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0in 5.4pt 0in 5.4pt;height:25.0pt">
                <p class="MsoNormal" align="right" style="margin-bottom:0in;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;{{$earning_3}}</span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:24;height:9.0pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:9.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:9.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:9.0pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:9.0pt"></td>
        </tr>
        <tr style="mso-yfti-irow:25;height:18.05pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:18.05pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:10.0pt;font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">* NPL = <span class="GramE">Non Performing</span> Loans<o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:18.05pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:18.05pt"></td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:18.05pt"></td>
        </tr>
        <tr style="mso-yfti-irow:26;height:20.15pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:10.0pt;font-family:&quot;Myanmar3&quot;,serif;
  mso-fareast-font-family:&quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM"># LLR = Loan Loss Reserve<o:p></o:p></span></p>
            </td>
            <td width="948" nowrap="" colspan="2" style="width:711.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  550.0pt;mso-char-indent-count:50.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Prepared by
  (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:27;height:8.1pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="531" nowrap="" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="mso-ascii-font-family:Calibri;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-hansi-font-family:Calibri;mso-bidi-font-family:Calibri;
  mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:28;height:20.15pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt"></td>
            <td width="948" nowrap="" colspan="2" style="width:711.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  550.0pt;mso-char-indent-count:50.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Checked by
  (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:29;height:8.1pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="531" nowrap="" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
            <td width="138" nowrap="" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:8.1pt"></td>
        </tr>
        <tr style="mso-yfti-irow:30;height:20.15pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt"></td>
            <td width="948" nowrap="" colspan="2" style="width:711.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-indent:
  550.0pt;mso-char-indent-count:50.0;line-height:normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
  mso-bidi-font-family:Calibri;mso-bidi-language:KHM">Approved by
  (Name/Signature)<o:p></o:p></span></p>
            </td>
            <td width="138" nowrap="" style="width:103.6pt;border:none;border-bottom:solid windowtext 1.0pt;
  mso-border-bottom-alt:solid windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
  height:20.15pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM">&nbsp;<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:31;height:13.5pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:13.5pt">
                <p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;font-family:&quot;Myanmar3&quot;,serif;mso-fareast-font-family:
  &quot;Times New Roman&quot;;mso-bidi-font-family:Calibri;mso-bidi-language:KHM"><o:p></o:p></span></p>
            </td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:13.5pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:13.5pt"></td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:13.5pt"></td>
        </tr>
        <tr style="mso-yfti-irow:32;mso-yfti-lastrow:yes;height:15.0pt">
            <td width="182" nowrap="" valign="bottom" style="width:136.7pt;padding:0in 5.4pt 0in 5.4pt;
  height:15.0pt"></td>
            <td width="531" nowrap="" valign="bottom" style="width:398.3pt;padding:0in 5.4pt 0in 5.4pt;
  height:15.0pt"></td>
            <td width="417" nowrap="" valign="bottom" style="width:312.95pt;padding:0in 5.4pt 0in 5.4pt;
  height:15.0pt"></td>
            <td width="138" nowrap="" valign="bottom" style="width:103.6pt;padding:0in 5.4pt 0in 5.4pt;
  height:15.0pt"></td>
        </tr>
        </tbody></table>

</div>
