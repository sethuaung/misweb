<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
@if($bals != null)

    <?php
        $base_currency = optional(\App\Models\Currency::orderBy('exchange_rate','asc')->first())->id;

    ?>
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <th>Type</th>
            <th>Date</th>
            <th>Num</th>
            <th>Amount</th>
            <th>Debit</th>
            <th>Credit</th>
        </thead>
        <tbody>


        <tr>
            <td colspan="12"><b>Ordinary Income / Expense</b></td>
        </tr>
        <?php
            $total_income = 0;
            $total_cogs = 0;
            $total_expense = 0;
            $total_net_income = 0;
            $total_net_ordinary = 0;
            $total_other_income= 0;
            $total_other_expense = 0;
            $total_all = 0;
            $gross_profit = 0;
        ?>
        @if(isset($bals[40]))
            @if(count($bals[40])>0)
                <tr>
                    <td colspan="11" style="padding-left: 30px"><b>Income</b></td>
                </tr>

                @foreach($bals[40] as $acc_id => $rd)

                    <tr>
                        <td colspan="11" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                    </tr>

                    <?php
                    $total_line = 0;
                    ?>
                    @foreach($rd as $row)
                        <?php
                            $currency_tran = $row->currency_id;
                            $price = exchangeRate($currency_tran,$base_currency,$row->sale_price);
                            $type = $row->tran_type;
                            $name = '';


                        $total_income += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                        ?>
                        <tr>
                            <td style="padding-left: 70px">{{$row->tran_type}}</td>
                            <td>{{$row->tran_date}}</td>
                            <td>{{$row->num}}</td>

                            <td class="right">{{$price?numb_format($price,2):''}}</td>
                            <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                            <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="font-weight: bold;padding-left: 60px">Total</td>
                        <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                        <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="font-weight: bold;padding-left: 30px">Total Income</td>
                    <th class="right">{{$total_income>0?numb_format($total_income,2):''}}</th>
                    <th class="right">{{$total_income<0?numb_format(-$total_income,2):''}}</th>
                </tr>
            @endif
        @endif


        @if(isset($bals[50]))
            @if(count($bals[50])>0)
                <tr>
                    <td colspan="11" style="padding-left: 30px"><b>COGS</b></td>
                </tr>

                @foreach($bals[50] as $acc_id => $rd)
                    <tr>
                        <td colspan="11" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                    </tr>

                    <?php
                    $total_line = 0;
                    ?>
                    @foreach($rd as $row)
                        <?php

                        $type = $row->tran_type;
                        $name = '';
                        if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                            $name = optional(\App\Models\Supply::find($row->name))->name;
                        }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                            $name = optional(\App\Models\Customer::find($row->name))->name;
                        }

                        $total_cogs += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $qt =  $row->qty != null? abs($row->qty)>0?abs($row->qty):1:1;
                        $cost = ((($row->dr_cal??0) - ($row->cr_cal??0)))/$qt;
                        ?>
                        <tr>
                            <td style="padding-left: 70px">{{$row->tran_type}}</td>
                            <td>{{$row->tran_date}}</td>
                            <td>{{$row->num}}</td>
                            <td>{{$name}}</td>
                            <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                            <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
                            <td>{{optional(\App\Models\ProductCategory::find($row->category_id))->title}}</td>
                            <td class="right">{{$row->qty!=0?numb_format($row->qty,2):''}}</td>
                            <td class="right">{{$cost?numb_format($cost,2):''}}</td>
                            <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                            <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="font-weight: bold;padding-left: 60px">Total</td>
                        <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                        <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="font-weight: bold;padding-left: 30px">Total COGS</td>
                    <th class="right">{{$total_cogs>0?numb_format($total_cogs,2):''}}</th>
                    <th class="right">{{$total_cogs<0?numb_format(-$total_cogs,2):''}}</th>
                </tr>
            @endif
        @endif

        @php
            $gross_profit = $total_income + $total_cogs;
        @endphp
        <tr>
            <td colspan="10" style="color: green;padding-left: 20px"><b>{{ $gross_profit < 0?'Gross Profit':'Net Loss' }}</b></td>
            <td style="color: green;text-align: right;padding-right: 20px"><u>{{ $gross_profit < 0?number_format(-$gross_profit,2):number_format($gross_profit,2) }}</u></td>
        </tr>

        @if(isset($bals[60]))
            @if(count($bals[60])>0)
                <tr>
                    <td colspan="11" style="padding-left: 30px"><b>Expense</b></td>
                </tr>

                @foreach($bals[60] as $acc_id => $rd)
                    <tr>
                        <td colspan="11" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                    </tr>

                    <?php
                    $total_line = 0;

                    ?>
                    @foreach($rd as $row)
                        <?php

                        $type = $row->tran_type;
                        $name = '';
                        if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                            $name = optional(\App\Models\Supply::find($row->name))->name;
                        }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                            $name = optional(\App\Models\Customer::find($row->name))->name;
                        }

                        $total_expense += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $cost = (($row->dr_cal??0) - ($row->cr_cal??0));
                        ?>
                        <tr>
                            <td style="padding-left: 70px">{{$row->tran_type}}</td>
                            <td>{{$row->tran_date}}</td>
                            <td>{{$row->num}}</td>
                            <td>{{$name}}</td>
                            <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                            <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
                            <td>{{optional(\App\Models\ProductCategory::find($row->category_id))->title}}</td>
                            <td class="right">{{$row->qty!=0?numb_format($row->qty,2):''}}</td>
                            <td class="right">{{$cost?numb_format($cost,2):''}}</td>
                            <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                            <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="font-weight: bold;padding-left: 60px">Total</td>
                        <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                        <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="font-weight: bold;padding-left: 30px">Total Income</td>
                    <th class="right">{{$total_expense>0?numb_format($total_expense,2):''}}</th>
                    <th class="right">{{$total_expense<0?numb_format(-$total_expense,2):''}}</th>
                </tr>
            @endif
        @endif
        @php
            $total_net_ordinary = $gross_profit + $total_expense;
        @endphp
        <tr>
            <td colspan="10" style="color: blue;"><b>{{ $gross_profit < 0?'Net Ordinary':'Net Loss Ordinary' }}</b></td>
            <td style="color: blue;text-align: right;padding-right: 20px"><u>{{ $total_net_ordinary < 0?number_format(-$total_net_ordinary,2):number_format($total_net_ordinary,2) }}</u></td>
        </tr>
        @if(isset($bals[70]))
            @if(count($bals[70])>0)
                <tr>
                    <td colspan="11" style="padding-left: 30px"><b>Other Income</b></td>
                </tr>

                @foreach($bals[70] as $acc_id => $rd)
                    <tr>
                        <td colspan="11" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                    </tr>

                    <?php
                    $total_line = 0;
                    $price = 0;
                    ?>
                    @foreach($rd as $row)
                        <?php
                         $currency_tran = $row->currency_id;
                        $price = exchangeRate($currency_tran,$base_currency,$row->sale_price);
                        $type = $row->tran_type;
                        $name = '';
                        if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                            $name = optional(\App\Models\Supply::find($row->name))->name;
                        }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                            $name = optional(\App\Models\Customer::find($row->name))->name;
                        }

                        $total_other_income += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                        ?>
                        <tr>
                            <td style="padding-left: 70px">{{$row->tran_type}}</td>
                            <td>{{$row->tran_date}}</td>
                            <td>{{$row->num}}</td>
                            <td>{{$name}}</td>
                            <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                            <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
                            <td>{{optional(\App\Models\ProductCategory::find($row->category_id))->title}}</td>
                            <td class="right">{{$row->qty!=0?numb_format($row->qty,2):''}}</td>
                            <td class="right">{{$price?numb_format($price,2):''}}</td>
                            <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                            <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="font-weight: bold;padding-left: 60px">Total</td>
                        <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                        <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="font-weight: bold;padding-left: 30px">Total Income</td>
                    <th class="right">{{$total_other_income>0?numb_format($total_other_income,2):''}}</th>
                    <th class="right">{{$total_other_income<0?numb_format(-$total_other_income,2):''}}</th>
                </tr>
            @endif
        @endif

        @if(isset($bals[80]))
            @if(count($bals[80])>0)
                <tr>
                    <td colspan="11" style="padding-left: 30px"><b>Income</b></td>
                </tr>

                @foreach($bals[80] as $acc_id => $rd)
                    <tr>
                        <td colspan="11" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                    </tr>

                    <?php
                    $total_line = 0;
                    ?>
                    @foreach($rd as $row)
                        <?php
                        $type = $row->tran_type;
                        $name = '';
                        if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                            $name = optional(\App\Models\Supply::find($row->name))->name;
                        }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                            $name = optional(\App\Models\Customer::find($row->name))->name;
                        }

                        $total_other_expense += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                        $cost = (($row->dr_cal??0) - ($row->cr_cal??0));
                        ?>
                        <tr>
                            <td style="padding-left: 70px">{{$row->tran_type}}</td>
                            <td>{{$row->tran_date}}</td>
                            <td>{{$row->num}}</td>
                            <td>{{$name}}</td>
                            <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                            <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
                            <td>{{optional(\App\Models\ProductCategory::find($row->category_id))->title}}</td>
                            <td class="right">{{$row->qty!=0?numb_format($row->qty,2):''}}</td>
                            <td class="right">{{$cost?numb_format($cost,2):''}}</td>
                            <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                            <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="font-weight: bold;padding-left: 60px">Total</td>
                        <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                        <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="font-weight: bold;padding-left: 30px">Total Income</td>
                    <th class="right">{{$total_other_expense>0?numb_format($total_other_expense,2):''}}</th>
                    <th class="right">{{$total_other_expense<0?numb_format(-$total_other_expense,2):''}}</th>
                </tr>
            @endif
        @endif
        @php
            $total_all = $total_net_ordinary + $total_other_income + $total_other_expense;
        @endphp
        @if($total_all != $total_net_ordinary)
            <tr>
                <td colspan="10" style="color: blue;"><b>{{ $net_income < 0?'Net Income':'Net Loss' }}</b></td>
                <td style="color: blue;text-align: right;padding-right: 20px"><u>{{ $net_income < 0?number_format(-$net_income,2):number_format($net_income,2) }}</u></td>
            </tr>
        @endif
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
