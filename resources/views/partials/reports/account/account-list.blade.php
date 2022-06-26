<div id="DivIdToPrint">
@if($rows != null)
<?php
$sections = $rows->groupBy('section_id');
?>
    @php
        $today = date('Y-m-d');
    @endphp
    @include('partials.reports.header',
    ['report_name'=>'Account List','from_date'=>null,'to_date'=>$end_date,'use_date'=>1])
    <div>{{$today}}</div>
        @foreach($branches as $branch)
        <span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span>
        @endforeach
    <table border="1" class="table-data" id="table-data">

        <thead>
            <tr>
                <th colspan="2"></th>
                <?php $sp = count($branches) +1; ?>
                @foreach($branches as $branch)
                    <th>{{ $branch >0? optional(\App\Models\Branch::find($branch))->title : 'NA' }}</th>
                @endforeach
            </tr>
            <tr>
                @if(companyReportPart() == 'company.mkt')
                    <th colspan="2">Account Name</th>
                @else
                    <th colspan="1">Account Name</th>
                @endif
                <th colspan="{{count($branches)}}">Balance</th>
            </tr>
        </thead>
        <tbody>
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
                    ?>
                    <tr>
                        @if(companyReportPart() == 'company.mkt')
                            <td style="padding-left: 40px;">{{$row->code}}</td>
                            <td style="padding-left: 40px;">{{$row->name}} </td>
                        @else
                            <td style="padding-left: 40px;">{{$row->code."-".$row->name}} </td>
                        @endif
                        

                        {{--<td style="text-align: right;">{{ $bal == 0?'':number_format($bal*$sign,2) }}</td>--}}
                        @foreach($branches as $b_id)
                            <?php

                            $b = isset($bal[$b_id])?($bal[$b_id]??0):0;

                            ?>
                            {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                            <td style="text-align: right;">{{ $bal == 0?'':number_format($b*$sign,2) }}</td>

                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
