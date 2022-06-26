<div id="DivIdToPrint">
@if($rows != null)
<?php
$sections = $rows->groupBy('section_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Account List','from_date'=>null,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $sec => $rows)
                <?php
                        //dd($rows);
                $acc_sec = optional(\App\Models\AccountSection::find($sec));
                ?>
                <tr>
                    <td colspan="3">{{$acc_sec->code."-".$acc_sec->title }}</td>
                </tr>
                @foreach($rows as $row)
                    <?php
                    $sign = $acc_sec->type == 'dr'?1:-1;

                    $bal = isset($bals[$row->id])?$bals[$row->id]:0;
                    ?>
                    <tr>
                        <td style="padding-left: 40px;">{{$row->external_acc_code.'-'.$row->external_acc_name}}</td>
                        <td style="text-align: right;">{{ $bal == 0?'':number_format($bal*$sign,2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
