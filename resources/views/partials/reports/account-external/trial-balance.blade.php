<div id="DivIdToPrint">
@if($rows != null)
<?php
$sections = $rows->groupBy('section_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Trial Balance','from_date'=>null,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        
        <thead>
            <tr>
                <th>Account Name</th>
                <th style="width: 150px">Dr</th>
                <th style="width: 150px">Cr</th>
            </tr>
        </thead>
        <tbody>
            @php
                $bal_dr = 0;
                $bal_cr = 0;
            @endphp
            @foreach($sections as $sec => $rows)
                <?php
                $acc_sec = optional(\App\Models\AccountSection::find($sec));

                ?>
                <tr>
                    <td colspan="3">{{ $acc_sec->code."-".$acc_sec->title }}</td>
                </tr>
                @foreach($rows as $row)
                    <?php
                    $sign = $acc_sec->type == 'dr'?1:-1;
                    $bal = isset($bals[$row->id])?$bals[$row->id]:0;

                    $bal_dr += ($bal>0?$bal:0);
                    $bal_cr += ($bal<0?$bal:0);

                    ?>

                    @if(abs($bal) == 0 || $bal == null)
                        @continue
                    @endif
                    <tr>
                        <td style="padding-left: 40px;">{{$row->external_acc_code."-".$row->external_acc_name}}</td>
                        <td style="text-align: right;">{{ $bal > 0?number_format($bal,2):'' }}</td>
                        <td style="text-align: right;">{{ $bal < 0?number_format(-$bal,2):'' }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td><b>Total</b></td>
                <td style="text-align: right;"><b>{{ number_format($bal_dr,2)}}</b></td>
                <td style="text-align: right;"><b>{{ number_format(-$bal_cr,2) }}</b></td>
            </tr>
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
