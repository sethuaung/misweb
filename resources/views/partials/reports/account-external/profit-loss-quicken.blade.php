<?php
    $start_date = $start_date??null;
    $end_date = $end_date??null;
    $branch_id = $branch_id??0;
    $type = '';
    $pl1_1          = \App\Models\ReportSRD::getAccountBalAllQuicken('5-21-100',$branch_id,$start_date,$end_date);
    $pl1_2          = \App\Models\ReportSRD::getAccountBalAllQuicken('5-64-100-001',$branch_id,$start_date,$end_date);
    $pl1_3          = \App\Models\ReportSRD::getAccountBalAllQuicken('5-21-100-009',$branch_id,$start_date,$end_date);
    $pl1_4          = \App\Models\ReportSRD::getAccountBalAllQuicken('5-21-100-010',$branch_id,$start_date,$end_date);

    $pl2_1         = \App\Models\ReportSRD::getAccountBalAllQuicken('6-12-900-001',$branch_id,$start_date,$end_date);
    $pl2_2         = \App\Models\ReportSRD::getAccountBalAllQuicken('6-23-200-003',$branch_id,$start_date,$end_date);
    $pl2_3         = \App\Models\ReportSRD::getAccountBalAllQuicken('6-23',$branch_id,$start_date,$end_date);
    $pl2_4         = \App\Models\ReportSRD::getAccountBalAllQuicken('6-23-200-004',$branch_id,$start_date,$end_date);

    $pl3     = \App\Models\ReportSRD::getAccountBalAllQuicken('1-2',$branch_id,$start_date,$end_date);

    $pl4_1    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-71-100-001',$branch_id,$start_date,$end_date);
    $pl4_2    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-73-100',$branch_id,$start_date,$end_date);

    $pl5    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-74-100',$branch_id,$start_date,$end_date);

    $pl6    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-21-100-010',$branch_id,$start_date,$end_date);

    $pl8    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31',$branch_id,$start_date,$end_date);
    $pl8_1    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-001',$branch_id,$start_date,$end_date);
    $pl8_2    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-450-003',$branch_id,$start_date,$end_date);
    $pl8_3    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-002',$branch_id,$start_date,$end_date);
    $pl8_4    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-003',$branch_id,$start_date,$end_date);
    $pl8_5    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-005',$branch_id,$start_date,$end_date);
    $pl8_6    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-006',$branch_id,$start_date,$end_date);
    $pl8_7    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-007',$branch_id,$start_date,$end_date);
    $pl8_8    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-200-001',$branch_id,$start_date,$end_date);
    $pl8_9    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-300-001',$branch_id,$start_date,$end_date);
    $pl8_10    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-400-001',$branch_id,$start_date,$end_date);
    $pl8_11    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-800-001',$branch_id,$start_date,$end_date);
    $pl8_12    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-900-001',$branch_id,$start_date,$end_date);
    $pl8_14    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31-100-004',$branch_id,$start_date,$end_date);

    // $pl9    = \App\Models\ReportSRD::getAccountBalAllQuicken('0-0',$branch_id,$start_date,$end_date);
    $pl9_1    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-100',$branch_id,$start_date,$end_date);
    $pl9_2    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-200',$branch_id,$start_date,$end_date);
    $pl9_3    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-300',$branch_id,$start_date,$end_date);
    $pl9_4    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-400',$branch_id,$start_date,$end_date);
    $pl9_5    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-100',$branch_id,$start_date,$end_date);
    $pl9_6    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-200',$branch_id,$start_date,$end_date);
    $pl9_7    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-300',$branch_id,$start_date,$end_date);
    $pl9_8    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-400',$branch_id,$start_date,$end_date);
    $pl9_9    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-450',$branch_id,$start_date,$end_date);
    $pl9_10    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-500',$branch_id,$start_date,$end_date);
    $pl9_11    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55-100',$branch_id,$start_date,$end_date);
    $pl9_12    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55-200',$branch_id,$start_date,$end_date);
    $pl9_13    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55-300',$branch_id,$start_date,$end_date);
    $pl9_14    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57-100',$branch_id,$start_date,$end_date);
    $pl9_15    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57-200',$branch_id,$start_date,$end_date);
    $pl9_16    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58-100',$branch_id,$start_date,$end_date);
    $pl9_17    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58-900',$branch_id,$start_date,$end_date);
    $pl9_18    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-61-100',$branch_id,$start_date,$end_date);
    $pl9_19    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62-100',$branch_id,$start_date,$end_date);
    $pl9_20    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62-200',$branch_id,$start_date,$end_date);
    $pl9_21    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-63-100',$branch_id,$start_date,$end_date);
    $pl9_22    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-66-100',$branch_id,$start_date,$end_date);
    $pl9_23    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-68-100',$branch_id,$start_date,$end_date);
    $pl9_24    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-69-100',$branch_id,$start_date,$end_date);
    $pl9_25    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-69-900',$branch_id,$start_date,$end_date);
    $pl9_26    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92-100',$branch_id,$start_date,$end_date);
    $pl9_27    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-81-100',$branch_id,$start_date,$end_date);
    $pl9_28    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-81-600',$branch_id,$start_date,$end_date);
    //dd($pl9_2);

    $pl10    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-300-001',$branch_id,$start_date,$end_date);
    
    $pl10_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-350-001',$branch_id,$start_date,$end_date);

    $pl10_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-400-001',$branch_id,$start_date,$end_date);

    $pl10_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-400-002',$branch_id,$start_date,$end_date);

    $pl10_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-400-003',$branch_id,$start_date,$end_date);

    $pl10_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-400-004',$branch_id,$start_date,$end_date);

    $pl10_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-500-001',$branch_id,$start_date,$end_date);

    $pl10_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-600-001',$branch_id,$start_date,$end_date);

    $pl10_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-700-001',$branch_id,$start_date,$end_date);

    $pl10_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-700-002',$branch_id,$start_date,$end_date);

    $pl10_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-100-001',$branch_id,$start_date,$end_date);

    $pl10_11 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42-200-001',$branch_id,$start_date,$end_date);

    $pl11    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-71-100',$branch_id,$start_date,$end_date);
    //$pl11_1    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-71-100',$branch_id,$start_date,$end_date);
    //dd($pl11,$pl11_1,$start_date,$end_date);
    $pl13    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-85-300-001',$branch_id,$start_date,$end_date);

    $pl14    = \App\Models\ReportSRD::getAccountBalAllQuicken('5-86-100',$branch_id,$start_date,$end_date);

    $pl16    = \App\Models\ReportSRD::getAccountBalAllQuicken('6-69-100-002',$branch_id,$start_date,$end_date);

    $acc_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53',$branch_id,$start_date,$end_date);
    $acc_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54',$branch_id,$start_date,$end_date);
    $acc_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55',$branch_id,$start_date,$end_date);
    $acc_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57',$branch_id,$start_date,$end_date);
    $acc_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58',$branch_id,$start_date,$end_date);
    $acc_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-61',$branch_id,$start_date,$end_date);
    $acc_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62',$branch_id,$start_date,$end_date);
    $acc_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-63',$branch_id,$start_date,$end_date);
    $acc_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-66',$branch_id,$start_date,$end_date);
    $acc_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-68',$branch_id,$start_date,$end_date);
    $acc_11 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92',$branch_id,$start_date,$end_date);

    $all_amount = [];
    if ($acc_1->isNotEmpty()) {
        foreach($acc_1 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_2->isNotEmpty()) {
        foreach($acc_2 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_3->isNotEmpty()) {
        foreach($acc_3 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_4->isNotEmpty()) {
        foreach($acc_4 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_5->isNotEmpty()) {
        foreach($acc_5 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_6->isNotEmpty()) {
        foreach($acc_6 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_7->isNotEmpty()) {
        foreach($acc_7 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_8->isNotEmpty()) {
        foreach($acc_8 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_9->isNotEmpty()) {
        foreach($acc_9 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_10->isNotEmpty()) {
        foreach($acc_10 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_11->isNotEmpty()) {
        foreach($acc_11 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    $num = 1;
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
                <th>
                    Account Code
                </th>
                <th class="text-center">
                    Total
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="border">
                <td class="text-right border">1</td>
                <td class="text-left border" style="padding-left: 20px;">Interest Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                   
                </td>
                @php
                    $interest_income = 0;
                @endphp
                <td class="text-right">-</td>
               
            </tr>
            <tr class="border">
                <td class="text-right border">1.1</td>
                <td class="text-left border" style="padding-left: 20px;">Interest Income from Loans to Customers</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                   5-21-100
                </td>
                @if($pl1_1->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl1_1 as $bal){
                            $amount += $bal->amt;
                            $interest_income += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">1.2</td>
                <td class="text-left border" style="padding-left: 20px;">Account with banks and financial institutions</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-64-100-001
                </td>
                @if($pl1_2->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl1_2 as $bal){
                            $amount += $bal->amt;
                            $interest_income += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">1.3</td>
                <td class="text-left border" style="padding-left: 20px;">Securities and Investments</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-21-100-009
                </td>
                @if($pl1_3->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl1_3 as $bal){
                            $amount += $bal->amt;
                            $interest_income += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">1.4</td>
                <td class="text-left border" style="padding-left: 20px;">Others</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-21-100-010
                </td>
                @if($pl1_4->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl1_4 as $bal){
                            $amount += $bal->amt;
                            $interest_income += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr>
                <td colspan="2"><strong>Total Interest Income</strong></td>
                @if($amount > 0)
                    <td colspan="2" style="text-align: right">{{$interest_income}}</td>
                @else
                    <td colspan="2" style="text-align: right">{{(-1)*$interest_income}}</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">2</td>
                <td class="text-left border" style="padding-left: 20px;">Interest Expenses</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                   
                </td>
                @php
                    $interest_expense = 0;
                @endphp
                <td class="text-right">-</td>
               
            </tr>
            <tr class="border">
                <td class="text-right border">2.1</td>
                <td class="text-left border" style="padding-left: 20px;">Interest on Customer Deposits</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-12-900-001
                </td>
                @if($pl2_1->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl2_1 as $bal){
                            $amount += $bal->amt;
                            $interest_expense += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">2.2</td>
                <td class="text-left border" style="padding-left: 20px;">Interest on Amounts Owing to bank and other  financial institutions</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-23-200-003
                </td>
                @if($pl2_2->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl2_2 as $bal){
                            $amount += $bal->amt;
                            $interest_expense += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">2.3</td>
                <td class="text-left border" style="padding-left: 20px;">Borrowings</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-23
                </td>
                @if($pl2_3->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl2_3 as $bal){
                            $amount += $bal->amt;
                            $interest_expense += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">2.4</td>
                <td class="text-left border" style="padding-left: 20px;">Others</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-23-200-004
                </td>
                @if($pl2_4->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl2_4 as $bal){
                            $amount += $bal->amt;
                            $interest_expense += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr>
                <td colspan="2"><strong>Total Interest Expense</strong></td>
                @if($interest_expense > 0)
                    <td colspan="2" style="text-align: right">{{$interest_expense}}</td>
                @else
                    <td colspan="2" style="text-align: right">{{(-1)*$interest_expense}}</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">3</td>
                <td class="text-left border" style="padding-left: 20px;">Net Interest Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                </td>
                @php
                    if($interest_income < 0 ){
                        $interest_income = (-1)*$interest_income;
                    }
                    if($interest_expense < 0){
                        $interest_expense = (-1)*$interest_expense;
                    }
                    $net = $interest_income - $interest_expense;
                @endphp
                @php
                //dd($net);
                    
                @endphp
                    <td class="text-right">{{$net}}</td>
            </tr>
            <tr class="border">
                <td class="text-right border">4</td>
                <td class="text-left border" style="padding-left: 20px;">Non - Interest Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                   
                </td>
                @php
                    $non_interest =  $non_interest??0;
                @endphp
                <td class="text-right">-</td>
               
            </tr> 
            <tr class="border">
                <td class="text-right border">4.1</td>
                <td class="text-left border" style="padding-left: 20px;">Commission and fee income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-71-100-001
                </td>
                @if($pl4_1->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl4_1 as $bal){
                            $amount += $bal->amt;
                            $non_interest += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">4.2</td>
                <td class="text-left border" style="padding-left: 20px;">Other Non-Interest Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-73-100
                </td>
                @if($pl4_2->isNotEmpty())
                    @php
                        $amount = 0;
                        foreach($pl4_2 as $bal){
                            $amount += $bal->amt;
                            $non_interest += $bal->amt;
                        }
                    @endphp
                    @if($amount > 0)
                        <td class="text-primary text-right">{{$amount}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$amount}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr>
                <td colspan="2"><strong>Total Non Interest Income</strong></td>
                @if($amount > 0)
                    <td colspan="2" style="text-align: right">{{$non_interest}}</td>
                @else
                    <td colspan="2" style="text-align: right">{{(-1)*$non_interest}}</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">5</td>
                <td class="text-left border" style="padding-left: 20px;">Foregin Exchange Gain / (Loss)</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-74-100
                </td>
                @if($pl5->isNotEmpty())
                    @php
                        $foreign = 0;
                        foreach($pl5 as $bal){
                            $foreign += $bal->amt;
                        }
                    @endphp
                    @if($foreign > 0)
                        <td class="text-primary text-right">{{$foreign}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$foreign}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">6</td>
                <td class="text-left border" style="padding-left: 20px;">Other Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-21-100-010
                </td>
                @if($pl6->isNotEmpty())
                    @php
                        $other_income = 0;
                        foreach($pl6 as $bal){
                            $other_income += $bal->amt;
                        }
                    @endphp
                    @if($other_income > 0)
                        <td class="text-primary text-right">{{$other_income}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$other_income}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                @php
                    $net = $net??0;
                    $non_interest = $non_interest??0;
                    $foreign = $foreign??0;
                    $other_income  = $other_income ??0;
                    if($foreign < 0){
                        $foreign = (-1)*$foreign;
                    }
                    if($other_income < 0){
                        $other_income = (-1)*$other_income;
                    }
                    if($non_interest < 0){
                        $non_interest = (-1)*$non_interest;
                    }
                    
                    $operation = $net + $non_interest + $foreign + $other_income;
                @endphp
                <td class="text-right border">7</td>
                <td class="text-left border" style="padding-left: 20px;">Operating Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
            
                </td>
                
                    <td class="text-right">{{$operation}}</td>
                
            </tr>
            <tr class="border">
                <td class="text-right border">8</td>
                <td class="text-left border" style="padding-left: 20px;">Staff Expense</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    
                </td>
               
                    @php
                        $staff = 0;
                        foreach($pl8_1 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_2 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_3 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_4 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_5 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_6 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_7 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_8 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_9 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_10 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_11 as $bal){
                            $staff += $bal->amt;
                        }
                        foreach($pl8_12 as $bal){
                            $staff += $bal->amt;
                        }
                       
                        foreach($pl8_14 as $bal){
                            $staff += $bal->amt;
                        }
                    @endphp
                    @if($staff > 0)
                        <td class="text-primary text-right">{{$staff}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$staff}}</td>
                    @endif
               
            </tr>
            <tr class="border">
                <td class="text-right border">9</td>
                <td class="text-left border" style="padding-left: 20px;">Admin and General Expenses</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    
                </td>
               
                    @php
                        $admin = 0;
                        foreach($pl9_1 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_2 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_3 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_4 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_5 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_6 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_7 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_8 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_9 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_10 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_11 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        //dd($admin);
                        foreach($pl9_12 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_13 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_14 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_15 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_16 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_17 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_18 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_19 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_20 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_21 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_22 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_23 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_24 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_25 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_26 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_27 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl9_28 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin += $amts;
                        }
                        foreach($pl8_2 as $bal){
                            $amts = $bal->amt;
                            if($amts <0){
                                $amts = -1*($amts);
                            }
                            $admin -= $amts;
                        }
                    @endphp
                    @if($admin > 0)
                        <td class="text-primary text-right">{{$admin}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$admin}}</td>
                    @endif
               
            </tr>
            <tr class="border">
                <td class="text-right border">10</td>
                <td class="text-left border" style="padding-left: 20px;">Depreciation</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    
                </td>
               
                    @php
                        $depre = 0;
                        foreach($pl10 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_1 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_2 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_3 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_4 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_5 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_6 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_7 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_8 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_9 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_10 as $bal){
                            $depre += $bal->amt;
                        }
                        foreach($pl10_11 as $bal){
                            $depre += $bal->amt;
                        }
                        
                    @endphp
                    @if($depre > 0)
                        <td class="text-primary text-right">{{$depre}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$depre}}</td>
                    @endif
               
            </tr>
            
            
            <tr class="border">
                <td class="text-right border">11</td>
                <td class="text-left border" style="padding-left: 20px;">Loan loss Provision</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    
                </td>
               
                    @php
                        $loss = 0;
                        foreach($pl11 as $bal){
                            $loss += $bal->amt;
                        }
                    @endphp
                    
                    @if($loss > 0)
                        <td class="text-primary text-right">{{$loss}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$loss}}</td>
                    @endif
                
            </tr>
            <tr class="border">
                <td class="text-right border">12</td>
                <td class="text-left border" style="padding-left: 20px;">Profit from operations</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    
                </td>
                
               
                    @php
                       $operation = $operation??0; //7
                       $staff = $staff??0; //8
                       $admin = $admin??0; //9
                       $depre = $depre??0; //10
                       $loss = $loss??0; //11
                       if($operation < 0 ){
                        $operation= -1*($operation);
                       }
                       if($staff < 0 ){
                        $staff= -1*($staff);
                       }
                       if($admin < 0 ){
                        $admin= -1*($admin);
                       }
                       if($depre < 0 ){
                        $depre= -1*($depre);
                       }
                       if($loss < 0 ){
                        $loss= -1*($loss);
                       }
                       //dd($operation,$staff,$admin,$depre,$loss);
                       $profit_expense = $operation - ($staff + $admin + $depre +  $loss);

                    @endphp
                  
                        <td class="text-primary text-right">{{$profit_expense}}</td>
                  
                
                   
            </tr>
            <tr class="border">
                <td class="text-right border">13</td>
                <td class="text-left border" style="padding-left: 20px;">Grants Income</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-85-300
                </td>
                @if($pl13->isNotEmpty())
                    @php
                        $grants = 0;
                        foreach($pl13 as $bal){
                            $grants += $bal->amt;
                        }
                    @endphp
                    @if($grants > 0)
                        <td class="text-primary text-right">{{$grants}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$grants}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">14</td>
                <td class="text-left border" style="padding-left: 20px;">Adjustments for subsidies</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    5-86-100
                </td>
                @if($pl14->isNotEmpty())
                    @php
                        $adj = 0;
                        foreach($pl14 as $bal){
                            $adj += $bal->amt;
                        }
                    @endphp
                    @if($adj > 0)
                        <td class="text-primary text-right">{{$adj}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$adj}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">15</td>
                <td class="text-left border" style="padding-left: 20px;">Profit Before Tax</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                   
                </td>
                @php
                    $profit_expense = $profit_expense??0;
                    $grants = $grants??0;
                    $adj = $adj??0;
                    $before_tax = $profit_expense + $grants + $adj;
                @endphp
                   
                        <td class="text-primary text-right">{{$before_tax}}</td>
                   
               
            </tr>
            <tr class="border">
                <td class="text-right border">16</td>
                <td class="text-left border" style="padding-left: 20px;">Tax on Profit</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-19
                </td>
                @if($pl16->isNotEmpty())
                    @php
                        $tax_on = 0;
                        foreach($pl16 as $bal){
                            $tax_on += $bal->amt;
                        }
                    @endphp
                    @if($tax_on > 0)
                        <td class="text-primary text-right">{{$tax_on}}</td>
                    @else
                        <td class="text-danger text-right">{{(-1)*$tax_on}}</td>
                    @endif
                @else 
                    <td class="text-right">-</td>
                @endif
            </tr>
            <tr class="border">
                <td class="text-right border">17</td>
                <td class="text-left border" style="padding-left: 20px;">Net Profit for the period</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    6-19
                </td>
               
                    @php
                       $before_tax = $before_tax??0;
                       $tax_on = $tax_on??0;
                       $net_profit = $before_tax - $tax_on;
                    @endphp
                   
                        <td class="text-primary text-right">{{$net_profit}}</td>
                   
                
               
            </tr>
        </tbody>
        <tfoot>
        <tr class="text-center no-border">
            <td colspan="3" class="text-right no-border">Prepared by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        <tr class="text-center no-border">
            <td colspan="3" class="text-right no-border">Checked by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        <tr class="text-center no-border">
            <td colspan="3" class="text-right no-border">Approved by (Name/Signature)</td>
            <td class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
