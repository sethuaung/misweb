<div id="DivIdToPrint">
    @if($customer != null)
        <?php
        $customer = $customer->groupBy('price_group_id');
        ?>
        @include('partials.reports.header',
        ['report_name'=>'Customer by Group Price','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

        <table class="table-data" id="table-data">
            <thead>
            <tr>
                <th>No</th>
                <th>Company</th>
                <th>E-mail</th>
                <th>Phone</th>
                <th>Current</th>
                <th>1 - 30</th>
                <th>31 - 60</th>
                <th>61 - 90</th>
                <th>91+</th>
                <th>Total</th>
                <th>Currency</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customer as $sup => $rows)
                <?php
                $supp = optional(\App\Models\PriceGroup::find($sup));
                ?>
                <tr>
                    <td colspan="8"><b>{{ $supp->name }}</b></td>
                </tr>
                @php
                    $t_b0 = 0;
                    $t_b30 = 0;
                    $t_b60 = 0;
                    $t_b90 = 0;
                    $t_b100 = 0;
                @endphp
                @foreach($rows as  $row)
                    @php
                        $b0 = isset($bals[$row->id][0])?$bals[$row->id][0]:0;
                        $b30 = isset($bals[$row->id][30])?$bals[$row->id][30]:0;
                        $b60 = isset($bals[$row->id][60])?$bals[$row->id][60]:0;
                        $b90 = isset($bals[$row->id][90])?$bals[$row->id][90]:0;
                        $b100 = isset($bals[$row->id][100])?$bals[$row->id][100]:0;

                        $t_b0 += $b0;
                        $t_b30 += $b30;
                        $t_b60 += $b60;
                        $t_b90 += $b90;
                        $t_b100 += $b100;
                    @endphp

                    <tr>
                        <td style="width: 50px;">{{ $loop->index + 1 }}</td>
                        <td style="min-width: 130px;">{{ $row->company??$row->name }}</td>
                        <td style="width: 100px;">{{ $row->email }}</td>
                        <td style="width: 100px;">{{ $row->phone }} {{ $row->mobile }}</td>

                        <td style="text-align: right;width: 80px;">{{ numb_format($b0,2) }}</td>
                        <td style="text-align: right;width: 80px;">{{ numb_format($b30,2) }}</td>
                        <td style="text-align: right;width: 80px;">{{ numb_format($b60,2) }}</td>
                        <td style="text-align: right;width: 80px;">{{ numb_format($b90,2) }}</td>
                        <td style="text-align: right;width: 80px;">{{ numb_format($b100,2) }}</td>
                        <td style="text-align: right;width: 80px;">{{ numb_format($b0 + $b30 + $b60 + $b90 + $b100,2) }}</td>

                        <td style="text-align: right;width: 50px;">
                            {{ optional(\App\Models\Currency::find($row->currency_id))->currency_name }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;"><b>Total {{ $supp->name }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b0,2) }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b30,2) }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b60,2) }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b90,2) }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b100,2) }}</b></td>
                    <td style="text-align: right;width: 80px;"><b>{{ numb_format($t_b0 + $t_b30 + $t_b60 + $t_b90 + $t_b100,2) }}</b></td>
                    <td style="text-align: right;width: 50px;">
                        <b>{{ optional(\App\Models\Currency::find($row->currency_id))->currency_name }}</b>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        <h1>No data</h1>
    @endif
</div>
