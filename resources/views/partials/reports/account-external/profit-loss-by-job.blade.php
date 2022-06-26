<div id="DivIdToPrint">
@if($bals != null)
    @include('partials.reports.header',
    ['report_name'=>'Profit Loss','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <th></th>
            <?php $sp = count($jobs) +1; ?>
            @foreach($jobs as $job)
                <th>{{ $job >0? optional(\App\Models\Job::find($job))->name : 'NA' }}</th>
            @endforeach
            <th>Total</th>
        </thead>

        <tbody>
            <tr>
                <td colspan="{{ $sp +1 }}" style="color: blue;"><b>Ordinary Income / Expense</b></td>


            </tr>
            @php
            $total_income = [];
            @endphp


            @if(isset($bals[40]))
                @if(count($bals[40])>0)
                <tr>
                    <td colspan="{{ $sp +1 }}" style="padding-left: 30px"><b>Income</b></td>
                </tr>

                @forelse($bals[40] as $acc_id => $j)

                    <tr>
                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            if(!isset($total_income[$j_id])) $total_income[$j_id] = 0;
                            $b = isset($j[$j_id])?($j[$j_id]??0):0;
                            $total_income[$j_id] += ($b??0);
                            $l_total += $b;
                            ?>
                            <td style="text-align: right">{{ number_format(-$b??0,2) }}</td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format(-$l_total,2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total Income</b></td>
                    <?php $l_total = 0; ?>
                    @foreach($jobs as $j_id)
                        <?php
                        $l_total += $total_income[$j_id];
                        ?>
                        <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_income[$j_id],2) }}</u></td>
                    @endforeach
                    <td  style="text-align: right;padding-right: 10px">{{ numb_format(-$l_total,2) }}</td>
                </tr>
                @endif
            @endif

            @php
                $total_cogs = [];
            @endphp
            @if(isset($bals[50]))
                @if(count($bals[50])>0)
                <tr>
                    <td colspan="{{ $sp +1 }}" style="padding-left: 30px"><b>COGS</b></td>
                </tr>

                @forelse($bals[50] as $acc_id => $j)

                    <tr>
                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            if(!isset($total_cogs[$j_id])) $total_cogs[$j_id] = 0;
                            $b = isset($j[$j_id])?($j[$j_id]??0):0;
                            $total_cogs[$j_id] += ($b??0);
                            $l_total += $b;
                            ?>
                            <td style="text-align: right">{{ number_format($b??0,2) }}</td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding-left: 30px"><b>Total COGS</b></td>
                    <?php $l_total = 0; ?>
                    @foreach($jobs as $j_id)
                        <?php
                        $l_total += $total_cogs[$j_id];
                        ?>
                        <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_cogs[$j_id],2) }}</u></td>
                    @endforeach
                    <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                </tr>
                @endif
            @endif
            <?php
                $gross_profit = [];
                foreach($jobs as $j_id){
                    $gross_profit[$j_id] =  (isset($total_cogs[$j_id])?$total_cogs[$j_id]:0) + (isset($total_income[$j_id])?$total_income[$j_id]:0);
                }
            ?>
            <tr>
                <td style="padding-left: 30px"><b>Gross Profit</b></td>
                <?php $l_total = 0; ?>
                @foreach($jobs as $j_id)
                    <?php
                    $l_total += $gross_profit[$j_id];
                    ?>
                    <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$gross_profit[$j_id],2) }}</u></td>
                @endforeach
                <td  style="text-align: right;padding-right: 10px">{{ numb_format(-$l_total,2) }}</td>
            </tr>

            @php
                $total_expense = [];
            @endphp
            @if(isset($bals[60]))
                @if(count($bals[60])>0)
                    <tr>
                        <td colspan="{{ $sp +1 }}" style="padding-left: 30px"><b>Expense</b></td>
                    </tr>

                    @forelse($bals[60] as $acc_id => $j)

                    <tr>
                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            if(!isset($total_expense[$j_id])) $total_expense[$j_id] = 0;
                            $b = isset($j[$j_id])?($j[$j_id]??0):0;
                            $total_expense[$j_id] += ($b??0);
                            $l_total += $b;
                            ?>
                            <td style="text-align: right">{{ number_format($b??0,2) }}</td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 30px"><b>Total Expense</b></td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            $l_total += $total_expense[$j_id];
                            ?>
                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_expense[$j_id],2) }}</u></td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                @endif
            @endif
            <?php
                $net_ordinary = [];
                foreach($jobs as $j_id){
                    $net_ordinary[$j_id] = (isset( $gross_profit[$j_id] )? $gross_profit[$j_id] :0) + (isset($total_expense[$j_id])?$total_expense[$j_id]:0);
                }
            ?>
            <tr>
                <td style="padding-left: 10px; color: green;"><b>Net Ordinary</b></td>
                <?php $l_total = 0; ?>
                @foreach($jobs as $j_id)
                    <?php
                    $l_total += $net_ordinary[$j_id];
                    ?>
                    <td style="text-align: right;padding-right: 10px; color: green;"><u>{{ number_format(-$net_ordinary[$j_id],2) }}</u></td>
                @endforeach
                <td  style="text-align: right;padding-right: 10px; color: green;">{{ numb_format(-$l_total,2) }}</td>
            </tr>
            @php
                $total_other_income = [];
            @endphp
            @if(isset($bals[70]))
                @if(count($bals[70])>0)
                    <tr>
                        <td colspan="{{ $sp +1 }}" style="padding-left: 30px"><b>Other Income</b></td>
                    </tr>

                    @forelse($bals[70] as $acc_id => $j)

                    <tr>
                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            if(!isset($total_other_income[$j_id])) $total_other_income[$j_id] = 0;
                            $b = isset($j[$j_id])?($j[$j_id]??0):0;
                            $total_other_income[$j_id] += ($b??0);
                            $l_total += $b;
                            ?>
                            <td style="text-align: right">{{ number_format($b??0,2) }}</td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 30px"><b>Total Other Income</b></td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            $l_total += $total_other_income[$j_id];
                            ?>
                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_other_income[$j_id],2) }}</u></td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format(-$l_total,2) }}</td>
                    </tr>
                @endif
            @endif
            @php
                $total_other_expense = [];
            @endphp
            @if(isset($bals[80]))
                @if(count($bals[80])>0)
                    <tr>
                        <td colspan="{{ $sp +1 }}" style="padding-left: 30px"><b>Other Expense</b></td>
                    </tr>

                    @forelse($bals[80] as $acc_id => $j)

                    <tr>
                        <td style="padding-left: 60px;">{{ optional(\App\Models\AccountChart::find($acc_id))->name }}</td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            if(!isset($total_other_expense[$j_id])) $total_other_expense[$j_id] = 0;
                            $b = isset($j[$j_id])?($j[$j_id]??0):0;
                            $total_other_expense[$j_id] += ($b??0);
                            $l_total += $b;
                            ?>
                            <td style="text-align: right">{{ number_format($b??0,2) }}</td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 30px"><b>Total Other Expense</b></td>
                        <?php $l_total = 0; ?>
                        @foreach($jobs as $j_id)
                            <?php
                            $l_total += $total_other_expense[$j_id];
                            ?>
                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_other_expense[$j_id],2) }}</u></td>
                        @endforeach
                        <td  style="text-align: right;padding-right: 10px">{{ numb_format($l_total,2) }}</td>
                    </tr>
                @endif
            @endif
            <?php
                $net_income = [];
                foreach($jobs as $j_id){
                    $net_income[$j_id] = (isset( $net_ordinary[$j_id] )? $net_ordinary[$j_id] :0) + (isset($total_other_income[$j_id])?$total_other_income[$j_id]:0) + (isset($total_other_expense[$j_id])?$total_other_expense[$j_id]:0) ;
                }
            ?>
            <tr>
                <td style="color: blue;"><b>Net Income</b></td>
                <?php $l_total = 0; ?>
                @foreach($jobs as $j_id)
                    <?php
                    $l_total += $net_income[$j_id];
                    ?>
                    <td style="text-align: right;padding-right: 10px; color: blue;"><u>{{ number_format(-$net_income[$j_id],2) }}</u></td>
                @endforeach
                <td  style="text-align: right;padding-right: 10px; color: blue;">{{ numb_format(-$l_total,2) }}</td>
            </tr>

        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
