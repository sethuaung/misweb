<div id="DivIdToPrint">
@if($bals != null)
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <tbody>
            <tr>
                <td colspan="2" style="color: blue;"><b>Assets</b></td>
            </tr>
            @php
            $total_current_asset = 0;
            @endphp
            @if(isset($bals[10]) || isset($bals[12]) || isset($bals[14]))
                <tr>
                    <td colspan="2" style="padding-left: 30px"><b>Current Assets</b></td>
                </tr>
                @foreach([10,12,14] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    $total_current_asset += ($bal??0);
                                    $chart_ex = \App\Models\AccountChartExternal::find($acc_id);

                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                                    <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Current Assets</b></td>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_current_asset,2) }}</u></td>
                </tr>
            @endif

            @php
                $total_fixed_asset = 0;
            @endphp
            @if(isset($bals[16]))
                <tr>
                    <td colspan="2" style="padding-left: 30px"><b>Fixed Assets</b></td>
                </tr>
                @foreach([16] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    $total_fixed_asset += ($bal??0);
                                    $chart_ex = \App\Models\AccountChartExternal::find($acc_id);
                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                                    <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Fixed Assets</b></td>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_fixed_asset,2) }}</u></td>
                </tr>
            @endif


            @php
                $total_other_asset = 0;
            @endphp
            @if(isset($bals[18]))
                <tr>
                    <td colspan="2" style="padding-left: 30px"><b>Other Assets</b></td>
                </tr>
                @foreach([18] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                      $total_other_asset += ($bal??0);
                                      $chart_ex = \App\Models\AccountChartExternal::find($acc_id);
                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>>
                                    <td style="text-align: right">{{ number_format($bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Other Assets</b></td>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_other_asset,2) }}</u></td>
                </tr>
            @endif


            @php
                $total_all_assets = $total_current_asset + $total_fixed_asset + $total_other_asset;
            @endphp
            <tr>
                <td style="color: blue;"><b>Total Assets</b></td>
                <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ numb_format($total_all_assets,2) }}</u></td>
            </tr>


           {{--////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////--}}
           <tr>
                <td colspan="2" style="color: green;"><b>Liabilities & Owners Equity</b></td>
            </tr>
           <tr>
                <td colspan="2" style="color: blue;padding-left: 10px"><b>Liabilities</b></td>
            </tr>
            @php
            $total_current_liabilities = 0;
            @endphp
            @if(isset($bals[20]) || isset($bals[22]) || isset($bals[24]))
                <tr>
                    <td colspan="2" style="padding-left: 30px"><b>Current Liabilities</b></td>
                </tr>
                @foreach([20,22,24] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    $total_current_liabilities += ($bal??0);
                                    $chart_ex = \App\Models\AccountChartExternal::find($acc_id);
                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                                    <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Current Liabilities</b></td>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_current_liabilities,2) }}</u></td>
                </tr>
            @endif


            @php
                $total_long_term_liabilities = 0;
            @endphp
            @if(isset($bals[26]))
                <tr>
                    <td colspan="2" style="padding-left: 30px"><b>Long Term Liabilities</b></td>
                </tr>
                @foreach([26] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    $total_long_term_liabilities += ($bal??0);
                                     $chart_ex = \App\Models\AccountChartExternal::find($acc_id);
                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                                    <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Long Term Liabilities</b></td>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_long_term_liabilities,2) }}</u></td>
                </tr>
            @endif


            @php
                $total_all_liabilities = $total_current_liabilities + $total_long_term_liabilities;
            @endphp
            <tr>
                <td style="color: blue;padding-left: 10px"><b>Total Liabilities</b></td>
                <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ number_format(-$total_all_liabilities,2) }}</u></td>
            </tr>


           {{--////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////--}}
           <tr>
                <td colspan="2" style="color: blue;padding-left: 10px"><b>Owners Equity</b></td>
            </tr>

            @php
                $total_owners_equity = 0;
            @endphp
            @if(isset($bals[30]))
                @foreach([30] as $sec)
                    @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    $total_owners_equity += ($bal??0);
                                    $chart_ex = \App\Models\AccountChartExternal::find($acc_id);
                                @endphp
                                <tr>
                                    <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                                    <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            @endif



            @php


                $total_all_owners_equity = $total_owners_equity + $retainedEarningBegin + $profit;
            @endphp
            <tr>
                <td style="padding-left: 30px;">Retained Earning</td>
                <td style="text-align: right">{{ number_format(-$retainedEarningBegin??0,2) }}</td>
            </tr>

            <tr>
                <td style="padding-left: 30px;">{{ $profit<0?'Net Profit':'Net Loss' }}</td>
                <td style="text-align: right">{{ number_format(-$profit??0,2) }}</td>
            </tr>


            <tr>
                <td style="color: blue;padding-left: 10px"><b>Total Owners Equity</b></td>
                <td style="color: blue;padding-right: 20px;text-align: right;"><u>{{ number_format(-$total_all_owners_equity,2) }}</u></td>
            </tr>


            <tr>
                <td style="color: green;"><b>Total Liabilities & Owners Equity</b></td>
                <td style="color: green;padding-right: 20px;text-align: right;"><u>{{ number_format(-($total_all_owners_equity+$total_all_liabilities),2) }}</u></td>
            </tr>


           {{--////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////--}}

        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
