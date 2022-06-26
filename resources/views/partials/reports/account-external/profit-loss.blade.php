<?php
    $start_date = $start_date??null;
    $end_date = $end_date??null;
    $branch_id = $branch_id??0;
    $type = '';
    $pl1_1 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-1.1',$branch_id,$start_date,$end_date,$type);
    $pl1_2 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-1.2',$branch_id,$start_date,$end_date,$type);
    $pl1_3 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-1.3',$branch_id,$start_date,$end_date,$type);
    $pl1_4 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-1.4',$branch_id,$start_date,$end_date,$type);
    $pl2_1 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-2.1',$branch_id,$start_date,$end_date,$type);
    $pl2_2 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-2.2',$branch_id,$start_date,$end_date,$type);
    $pl2_3 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-2.3',$branch_id,$start_date,$end_date,$type);
    $pl2_4 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-2.4',$branch_id,$start_date,$end_date,$type);
    $pl4_1 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-4.1',$branch_id,$start_date,$end_date,$type);
    $pl4_2 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-4.2',$branch_id,$start_date,$end_date,$type);
    $pl_5 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-5',$branch_id,$start_date,$end_date,$type);
    $pl_6 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-6',$branch_id,$start_date,$end_date,$type);
    $pl_8 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-8',$branch_id,$start_date,$end_date,$type);
    $pl_9 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-9',$branch_id,$start_date,$end_date,$type);
    $pl_10 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-10',$branch_id,$start_date,$end_date,$type);
    $pl_11 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-11',$branch_id,$start_date,$end_date,$type);
    $pl_13 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-13',$branch_id,$start_date,$end_date,$type);
    $pl_14 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-14',$branch_id,$start_date,$end_date,$type);
    $pl_16 = \App\Helpers\ACC::getFrdProfitAccountAmount('pl-16',$branch_id,$start_date,$end_date,$type);

    $t1 = $pl1_1 + $pl1_2 + $pl1_3 + $pl1_4;
    $t2 = $pl2_1 + $pl2_2 + $pl2_3 + $pl2_4;
    $t3 = $t1 + $t2;
    $t4 = $pl4_1 + $pl4_2;
    $t7 = $t3 + $t4 + $pl_5 + $pl_6;
    $t12 = $t7 + $pl_8 + $pl_9 + $pl_10 + $pl_11;
    $t15 = $t12 + $pl_13 + $pl_14;
    $t17 = $t15 + $pl_16;

    $acc1_1 = \App\Helpers\ACC::getMMacountName('pl-1.1');
    $acc1_2 = \App\Helpers\ACC::getMMacountName('pl-1.2');
    $acc1_3 = \App\Helpers\ACC::getMMacountName('pl-1.3');
    $acc1_4 = \App\Helpers\ACC::getMMacountName('pl-1.4');
    $acc2_1 = \App\Helpers\ACC::getMMacountName('pl-2.1');
    $acc2_2 = \App\Helpers\ACC::getMMacountName('pl-2.2');
    $acc2_3 = \App\Helpers\ACC::getMMacountName('pl-2.3');
    $acc2_4 = \App\Helpers\ACC::getMMacountName('pl-2.4');
    $acc4_1 = \App\Helpers\ACC::getMMacountName('pl-4.1');
    $acc4_2 = \App\Helpers\ACC::getMMacountName('pl-4.2');
    $acc_5 = \App\Helpers\ACC::getMMacountName('pl-6');
    $acc_6 = \App\Helpers\ACC::getMMacountName('pl-5');
    $acc_8 = \App\Helpers\ACC::getMMacountName('pl-8');
    $acc_9 = \App\Helpers\ACC::getMMacountName('pl-9');
    $acc_10 = \App\Helpers\ACC::getMMacountName('pl-10');
    $acc_11 = \App\Helpers\ACC::getMMacountName('pl-11');
    $acc_13 = \App\Helpers\ACC::getMMacountName('pl-13');
    $acc_14 = \App\Helpers\ACC::getMMacountName('pl-14');
    $acc_16 = \App\Helpers\ACC::getMMacountName('pl-16');
?>
<div id="DivIdToPrint">

    <table class="table-data" id="table-data">
        <thead>
        <tr>
            <th class="text-center">
                No.
            </th>
            <th class="text-center">
                Description
            </th>
            <th class="text-center">
                Cumulative Year To Date
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="border">
            <th class="text-right border">1</th>
            <th class="text-left border">Interest Income</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$t1}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">1.1</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc1_1}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl1_1}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">1.2</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc1_2}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl1_2}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">1.3</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc1_3}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl1_3}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">1.4</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc1_4}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                0
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">2</th>
            <th class="text-left border">Interest Expenses</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$t2}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">2.1</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc2_1}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl2_1}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">2.2</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc2_2}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl2_2}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">2.3</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc2_3}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl2_3}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">2.4</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc2_4}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl2_4}}
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">3</th>
            <th class="text-left border">Net Interest Income (1-2)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t3}}
            </th>
        </tr>
        <tr class="border">
            <th class="text-right border">4</th>
            <th class="text-left border">Non-interest Income (net)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t4}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">4.1</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc4_1}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl4_1}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">4.2</td>
            <td class="text-left border" style="padding-left: 20px;">{{$acc4_2}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl4_2}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">5</td>
            <td class="text-left border">{{$acc_5}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl_5}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">6</td>
            <td class="text-left border">{{$acc_6}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl_6}}
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">7</th>
            <th class="text-left border">Operating Income (3+4+5+6)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t7}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">8</td>
            <td class="text-left border">{{$acc_8}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl_8}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">9</td>
            <td class="text-left border">{{$acc_9}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl_9}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">10</td>
            <td class="text-left border">{{$acc_10}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl_10}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">11</td>
            <td class="text-left border">{{$acc_11}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl_11}}
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">12</th>
            <th class="text-left border">Profit from Operations (7-8+9+10+11)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t12}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">13</td>
            <td class="text-left border">{{$acc_13}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl_13}}
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">14</td>
            <td class="text-left border">{{$acc_14}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{$pl_14}}
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">15</th>
            <th class="text-left border">Profit before Tax (12+13+14)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t15}}
            </th>
        </tr>
        <tr class="border">
            <td class="text-right border">16</td>
            <td class="text-left border">{{$acc_16}}</td>
            <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$pl_16}}
            </td>
        </tr>
        <tr class="border">
            <th class="text-right border">17</th>
            <th class="text-left border">Net Profit for the period (15-16)</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                {{-$t17}}
            </th>
        </tr>
        </tbody>
        <tfoot>
        <tr class="text-center no-border">
            <td colspan="2" class="text-right no-border">Prepared by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        <tr class="text-center no-border">
            <td colspan="2" class="text-right no-border">Checked by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        <tr class="text-center no-border">
            <td colspan="2" class="text-right no-border">Approved by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
