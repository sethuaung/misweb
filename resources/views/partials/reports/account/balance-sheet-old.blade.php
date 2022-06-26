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
        function excelGeneral(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            // console.log(tableID);
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
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
    .modal-lg {
        width: 75%;
    }
</style>

<div id="DivIdToPrint">
    @if($bals != null)
    <?php $today = date('Y-m-d'); ?>

    @include('partials.reports.header', ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
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
            </tr>

            <?php $total_current_asset = []; ?>
            @if(isset($bals[10]) || isset($bals[12]) || isset($bals[14]))
            <tr>
                <td colspan="{{count($branchs)+1}}" style="padding-left: 30px"><b>Current Assets</b></td>
            </tr>

            @foreach([10,12,14] as $sec)
            @if(isset($bals[$sec]))
            @if(count($bals[$sec])>0)
            @forelse($bals[$sec] as $acc_id => $bal)

            <?php
            if ($acc_id == 162) {
                if (companyReportPart() == "company.moeyan") {
                    $chat_account = null;
                }
            } else {
                $chat_account = \App\Models\AccountChart::find($acc_id);
            }
            ?>

            <tr>
                @if($chat_account)
                <?php $total = 0; ?>

                <td style="padding-left: 60px;">
                    {{ optional($chat_account)->code."-".optional($chat_account)->name}}
                </td>

                @foreach($branchs as $br_id)

                <?php
                if (!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;
                $total += ($b ?? 0);
                $total_current_asset[$br_id] += ($b ?? 0);
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

                ?>
                @if(companyReportPart() == "company.moeyan" && $total_current_asset[$br_id] < 0) <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format($total_current_asset[$br_id]??0,2)) }}</u></td>
                    @else
                    <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_current_asset[$br_id]??0,2)) }}</u></td>
                    @endif
                    @endforeach
                    {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
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
                if (!isset($total_fixed_asset[$br_id])) $total_fixed_asset[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;
                $total_fixed_asset[$br_id] += ($b ?? 0);
                $total += ($b ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $b < 0) <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                    {{ RemoveDecimal(number_format($b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
                    </td>
                    @else
                    <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                        {{ RemoveDecimal(number_format($b??0,2)) }}
                        <input type="hidden" class="branch" value="{{ $br_id }}" />
                    </td>
                    @endif

                    @endforeach
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
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
                @if(companyReportPart() == "company.moeyan" && $total_fixed_asset[$br_id] < 0) <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format($total_fixed_asset[$br_id]??0,2)) }}</u></td>
                    @else
                    <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_fixed_asset[$br_id]??0,2)) }}</u></td>
                    @endif
                    @endforeach
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
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
                if (!isset($total_other_asset[$br_id])) $total_other_asset[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;
                $total_other_asset[$br_id] += ($b ?? 0);
                $total += ($b ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $b < 0) <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                    {{ RemoveDecimal(number_format($b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
                    </td>
                    @else
                    <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                        {{ RemoveDecimal(number_format($b??0,2)) }}
                        <input type="hidden" class="branch" value="{{ $br_id }}" />
                    </td>
                    @endif
                    @endforeach
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
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
                $l_other_asset = 0;
                $total = 0;
                ?>
                <td style="padding-left: 30px"><b>Total Other Assets</b></td>
                @foreach($branchs as $br_id)
                <?php
                //$l_other_asset += $total_other_asset[$br_id];
                $total += ($total_other_asset[$br_id] ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $total_other_asset[$br_id] < 0) <td style="text-align: right;padding-right: 10px;color:red;"><u>{{ RemoveDecimal(number_format( $total_other_asset[$br_id]??0,2)) }}</u></td>
                    @else
                    <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format( $total_other_asset[$br_id]??0,2)) }}</u></td>
                    @endif
                    @endforeach
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
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
                @if(companyReportPart() == "company.moeyan" && $total_asset < 0) <td style="color: blue;padding-right: 20px;text-align: right;color:red;"><u>{{ RemoveDecimal(numb_format($total_asset,2)) }}</u></td>
                    @else

                    <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ RemoveDecimal(numb_format($total_asset,2)) }}</u></td>
                    @endif
                    @endforeach
                    @if(companyReportPart() == 'company.moeyan')
                    @if($total < 0) <td style="text-align: right;color:red;">{{RemoveDecimal(number_format($total??0))}}</td>
                        @else
                        <td style="text-align: right;">{{RemoveDecimal(number_format($total??0))}}</td>
                        @endif
                        @endif
            </tr>

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
                if (!isset($total_current_liabilities[$br_id])) $total_current_liabilities[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;
                $total_current_liabilities[$br_id] += ($b ?? 0);
                $total += ($b ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $b > 0)
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
                </td>
                @else
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
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
                @foreach($branchs as $br_id)
                <?php
                if (!isset($total_long_term_liabilities[$br_id])) $total_long_term_liabilities[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;
                $total_long_term_liabilities[$br_id] += ($b ?? 0);
                $total += ($b ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $b > 0)
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
                </td>
                @else
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
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
                @php $total = 0; @endphp
                @foreach($branchs as $br_id)
                <?php
                if (!isset($total_owners_equity[$br_id])) $total_owners_equity[$br_id] = 0;
                $b = isset($bal[$br_id]) ? ($bal[$br_id] ?? 0) : 0;

                $total_owners_equity[$br_id] += ($b ?? 0);
                $total += ($b ?? 0);
                ?>
                @if(companyReportPart() == "company.moeyan" && $b > 0)
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;color:red;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
                </td>
                @else
                <td class="general-leg" id="{{$acc_id}}" style="text-decoration: underline;cursor: pointer;text-align: right;">
                    {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    <input type="hidden" class="branch" value="{{ $br_id }}" />
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
            (isset($retainedEarningBegin[$br_id])?$retainedEarningBegin[$br_id]:0) + (isset($profit[$br_id])?$profit[$br_id]:0);
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

        </tbody>
    </table>

    @else
    <h1>No data</h1>
    @endif
</div>

<script>
    $(".general-leg").click(function() {
        var branch = $(this).find('.branch').val();
        getReport($(this).attr("id"), branch);
    });

    function getReport(id, branch_id) {
        $("#whole_page_loader").fadeIn(function() {
            var acc_chart_id = [id];
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
                    start_date: start_date,
                    end_date: end_date,
                    month: month,
                    acc_chart_id: acc_chart_id,
                    show_zero: show_zero,
                    branch_id: [branch_id]
                },
                success: function(d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').html(d);
                    $('#show-detail-modal').modal("show");
                },
                error: function(d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').hide();
                    $('#show-detail-modal').modal("hide");
                    alert('error');
                }
            });
        });
    }

    function printDiv() {

        var DivIdToPrintPop = document.getElementById('DivIdToPrintPop');
        if (DivIdToPrintPop != null) {
            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + DivIdToPrintPop.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                newWin.close();
            }, 10);
        }
    }
</script>