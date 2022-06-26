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
<div id="DivIdToPrint">
    @if($bals != null)
    <?php

    ?>

    @include('partials.reports.header',
    ['report_name'=>'Profit Loss','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table border="1" class="table-data" id="table-data">
        <thead>
            <th></th>
            <?php $sp = count($branches) + 1; ?>
            @foreach($branches as $branch)
            <th class="branchs">{{ optional(\App\Models\Branch::find($branch))->title  }}</th>
            @endforeach
            @if(companyReportPart() == 'company.moeyan')
            <th>Total</th>
            @endif
        </thead>

        <tbody>

            {{--Ordinary Income--}}
            <tr>
                @if(companyReportPart() == 'company.moeyan')
                <td colspan="{{count($branches)+2}}" style="color: blue;"><b>Ordinary Income / Expense</b></td>
                @else
                <td colspan="{{count($branches)+1}}" style="color: blue;"><b>Ordinary Income / Expense</b></td>
                @endif
            </tr>
            @php
            //$total_income += ($bal??0);
            $total_equity = [];
            @endphp

            @if(isset($bals[30]) && companyReportPart() == 'company.moeyan')
            @if(count($bals[30])>0)
            <tr>
                <td colspan="{{count($branches)+2}}" style="padding-left: 30px"><b>Equity</b></td>
            </tr>

            @forelse($bals[30] as $acc_id => $bal)

            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg30" id="{{ $acc_id }}" style="text-decoration: underline;cursor: pointer">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>
                @foreach($branches as $b_id)
                <?php
                $total = 0;
                if (!isset($total_equity[$b_id])) $total_equity[$b_id] = 0;

                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_equity[$b_id] += ($b ?? 0);
                $total = array_sum($bal);

                ?>
                <td style="text-align: right">
                    <a style="text-decoration:underline;cursor: pointer" class="general-leg30" id="{{ $acc_id }}">
                        {{ RemoveDecimal(number_format(-$b??0,2)) }}
                        <input type="hidden" class="branch" value="{{ $b_id }}" />
                    </a>
                </td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                @endif

                {{-- <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
            </tr>
            @endforeach

            <tr>
                <td style="padding-left: 30px"><b>Total Equity</b></td>
                <?php $total = 0; ?>
                @foreach($branches as $b_id)
                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format(-$total_equity[$b_id],2)) }}</u></td>
                <?php
                $total += $total_equity[$b_id];
                ?>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                @endif
            </tr>



            @endif
            @endif

            @php
            //$total_income += ($bal??0);
            $total_income = [];
            @endphp

            @if(isset($bals[40]))
            @if(count($bals[40])>0)
            <tr>
                @if(companyReportPart() == 'company.moeyan')
                <td colspan="{{count($branches)+2}}" style="padding-left: 30px"><b>Income</b></td>
                @else
                <td colspan="{{count($branches)+1}}" style="padding-left: 30px"><b>Income</b></td>
                @endif
            </tr>

            @forelse($bals[40] as $acc_id => $bal)

            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg40" id="{{ $acc_id }}" style="text-decoration: underline;cursor: pointer">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>
                @foreach($branches as $b_id)
                <?php
                $total = 0;
                if (!isset($total_income[$b_id])) $total_income[$b_id] = 0;

                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_income[$b_id] += ($b ?? 0);
                $total = array_sum($bal);

                ?>
                <td style="text-align: right">
                    <a style="text-decoration:underline;cursor: pointer" class="general-leg40" id="{{ $acc_id }}">
                        <input type="hidden" class="branch" value="{{ $b_id }}" />
                        {{ RemoveDecimal(number_format(-$b??0,2)) }}
                    </a>
                </td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                @endif

                {{-- <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
            </tr>
            @endforeach

            <tr>
                <td style="padding-left: 30px"><b>Total Income</b></td>
                <?php $total = 0; ?>
                @foreach($branches as $b_id)
                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                <input type="hidden" class="branch" value="{{ $b_id }}" />
                <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format(-$total_income[$b_id],2)) }}</u></td>
                <?php
                $total += $total_income[$b_id];
                ?>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                @endif
            </tr>



            @endif
            @endif



            {{--Cost of Goods Sold--}}


            @php
            $total_cogs = [];
            $gross_profit = [];
            $total = 0;
            @endphp
            @if(isset($bals[50]))
            @if(count($bals[50])>0)
            <tr>
                <td colspan="{{count($branches)+1}}" style="padding-left: 30px"><b>Cost of Goods Sold</b></td>
            </tr>
            @forelse($bals[50] as $acc_id => $bal)

            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg50" id="{{ $acc_id }}" style="text-decoration:underline;cursor: pointer">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>

                @foreach($branches as $b_id)
                <?php
                if (!isset($total_cogs[$b_id])) $total_cogs[$b_id] = 0;
                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_cogs[$b_id] += ($b ?? 0);
                $total = array_sum($bal);
                ?>
                <td style="text-align: right">
                    <a style="text-decoration:underline;cursor: pointer" class="general-leg50" id="{{ $acc_id }}">
                        <input type="hidden" class="branch" value="{{ $b_id }}" />
                        {{ RemoveDecimal(number_format($b??0,2)) }}
                    </a>
                </td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format($total,2))}}</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <?php $total = 0; ?>
                <td style="padding-left: 30px"><b>Total Cost of Goods Sold</b></td>
                @foreach($branches as $b_id)
                <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_cogs[$b_id],2)) }}</u></td>
                <?php $total += $total_cogs[$b_id]; ?>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format($total,2))}}</td>
                @endif
            </tr>
            @endif
            @endif


            <tr>

                <?php
                $n = 0;
                $total = 0;
                ?>
                @foreach($branches as $b_id)

                <?php
                if (!isset($gross_profit[$b_id])) $gross_profit[$b_id] = 0;
                $gross_profit[$b_id] = ($total_income[$b_id] ?? 0) + ($total_cogs[$b_id] ?? 0);
                $n++;
                ?>
                @if(companyReportPart() == 'company.moeyan')
                @if ($n > 1)
                @if($gross_profit[$b_id] <= 0) <td style="color: green;text-align: right;"><u>{{ RemoveDecimal(number_format(-$gross_profit[$b_id],2)) }}</u></td>
                    @else
                    <td style="color: red;text-align: right;"><u>{{ RemoveDecimal(number_format(-$gross_profit[$b_id],2)) }}</u></td>
                    @endif
                    @else
                    <td style="color: green;padding-left: 20px"><b>{{ $gross_profit[$b_id] < 0?'Gross Profit':'Net Loss' }}</b> </td>
                    @if($gross_profit[$b_id] < 0) <td style="color: green;text-align: right;padding-right: 20px"><u>{{ RemoveDecimal(number_format(-$gross_profit[$b_id],2))}}</u></td>
                        @else
                        <td style="color: red;text-align: right;padding-right: 20px"><u>{{ RemoveDecimal(number_format(-$gross_profit[$b_id],2)) }}</u></td>
                        @endif
                        @endif
                        <?php
                        $total += $gross_profit[$b_id];
                        ?>
                        @else
                        @if ($n > 1)
                        <td style="color: green;padding-left: 20px"><b>{{ $gross_profit[$b_id] < 0?'Gross Profit':'Net Loss' }}</b> <u>{{ $gross_profit[$b_id] < 0?number_format(-$gross_profit[$b_id],2):number_format($gross_profit[$b_id],2) }}</u></td>
                        @else
                        <td style="color: green;padding-left: 20px"><b>{{ $gross_profit[$b_id] < 0?'Gross Profit':'Net Loss' }}</b> </td>
                        <td style="color: green;text-align: right;padding-right: 20px"><u>{{ $gross_profit[$b_id] < 0?number_format(-$gross_profit[$b_id],2):number_format($gross_profit[$b_id],2) }}</u></td>
                        @endif
                        @endif
                        @endforeach
                        @if(companyReportPart() == 'company.moeyan')
                        @if($n >1)
                        @if($total < 0) <td>{{number_format(RemoveDecimal(-$total,2))}}</td>
                            @else
                            <td style="color: red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                            @endif
                            @else
                            @if($total < 0) <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @else
                                <td style="color: red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @endif
                                @endif
                                @endif
            </tr>



            {{--Close Cost of good sold--}}



            {{--Expense--}}

            @php
            $total_expense = [];
            $net_ordinary = [];
            $total = 0;
            @endphp
            @if(isset($bals[60]))
            @if(count($bals[60])>0)
            <tr>
                <td colspan="{{count($branches)+1}}" style="padding-left: 30px"><b>Expense</b></td>
            </tr>
            @forelse($bals[60] as $acc_id => $bal)

            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg60" id="{{ $acc_id }}" style="text-decoration:underline;cursor: pointer;">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>
                @foreach($branches as $b_id)
                <?php
                if (!isset($total_expense[$b_id])) $total_expense[$b_id] = 0;
                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_expense[$b_id] += $b;
                $total = array_sum($bal);
                ?>
                <td style="text-align: right">
                    <a style="text-decoration:underline;cursor: pointer" class="general-leg60" id="{{ $acc_id }}">
                        <input type="hidden" class="branch" value="{{ $b_id }}" />
                        {{ RemoveDecimal(number_format($b??0,2)) }}
                    </a>
                </td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                @if($total < 0) <td style="color:red;">{{RemoveDecimal(number_format($total,2))}}</td>
                    @else
                    <td>{{RemoveDecimal(number_format($total,2))}}</td>
                    @endif
                    @endif
            </tr>
            @endforeach
            <tr>
                <td style="padding-left: 30px"><b>Total Expense</b></td>
                @php $total=0; @endphp
                @foreach($branches as $b_id)
                <td style="text-align: right;padding-right: 10px"><u>{{ RemoveDecimal(number_format($total_expense[$b_id],2)) }}</u></td>
                @php
                $total += $total_expense[$b_id];
                @endphp
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                @if($total < 0) <td style="color:red;">{{RemoveDecimal(number_format($total,2))}}</td>
                    @else
                    <td>{{RemoveDecimal(number_format($total,2))}}</td>
                    @endif
                    @endif
            </tr>
            @endif
            @endif


            <?php
            $n = 0;
            $total = 0;
            ?>


            <tr>
                @foreach($branches as $b_id)

                @php
                if(!isset($net_ordinary[$b_id])) $net_ordinary[$b_id] = 0;
                $net_ordinary[$b_id] = ($gross_profit[$b_id]??0) + ($total_expense[$b_id]??0);
                $n++;
                @endphp
                @if(companyReportPart() == 'company.moeyan')
                @if ($n > 1)
                @if($net_ordinary[$b_id] < 0) <td style="color: blue;text-align: right;"><u>{{RemoveDecimal(number_format(-$net_ordinary[$b_id],2))}}</u></td>
                    @else
                    <td style="color: red;text-align: right;"><u>{{RemoveDecimal(number_format(-$net_ordinary[$b_id],2)) }}</u></td>
                    @endif
                    @else
                    <td style="color: blue;"><b>{{ $gross_profit[$b_id] < 0?'Net Ordinary':'Net Loss Ordinary' }}</b></td>
                    @if($net_ordinary[$b_id] < 0) <td style="color: blue;text-align: right;padding-right: 30px"><u>{{ RemoveDecimal(number_format(-$net_ordinary[$b_id],2)) }}</u></td>
                        @else
                        <td style="color: red;text-align: right;padding-right: 30px"><u>{{ RemoveDecimal(number_format(-$net_ordinary[$b_id],2)) }}</u></td>
                        @endif
                        @endif
                        <?php
                        $total += $net_ordinary[$b_id];
                        ?>
                        @else
                        @if ($n > 1)
                        <td style="color: blue;"><b>{{ $gross_profit[$b_id] < 0?'Net Ordinary':'Net Loss Ordinary' }}</b> <u>{{ $net_ordinary[$b_id] < 0?number_format(-$net_ordinary[$b_id],2):number_format($net_ordinary[$b_id],2) }}</u></td>
                        @else
                        <td style="color: blue;"><b>{{ $gross_profit[$b_id] < 0?'Net Ordinary':'Net Loss Ordinary' }}</b></td>
                        <td style="color: blue;text-align: right;padding-right: 30px"><u>{{ $net_ordinary[$b_id] < 0?number_format(-$net_ordinary[$b_id],2):number_format($net_ordinary[$b_id],2) }}</u></td>
                        @endif
                        @endif
                        @endforeach
                        @if(companyReportPart() == 'company.moeyan')
                        @if($n >1)
                        @if($total < 0) <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                            @else
                            <td style="color: red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                            @endif
                            @else
                            @if($net_ordinary[$b_id] < 0) <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @else
                                <td style="color: red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @endif
                                @endif
                                @endif
            </tr>

            {{--Close Expense--}}



            {{--Total Other Income--}}


            @php
            $total_other_income = [];
            $total = 0;
            @endphp


            @if(isset($bals[70]))
            @if(count($bals[70])>0)
            <tr>
                <td colspan="{{count($branches)+1}}" style="color: #006f6d;"><b>Other Income</b></td>
            </tr>
            @forelse($bals[70] as $acc_id => $bal)
            @php
            //$total_other_income += ($bal??0);
            @endphp
            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg70" id="{{ $acc_id }}" style="text-decoration:underline;cursor: pointer;">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>
                @foreach($branches as $b_id)
                <?php
                if (!isset($total_other_income[$b_id])) $total_other_income[$b_id] = 0;
                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_other_income[$b_id] += $b;
                $total = array_sum($bal);
                ?>
                @if(companyReportPart() == 'company.moeyan')
                @if($b > 0)
                <td style="color:red;text-align: right">{{ RemoveDecimal(number_format(-$b,2)) }}</td>
                @else
                <td style="color:green;text-align: right">{{ RemoveDecimal(number_format(-$b,2)) }}</td>
                @endif
                @else
                <td style="text-align: right">{{ number_format($b??0,2) }}</td>
                @endif
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                @if($total < 0) <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                    @else
                    <td style="color:red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                    @endif
                    @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="{{count($branches)+1}}" style="color: #006f6d;padding-right: 10px"><u>Total Other Income</u></td>
            </tr>
            @endif
            @endif


            {{--Close Total Other Income--}}



            {{--Total Other Expense--}}
            @php
            $total_other_expense = [];
            $net_income = [];
            $total = 0;
            @endphp
            @if(isset($bals[80]))
            @if(count($bals[80])>0)
            <tr>
                <td colspan="{{count($branches)+1}}" style="color: red;"><b>Other Expense</b></td>
            </tr>
            @forelse($bals[80] as $acc_id => $bal)

            <tr>
                <td style="padding-left: 60px;">
                    <a class="general-leg80" id="{{ $acc_id }}" style="text-decoration:underline;cursor: pointer">
                        {{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}
                        <input type="hidden" class="branch" value="{{ json_encode(array_values($branches)) }}" />
                    </a>
                </td>
                @foreach($branches as $b_id)
                <?php
                if (!isset($total_other_expense[$b_id])) $total_other_expense[$b_id] = 0;
                $b = isset($bal[$b_id]) ? ($bal[$b_id] ?? 0) : 0;
                $total_other_expense[$b_id] += $b;
                $total = array_sum($bal);
                ?>
                <td style="text-align: right">
                    <a style="text-decoration:underline;cursor: pointer" class="general-leg80" id="{{ $acc_id }}">
                    <input type="hidden" class="branch" value="{{ $b_id }}" />
                        {{ RemoveDecimal(number_format($b??0,2)) }}
                    </a>
                </td>
                @endforeach
                @if(companyReportPart() == 'company.moeyan')
                <td>{{RemoveDecimal(number_format($total,2))}}</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="{{count($branches)+1}}" style="color: red;padding-right: 10px"><u>Total Other Expense</u></td>
            </tr>
            @endif
            @endif



            <tr>
                <?php
                $n = 0;
                $total = 0;
                ?>
                @foreach($branches as $b_id)

                @php
                if(!isset($net_income[$b_id])) $net_income[$b_id] = 0;
                $net_income[$b_id] = ( $net_ordinary[$b_id]??0) + ($total_other_income[$b_id]??0) + ($total_other_expense[$b_id]??0);
                $n++;
                $total += $net_income[$b_id];
                @endphp
                @if(companyReportPart() == 'company.moeyan')
                @if($net_income[$b_id] != $net_ordinary[$b_id])

                @if ($n > 1)
                @if($net_income[$b_id] < 0) <td style="color: blue;text-align: right;"><u>{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</u></td>
                    @else
                    <td style="color: red;text-align: right;"><u>{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</u></td>
                    @endif
                    @else
                    <td style="color: blue;"><b>{{ $net_income[$b_id] < 0?'Net Income':'Net Loss' }}</b></td>
                    @if($net_income[$b_id] < 0) <td style="color: blue;text-align: right;padding-right: 50px"><u>{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</u></td>
                        @else
                        <td style="color: red;text-align: right;padding-right: 50px"><u>{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</u></td>
                        @endif
                        @endif
                        @elseif($net_income[$b_id] == $net_ordinary[$b_id] && isset($total_other_income[$b_id]))
                        @if($total != 0)
                        @if($net_income[$b_id] < 0) @if($n==1) <td>Net Loss</td>
                            @endif
                            <td style="color: blue;text-align: right;">{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</td>
                            @else
                            <td style="color: red;text-align: right;">{{ RemoveDecimal(number_format(-$net_income[$b_id],2)) }}</td>
                            @endif
                            @endif
                            @endif
                            @else
                            @if($net_income[$b_id] != $net_ordinary[$b_id])

                            @if ($n > 1)
                            <td style="color: blue;"><b>{{ $net_income[$b_id] < 0?'Net Income':'Net Loss' }}</b> <u>{{ $net_income[$b_id] < 0?number_format(-$net_income[$b_id],2):number_format($net_income[$b_id],2) }}</u></td>
                            @else
                            <td style="color: blue;"><b>{{ $net_income[$b_id] < 0?'Net Income':'Net Loss' }}</b></td>
                            <td style="color: blue;text-align: right;padding-right: 50px"><u>{{ $net_income[$b_id] < 0?number_format(-$net_income[$b_id],2):number_format($net_income[$b_id],2) }}</u></td>
                            @endif
                            @endif
                            @endif
                            @endforeach
                            @if(companyReportPart() == 'company.moeyan')
                            @if($total != 0 && isset($total_other_income[$b_id]))
                            @if($n >= 1)
                            @if($total < 0) <td>{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @else
                                <td style="color: red;">{{RemoveDecimal(number_format(-$total,2))}}</td>
                                @endif
                                @endif
                                @endif
                                @endif
            </tr>
        </tbody>
    </table>
    <script>
        $(".general-leg30, .general-leg40, .general-leg50, .general-leg60, .general-leg70, .general-leg80").click(function() {
            var branch = JSON.parse($(this).find('.branch').val());
            console.log(branch);
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
                        $('#show-detail-modal').modal("show");
                        alert('error');
                    }
                });
            });
        }

        function printDiv() {

            var DivIdToPrintPop = document.getElementById('DivIdToPrintPop'); //DivIdToPrintPop
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
    @else
    <h1>No data</h1>
    @endif
</div>