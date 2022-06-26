<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
@if($rows != null)
    @php
        $acc = $rows->groupBy('acc_chart_id');
    @endphp
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <th>Type</th>
            <th>Date</th>
            <th>Num</th>
            <th>Name</th>
            <th>Class</th>
            <th>Item</th>
            <th>Inventory</th>
            <th>Qty</th>
            <th>Amt</th>
            <th>Debit</th>
            <th>Credit</th>
        </thead>
        <tbody>

            @foreach($acc as $acc_id => $rd)
                <tr>
                    <td colspan="11" style="font-weight: bold;">{{optional(\App\Models\AccountChart::find($acc_id))->name}}</td>
                </tr>
            @foreach($rd as $row)
                <?php
                      //  dd($row);
                    $type = $row->tran_type;
                    $name = '';
                    if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                        $name = optional(\App\Models\Supply::find($row->name))->name;
                    }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                        $name = optional(\App\Models\Customer::find($row->name))->name;
                    }

                    $bb = ($row->dr_cal??0) - ($row->cr_cal??0);
                    $p = $row->qty != null ? abs($row->qty)>0?$bb/abs($row->qty):'':'';
                ?>
                <tr>
                    <td>{{$row->tran_type}}</td>
                    <td>{{$row->tran_date}}</td>
                    <td>{{$row->num}}</td>
                    <td>{{$name}}</td>
                    <td>{{optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                    <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
                    <td>{{optional(\App\Models\ProductCategory::find($row->category_id))->title}}</td>
                    <td class="right">{{$row->qty!=0?numb_format($row->qty,2):''}}</td>
                    <td class="right">{{$row->sale_price?numb_format(abs($p),2):''}}</td>
                    <td class="right">{{$row->dr!=0?numb_format($row->dr_cal,2):''}}</td>
                    <td class="right">{{$row->cr!=0?numb_format($row->cr_cal,2):''}}</td>
                </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
