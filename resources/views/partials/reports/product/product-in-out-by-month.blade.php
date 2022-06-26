<div id="DivIdToPrint">
@if($warehouse != null)


    @include('partials.reports.header',
    ['report_name'=>'Product-Transaction','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">

        <thead>
            <tr>
                <th rowspan="2">Product</th>
                <th rowspan="2">BEGIN</th>
                @foreach($tran_type as $y => $type)
                <th colspan="2"><span style="color: #030522;">{{ $y }}</span></th>
                @endforeach
                <th rowspan="2">END</th>
            </tr>
            <tr>
                @foreach($tran_type as $type)
                <th><span style="color: #030522;">IN</span></th>
                <th><span style="color: #030522;">OUT</span></th>
                @endforeach
            </tr>
        </thead>
        @php $cp = count($tran_type)*2 + 3; @endphp
        <tbody>
            @foreach($warehouse as $wh_id => $product)
                <tr><td colspan="{{ $cp }}"><b>{{ $wh_id=='NA'?'NA' : optional(\App\Models\Warehouse::find($wh_id))->name }}</b></td></tr>
                @foreach($product as $pro_id)
                    @php
                        $l_total = 0;
                       $beg = isset($begin[$wh_id][$pro_id])?($begin[$wh_id][$pro_id]??0):0;
                        $l_total += $beg;
                    @endphp
                    <tr>
                        <td style="padding-left: 30px;"><b>{{ optional(\App\Models\Product::find($pro_id))->product_name }}</b></td>
                        <td style="text-align: right;">{{ convertUnit($pro_id,$beg) }}</td>
                        @foreach($tran_type as $y=>$type)
                            @php
                                $cur_in = isset($current[$wh_id][$pro_id][$y]['IN'])?($current[$wh_id][$pro_id][$y]['IN']??0):0;
                                $cur_out = isset($current[$wh_id][$pro_id][$y]['OUT'])?($current[$wh_id][$pro_id][$y]['OUT']??0):0;
                                $l_total+= ($cur_in + $cur_out);
                            @endphp
                            <td style="text-align: right;">{{ convertUnit($pro_id,$cur_in)  }}</td>
                            <td style="text-align: right;">{{ convertUnit($pro_id,$cur_out)  }}</td>
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
