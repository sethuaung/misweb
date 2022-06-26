<?php
    $end_date=null;
    $brand_id=0;
    $type='';

    $bs_1_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-1.1',$brand_id,$end_date,$type);
    $bs_1_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-1.2',$brand_id,$end_date,$type);
    $bs_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-2',$brand_id,$end_date,$type);
    $bs_3_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-3.1',$brand_id,$end_date,$type);
    $bs_3_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-3.2',$brand_id,$end_date,$type);
    $bs_4= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-4',$brand_id,$end_date,$type);
    $bs_5= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-5',$brand_id,$end_date,$type);
    $bs_6_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.1',$brand_id,$end_date,$type);
    $bs_6_1_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.1.1',$brand_id,$end_date,$type);
    $bs_6_1_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.1.2',$brand_id,$end_date,$type);
    $bs_6_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.2',$brand_id,$end_date,$type);
    $bs_6_2_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.2.1',$brand_id,$end_date,$type);
    $bs_6_2_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.2.2',$brand_id,$end_date,$type);
    $bs_6_3= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.3',$brand_id,$end_date,$type);
    $bs_6_3_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.3.1',$brand_id,$end_date,$type);
    $bs_6_3_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-6.3.2',$brand_id,$end_date,$type);
    $bs_7= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-7',$brand_id,$end_date,$type);
    $bs_8= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-8',$brand_id,$end_date,$type);
    $bs_9_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.1',$brand_id,$end_date,$type);
    $bs_9_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.2',$brand_id,$end_date,$type);
    $bs_9_2_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.2.1',$brand_id,$end_date,$type);
    $bs_9_2_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.2.2',$brand_id,$end_date,$type);
    $bs_9_2_3= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.2.3',$brand_id,$end_date,$type);
    $bs_9_2_4= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-9.2.4',$brand_id,$end_date,$type);
    $bs_10= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-10',$brand_id,$end_date,$type);
    $bs_11= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-11',$brand_id,$end_date,$type);
    $bs_12= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-12',$brand_id,$end_date,$type);
    $bs_13_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-13.1',$brand_id,$end_date,$type);
    $bs_13_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-13.2',$brand_id,$end_date,$type);
    $bs_14= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-14',$brand_id,$end_date,$type);
    $bs_15= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-15',$brand_id,$end_date,$type);
    $bs_16= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-16',$brand_id,$end_date,$type);
    $bs_17= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-17',$brand_id,$end_date,$type);
    $bs_18= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-18',$brand_id,$end_date,$type);
    $bs_19_1= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.1',$brand_id,$end_date,$type);
    $bs_19_2= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.2',$brand_id,$end_date,$type);
    $bs_19_3= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.3',$brand_id,$end_date,$type);
    $bs_19_4= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.4',$brand_id,$end_date,$type);
    $bs_19_5= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.5',$brand_id,$end_date,$type);
    $bs_19_6= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.6',$brand_id,$end_date,$type);
    $bs_19_7= \App\Helpers\ACC::getFrdBalanceSheetAccountAmount('bs-19.7',$brand_id,$end_date,$type);


    $bs_1=$bs_1_1+$bs_1_2;
    $bs_3=$bs_3_1+$bs_3_2;
    $bs_6=$bs_6_1_1+$bs_6_1_2+$bs_6_2_1+$bs_6_2_2+$bs_6_3_1+$bs_6_3_2;
    $bs_9=$bs_9_1+$bs_9_2_1+$bs_9_2_2+$bs_9_2_3+$bs_9_2_4;
    $bs_13=$bs_13_1+$bs_13_2;
    $bs_19=$bs_19_1+$bs_19_2+$bs_19_3+$bs_19_4+$bs_19_5+$bs_19_6+$bs_19_7;

    $total_assets=$bs_1+$bs_2+$bs_3+$bs_4+$bs_5+$bs_6+$bs_7+$bs_8;
    $total_liabilities=$bs_9+$bs_10+$bs_11+$bs_12+$bs_13+$bs_14+$bs_15+$bs_16+$bs_17+$bs_18;
    $total_liabilities_and_equity=$total_liabilities+$bs_19;

    $acc_1_1= \App\Helpers\ACC::getMMacountName('bs-1.1');
    $acc_1_2= \App\Helpers\ACC::getMMacountName('bs-1.2');
    $acc_2= \App\Helpers\ACC::getMMacountName('bs-2');
    $acc_3_1= \App\Helpers\ACC::getMMacountName('bs-3.1');
    $acc_3_2= \App\Helpers\ACC::getMMacountName('bs-3.2');
    $acc_4= \App\Helpers\ACC::getMMacountName('bs-4');
    $acc_5= \App\Helpers\ACC::getMMacountName('bs-5');
    $acc_6_1= \App\Helpers\ACC::getMMacountName('bs-6.1');
    $acc_6_1_1= \App\Helpers\ACC::getMMacountName('bs-6.1.1');
    $acc_6_1_2= \App\Helpers\ACC::getMMacountName('bs-6.1.2');
    $acc_6_2= \App\Helpers\ACC::getMMacountName('bs-6.2');
    $acc_6_2_1= \App\Helpers\ACC::getMMacountName('bs-6.2.1');
    $acc_6_2_2= \App\Helpers\ACC::getMMacountName('bs-6.2.2');
    $acc_6_3= \App\Helpers\ACC::getMMacountName('bs-6.3');
    $acc_6_3_1= \App\Helpers\ACC::getMMacountName('bs-6.3.1');
    $acc_6_3_2= \App\Helpers\ACC::getMMacountName('bs-6.3.2');
    $acc_7= \App\Helpers\ACC::getMMacountName('bs-7');
    $acc_8= \App\Helpers\ACC::getMMacountName('bs-8');
    $acc_9_1= \App\Helpers\ACC::getMMacountName('bs-9.1');
    $acc_9_2= \App\Helpers\ACC::getMMacountName('bs-9.2');
    $acc_9_2_1= \App\Helpers\ACC::getMMacountName('bs-9.2.1');
    $acc_9_2_2= \App\Helpers\ACC::getMMacountName('bs-9.2.2');
    $acc_9_2_3= \App\Helpers\ACC::getMMacountName('bs-9.2.3');
    $acc_9_2_4= \App\Helpers\ACC::getMMacountName('bs-9.2.4');
    $acc_10= \App\Helpers\ACC::getMMacountName('bs-10');
    $acc_11= \App\Helpers\ACC::getMMacountName('bs-11');
    $acc_12= \App\Helpers\ACC::getMMacountName('bs-12');
    $acc_13_1= \App\Helpers\ACC::getMMacountName('bs-13.1');
    $acc_13_2= \App\Helpers\ACC::getMMacountName('bs-13.2');
    $acc_14= \App\Helpers\ACC::getMMacountName('bs-14');
    $acc_15= \App\Helpers\ACC::getMMacountName('bs-15');
    $acc_16= \App\Helpers\ACC::getMMacountName('bs-16');
    $acc_17= \App\Helpers\ACC::getMMacountName('bs-17');
    $acc_18= \App\Helpers\ACC::getMMacountName('bs-18');
    $acc_19_1= \App\Helpers\ACC::getMMacountName('bs-19.1');
    $acc_19_2= \App\Helpers\ACC::getMMacountName('bs-19.2');
    $acc_19_3= \App\Helpers\ACC::getMMacountName('bs-19.3');
    $acc_19_4= \App\Helpers\ACC::getMMacountName('bs-19.4');
    $acc_19_5= \App\Helpers\ACC::getMMacountName('bs-19.5');
    $acc_19_6= \App\Helpers\ACC::getMMacountName('bs-19.6');
    $acc_19_7= \App\Helpers\ACC::getMMacountName('bs-19.7');


?>

<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>


        <table class="table-data" id="table-data">
            <thead>
            <tr>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:1%;">
                    No.
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:25%;">
                    Account Name
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    Total
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="border">
                <th colspan="3" class="text-center border">Assets</th>
            </tr>
            <tr class="border">
                <th class="text-right border">1</th>
                <th class="text-left border">Cash and Balances in Banks</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_1 }}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">1.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_1_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_1_1 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">1.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_1_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_1_2 }}
                </td>
            </tr>
            <tr class="border">
                <th class="text-right border">2</th>
                <th class="text-left border">{{ $acc_2 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_2 }}
                </th>
            </tr>
            <tr class="border">
                <th class="text-right border">3</th>
                <th class="text-left border">Loans to Customers (3.1 - 3.2)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_3 }}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">3.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_3_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_3_1 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">3.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_3_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_3_2 }}
                </td>
            </tr>
            <tr class="border">
                <th class="text-right border">4</th>
                <th class="text-left border">{{ $acc_4 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_4 }}
                </th>
            </tr>
            <tr class="border">
                <th class="text-right border">5</th>
                <th class="text-left border">{{ $acc_5 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_5 }}
                </th>
            </tr>
            <tr class="border">
                <th class="text-right border">6</th>
                <th class="text-left border">Property and Equipment</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6 }}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_6_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_1 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1.1</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_1_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_1_1 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1.2</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_1_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_1_2 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_6_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_2 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2.1</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_2_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_2_1 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2.2</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_2_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_2_2 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.3</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_6_3 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_3 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.3.1</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_3_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_3_1 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">6.3.2</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_6_3_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_6_3_2 }}
                </td>

            </tr>
            <tr class="border">
                <th class="text-right border">7</th>
                <th class="text-left border">{{ $acc_7 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_7 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">8</th>
                <th class="text-left border">{{ $acc_8 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $bs_8 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border"></th>
                <th class="text-left border">Total Assets (1+2+3+4+5+6+7+8)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $total_assets }}
                </th>
            </tr>
            <tr class="border">
                <th colspan="3" class="text-center border">Liabilities</th>
            </tr>
            <tr class="border">
                <th class="text-right border">9</th>
                <th class="text-left border">Customers' Deposits</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9 }}
                </th>

            </tr>
            <tr class="border">
                <td class="text-right border">9.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_9_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_1 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">9.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_9_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_2 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">9.2.1</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_9_2_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_2_1 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">9.2.2</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_9_2_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_2_2 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">9.2.3</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_9_2_3 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_2_3 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">9.2.4</td>
                <td class="text-left border" style="padding-left: 40px;">{{ $acc_9_2_4 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_9_2_4 }}
                </td>

            </tr>
            <tr class="border">
                <th class="text-right border">10</th>
                <th class="text-left border">{{ $acc_10 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_10 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">11</th>
                <th class="text-left border">{{ $acc_11 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_11 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">12</th>
                <th class="text-left border">{{ $acc_12 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_12 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">13</th>
                <th class="text-left border">Long-term Borrowing</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_13 }}
                </th>

            </tr>
            <tr class="border">
                <td class="text-right border">13.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_13_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_13_1 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">13.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_13_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_13_2 }}
                </td>

            </tr>
            <tr class="border">
                <th class="text-right border">14</th>
                <th class="text-left border">{{ $acc_14 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_14 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">15</th>
                <th class="text-left border">{{ $acc_15 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_15 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">16</th>
                <th class="text-left border">{{ $acc_16 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_16 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">17</th>
                <th class="text-left border">{{ $acc_17 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_17 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">18</th>
                <th class="text-left border">{{ $acc_18 }}</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_18 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border"></th>
                <th class="text-left border">Total Liabilities (9+10+11+12+13+14+15+16+17+18)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$total_liabilities }}
                </th>

            </tr>
            <tr class="border">
                <th colspan="3" class="text-center border">Equity</th>
            </tr>
            <tr class="border">
                <th class="text-right border">19</th>
                <th class="text-left border">Equity</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{-$bs_19}}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">19.1</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_1 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_1 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.2</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_2 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_2 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.3</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_3 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_3 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.4</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_4 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_4 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.5</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_5 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_5 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.6</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_6 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_6 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border">19.7</td>
                <td class="text-left border" style="padding-left: 20px;">{{ $acc_19_7 }}</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19_7 }}
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border"></td>
                <td class="text-left border">Total Equity(19)</td>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$bs_19 }}
                </th>

            </tr>
            <tr class="border">
                <th class="text-right border">20</th>
                <th class="text-left border">Total Liabilities and Total Equity</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$total_liabilities_and_equity }}
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
