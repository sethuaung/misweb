<div id="DivIdToPrint">
    @if($bals != null)
        @include('partials.reports.header',
        ['report_name'=>'Profit Loss','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

        <table class="table-data" id="table-data">
            <tbody>
            <tr>
                <td colspan="2" style="color: blue;"><b>Ordinary Income / Expense</b></td>
            </tr>
            @php
                $total_income = 0;
            @endphp
            @if(isset($bals[40]))
                @if(count($bals[40])>0)
                    <tr>
                        <td colspan="2" style="padding-left: 30px"><b>Income</b></td>
                    </tr>
                    @forelse($bals[40] as $acc_id => $bal)
                        @php
                            $total_income += ($bal??0);
                        @endphp
                        <tr>
                            <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td style="padding-left: 30px"><b>Total Income</b></td>
                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_income,2) }}</u></td>
                        </tr>
                        @endif
                        @endif

                        @php
                            $total_cogs = 0;
                        @endphp
                        @if(isset($bals[50]))
                            @if(count($bals[50])>0)
                                <tr>
                                    <td colspan="2" style="padding-left: 30px"><b>Cost of Goods Sold</b></td>
                                </tr>
                                @forelse($bals[50] as $acc_id => $bal)
                                    @php
                                        $total_cogs += ($bal??0);
                                    @endphp
                                    <tr>
                                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                                        <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="padding-left: 30px"><b>Total Cost of Goods Sold</b></td>
                                        <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_cogs,2) }}</u></td>
                                    </tr>
                                    @endif
                                    @endif
                                    @php
                                        $gross_profit = $total_income + $total_cogs;
                                    @endphp
                                    <tr>
                                        <td style="color: green;padding-left: 20px"><b>{{ $gross_profit < 0?'Gross Profit':'Net Loss' }}</b></td>
                                        <td style="color: green;text-align: right;padding-right: 20px"><u>{{ $gross_profit < 0?number_format(-$gross_profit,2):number_format($gross_profit,2) }}</u></td>
                                    </tr>


                                    @php
                                        $total_expense = 0;
                                    @endphp
                                    @if(isset($bals[60]))
                                        @if(count($bals[60])>0)
                                            <tr>
                                                <td colspan="2" style="padding-left: 30px"><b>Expense</b></td>
                                            </tr>
                                            @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $total_expense += ($bal??0);
                                                @endphp
                                                <tr>
                                                    <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                                                    <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td style="padding-left: 30px"><b>Total Expense</b></td>
                                                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_expense,2) }}</u></td>
                                                </tr>
                                                @endif
                                                @endif

                                                @php
                                                    $net_ordinary = $gross_profit + $total_expense;
                                                @endphp


                                                <tr>
                                                    <td style="color: blue;"><b>{{ $gross_profit < 0?'Net Ordinary':'Net Loss Ordinary' }}</b></td>
                                                    <td style="color: blue;text-align: right;padding-right: 30px"><u>{{ $net_ordinary < 0?number_format(-$net_ordinary,2):number_format($net_ordinary,2) }}</u></td>
                                                </tr>

                                                @php
                                                    $total_other_income = 0;
                                                @endphp
                                                @if(isset($bals[70]))
                                                    @if(count($bals[70])>0)
                                                        <tr>
                                                            <td colspan="2" style="color: #006f6d;"><b>Other Income</b></td>
                                                        </tr>
                                                        @forelse($bals[70] as $acc_id => $bal)
                                                            @php
                                                                $total_other_income += ($bal??0);
                                                            @endphp
                                                            <tr>
                                                                <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                                                                <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="2" style="color: #006f6d;padding-right: 10px"><u>Total Other Income</u></td>
                                                            </tr>
                                                            @endif
                                                            @endif
                                                            @php
                                                                $total_other_expense = 0;
                                                            @endphp
                                                            @if(isset($bals[80]))
                                                                @if(count($bals[80])>0)
                                                                    <tr>
                                                                        <td colspan="2" style="color: red;"><b>Other Expense</b></td>
                                                                    </tr>
                                                                    @forelse($bals[80] as $acc_id => $bal)
                                                                        @php
                                                                            $total_other_expense += ($bal??0);
                                                                        @endphp
                                                                        <tr>
                                                                            <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->code."-".optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                                                                            <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                        <tr>
                                                                            <td colspan="2" style="color: red;padding-right: 10px"><u>Total Other Expense</u></td>
                                                                        </tr>
                                                                        @endif
                                                                        @endif

                                                                        @php
                                                                            $net_income = $net_ordinary + $total_other_income + $total_other_expense;
                                                                        @endphp
                                                                        @if($net_income != $net_ordinary)
                                                                            <tr>
                                                                                <td style="color: blue;"><b>{{ $net_income < 0?'Net Income':'Net Loss' }}</b></td>
                                                                                <td style="color: blue;text-align: right;padding-right: 50px"><u>{{ $net_income < 0?number_format(-$net_income,2):number_format($net_income,2) }}</u></td>
                                                                            </tr>
                                                                        @endif
            </tbody>
        </table>

    @else
        <h1>No data</h1>
    @endif
</div>