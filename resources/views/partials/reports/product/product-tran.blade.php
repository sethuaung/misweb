<div id="DivIdToPrint">
@if($warehouse != null)

    @include('partials.reports.header',
    ['report_name'=>'Product-Transaction','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">

        <thead>
            <tr style="font-size: 12px;">
                <th>Product</th>
                <th>BEGIN</th>
                @foreach($tran_type as $type)
                <th><span style="color: #030522;">{{ strtoupper($type) }}</span></th>
                @endforeach
                <th>END</th>
            </tr>
        </thead>
        @php $cp = count($tran_type) + 3; @endphp
        <tbody>
            @foreach($warehouse as $wh_id => $product)
                <tr><td colspan="{{ $cp }}"><b>{{ $wh_id=='NA'?'NA' : optional(\App\Models\Warehouse::find($wh_id))->name }}</b></td></tr>
                @foreach($product as $pro_id)
                    @php
                        $l_total = 0;
                       $beg = isset($begin[$wh_id][$pro_id])?($begin[$wh_id][$pro_id]??0):0;
                        $l_total += $beg;
                    @endphp
                    <tr style="font-size: 12px;">
                        <td style="padding-left: 30px;"><b>{{ optional(\App\Models\Product::find($pro_id))->product_name }}</b></td>
                        <td style="text-align: right;">{{ convertUnit($pro_id,$beg) }}</td>
                        @foreach($tran_type as $type)
                            @php
                                $cur = isset($current[$wh_id][$pro_id][$type])?($current[$wh_id][$pro_id][$type]??0):0;
                                $l_total+= $cur;
                            @endphp
                            <td style="text-align: right;">{{ convertUnit($pro_id,$cur)  }}</td>
                        @endforeach
                        <td style="text-align: right;">{{ convertUnit($pro_id,$l_total) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
