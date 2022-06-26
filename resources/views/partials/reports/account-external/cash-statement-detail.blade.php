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

        <table border="1" class="table-data" id="table-data">
            <thead>
            <th>Type</th>
            <th>Date</th>
            <th>Num</th>
            <th>Name</th>
            <th>Class</th>
            <th>Amount</th>
            <th>Debit</th>
            <th>Credit</th>
            </thead>
            <tbody>


            <tr>
                <td colspan="12"><b>Cash</b></td>
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
            @if(isset($bals[10]))
                @if(count($bals[10])>0)
                    @foreach($bals[10] as $acc_id => $rd)

                        <tr>
                            <td colspan="9" style="font-weight: bold;padding-left: 60px">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
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
                            if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                                $name = optional(\App\Models\Supply::find($row->name))->name;
                            }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                                $name = optional(\App\Models\Customer::find($row->name))->name;
                            }

                            $total_income += (($row->dr_cal??0) - ($row->cr_cal??0));
                            $total_line += (($row->dr_cal??0) - ($row->cr_cal??0));
                            ?>
                            <tr>
                                <td style="padding-left: 70px">{{$row->tran_type}}</td>
                                <td>{{$row->tran_date}}</td>
                                <td>{{$row->num}}</td>
                                <td>{{$name}}</td>
                                <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                                <td class="right">{{$price?numb_format($price,2):''}}</td>
                                <td class="right">{{$row->dr_cal!=0?numb_format($row->dr_cal,2):''}}</td>
                                <td class="right">{{$row->cr_cal!=0?numb_format($row->cr_cal,2):''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" style="font-weight: bold;padding-left: 60px">Total</td>
                            <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                            <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" style="font-weight: bold;padding-left: 30px">Total Cash</td>
                        <th class="right">{{$total_income>0?numb_format($total_income,2):''}}</th>
                        <th class="right">{{$total_income<0?numb_format(-$total_income,2):''}}</th>
                    </tr>
                @endif
            @endif
            </tbody>
        </table>

    @else
        <h1>No data</h1>
    @endif
</div>
