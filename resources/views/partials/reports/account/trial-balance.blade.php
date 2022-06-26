<div id="DivIdToPrint" style="height:1000px">
        
<style>
    .tableFixHead          { display: block; overflow: auto; white-space: nowrap; max-height: 700px; border: 0;}
    .tableFixHead thead .first th { position: sticky; top: 0; }
    .tableFixHead thead .second th { position: sticky; top: 30px; }
    table  { border-collapse: collapse; min-width: 100%; }
    .tableFixHead td { padding: 13px 10px }
    .tableFixHead td { min-width: 100px;}
    .tableFixHead th     { background:#eee; }
</style>
@if($rows != null)
<?php
$sections = $rows->groupBy('section_id');

?>
    @php
        $today = date('Y-m-d');
    @endphp
    @include('partials.reports.header',
    ['report_name'=>'Trial Balance','from_date'=>null,'to_date'=>$end_date,'use_date'=>1])
    <div>{{$today}}</div>
        @foreach($branches as $branch)
        <div><span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span></div>
        @endforeach
    <table class="table-data tableFixHead" id="table-data">

        <thead>
            <tr class="first">
                @if(companyReportPart() == "company.mkt")
                    <th colspan="2"></th>
                @else
                    <th></th>
                @endif
                @foreach($branches as $b)
                <th colspan="2">{{optional(\App\Models\BranchU::find($b))->title}}</th>
                @endforeach
                <th colspan="2">Balance</th>
            </tr>
            <tr class="second">
                @if(companyReportPart() == "company.mkt")
                    <th colspan="2">Account Name</th>
                @else
                    <th>Account Name</th>
                @endif
                
                @foreach($branches as $b)
                <th style="width: 150px">Dr</th>
                <th style="width: 150px">Cr</th>
                @endforeach
                <th style="width: 150px">Dr</th>
                <th style="width: 150px">Cr</th>
            </tr>
        </thead>
        <tbody>
            @php
                $arr_total_dr = [];
                $arr_total_cr = [];
            @endphp
            @foreach($sections as $sec => $rows)
                <?php
                $acc_sec = optional(\App\Models\AccountSection::find($sec));
                $bal = 0;
                ?>
                <tr>
                    <td colspan="20">{{ $acc_sec->code.''.$acc_sec->title }}</td>
                </tr>


                @foreach($rows as $row)

                        <?php
                        $tr_l = '';
                        $tr_l_bal = 0;

                        $tr_line = '';
                        $tr_line_bal = 0;
                        foreach($branches as $b_id){
                            $sign = $acc_sec->type == 'dr'?1:-1;
                            $bal = isset($bals[$row->id][$b_id])?$bals[$row->id][$b_id]:0;

                            $tr_line_bal += $bal;



                            $tr_l .= '<td style="text-align: right;">'.($bal > 0?number_format($bal,2):'').'</td>';
                            $tr_l .= '<td style="text-align: right;">'.($bal < 0?number_format(-$bal,2):'').'</td>';

                            if(abs($bal)>0)  $tr_l_bal += abs($bal);

                            if(!isset($arr_total_dr[$b_id])) $arr_total_dr[$b_id] = 0;
                            if(!isset($arr_total_cr[$b_id])) $arr_total_cr[$b_id] = 0;

                            if($bal>0) $arr_total_dr[$b_id] += $bal;
                            if($bal<0) $arr_total_cr[$b_id] += $bal;

                        }
                        ?>
                        @if(abs($tr_l_bal) == 0 || $tr_l_bal == null)
                            @continue
                        @endif
                    <tr>
                        @if(companyReportPart() == "company.mkt")
                            <td style="padding-left: 40px;">{{$row->code}}</td>
                            <td style="padding-left: 40px;">{{$row->name}}</td>
                        @else
                            <td style="padding-left: 40px;">{{$row->code.'-'.$row->name}}</td>
                        @endif
                        
                        {!! $tr_l !!}
                        <td style="text-align: right;">{{($tr_line_bal > 0?number_format($tr_line_bal,2):'')}}</td>
                        <td style="text-align: right;">{{($tr_line_bal < 0?number_format(-$tr_line_bal,2):'')}}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="1"><b>Total</b></td>
                <?php
                    $total_bal_dr = 0;
                    $total_bal_cr = 0;
                ?>
                @foreach($branches as $b_id)
                @php
                    $bal_dr = isset($arr_total_dr[$b_id])?$arr_total_dr[$b_id]:0;
                    $bal_cr = isset($arr_total_cr[$b_id])?$arr_total_cr[$b_id]:0;
                    $total_bal_dr += $bal_dr;
                    $total_bal_cr += $bal_cr;
                @endphp
                <td style="text-align: right;"><b>{{ numb_format($bal_dr,2) }}</b></td>
                <td style="text-align: right;"><b>{{ numb_format(-$bal_cr,2) }}</b></td>
                @endforeach
                <td style="text-align: right;"><b>{{ numb_format($total_bal_dr,2) }}</b></td>
                <td style="text-align: right;"><b>{{ numb_format(-$total_bal_cr,2) }}</b></td>
            </tr>
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
