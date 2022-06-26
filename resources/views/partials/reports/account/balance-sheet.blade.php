<div class="modal fade" id="show-detail-modal" tabindex="-1" role="dialog" aria-labelledby="show-detail-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="DivIdToPrintPop">

            </div>
            <div class="action" style="float: right;">
                <button type="button" onclick="excelGeneral('DivIdToPrintPop') " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">EXCEL</button>
            </div>
            <div class="action" style="float: right;">
                <button type="button" onclick="printDiv() " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">PRINT</button>
            </div>
        </div>
    </div>

<script>
    function excelGeneral(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        // console.log(tableID);
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>

</div>
<input type="hidden" id="start_date" value="{{$start_date}}">
<input type="hidden" id="end_date" value="{{$end_date}}">
<input type="hidden" id="company" value="{{companyReportPart()}}">
<style>
    .modal-lg{
        width: 75%;
    }
</style>
<div id="DivIdToPrint">
    @if($bals != null)
        @php
            //dd($bals);
            $today = date('Y-m-d');
            $net_income = $net_income??null;
        @endphp
        @include('partials.reports.header',
        ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
        <div>{{$today}}</div>
        @foreach($branchs as $branch)
            <div><span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span></div>
        @endforeach
        <table border="1" class="table-data" id="table-data">
            <tbody>
            <tr>
                <td style="color: blue;"><b>Assets</b></td>
                @foreach($branchs as $b)

                    <td colspan="0" style="color: blue;"><b>{{optional(\App\Models\BranchU::find($b))->title}}</b></td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td style="text-align:center;color:black;">Total</td>
                @endif
            </tr>

            @php
                $total_current_asset = [];
            @endphp
            @if(companyReportPart() != 'company.mkt')
            @php
                //dd("nyi nyi");
            @endphp
            @if(isset($bals[10]) || isset($bals[12]) || isset($bals[14]))
            <tr>
                <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Current Assets</b></td>
            </tr>
            @foreach([10,12,14] as $sec)
                @if(isset($bals[$sec]))
                    @if(count($bals[$sec])>0)
                        @forelse($bals[$sec] as $acc_id => $bal)
                        @php
                            if($acc_id == 162){
                                if(companyReportPart() == "company.moeyan"){
                                    $chat_account = null;
                                }
                            }else{
                                $chat_account = \App\Models\AccountChart::find($acc_id);
                            }
                            @endphp
                            <tr>
                                @if($chat_account)
                                @php $total = 0; @endphp
                                <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                @foreach($branchs as $br_id)
                                    <?php
                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                    $b = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                    $total_current_asset[$br_id] += ($b??0);
                                    $total += ($b??0);
                                    ?>
                                    @if($b < 0 && companyReportPart() == "company.moeyan")
                                        <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                                            {{ RemoveDecimal(number_format($b??0,2)) }}
                                            <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                        </td>
                                    @else
                                        <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                                            {{ RemoveDecimal(number_format($b??0,2)) }}
                                            <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                        </td>
                                    @endif
                                @endforeach
                                @if(companyReportPart() == 'company.moeyan')
                                    @if($total < 0)
                                    <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                    @else
                                    <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                    @endif
                                @endif
                                @endif
                            </tr>
                            @endforeach
                            @endif
                            @endif
                            @endforeach
                            <tr>
                                <td style="padding-left: 30px"><b>Total Current Assets</b></td>
                                <?php $l_current_asset = 0;
                                    $total = 0;
                                 ?>
                                @foreach($branchs as $br_id)
                                    <?php
                                    //$l_current_asset += $total_current_asset[$br_id];
                                        $total += ($total_current_asset[$br_id]??0);
                                    ?>
                                    @if(companyReportPart() == "company.moeyan" && $total_current_asset[$br_id] < 0)
                                        <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format($total_current_asset[$br_id]??0,2)) }}</u></td>
                                    @else
                                        <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_current_asset[$br_id]??0,2)) }}</u></td>
                                    @endif
                                @endforeach
                                {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                @if(companyReportPart() == 'company.moeyan')
                                    @if($total < 0)
                                    <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                    @else
                                    <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                    @endif
                                @endif
                            </tr>
                            @endif
                            @php
                            $total_fixed_asset = [];

                        @endphp
                        @if(isset($bals[16]))

                            <tr>
                                <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Fixed Assets</b></td>
                            </tr>
                            @foreach([16] as $sec)

                                @if(isset($bals[$sec]))
                                    @if(count($bals[$sec])>0)
                                        @forelse($bals[$sec] as $acc_id => $bal)
                                            @php

                                                $chat_account = \App\Models\AccountChart::find($acc_id);
                                                $total = 0;
                                            @endphp
                                            <tr>
                                                <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                @foreach($branchs as $br_id)
                                                    <?php
                                                    if(!isset($total_fixed_asset[$br_id])) $total_fixed_asset[$br_id] = 0;
                                                    $b = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                    $total_fixed_asset[$br_id] += ($b??0);
                                                    $total += ($b??0);
                                                    ?>
                                                    @if(companyReportPart() == "company.moeyan" && $b < 0)
                                                        <td class="general-leg"  id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                                                        {{ RemoveDecimal(number_format($b??0,2)) }}
                                                            <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                        </td>
                                                    @else
                                                        <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                                                        {{ RemoveDecimal(number_format($b??0,2)) }}
                                                        <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                    </td>
                                                    @endif
                                                @endforeach
                                                @if(companyReportPart() == 'company.moeyan')
                                                    @if($total < 0)
                                                    <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                    @else
                                                    <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                    @endif
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                            @endif
                                            @endforeach
                                            <tr>

                                                <td style="padding-left: 30px"><b>Total Fixed Assets</b></td>
                                                @php $total = 0; @endphp
                                                @foreach($branchs as $br_id)
                                                @php $total += ($total_fixed_asset[$br_id]??0); @endphp
                                                    @if(companyReportPart() == "company.moeyan" && $total_fixed_asset[$br_id] < 0)
                                                        <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format($total_fixed_asset[$br_id]??0,2)) }}</u></td>
                                                    @else
                                                        <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_fixed_asset[$br_id]??0,2)) }}</u></td>
                                                    @endif
                                                @endforeach
                                                @if(companyReportPart() == 'company.moeyan')
                                                    @if($total < 0)
                                                    <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                    @else
                                                    <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                    @endif
                                                @endif
                                            </tr>
                                            @endif


                                            @php
                                                $total_other_asset = [];

                                            @endphp
                                            @if(isset($bals[18]))
                                                <tr>
                                                    <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Other Assets</b></td>
                                                </tr>
                                                @foreach([18] as $sec)
                                                    @if(isset($bals[$sec]))
                                                        @if(count($bals[$sec])>0)
                                                            @forelse($bals[$sec] as $acc_id => $bal)
                                                                @php

                                                                    $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                    $total = 0;
                                                                @endphp
                                                                <tr>
                                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                    {{--<td style="text-align: right">{{ number_format($bal??0,2) }}</td>--}}
                                                                    @foreach($branchs as $br_id)
                                                                        <?php
                                                                        if(!isset($total_other_asset[$br_id])) $total_other_asset[$br_id] = 0;
                                                                        $b = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                        $total_other_asset[$br_id] += ($b??0);
                                                                        $total += ($b??0);
                                                                        ?>
                                                                        @if(companyReportPart() == "company.moeyan" && $b < 0)
                                                                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                                                                            {{ RemoveDecimal(number_format($b??0,2)) }}
                                                                            <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                                            </td>
                                                                        @else
                                                                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                                                                            {{ RemoveDecimal(number_format($b??0,2)) }}
                                                                            <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                                        </td>
                                                                        @endif
                                                                    @endforeach
                                                                    @if(companyReportPart() == 'company.moeyan')
                                                                        @if($total < 0)
                                                                        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @else
                                                                        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                                <tr>
                                                                    <?php
                                                                    $l_other_asset =0;
                                                                    $total = 0;
                                                                    ?>
                                                                    <td style="padding-left: 30px"><b>Total Other Assets</b></td>
                                                                    @foreach($branchs as $br_id)
                                                                        <?php
                                                                        //$l_other_asset += $total_other_asset[$br_id];
                                                                        $total += ($total_other_asset[$br_id]??0);
                                                                        ?>
                                                                        @if(companyReportPart() == "company.moeyan" && $total_other_asset[$br_id] < 0)
                                                                            <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format( $total_other_asset[$br_id]??0,2)) }}</u></td>
                                                                        @else
                                                                            <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format( $total_other_asset[$br_id]??0,2)) }}</u></td>
                                                                        @endif
                                                                    @endforeach
                                                                    @if(companyReportPart() == 'company.moeyan')
                                                                        @if($total < 0)
                                                                        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @else
                                                                        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                                @endif


                                                                @php
                                                                    $total_all_assets = [];
                                                                    $total = 0;
                                                                    foreach($branchs as $br_id){
                                                                        $total_all_assets[$br_id] =(isset( $total_current_asset[$br_id] )? $total_current_asset[$br_id] :0)+(isset($total_fixed_asset[$br_id])?$total_fixed_asset[$br_id]:0)
                                                                        + (isset($total_other_asset[$br_id])?$total_other_asset[$br_id]:0);
                                                                    }

                                                                @endphp
                                                                <tr>
                                                                    <td style="color: blue;"><b>Total Assets</b></td>
                                                                    @php $total_asset = 0; @endphp
                                                                    @foreach($branchs as $br_id)
                                                                    @php $total_asset = $total_all_assets[$br_id]??0; 
                                                                        $total += ($total_all_assets[$br_id]??0);
                                                                    @endphp
                                                                        @if(companyReportPart() == "company.moeyan" && $total_asset < 0)
                                                                            <td style="color: blue;padding-right: 20px;text-align: right;color:red;"><u>{{ RemoveDecimal(numb_format($total_asset,2)) }}</u></td>
                                                                        @else
                                                                        
                                                                            <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(numb_format($total_asset,2)) }}</u></td>
                                                                        @endif
                                                                    @endforeach
                                                                    @if(companyReportPart() == 'company.moeyan')
                                                                        @if($total < 0)
                                                                        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @else
                                                                        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                        @endif
                                                                    @endif
                                                                </tr>


                                                                {{--////////////////////////////////////////////////////////
                                                                ////////////////////////////////////////////////////////
                                                                ////////////////////////////////////////////////////////--}}
                                                                <tr>
                                                                    <td colspan="{{count($branchs)+1}}" style="color: green;"><b>Liabilities & Owners Equity</b></td>
                                                                </tr>
                                                                <tr>
                                                                <td colspan="{{count($branchs)+1}}" style="color: blue;padding-left: 10px"><b>Liabilities</b></td>
                                                                </tr>
                                                                @php
                                                                    $total_current_liabilities = [];
                                                                @endphp
                                                                @if(isset($bals[20]) || isset($bals[22]) || isset($bals[24]))
                                                                    <tr>
                                                                        <td colspan="{{count($branchs)+2}}" style="padding-left: 30px"><b>Current Liabilities</b></td>
                                                                    </tr>
                                                                    @foreach([20,22,24] as $sec)
                                                                        @if(isset($bals[$sec]))
                                                                            @if(count($bals[$sec])>0)
                                                                                @forelse($bals[$sec] as $acc_id => $bal)
                                                                                    @php

                                                                                        $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                                        $total = 0;
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>

                                                                                        @foreach($branchs as $br_id)
                                                                                            <?php
                                                                                            if(!isset($total_current_liabilities[$br_id])) $total_current_liabilities[$br_id] = 0;
                                                                                            $b = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                            $total_current_liabilities[$br_id] += ($b??0);
                                                                                            $total += ($b??0);
                                                                                            ?>
                                                                                            @if(companyReportPart() == "company.moeyan" && $b > 0)
                                                                                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                                                                                                {{ RemoveDecimal(number_format(-$b??0,2)) }}
                                                                                                <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                                                            </td>
                                                                                            @else
                                                                                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                                                                                                {{ RemoveDecimal(number_format(-$b??0,2)) }}
                                                                                                <input type="hidden" class="branch" value="{{ $br_id }}"/>
                                                                                            </td>
                                                                                            @endif
                                                                                        @endforeach
                                                                                        @if(companyReportPart() == 'company.moeyan')
                                                                                            @if($total > 0)
                                                                                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                                                                                            @elseif ($total == 0)
                                                                                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                                            @else
                                                                                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                                                                                            @endif
                                                                                        @endif
                                                                                    </tr>
                                                                                    @endforeach
                                                                                    @endif
                                                                                    @endif
                                                                                    @endforeach
                                                                                    <tr>
                                                                                        <?php
                                                                                        $l_current_liabilities = 0
                                                                                        
                                                                                        ?>
                                                                                        <td style="padding-left: 30px"><b>Total Current Liabilities</b></td>
                                                                                        @php $total = 0; @endphp
                                                                                        @foreach($branchs as $br_id)
                                                                                        @php $total += $total_current_liabilities[$br_id]??0; @endphp
                                                                                            @if(companyReportPart() == "company.moeyan" && $total_current_liabilities[$br_id] > 0)
                                                                                            <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format(-$total_current_liabilities[$br_id]??0,2)) }}</u></td>
                                                                                            @else
                                                                                            <td style="text-align: right;padding-right: 10px;"><u>{{ RemoveDecimal(number_format(-$total_current_liabilities[$br_id]??0,2)) }}</u></td>
                                                                                            @endif
                                                                                        @endforeach
                                                                                        @if(companyReportPart() == 'company.moeyan')
                                                                                            @if($total > 0)
                                                                                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                                                                                            @elseif ($total == 0)
                                                                                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                                                                                            @else
                                                                                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                                                                                            @endif
                                                                                        @endif
                                                                                    </tr>
                                                                                    @endif


                                                                                    @php
                                                                                        $total_long_term_liabilities = [];

                                                                                    @endphp
@if(isset($bals[26]))
<tr>
<td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Long Term Liabilities</b></td>
</tr>
@foreach([26] as $sec)
@if(isset($bals[$sec]))
@if(count($bals[$sec])>0)
@forelse($bals[$sec] as $acc_id => $bal)
@php

    $chat_account = \App\Models\AccountChart::find($acc_id);
    $total = 0;
@endphp
<tr>
    <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
    {{--<td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
    @foreach($branchs as $br_id)
        <?php
        if(!isset($total_long_term_liabilities[$br_id])) $total_long_term_liabilities[$br_id] = 0;
        $b = isset($bal[$br_id])?($bal[$br_id]??0):0;
        $total_long_term_liabilities[$br_id] += ($b??0);
        $total += ($b??0);
        ?>
        @if(companyReportPart() == "company.moeyan" && $b > 0)
        <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
            {{ RemoveDecimal(number_format(-$b??0,2)) }}
            <input type="hidden" class="branch" value="{{ $br_id }}"/>
        </td>
        @else
        <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
            {{ RemoveDecimal(number_format(-$b??0,2)) }}
            <input type="hidden" class="branch" value="{{ $br_id }}"/>
        </td>
        @endif
    @endforeach
    @if(companyReportPart() == 'company.moeyan')
        @if($total > 0)
        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @elseif ($total == 0)
        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
        @else
        <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @endif
    @endif
</tr>
@endforeach
@endif
@endif
@endforeach

<tr>
    <td style="padding-left: 30px"><b>Total Long Term Liabilities</b></td>
    @php $total = 0; @endphp
    @foreach($branchs as $br_id)
        @php $total += ($total_long_term_liabilities[$br_id]??0); @endphp
        @if(companyReportPart() == "company.moeyan" && $total_long_term_liabilities[$br_id] > 0)
        <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format(-$total_long_term_liabilities[$br_id]??0,2)) }}</u></td>
        @else
        <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format(-$total_long_term_liabilities[$br_id]??0,2)) }}</u></td>
        @endif
    @endforeach
    @if(companyReportPart() == 'company.moeyan')
        @if($total > 0)
        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @elseif ($total == 0)
        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
        @else
        <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @endif
    @endif
</tr>
@endif


@php
    $total_all_liabilities = [];
    foreach($branchs as $br_id){
    $total_all_liabilities[$br_id] = (isset($total_current_liabilities[$br_id])?$total_current_liabilities[$br_id]:0) + (isset($total_long_term_liabilities[$br_id])?$total_long_term_liabilities[$br_id]:0);
    }

@endphp
<tr>
    <td style="color: blue;padding-left: 10px"><b>Total Liabilities</b></td>
    @php $total = 0; @endphp
    @foreach($branchs as $br_id)
        @php $total += ($total_all_liabilities[$br_id]??0); @endphp
        @if(companyReportPart() == "company.moeyan" && $total_all_liabilities[$br_id] > 0)
        <td style="color: blue;padding-right: 20px;text-align: right;color:red;"><u>{{ RemoveDecimal(number_format(-$total_all_liabilities[$br_id]??0,2)) }}</u></td>
        @else
        <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(number_format(-$total_all_liabilities[$br_id]??0,2)) }}</u></td>
        @endif
    @endforeach
    @if(companyReportPart() == 'company.moeyan')
        @if($total > 0)
        <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @elseif ($total == 0)
        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
        @else
        <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
        @endif
    @endif
</tr>


{{--////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////--}}
<tr>
    <td colspan="{{count($branchs)+1}}" style="color: blue;padding-left: 10px"><b>Owners Equity</b></td>
</tr>

@php
    $total_owners_equity = [];

@endphp
@if(isset($bals[30]))
    @foreach([30] as $sec)
        @if(isset($bals[$sec]))
            @if(count($bals[$sec])>0)
                @forelse($bals[$sec] as $acc_id => $bal)
                    @php

                        $chat_account = \App\Models\AccountChart::find($acc_id);

                    @endphp
                    <tr>
                        <td style="padding-left: 30px;">{{optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                        {{--<td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                        @php $total = 0; @endphp
                        @foreach($branchs as $br_id)
                            <?php
                            if(!isset($total_owners_equity[$br_id])) $total_owners_equity[$br_id] = 0;
                            $b = isset($bal[$br_id])?($bal[$br_id]??0):0;

                            $total_owners_equity[$br_id] += ($b??0);
                            $total += ($b??0);
                            ?>
                            @if(companyReportPart() == "company.moeyan" && $b > 0)
                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                                {{ RemoveDecimal(number_format(-$b??0,2)) }}
                                <input type="hidden" class="branch" value="{{ $br_id }}"/>
                            </td>
                            @else
                            <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                                {{ RemoveDecimal(number_format(-$b??0,2)) }}
                                <input type="hidden" class="branch" value="{{ $br_id }}"/>
                            </td>
                            @endif
                        @endforeach
                        @if(companyReportPart() == 'company.moeyan')
                            @if($total > 0)
                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @elseif ($total == 0)
                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                            @else
                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @endif
                        @endif
                    </tr>
                    @endforeach
                    @endif
                    @endif
                    @endforeach
                    @endif
                    @php
                        $total_all_owners_equity = [];
                        foreach($branchs as $br_id){
                        $total_all_owners_equity[$br_id] = (isset($total_owners_equity[$br_id])?$total_owners_equity[$br_id]:0) +
                                                    (isset($retainedEarningBegin[$br_id])?$retainedEarningBegin[$br_id]:0)  + (isset($profit[$br_id])?$profit[$br_id]:0);
                        }

                    @endphp
                    <tr>
                        <td style="padding-left: 30px;">Retained Earning</td>
                        @php $retain = 0; $total = 0; @endphp
                        @foreach($branchs as $br_id)
                        @php $retain = $retainedEarningBegin[$br_id]??0;
                            $total += ($retainedEarningBegin[$br_id]??0);
                         @endphp
                            @if(companyReportPart() == "company.moeyan" && $retain > 0)
                            <td style="text-align: right;color:red;">{{ RemoveDecimal(number_format(-$retain,2)) }}</td>
                            @else
                            <td style="text-align: right">{{ RemoveDecimal(number_format(-$retain,2)) }}</td>
                            @endif
                        @endforeach
                        @if(companyReportPart() == 'company.moeyan')
                            @if($total > 0)
                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @elseif ($total == 0)
                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                            @else
                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @endif
                        @endif
                    </tr>

                   <tr>
                        <td style="padding-left: 30px;">Net Profit/Net Loss</td>
                        @php $total = 0; @endphp
                        @if(companyReportPart() == "company.moeyan")
                        @foreach($branchs as $br_id)
                        @php $net = $net_income[$br_id]??0; 
                            $total += ($net_income[$br_id]??0);
                        @endphp
                            @if($net > 0)
                            <td><span style="float: right;color:red;">{{ RemoveDecimal(number_format(-$net,2)) }}</span></td>
                            @else
                            <td><span style="float: right">{{ RemoveDecimal(number_format(-$net,2)) }}</span></td>
                            @endif
                        @endforeach
                        @else
                        @foreach($branchs as $br_id)
                            <td><span style="text-align: left">{{ (isset($net_income[$br_id])?$net_income[$br_id]:0)<0?'Net Profit':'Net Loss' }}</span><span style="float: right">{{ RemoveDecimal(number_format(-(isset($net_income[$br_id])?$net_income[$br_id]:0)??0,2)) }}</span></td>
                        @endforeach
                        @endif
                        @if(companyReportPart() == 'company.moeyan')
                            @if($total > 0)
                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @elseif ($total == 0)
                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                            @else
                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @endif
                        @endif
                    </tr> 


                    <tr>
                        <td style="color: blue;padding-left: 10px"><b>Total Owners Equity</b></td>
                        @php $total = 0; @endphp
                        @foreach($branchs as $br_id)
                        @php $owner = $total_all_owners_equity[$br_id]??0; 
                            $total += ($total_all_owners_equity[$br_id]??0);
                        @endphp
                            @if($owner > 0 && companyReportPart() == "company.moeyan")
                            <td style="color: blue;padding-right: 20px;text-align: right;color:red;"><u>{{ RemoveDecimal(number_format(-$owner,2)) }}</u></td>
                            @else
                            <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(number_format(-$owner,2)) }}</u></td>
                            @endif
                        @endforeach
                        @if(companyReportPart() == 'company.moeyan')
                            @if($total > 0)
                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @elseif ($total == 0)
                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                            @else
                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @endif
                        @endif
                    </tr>
                    <tr>
                        <td style="color: green;"><b>Total Liabilities & Owners Equity</b></td>
                        @php $total = 0; @endphp
                        @if(companyReportPart() == "company.moeyan")
                        @php $owner_total = 0; @endphp
                        @foreach($branchs as $br_id)
                            @php $owner_total = ($total_all_owners_equity[$br_id]??0)+($total_all_liabilities[$br_id]??0); 
                                $total += ($owner_total??0);
                            @endphp
                            @if($owner_total > 0)
                            <td style="color: red;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(number_format(-$owner_total??0,2)) }}</u></td>
                            @else
                            <td style="color: green;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(number_format(-$owner_total??0,2)) }}</u></td>
                            @endif
                        @endforeach
                        @else
                        @foreach($branchs as $br_id)
                            <td style="color: green;padding-right: 20px;text-align: right;"><u>{{ number_format(-((isset($total_all_owners_equity[$br_id])?$total_all_owners_equity[$br_id]:0)+(isset($total_all_liabilities[$br_id])?$total_all_liabilities[$br_id]:0)),2) }}</u></td>
                        @endforeach
                        @endif
                        @if(companyReportPart() == 'company.moeyan')
                            @if($total > 0)
                            <td style="text-align: right;color:red;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @elseif ($total == 0)
                            <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                            @else
                            <td style="text-align: right;">{{RemoveDecimal(number_format(-$total??0))}}</td>
                            @endif
                        @endif
                    </tr>
            @else
            @php
            //dd($net_income);
       @endphp
       @if(isset($bals[16]))
       
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Fixed Assets</b></td>
       </tr>    
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Intangible Assets</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Formation Expenses</b></td>
       </tr>
           
       @foreach([16] as $sec)
       @if(isset($bals_dr[$sec]))
           @if(count($bals_dr[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals_dr[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                           //dd($chat_account);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);                                                                                           
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['100120','100130','100102','100101'])
                                                               ->first();
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                           <?php
                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                           $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $f_exp = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                    $formation_exp = $formation_exp??0;
                                    $formation_exp += $f_exp;
                                
                        
                          
                          
                           ?>
                          
                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                         @endif
                       @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                      
                       @endif
                       {{-- Minus from net profit loss start here --}}
                       @if(isset($bals[16]))
           
       @foreach([16] as $sec)
       @if(isset($bals_dr[$sec]))
           @if(count($bals_dr[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals_dr[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                           //dd($chat_account);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);                                                                                           
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['100130','100220','100710','105120','107020','107390','107520','108040','108520'])
                                                               ->first();
                   }
                   @endphp
                   @if($chat_account != null)
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                           <?php
                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                           $minus_solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                           $minus = $minus??0;
                           $minus += $minus_solo;
                            ?>
                          
                           
                         @endif
                       @endforeach
                   
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                      
                       @endif
                {{-- Minus from net profit loss end here --}}

                       @if(isset($bals_dr[16]))
                      
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Software Cap</b></td>
                       </tr>
                           
                       @foreach([16] as $sec)
                       @if(isset($bals_dr[$sec]))
                           @if(count($bals_dr[$sec])>0)
                           @php
                              //dd($minus); 
                           @endphp
                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                   @php
                                       //dd($acc_id);
                                   if($acc_id == 162){
                                       if(!companyReportPart() == "company.moeyan"){
                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                       }
                                   }else{
                                       $account = \App\Models\AccountChart::find($acc_id);
                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                               ->whereIn('code',['100210','100220','100200'])
                                                                               
                                                                               
                                                                               ->first();
                                       //dd($chat_account);
                                   }
                                   @endphp
                                    @if($chat_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                    @endif
                                        @foreach($branchs as $br_id)
                                        @if($chat_account != null) 
                                            <?php
                                            if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                               $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $s_cap = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                               
                                                    $software_cap = $software_cap??0;
                                                    $software_cap += $s_cap
                                               
                                               
                                               ?>
                                            
                                               <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                           @endif
                                        @endforeach
                                    </tr>
                                   
                                   @endforeach
                                   @endif
                                   @endif
                                   @endforeach
                                       
                                       @endif
                                        @php
                                            //dd($software_cap);
                                        @endphp
                                       @if(isset($bals_dr[16]))
                      
                                       <tr>
                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Intangible Assets</b></td>
                                       </tr>
                                           
                                       @foreach([16] as $sec)
                                       @if(isset($bals_dr[$sec]))
                                           @if(count($bals_dr[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                   if($acc_id == 162){
                                                       if(!companyReportPart() == "company.moeyan"){
                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                       }
                                                   }else{
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->whereIn('code',['100700','100710','100300'])
                                                                                               
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   }
                                                   @endphp
                                                    @if($chat_account != null)
                                                    <tr>
                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                    @endif
                                                    @foreach($branchs as $br_id)
                                                    @if($chat_account != null)  
                                                    <?php
                                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $o_asset = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                       
                                                                $other_asset = $other_asset??0;
                                                                $other_asset += $o_asset;
                                                            
                                                      
                                                      
                                                       //dd($software_cap);
                                                       ?>
                                                   
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                @endforeach
                                                    </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                   <tr>
                                                       <td style="padding-left: 30px"><b>Total Intangible Assets</b></td>
                                                       <?php $l_current_asset = 0; ?>
                                                       @foreach($branchs as $br_id)
                                                           <?php
                                                           $other_asset = $other_asset??0;
                                                           $software_cap = $software_cap??0;
                                                           $formation_exp = $formation_exp??0;

                                                           $intangible_assets = $other_asset + $software_cap + $formation_exp;
                                                           //$l_current_asset += $total_current_asset[$br_id];
                                                           //dd($intangible_assets);
                                                           ?>
                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($intangible_assets,2) }}</u></td>
                                                       @endforeach
                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                   </tr>
                                                   <tr>
                                                       <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                   </tr>
                                                       @endif
                                                       @if(isset($bals[16]))
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Tangible Assets</b></td>
                                                       </tr>    
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Land</b></td>
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Buildings</b></td>
                                                       </tr>
                                                           
                                                       @foreach([16] as $sec)
                                                       @if(isset($bals_dr[$sec]))
                                                           @if(count($bals_dr[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['105110','105210','105100','105001','105000'])
                                                                                                              
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                      $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $build = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                            
                                                                                    $building = $building??0;
                                                                                    $building += $build;
                                                                            
                                                                      
                                                                      ?>
                                                                    
                                                                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                  @endif
                                                               @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                      
                                                                       @endif
                                                    @if(isset($bals[16]))
                                                      
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Furniture Fixtures & Fittings</b></td>
                                                       </tr>
                                                           
                                                       @foreach([16] as $sec)
                                                       @if(isset($bals_dr[$sec]))
                                                           @if(count($bals_dr[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['107010','107020','107000'])
                                                                                                               
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                      $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      
                                                                        $furni = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                            
                                                                                    $furniture = $furniture??0;
                                                                                    $furniture += $furni;
                                                                            
                                                                      
                                                                      ?>
                                                                   
                                                                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                  @endif
                                                               @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                      
                                                                       @endif
       @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Electronic and Electrical equipment</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['107310','107390','107300'])
                                                       
                                                      
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
               $elect = isset($bal[$br_id])?($bal[$br_id]??0):0;

                   
                            $electronic = $electronic??0;
                            $electronic += $elect;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif

       @if(isset($bals_dr[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Computer & IT Equipments</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['107510','107520','107500'])
                                                       
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
                $com = isset($bal[$br_id])?($bal[$br_id]??0):0;

                            $computer = $computer??0;
                            $computer += $com;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif
       @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Motor Vehicles</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['108010','108040','108000'])
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;

             $mot = isset($bal[$br_id])?($bal[$br_id]??0):0;

                    
                            $motor = $motor??0;
                            $motor += $mot;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif
               
           @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Tools & Equipments</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['108510','108520','108500'])
                                                      
                                                       
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null) 
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
               $tools = isset($bal[$br_id])?($bal[$br_id]??0):0;

                    
                            $equipment = $equipment??0;
                            $equipment += $tools;
                    
              
              ?>
           
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
           <tr>
               <td style="padding-left: 30px"><b>Total Tangible Assets</b></td>
               <?php $l_current_asset = 0; ?>
               @foreach($branchs as $br_id)
                   <?php
                       $furniture = $furniture??0;
                       $building = $building??0;
                       $electronic = $electronic??0;
                       $motor = $motor??0;
                       $computer = $computer??0;
                       $equipment = $equipment??0;
                       $tangible_assets = $furniture + $building +  $electronic + $motor + $computer + $equipment;
                   ?>
                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($tangible_assets,2) }}</u></td>
               @endforeach
               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
           </tr>
           <tr>
               <td style="padding-left: 10px"><b>Total Fixed Assets</b></td>
               <?php $l_current_asset = 0; ?>
               @foreach($branchs as $br_id)
                   <?php
                       $fixed_assets = $intangible_assets + $tangible_assets;
                   ?>
                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($fixed_assets,2) }}</u></td>
               @endforeach
               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
           </tr>
           <tr>
               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
           </tr>
               @endif
       @if(isset($bals[10]))
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Current Assets</b></td>
       </tr>    
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Cash And Bank Equivalents</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Bank Account</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 150 . '%')
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                   @foreach($branchs as $br_id)
                   @if($chat_account != null)
                   <?php
                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                      $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $bank = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                      $bank_total = $bank_total??0;
                      $current = $current??0;

                      $current += $current_assets;
                      $bank_total += $bank;
                      ?>
                    
                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                  @endif
               @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px"><b>Total Bank</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $bank_total = $bank_total??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($bank_total,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

       @if(isset($bals[10]))
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>CASH IN HAND</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 153 . '%')
                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                   @foreach($branchs as $br_id)
                   @if($chat_account != null)
                   <?php
                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                      $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $cash_in = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                      $cash_in_hand = $cash_in_hand??0;
                      $current = $current??0;
                      $current += $current_assets;
                      $cash_in_hand += $cash_in;
                      ?>
                   
                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                  @endif
               @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px"><b>Total Cash</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $cash_in_hand = $cash_in_hand??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($cash_in_hand,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[10]))
         
       
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>CASH IN TRANSIT</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 1570 . '%')
                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $cash_transit = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $cash_in_transit = $cash_in_transit??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $cash_in_transit += $cash_transit;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px"><b>Total Cash in Transit</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $cash_in_transit= $cash_in_transit??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($cash_in_transit,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[14]))
         
       
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Inventorys</b></td>
       </tr>
           
       @foreach([14] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['165000','179900','179910','179940'])
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $invertory = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $total_inventory = $total_inventory??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $total_inventory += $invertory;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px"><b>Total Inventorys</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                $total_inventory = $total_inventory??0;   
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_inventory,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[12]))
         
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>ACCOUNT RECEIVABLES</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Service Receivable</b></td>
       </tr>
           
       @foreach([12] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['183000','185000','185810','185820','185830','185840','185850','185860','185910','185920','185930','185950','185960','185965'])
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $receviable = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $service_receviable = $service_receviable??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $service_receviable += $receviable;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px"><b>Total Service Receivable</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $service_receviable = $service_receviable??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($service_receviable,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[12]))
         
                       
                       <tr>
                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Interest Receivable</b></td>
                       </tr>
                           
                       @foreach([12] as $sec)
                       @if(isset($bals[$sec]))
                           @if(count($bals[$sec])>0)
                           @php
                              //dd($bals); 
                           @endphp
                               @forelse($bals[$sec] as $acc_id => $bal)
                                   @php
                                       //dd($acc_id);
                                   if($acc_id == 162){
                                       if(!companyReportPart() == "company.moeyan"){
                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                       }
                                   }else{
                                       $account = \App\Models\AccountChart::find($acc_id);
                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                               ->whereIn('code',['186400','186110','186120','186130','186140','186200','186210','186150','186250','186260','186160','186230','186220','186170'])
                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                               ->first();
                                       //dd($chat_account);
                                   }
                                   @endphp
                                   @if($chat_account != null)
                                   <tr>
                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                   @endif
                                       @foreach($branchs as $br_id)
                                       @if($chat_account != null)
                                       <?php
                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                       $interest = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                       $interest_receviable = $interest_receviable??0;
                                       $current = $current??0;
                                       $current += $current_assets;
                                       $interest_receviable += $interest;
                                       ?>
                                     
                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                   @endif
                                   @endforeach
                                   </tr>
                                   
                                   @endforeach
                                   @endif
                                   @endif
                                   @endforeach
                                       <tr>
                                           <td style="padding-left: 30px"><b>Total Interest Receivable</b></td>
                                           <?php $l_current_asset = 0; ?>
                                           @foreach($branchs as $br_id)
                                               <?php
                                                   $interest_receviable = $interest_receviable??0;
                                               ?>
                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($interest_receviable,2) }}</u></td>
                                           @endforeach
                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                       </tr>
                                       <tr>
                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                       </tr>
                                       @endif

                                       @if(isset($bals[14]))
         
                       
                                       <tr>
                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Provisions For Doubtful Debts</b></td>
                                       </tr>
                                           
                                       @foreach([14] as $sec)
                                       @if(isset($bals[$sec]))
                                           @if(count($bals[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                   if($acc_id == 162){
                                                       if(!companyReportPart() == "company.moeyan"){
                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                       }
                                                   }else{
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->where('code','189010')
                                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   }
                                                   @endphp
                                                   @if($chat_account != null)
                                                   <tr>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                   @endif
                                                       @foreach($branchs as $br_id)
                                                       @if($chat_account != null)
                                                       <?php
                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $debts = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                       $total_debts = $total_debts??0;
                                                       $current = $current??0;
                                                       $current += $current_assets;
                                                       $total_debts += $debts;
                                                       ?>
                                                     
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                   @endforeach
                                                   </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                       <tr>
                                                           <td style="padding-left: 30px"><b>Total Provisions For Doubtful Debts</b></td>
                                                           <?php $l_current_asset = 0; ?>
                                                           @foreach($branchs as $br_id)
                                                               <?php
                                                                   $total_debts = $total_debts??0;
                                                               ?>
                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_debts,2) }}</u></td>
                                                           @endforeach
                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                       </tr>
                                                       @endif

                                                       @if(isset($bals[14]))
         
                       
                                       <tr>
                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Receivables</b></td>
                                       </tr>
                                           
                                       @foreach([14] as $sec)
                                       @if(isset($bals[$sec]))
                                           @if(count($bals[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                   if($acc_id == 162){
                                                       if(!companyReportPart() == "company.moeyan"){
                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                       }
                                                   }else{
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->whereIn('code',['190000','190030','190090','190100'])
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   }
                                                   @endphp
                                                   @if($chat_account != null)
                                                   <tr>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                   @endif
                                                       @foreach($branchs as $br_id)
                                                       @if($chat_account != null) 
                                                       <?php
                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $other = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       
                                                       $total_receivable = $total_receivable??0;
                                                       $current = $current??0;
                                                       $current += $current_assets;
                                                       $total_receivable += $other;
                                                       ?>
                                                    
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                   @endforeach
                                                   </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                       <tr>
                                                           <td style="padding-left: 30px"><b>Total Other Receivables</b></td>
                                                           <?php $l_current_asset = 0; ?>
                                                           @foreach($branchs as $br_id)
                                                               <?php
                                                                   $total_receivable = $total_receivable??0;
                                                               ?>
                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_receivable,2) }}</u></td>
                                                             @endforeach
                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                       </tr>
                                                       @endif

                                                       @if(isset($bals[14]))
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Tax Receivables</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['190510','190500'])
                                                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $tax = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $tax_receivable = $tax_receivable??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $tax_receivable += $tax;
                                                                       ?>
                                                                     
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px"><b>Total Tax Receivables</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $tax_receivable = $tax_receivable??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($tax_receivable,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                       @endif

                                                                       @if(isset($bals[14]))
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Receivable Group</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['190250','190240','190245','192213','192200'])
                                                                                                               ->first();
                                                                       
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                  
                                                                       @foreach($branchs as $br_id)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $group = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                       $other_group = $other_group??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $other_group += $group;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                       @endforeach
                                                                   @endif
                                                                  
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px"><b>Total Other Receivable Group</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $other_group = $other_group??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($other_group,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                       @endif

                                                                       @if(isset($bals[14]))
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Prepayments & Accrued Income</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['193000','193020','193240','193050','193060','193120','193250'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $prepayment = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                      
                                                                       $prepayments = $prepayments??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $prepayments += $prepayment;
                                                                       ?>
                                                                    
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px"><b>Total Prepayments & Accrued Income</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $prepayments = $prepayments??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($prepayments,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                      
                                                                       
                                                                       @endif
                                                                       <tr>
                                                                        <td style="padding-left: 10px"><b>Total Current Assets</b></td>
                                                                        <?php $l_current_asset = 0; ?>
                                                                        @foreach($branchs as $br_id)
                                                                            <?php
                                                                                $current = $current??0;
                                                                            ?>
                                                                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format($current,2) }}</u></td>
                                                                          @endforeach
                                                                        {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                    </tr>
                                                                       <tr>
                                                                        <td style="padding-left: 10px; color: blue;"><b>Total Assets</b></td>
                                                                        <?php $l_current_asset = 0; ?>
                                                                        @foreach($branchs as $br_id)
                                                                            <?php
                                                                            $current = $current??0;
                                                                            $fixed_assets = $fixed_assets??0;
                                                                            $total_asset = $current + $fixed_assets;
                                                                            ?>
                                                                            <td style="text-align: right;padding-right: 10px; color: blue;;"><u>{{ number_format($total_asset,2) }}</u></td>
                                                                          @endforeach
                                                                        {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                    </tr>
                                                                       @if(isset($bals[26]))
                                                                       
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 10px"><b>CAPITAL AND LIABILITIES</b></td>
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Long Term Liabilities</b></td>
                                                       </tr> 
                                                       @foreach([26] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['250000','250030','250040','250050','250080'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $long = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $long_term = $long_term??0;
                                                                       $capitalli = $capitalli??0;
                                                                       $long_term += $long;
                                                                       $capitalli += $capital;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px"><b>Total Long Term Liabilities</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $long_term = $long_term??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($long_term,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                      
                                                                       @endif
                                                                       

                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>CURRENT LIABILITIES</b></td>
                                                                       </tr>  
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Service Creditors(Saving)</b></td>
                                                                       </tr> 
                                                                       @foreach([24] as $sec)
                                                                       @if(isset($bals[$sec]))
                                                                           @if(count($bals[$sec])>0)
                                                                           @php
                                                                              //dd($bals[$sec]); 
                                                                           @endphp
                                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                                   @php
                                                                                       //dd($acc_id);
                                                                                   if($acc_id == 162){
                                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                                       }
                                                                                   }else{
                                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                               ->whereIn('code', ['203851','203852','203000','200001'])
                                                                                                                               ->first();
                                                                                       //dd($chat_account);
                                                                                   }
                                                                                   @endphp
                                                                                   @if($chat_account != null)
                                                                                   <tr>
                                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                                   @endif
                                                                                       @foreach($branchs as $br_id)
                                                                                       @if($chat_account != null)
                                                                                       <?php
                                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                       $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $saving = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       
                                                                                       $service_saving = $service_saving??0;
                                                                                       $capitalli = $capitalli??0;
                                                                                       $current_libs = $current_libs??0;

                                                                                       $service_saving += $saving;
                                                                                       $capitalli += $capital;
                                                                                       $current_libs += $current_lib;
                                                                                       ?>
                                                                                   
                                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                                   @endif
                                                                                   @endforeach
                                                                                   </tr>
                                                                                   
                                                                                   @endforeach
                                                                                   @endif
                                                                                   @endif
                                                                                   @endforeach
                                                                                       <tr>
                                                                                           <td style="padding-left: 30px"><b>Total</b></td>
                                                                                           <?php $l_current_asset = 0; ?>
                                                                                           @foreach($branchs as $br_id)
                                                                                               <?php
                                                                                                   $service_saving = $service_saving??0;
                                                                                               ?>
                                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($service_saving,2) }}</u></td>
                                                                                             @endforeach
                                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                       </tr>
                                                                                      
                                                                                       @endif
                                                                                       

                                   @if(isset($bals[24]))
                                   @foreach([24] as $sec)
                                   @if(isset($bals[$sec]))
                                       @if(count($bals[$sec])>0)
                                       @php
                                           //dd($bals[$sec]); 
                                       @endphp
                                           @forelse($bals[$sec] as $acc_id => $bal)
                                               @php
                                                   //dd($acc_id);
                                               if($acc_id == 162){
                                                   if(!companyReportPart() == "company.moeyan"){
                                                       $chat_account = \App\Models\AccountChart::find($acc_id);
                                                   }
                                               }else{
                                                   $account = \App\Models\AccountChart::find($acc_id);
                                                   $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                           ->whereIn('code', ['203853','203854'])
                                                                                           ->first();
                                                   //dd($chat_account);
                                               }
                                               @endphp
                                               @if($chat_account != null)
                                               <tr>
                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                               @endif
                                                   @foreach($branchs as $br_id)
                                                   @if($chat_account != null)
                                                   <?php
                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                   $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $total_2 = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                   $current_libs = $current_libs??0;
                                                   $total2 = $total2??0;
                                                   $capitalli = $capitalli??0;

                                                   $total2 += $total_2;
                                                   $capitalli += $capital;
                                                   $current_libs += $current_lib;
                                                   ?>
                                               
                                                   <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                               @endif
                                               @endforeach
                                               </tr>
                                               
                                               @endforeach
                                               @endif
                                               @endif
                                               @endforeach
                                                   <tr>
                                                       <td style="padding-left: 30px"><b>Total</b></td>
                                                       <?php $l_current_asset = 0; ?>
                                                       @foreach($branchs as $br_id)
                                                           <?php
                                                               $total2 = $total2??0;
                                                           ?>
                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total2,2) }}</u></td>
                                                           @endforeach
                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                   </tr>
                                                   
                                                   @endif                                            

                                                   @if(isset($bals[24]))
                                                   @foreach([24] as $sec)
                                                   @if(isset($bals[$sec]))
                                                       @if(count($bals[$sec])>0)
                                                       @php
                                                           //dd($bals[$sec]); 
                                                       @endphp
                                                           @forelse($bals[$sec] as $acc_id => $bal)
                                                               @php
                                                                   //dd($acc_id);
                                                               if($acc_id == 162){
                                                                   if(!companyReportPart() == "company.moeyan"){
                                                                       $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                   }
                                                               }else{
                                                                   $account = \App\Models\AccountChart::find($acc_id);
                                                                   $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                           ->whereIn('code',['213520','213521'])
                                                                                                           
                                                                                                           ->first();
                                                                   //dd($chat_account);
                                                               }
                                                               @endphp
                                                               @if($chat_account != null)
                                                               <tr>
                                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                               @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                   $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $total_3 = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   
                                                                   $total3 = $total3??0;
                                                                   $capitalli = $capitalli??0;
                                                                   $current_libs = $current_libs??0;

                                                                   $total3 += $total_3;
                                                                   $capitalli += $capital;
                                                                   $current_libs += $current_lib;
                                                                   ?>
                                                               
                                                                   <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                               @endif
                                                               @endforeach
                                                               </tr>
                                                               
                                                               @endforeach
                                                               @endif
                                                               @endif
                                                               @endforeach
                                                                   <tr>
                                                                       <td style="padding-left: 30px"><b>Total</b></td>
                                                                       <?php $l_current_asset = 0; ?>
                                                                       @foreach($branchs as $br_id)
                                                                           <?php
                                                                               $total3 = $total3??0;
                                                                           ?>
                                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total3,2) }}</u></td>
                                                                           @endforeach
                                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                   </tr>
                                                                   <tr>
                                                                       <td style="padding-left: 10px"><b>Total Service Creditors(Saving)</b></td>
                                                                       <?php $l_current_asset = 0; ?>
                                                                       @foreach($branchs as $br_id)
                                                                           <?php
                                                                               $capitalli = $capitalli??0;
                                                                           ?>
                                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($capitalli,2) }}</u></td>
                                                                           @endforeach
                                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                   </tr>
                                                                   <tr>
                                                                       <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                   </tr>
                                                                   
                                                                   @endif

                                                           @if(isset($bals[24]))
                                                           
   
               
                                                               
                                                               <tr>
                                                                   <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Current Liabilities</b></td>
                                                               </tr> 
                                                               @foreach([24] as $sec)
                                                               @if(isset($bals[$sec]))
                                                                   @if(count($bals[$sec])>0)
                                                                   @php
                                                                       //dd($bals[$sec]); 
                                                                   @endphp
                                                                       @forelse($bals[$sec] as $acc_id => $bal)
                                                                           @php
                                                                               //dd($acc_id);
                                                                           if($acc_id == 162){
                                                                               if(!companyReportPart() == "company.moeyan"){
                                                                                   $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                               }
                                                                           }else{
                                                                               $account = \App\Models\AccountChart::find($acc_id);
                                                                               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                       ->whereIn('code',['210000','210001','250020','210010','210030','210040'])
                                                                                                                       ->first();
                                                                               //dd($chat_account);
                                                                           }
                                                                           @endphp
                                                                           @if($chat_account != null)
                                                                           <tr>
                                                                               <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                           @endif
                                                                               @foreach($branchs as $br_id)
                                                                               @if($chat_account != null)
                                                                               <?php
                                                                               if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                               $total_li = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               
                                                                               $total_other = $total_other??0;
                                                                               $current_libs = $current_libs??0;
                                                                               $total_other += $total_li;
                                                                               $current_libs += $current_lib;
                                                                               ?>
                                                                           
                                                                               <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                           @endif
                                                                           @endforeach
                                                                           </tr>
                                                                           
                                                                           @endforeach
                                                                           @endif
                                                                           @endif
                                                                           @endforeach
                                                                               <tr>
                                                                                   <td style="padding-left: 30px"><b>Total Other Current Liabilities</b></td>
                                                                                   <?php $l_current_asset = 0; ?>
                                                                                   @foreach($branchs as $br_id)
                                                                                       <?php
                                                                                           $total_other = $total_other??0;
                                                                                       ?>
                                                                                       <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_other,2) }}</u></td>
                                                                                       @endforeach
                                                                                   {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                               </tr>
                                                                               <tr>
                                                                                   <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                               </tr>
                                                                               @endif
                                                                               

                                                                       @if(isset($bals[24]))

       
                                                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Tax Payables</b></td>
                                                       </tr> 
                                                       @foreach([24] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                               //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['213020','213000'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $tax_p = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $tax_pay = $tax_pay??0;
                                                                       $current_libs = $current_libs??0;
                                                                       $tax_pay += $tax_p;
                                                                       $current_libs += $current_lib;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px"><b>Total Tax Payables</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $tax_pay = $tax_pay??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($tax_pay,2) }}</u></td>
                                                                               @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                      
                                                                       @endif

                                           @if(isset($bals[24]))


                                   
                                           <tr>
                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Personnel Payable</b></td>
                                           </tr> 
                                           @foreach([24] as $sec)
                                           @if(isset($bals[$sec]))
                                               @if(count($bals[$sec])>0)
                                               @php
                                                   //dd($bals[$sec]); 
                                               @endphp
                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                       @php
                                                           //dd($acc_id);
                                                       if($acc_id == 162){
                                                           if(!companyReportPart() == "company.moeyan"){
                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                           }
                                                       }else{
                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                   ->whereIn('code', ['213500','213510','213570','213580','213611'])
                                                                                                   ->first();
                                                           //dd($chat_account);
                                                       }
                                                       @endphp
                                                       @if($chat_account != null)
                                                       <tr>
                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                       @endif
                                                               @foreach($branchs as $br_id)
                                                               @if($chat_account != null)
                                                               <?php
                                                               if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                               $other_p = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               
                                                               $other_pay = $other_pay??0;
                                                               $current_libs = $current_libs??0;
                                                               $other_pay += $other_p;
                                                               $current_libs += $current_lib;
                                                               ?>
                                                           
                                                               <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                           @endif
                                                           @endforeach
                                                       </tr>
                                                       
                                                       @endforeach
                                                       @endif
                                                       @endif
                                                       @endforeach
                                                           <tr>
                                                               <td style="padding-left: 30px"><b>Total Other Personnel Payable</b></td>
                                                               <?php $l_current_asset = 0; ?>
                                                               @foreach($branchs as $br_id)
                                                                   <?php
                                                                   
                                                                   $other_pay = $other_pay??0;
                                                                   ?>
                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($other_pay,2) }}</u></td>
                                                                   @endforeach
                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                           </tr>
                                                           <tr>
                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                           </tr>
                                                           
                                                           @endif
                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Accurals</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                       if($acc_id == 162){
                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                           }
                                                                       }else{
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',['215080','215120','215000'])
                                                                                                                   
                                                                                                                   ->first();
                                                                           //dd($chat_account);
                                                                       }
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $accu = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $accurals = $accurals??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $accurals += $accu;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px"><b>Total Accurals</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $accurals = $accurals??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($accurals,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           @endif

                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Deferred Income</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                       if($acc_id == 162){
                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                           }
                                                                       }else{
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $code = ['215180','215181','215182','215183','215184','215185','215186','215187','215188','215189','215190','215191','215192','215193','215194','216000'];
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',$code)
                                                                                                                   ->first();
                                                                           //dd($chat_account);
                                                                       }
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $defer = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $defer_income = $defer_income??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $defer_income += $defer;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px"><b>Total Deferred Income</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $defer_income = $defer_income??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($defer_income,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           @endif

                                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Payables</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                       if($acc_id == 162){
                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                           }
                                                                       }else{
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',['217000','217130','274030','274040'])
                                                                                                                   ->first();
                                                                           //dd($chat_account);
                                                                       }
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $other_pay = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $other_pays = $other_pays??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $other_pays += $other_pay;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px"><b>Total Other Payables</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $other_pays = $other_pays??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($other_pays,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                               <td style="padding-left: 30px"><b>Total CURRENT LIABILITIES</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                        $current_libs = $current_libs??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($current_libs,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                            <td style="padding-left: 30px"><b>Total LIABILITIES</b></td>
                                                                            <?php $l_current_asset = 0; ?>
                                                                            @foreach($branchs as $br_id)
                                                                                <?php
                                                                                     $total_lib = $current_libs +  $total_other;
                                                                                ?>
                                                                                <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_lib,2) }}</u></td>
                                                                                @endforeach
                                                                            {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                        </tr>
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           
                                                                           @endif
                                                                           @if(companyReportPart() == 'company.mkt')
                                                                           @if(isset($bals[30]))


                                   
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Equity</b></td>
                                                                           </tr> 
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>SHAREHOLDERS' EQUITY</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                       if($acc_id == 162){
                                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                                           }
                                                                                       }else{
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['300000','310000','310010'])
                                                                                                                                   
                                                                                                                                   ->first();
                                                                                           //dd($chat_account);
                                                                                       }
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $share_eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $share_equity = $share_equity??0;
                                                                                           $equity = $equity??0;
                                                                                           $share_equity += $share_eq;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                                       @endif
                                                                                       @endforeach
                                                                                       </tr>
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       @endforeach
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px"><b>Total SHAREHOLDERS' EQUITY</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $share_equity = $share_equity??0;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($share_equity,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                                           </tr>
                                                                                           
                                                                                           @endif
                                                                                           
                                                                                           @if(isset($bals[30]))
                
                
                                                   
                                                                          
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Reserves</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                       if($acc_id == 162){
                                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                                           }
                                                                                       }else{
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['321000','321200','321500','323000'])
                                                                                                                                   ->first(); 
                                                                                           //dd($chat_account);
                                                                                       }
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $re = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $reserve = $reserve??0;
                                                                                           $equity = $equity??0;
                                                                                           $reserve += $re;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                                       @endif
                                                                                       @endforeach
                                                                                          
                                                                                       </tr>
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       @endforeach
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px"><b>Total Reserves</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $reserve = $reserve??0;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($reserve,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                               <td colspan="{{count($branchs)+1}}" style="height: 30px; overflow:hidden;"></td>
                                                                                           </tr>
                                                                                         
                                                                                           @endif
                                                                                           <br>
                
                                                                       @if(isset($bals[30]))
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Retained Earning</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                       if($acc_id == 162){
                                                                                           if(!companyReportPart() == "company.moeyan"){
                                                                                               $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                                           }
                                                                                       }else{
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['332010','332000','340100'])
                                                                                                                                   ->first();
                                                                                           
                                                                                       }
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code."-".optional($chat_account)->name}}</td>
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $retain = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $retain_earn = $retain_earn??0;
                                                                                           $equity = $equity??0;
                                                                                           $retain_earn += $retain;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                                       @endif
                                                                                       @endforeach
                                                                                          
                                                                                       </tr>
                                                                                       
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       @endforeach
                                                                                       <tr>
                                                                                        <td style="padding-left: 60px;">332020 - Retained Earning Current Year</td>
                                                                                   
                                                                                        @foreach($branchs as $br_id)
                                                                                       @php
                                                                                           $net_income[$br_id] = $net_income[$br_id]??0;
                                                                                           $minus = $minus??0;
                                                                                           if($minus<0){
                                                                                            $current_year =  $net_income[$br_id] - $minus;
                                                                                           }
                                                                                           else{
                                                                                            $current_year =  $net_income[$br_id] + $minus;
                                                                                           }
                                                                                           
                                                                                       @endphp
                                                                                       
                                                                                    
                                                                                        <td style="text-align: right">{{ number_format($current_year??0,2) }}</td>
                                                                                  
                                                                                    @endforeach
                                                                                       
                                                                                    </tr>
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px"><b>Total Retained Earning</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $retain_earn = $retain_earn??0;
                                                                                                       $retain_earn = $retain_earn + $current_year;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($retain_earn,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                               <td style="padding-left: 10px"><b>Total Equity</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $equity = $equity??0;
                                                                                                       $equity = $equity + $current_year;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($equity,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                          
                                                                                          
                                                                                           
                                                                                           @endif
                                                                           @endif

                                                                           <tr>
                                                                            <td style="padding-left: 0px; color: blue;"><b>TOTAL CAPITAL AND LIABILITIES</b></td>
                                                                            <?php $l_current_asset = 0; ?>
                                                                            @foreach($branchs as $br_id)
                                                                                <?php
                                                                                     $current_libs = $current_libs??0;
                                                                                     $equity = $equity??0;
                                                                                     $total_cap_li = isset($total_lib) ?  $total_lib + $equity : $equity;
                                                                                ?>
                                                                                <td style="text-align: right;padding-right: 10px;color: blue;"><u>{{ number_format($total_cap_li,2) }}</u></td>
                                                                                @endforeach
                                                                            {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                        </tr>
                                                                            

       {{-- @endif --}}

                          
                                                                                                                               {{--////////////////////////////////////////////////////////
                                                                                                                               ////////////////////////////////////////////////////////
                                                                                                                               ////////////////////////////////////////////////////////--}}

       </tbody>
   </table>

@else
   <h1>No data</h1>
@endif
</div>

<script>
    $(".general-leg").click(function(){
        var branch = $(this).find('.branch').val();
        getReport($(this).attr("id"), branch);
    });


    function getReport(id, branch_id){
        $("#whole_page_loader").fadeIn(function(){
            var acc_chart_id= [id];
            var start_date = $('[name="start_date"]').val();
            var end_date = $('[name="end_date"]').val();
            var month = $('[name="month"]').val();
            var show_zero = $('[name="show_zero"]').val();
            var company = $('#company').val();
            var url = company == 'company.moeyan' ? "{{ url('report/general-leger-moeyan')}}" : "{{ url('report/general-leger')}}";
            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                dataType: 'html',
                data: {
                    start_date:start_date,
                    end_date:end_date,
                    month:month,
                    acc_chart_id:acc_chart_id,
                    show_zero:show_zero,
                    branch_id: [branch_id]
                },
                success: function (d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').html(d);
                    $('#show-detail-modal').modal("show");
                },
                error: function (d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').hide();
                    $('#show-detail-modal').modal("hide");
                    alert('error');
                }
            });
        });
    }

    function printDiv() {

        var DivIdToPrintPop = document.getElementById('DivIdToPrintPop');//DivIdToPrintPop
        if(DivIdToPrintPop != null) {
            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + DivIdToPrintPop.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function () {
                newWin.close();
            }, 10);
        }
    }
</script>

