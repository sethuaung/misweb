<?php
//dd($branch_id);
    $date_start = $start_date??null;
    $start_date = null;
    $end_date= $end_date??null;
    $branch_id = $branch_id??0;
    $acc_chart_id = $acc_chart_id??null;
    $acc_chart_array = [];
    $type='';
    $time = explode('-',$date_start);
    //dd($time);
    if($time[1] < 10){
        $year = $time[0] - 1;
        $year_two = $time[0] - 2;
    }
    else{
        $year = $time[0];
        $year_two = $time[0]-1;
    }
    //$retain_date = "$year-10-01";
    $start_date_report = "$year-10-01";
    $end_date_retain = "$year-09-31";
    $start_date_retain = "$year_two-10-01";
    $bs_1_1= \App\Models\ReportSRD::getAccountBalAllQuicken('1-11-100-002',$branch_id,$start_date_report,$end_date);
    $extra_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('1-11-100-001',$branch_id,$start_date_report,$end_date);
    //dd($bs_1_1,$extra_1);

    // $getAccountBalAll_002 = \App\Models\ReportAccounting::getAccountBalAll($acc_chart_array,$start_date_report,$end_date,[10],false,$branch_id);
    // foreach($getAccountBalAll_002 as $key => $value){
    //     if($key == 3){
    //         //dd($value);
    //         $gg_dr = $value->t_dr;
    //         $gg_cr = $value->t_cr;
    //         $gg = $gg_dr - $gg_cr;
    //     }
    // }
    //dd($bs_1_1,$extra_1);
    $bs_1_2= \App\Models\ReportSRD::getAccountBalAllQuicken('1-15-100-003',$branch_id,$start_date_report,$end_date);
    $extra_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('1-15-100-005',$branch_id,$start_date_report,$end_date);

    $bs_2= \App\Models\ReportSRD::getAccountBalAllQuicken('1-17-100',$branch_id,$start_date_report,$end_date);  

    $bs_3_1= \App\Models\ReportSRD::getAccountBalAllQuicken('1-31',$branch_id,$start_date_report,$end_date);

    $bs_4= \App\Models\ReportSRD::getAccountBalAllQuicken('2-21-500-001',$branch_id,$start_date_report,$end_date);
    $extra_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-21-700-001',$branch_id,$start_date_report,$end_date);
    
    $bs_5= \App\Models\ReportSRD::getAccountBalAllQuicken('2-20-100',$branch_id,$start_date_report,$end_date);
    
    $bs_6_1= \App\Models\ReportSRD::getAccountBalAllQuicken('6.1',$branch_id,$start_date_report,$end_date);
    $bs_6_1_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-002',$branch_id,$start_date_report,$end_date);
    $bs_6_1_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-003',$branch_id,$start_date_report,$end_date);
    $bs_6_2= \App\Models\ReportSRD::getAccountBalAllQuicken('6.2',$branch_id,$start_date_report,$end_date);
    $bs_6_2_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-003',$branch_id,$start_date_report,$end_date);
    $bs_6_2_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-002',$branch_id,$start_date_report,$end_date);
    $bs_6_3= \App\Models\ReportSRD::getAccountBalAllQuicken('6.3',$branch_id,$start_date_report,$end_date);

    $bs_6_3_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-100-001',$branch_id,$start_date_report,$end_date);
    $extra63_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-001',$branch_id,$start_date_report,$end_date);
    $extra63_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-002',$branch_id,$start_date_report,$end_date);
    $extra63_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-003',$branch_id,$start_date_report,$end_date);
    $extra63_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-004',$branch_id,$start_date_report,$end_date);
    $extra63_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-300-001',$branch_id,$start_date_report,$end_date);
    $extra63_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-400-001',$branch_id,$start_date_report,$end_date);
    $extra63_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-001',$branch_id,$start_date_report,$end_date);
    $extra63_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-003',$branch_id,$start_date_report,$end_date);
    $extra63_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-95-100-001',$branch_id,$start_date_report,$end_date);
    $extra63_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-95-200-001',$branch_id,$start_date_report,$end_date);

    $bs_6_3_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-300-001',$branch_id,$start_date_report,$end_date);
    $extra64_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-400-001',$branch_id,$start_date_report,$end_date);
    $extra64_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-001',$branch_id,$start_date_report,$end_date);
    $extra64_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-002',$branch_id,$start_date_report,$end_date);
    $extra64_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-003',$branch_id,$start_date_report,$end_date);
    $extra64_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-004',$branch_id,$start_date_report,$end_date);
    $extra64_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-600-001',$branch_id,$start_date_report,$end_date);
    $extra64_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-700-001',$branch_id,$start_date_report,$end_date);
    $extra64_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-001',$branch_id,$start_date_report,$end_date);
    $extra64_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-910-001',$branch_id,$start_date_report,$end_date);
    $extra64_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-920-001',$branch_id,$start_date_report,$end_date);

    $bs_7= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-600',$branch_id,$start_date_report,$end_date);

    $bs_8= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-600',$branch_id,$start_date_report,$end_date);

    //$bs_9= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22',$branch_id,$start_date,$end_date);
    $bs_9_1= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-001',$branch_id,$start_date_report,$end_date);
    $bs_9_2= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-002',$branch_id,$start_date_report,$end_date);
    $bs_9_2_1= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-004',$branch_id,$start_date_report,$end_date);
    $bs_9_2_2= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-005',$branch_id,$start_date_report,$end_date);
    $bs_9_2_3= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-006',$branch_id,$start_date_report,$end_date);
    $bs_9_2_4= \App\Models\ReportSRD::getAccountBalAllQuicken('3-22-900-007',$branch_id,$start_date_report,$end_date);

    $bs_10= \App\Models\ReportSRD::getAccountBalAllQuicken('3-24-100-001',$branch_id,$start_date_report,$end_date);

    $bs_11= \App\Models\ReportSRD::getAccountBalAllQuicken('3-89-800',$branch_id,$start_date_report,$end_date);

    $bs_12= \App\Models\ReportSRD::getAccountBalAllQuicken('2-43-100-001',$branch_id,$start_date_report,$end_date);

    $bs_13= \App\Models\ReportSRD::getAccountBalAllQuicken('3-33',$branch_id,$start_date_report,$end_date);
    $bs_13_1= \App\Models\ReportSRD::getAccountBalAllQuicken('3-33-100-001',$branch_id,$start_date_report,$end_date);
    $bs_13_2= \App\Models\ReportSRD::getAccountBalAllQuicken('3-33-200',$branch_id,$start_date_report,$end_date);

    $bs_14= \App\Models\ReportSRD::getAccountBalAllQuicken('3-45-100',$branch_id,$start_date_report,$end_date);

    $bs_15= \App\Models\ReportSRD::getAccountBalAllQuicken('15',$branch_id,$start_date_report,$end_date);
    $bs_16= \App\Models\ReportSRD::getAccountBalAllQuicken('16',$branch_id,$start_date_report,$end_date);

    $bs_17= \App\Models\ReportSRD::getAccountBalAllQuicken('3-42-900-001',$branch_id,$start_date_report,$end_date);
    $bs_18= \App\Models\ReportSRD::getAccountBalAllQuicken('3-44',$branch_id,$start_date_report,$end_date);

    $bs_19_1= \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-100-001',$branch_id,$start_date_report,$end_date);
    $bs_19_2= \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-100-003',$branch_id,$start_date_report,$end_date);
    $bs_19_3= \App\Models\ReportSRD::getAccountBalAllQuicken('4-02-100',$branch_id,$start_date_report,$end_date);
    $bs_19_4= \App\Models\ReportSRD::getAccountBalAllQuicken('4-04-400-001',$branch_id,$start_date_report,$end_date);
    $bs_19_5= \App\Models\ReportSRD::getAccountBalAllQuicken('4-05',$branch_id,$start_date_report,$end_date);
    $bs_19_6= \App\Models\ReportSRD::getAccountBalAllQuicken('4-07-100-002',$branch_id,$start_date_report,$end_date);
    $bs_19_7= \App\Models\ReportSRD::getAccountBalAllQuicken('4-08',$branch_id,$start_date_report,$end_date);


    // $bs_1=$bs_1_1+$bs_1_2;
    // $bs_3=$bs_3_1+$bs_3_2;
    // $bs_6=$bs_6_1_1+$bs_6_1_2+$bs_6_2_1+$bs_6_2_2+$bs_6_3_1+$bs_6_3_2;
    // $bs_9=$bs_9_1+$bs_9_2_1+$bs_9_2_2+$bs_9_2_3+$bs_9_2_4;
    // $bs_13=$bs_13_1+$bs_13_2;
    // $bs_19=$bs_19_1+$bs_19_2+$bs_19_3+$bs_19_4+$bs_19_5+$bs_19_6+$bs_19_7;

    // $total_assets=$bs_1+$bs_2+$bs_3+$bs_4+$bs_5+$bs_6+$bs_7+$bs_8;
    // $total_liabilities=$bs_9+$bs_10+$bs_11+$bs_12+$bs_13+$bs_14+$bs_15+$bs_16+$bs_17+$bs_18;
    // $liability=$total_liabilities+$bs_19;

    // $acc_1_1= \App\Models\ReportSRD::getMMacountName('1.1');
    // $acc_1_2= \App\Models\ReportSRD::getMMacountName('1.2');
    // $acc_2= \App\Models\ReportSRD::getMMacountName('2');
    // $acc_3_1= \App\Models\ReportSRD::getMMacountName('3.1');
    // $acc_3_2= \App\Models\ReportSRD::getMMacountName('3.2');
    // $acc_4= \App\Models\ReportSRD::getMMacountName('4');
    // $acc_5= \App\Models\ReportSRD::getMMacountName('5');
    // $acc_6_1= \App\Models\ReportSRD::getMMacountName('6.1');
    // $acc_6_1_1= \App\Models\ReportSRD::getMMacountName('6.1.1');
    // $acc_6_1_2= \App\Models\ReportSRD::getMMacountName('6.1.2');
    // $acc_6_2= \App\Models\ReportSRD::getMMacountName('6.2');
    // $acc_6_2_1= \App\Models\ReportSRD::getMMacountName('6.2.1');
    // $acc_6_2_2= \App\Models\ReportSRD::getMMacountName('6.2.2');
    // $acc_6_3= \App\Models\ReportSRD::getMMacountName('6.3');
    // $acc_6_3_1= \App\Models\ReportSRD::getMMacountName('6.3.1');
    // $acc_6_3_2= \App\Models\ReportSRD::getMMacountName('6.3.2');
    // $acc_7= \App\Models\ReportSRD::getMMacountName('7');
    // $acc_8= \App\Models\ReportSRD::getMMacountName('8');
    // $acc_9_1= \App\Models\ReportSRD::getMMacountName('9.1');
    // $acc_9_2= \App\Models\ReportSRD::getMMacountName('9.2');
    // $acc_9_2_1= \App\Models\ReportSRD::getMMacountName('9.2.1');
    // $acc_9_2_2= \App\Models\ReportSRD::getMMacountName('9.2.2');
    // $acc_9_2_3= \App\Models\ReportSRD::getMMacountName('9.2.3');
    // $acc_9_2_4= \App\Models\ReportSRD::getMMacountName('9.2.4');
    // $acc_10= \App\Models\ReportSRD::getMMacountName('10');
    // $acc_11= \App\Models\ReportSRD::getMMacountName('11');
    // $acc_12= \App\Models\ReportSRD::getMMacountName('12');
    // $acc_13_1= \App\Models\ReportSRD::getMMacountName('13.1');
    // $acc_13_2= \App\Models\ReportSRD::getMMacountName('13.2');
    // $acc_14= \App\Models\ReportSRD::getMMacountName('14');
    // $acc_15= \App\Models\ReportSRD::getMMacountName('15');
    // $acc_16= \App\Models\ReportSRD::getMMacountName('16');
    // $acc_17= \App\Models\ReportSRD::getMMacountName('17');
    // $acc_18= \App\Models\ReportSRD::getMMacountName('18');
    // $acc_19_1= \App\Models\ReportSRD::getMMacountName('19.1');
    // $acc_19_2= \App\Models\ReportSRD::getMMacountName('19.2');
    // $acc_19_3= \App\Models\ReportSRD::getMMacountName('19.3');
    // $acc_19_4= \App\Models\ReportSRD::getMMacountName('19.4');
    // $acc_19_5= \App\Models\ReportSRD::getMMacountName('19.5');
    // $acc_19_6= \App\Models\ReportSRD::getMMacountName('19.6');
    // $acc_19_7= \App\Models\ReportSRD::getMMacountName('19.7');
    
    $getRetainedEarningBegin = App\Models\ReportAccounting::getRetainedEarning($date_start,null,true,$branch_id);
    
    $returnEarningBeg = [];
        //dd($date_start);
    if($getRetainedEarningBegin != null){
        foreach ($getRetainedEarningBegin as $e){
            $returnEarningBeg[$e->branch_id] = $e->bal??0;
        }
    }
    //dd($end_date);
    

    $getAccountBalAll = App\Models\ReportAccounting::getAccountBalAll($acc_chart_id,$start_date_report,$end_date,[30,40,50,60,70,80],false,$branch_id);
    //dd($start_date,$end_date_retain);
    $retain_earning = App\Models\ReportAccounting::getAccountBalAll($acc_chart_id,$start_date_retain,$end_date_retain,[30,40,50,60,70,80],false,$branch_id);
        
        $bals = [];
        $branches = [];
        
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                if($r->tran_type == "payment"){
                    $bals[$r->section_id][$r->acc_chart_id][$r->branch_id] = -($r->t_cr??0);
                }else{ 
                    $bals[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) -($r->t_cr??0);
                }

                $branches[$r->branch_id] = $r->branch_id;
            }
        }
    //dd($bals);
    $net_income = $net_income??0;
    foreach ($bals as $bal_amounts) {
        foreach ($bal_amounts as $bal_amount) {
           $net_income += $bal_amount[$branch_id[0]];
        }
    }

        $bals_retain = [];
        $branches_retain = [];
        
        if($retain_earning != null){
            foreach ($retain_earning as $r){
                if($r->tran_type == "payment"){
                    $bals_retain[$r->section_id][$r->acc_chart_id][$r->branch_id] = -($r->t_cr??0);
                }else{ 
                    $bals_retain[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) -($r->t_cr??0);
                }

                $branches_retain[$r->branch_id] = $r->branch_id;
            }
        }
    //dd($bals);
    $retain = $retain??0;
    foreach ($bals_retain as $bal_amounts) {
        foreach ($bal_amounts as $bal_amount) {
            $retain += $bal_amount[$branch_id[0]];
        }
    }
    $usd1 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92-110-001',$branch_id,$start_date_report,$end_date);
    $usd2 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55-110-002',$branch_id,$start_date_report,$end_date);
    $usd3 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-510-001',$branch_id,$start_date_report,$end_date);
    $usd4 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-110-003',$branch_id,$start_date_report,$end_date);
    $usd5 = \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-110-001',$branch_id,$start_date_report,$end_date);
    $usd6 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-56-100-001',$branch_id,$start_date_report,$end_date);

    //dd($net_income,$usd1,$usd2);
    foreach ($usd1 as $bal) {
        $net_income -= $bal->amt;
    }
    foreach ($usd2 as $bal) {
        $net_income -= $bal->amt;
    }
    foreach ($usd3 as $bal) {
        $net_income -= $bal->amt;
    }
    foreach ($usd4 as $bal) {
        $net_income -= $bal->amt;
    }
    foreach ($usd5 as $bal) {
        $net_income -= $bal->amt;
    }
    foreach ($usd6 as $bal) {
        $net_income -= $bal->amt;
    }

    $usd1_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92-110-001',$branch_id,$start_date_retain,$end_date_retain);
    $usd2_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55-110-002',$branch_id,$start_date_retain,$end_date_retain);
    $usd3_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54-510-001',$branch_id,$start_date_retain,$end_date_retain);
    $usd4_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53-110-003',$branch_id,$start_date_retain,$end_date_retain);
    $usd5_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-110-001',$branch_id,$start_date_retain,$end_date_retain);
    //$usd6_retain = \App\Models\ReportSRD::getAccountBalAllQuicken('1-11-110-003',$branch_id,$start_date_retain,$end_date_retain);

    foreach ($usd1_retain as $bal) {
        $retain -= $bal->amt;
    }
    foreach ($usd2_retain as $bal) {
        $retain -= $bal->amt;
    }
   
    foreach ($usd3_retain as $bal) {
        $retain -= $bal->amt;
    }
    foreach ($usd4_retain as $bal) {
        $retain -= $bal->amt;
    }
    foreach ($usd5_retain as $bal) {
        $retain -= $bal->amt;
    }
    // foreach ($usd6_retain as $bal) {
    //     $retain -= $bal->amt;
    // }
    //dd($retain,$usd6_retain);
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
                @php
                    $cash_on_hand = 0;
                    $balance_bank = 0;
                    $cash_bank = 0;
                @endphp
               
                    @php
                        
                        $asset = $asset??0;
                        $gg = $gg??0;
                        $extra1 = $extra1??0;
                        $extra2 = $extra2??0;
                        
                        foreach($bs_1_1 as $bal){
                            $gg += $bal->amt;
                            
                            $asset  += $bal->amt;
                        }
                        foreach($bs_1_2 as $bal){
                            $balance_bank += $bal->amt;
                            
                            $asset  += $bal->amt;
                        }
                        foreach($extra_1 as $bal){
                            $extra1 += $bal->amt;
                        }
                        foreach($extra_2 as $bal){
                            $extra2 += $bal->amt;
                        }
                        if($cash_on_hand < 0){
                            $cash_on_hand = -1*($cash_on_hand);
                        }
                        if($balance_bank < 0){
                            $balance_bank = -1*($balance_bank);
                        }
                        if($extra1 < 0){
                            $extra1 = -1*($extra1);
                        }
                        if($extra2 < 0){
                            $extra2 = -1*($extra2);
                        }
                    @endphp
               
                <th class="text-right border" style="text-align: right;">1.00</th>
                <th class="text-left border" style="text-align: left;">Cash and Balances Banks</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%; text-align: right;" >
                    {{ $gg + $balance_bank + $extra1 + $extra2}}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">1.10</td>
                <td class="text-left border" style="padding-left: 20px;">Cash on Hand and in vault</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $gg + $extra1}}
                </td>
            </tr>
            {{-- <tr class="border">
                <td class="text-right border"></td>
                <td class="text-left border" style="padding-left: 20px;"></td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $extra1??0 }}
                </td>
            </tr> --}}
            <tr class="border">
                <td class="text-right border">1.20  </td>
                <td class="text-left border" style="padding-left: 20px;">Balance with banks and other Financial Institutions</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $balance_bank + $extra2}}
                </td>
            </tr>
            {{-- <tr class="border">
                <td class="text-right border"></td>
                <td class="text-left border" style="padding-left: 20px;"></td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    @if ($extra2 > 0)
                        {{ $extra2??0 }}
                    @else
                        {{(-1)*$extra2??0 }}
                    @endif
                </td>
            </tr> --}}
            @php
                $market = $market??0;
                $asset = $asset??0;
                $extra631 = $extra631??0;
                $extra632 = $extra632??0;
                $extra633 = $extra633??0;
                $extra634 = $extra634??0;
                $extra635 = $extra635??0;
                $extra636 = $extra636??0;
                $extra637 = $extra637??0;
                $extra638 = $extra638??0;
                $extra639 = $extra639??0;
                $extra6310 = $extra6310??0;

                $extra641 = $extra641??0;
                $extra642 = $extra642??0;
                $extra643 = $extra643??0;
                $extra644 = $extra644??0;
                $extra645 = $extra645??0;
                $extra646 = $extra646??0;
                $extra647 = $extra647??0;
                $extra648 = $extra648??0;
                $extra649 = $extra649??0;
                $extra6410 = $extra6410??0;
            @endphp
            {{-- <tr style="height: 30px;">
                <td></td>
            </tr> --}}
            <tr class="border">
                @if($bs_2->isNotEmpty())
                    @php
                        
                        
                        
                        foreach($bs_2 as $bal){
                            $market += $bal->amt;
                            
                        }

                    @endphp
                @endif
                <th class="text-right border" style="text-align: right;">2</th>
                <th class="text-left border" style="text-align: left;">Marketable Securities & short - term investments</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%; text-align: right;">
                    @if ($market > 0)
                        {{$market??0 }}
                    @else
                        {{(-1)*$market??0 }}
                    @endif
                    
                </th>
            </tr>
            <tr class="border">
                @php
                    $asset = $asset??0;
                    $loan_out = $loan_out??0;
                    $loan_loss = $loan_loss??0;
                @endphp
                @if($bs_3_1->isNotEmpty())
                @php
                    
                    

                    foreach($bs_3_1 as $bal){
                        $loan_out += $bal->amt;
                        $asset  += $bal->amt;
                    }
                   
                @endphp
            @endif
            @php
                    $loan_loss = $loan_out * 0.01;
                    if($loan_out < 0){
                        $loan_out = $loan_out * (-1);
                    }
                    if($loan_loss < 0){
                        $loan_loss = $loan_loss * (-1);
                    }
                    $loan_to_cus = $loan_out - $loan_loss;
            @endphp
                <th class="text-right border" style="text-align: right;">3.00</th>
                <th class="text-left border" style="text-align: left;">Loans to Customers (3.1 - 3.2)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%; text-align: right;">
                    {{ $loan_to_cus??0 }}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">3.10</td>
                <td class="text-left border" style="padding-left: 20px;">Total Loans Outstanding</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $loan_out??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">3.20</td>
                <td class="text-left border" style="padding-left: 20px;">Less: Loan Loss reserve</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $loan_loss??0 }}
                </td>
            </tr>
            @php
                $asset = $asset??0;
                $pre = $pre??0;
                $extra4 =$extra4??0;
            @endphp
            <tr class="border">
                @if($bs_4->isNotEmpty())
                    @php
                        
                        
                        foreach($bs_4 as $bal){
                            $pre += $bal->amt;
                            $asset  += $bal->amt;
                        }
                        foreach($extra_4 as $bal){
                            $extra4 += $bal->amt;
                        }
                    @endphp
                @endif
                @if($extra_4->isNotEmpty())
                    @php
                    
                        foreach($extra_4 as $bal){
                            $extra4 += $bal->amt;
                        }
                    @endphp
                @endif
                <th class="text-right border" style="text-align: right;">4.00</th>
                <th class="text-left border" style="text-align: left;">Prepayment and Other Receivable</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%; text-align: right;">
                    {{ $pre + $extra4}}
                </th>
                
            </tr>
            {{-- <tr class="border">
                <td class="text-right border"></td>
                <td class="text-left border" style="padding-left: 20px;"></td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    @if ($extra4 > 0)
                        {{ $extra4??0 }}
                    @else
                        {{(-1)*$extra4??0 }}
                    @endif
                </td>
            </tr> --}}
            <tr class="border">
                @if($bs_5->isNotEmpty())
                    @php
                        
                        $asset = $asset??0;
                        $long_term = $long_term??0;
                        foreach($bs_5 as $bal){
                            $long_term += $bal->amt;
                            $asset  += $bal->amt;
                        }

                    @endphp
                @endif
                <th class="text-right border" style="text-align: right;">5.00</th>
                <th class="text-left border" style="text-align: left;">Long-Term Investments</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%; text-align: right;">
                    {{ $long_term??0 }}
                </th>
            </tr>
            <tr class="border">
               
                    @php
                        
                        $asset = $asset??0;
                        $property = $property??0;
                        $land = $land??0;
                        $land_cost = $land_cost??0;
                        $less = $less??0;
                        $building = $building??0;
                        $building_cost = $building_cost??0;
                        $second_less = $second_less??0;
                        $fixed_asset = $fixed_asset??0;
                        $fixed_asset_cost = $fixed_asset_cost??0;
                        $third_less = $third_less??0;


                        foreach($bs_6_1 as $bal){
                            $land += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_1_1 as $bal){
                            $land_cost += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_1_2 as $bal){
                            $less += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_1_2 as $bal){
                            $less += $bal->amt;
                            $property += $bal->amt;
                            
                        }
                        
                        foreach($bs_6_2 as $bal){
                            $building += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_2_1 as $bal){
                            $building_cost += $bal->amt;
                            $property += $bal->amt;
                           
                        }

                        foreach($bs_6_2_2 as $bal){
                            $second_less += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_3 as $bal){
                            $fixed_asset += $bal->amt;
                            $property += $bal->amt;
                           
                        }

                        foreach($bs_6_3_1 as $bal){
                            $fixed_asset_cost += $bal->amt;
                            $property += $bal->amt;
                            
                        }

                        foreach($bs_6_3_2 as $bal){
                            $third_less += $bal->amt;
                            $property += $bal->amt;
                           
                        }
                        
                        
                     @endphp
                @if($extra63_1->isNotEmpty())
                    @php
                        $extra631 = $extra631??0;
                      
                        foreach($extra63_1 as $bal){
                            $extra631 += $bal->amt;
                           
                        }

                    @endphp
                @endif
                @if($extra63_2->isNotEmpty())
                    @php
                        $extra632 = $extra632??0;
                      
                        foreach($extra63_2 as $bal){
                            $extra632 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_5->isNotEmpty())
                    @php
                        $extra635 = $extra635??0;
                      
                        foreach($extra63_5 as $bal){
                            $extra635 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_3->isNotEmpty())
                    @php
                        $extra633 = $extra633??0;
                      
                        foreach($extra63_3 as $bal){
                            $extra633 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_4->isNotEmpty())
                    @php
                        $extra634 = $extra634??0;
                      
                        foreach($extra63_4 as $bal){
                            $extra634 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_6->isNotEmpty())
                    @php
                        $extra636 = $extra636??0;
                      
                        foreach($extra63_6 as $bal){
                            $extra636 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_7->isNotEmpty())
                    @php
                        $extra637 = $extra637??0;
                      
                        foreach($extra63_7 as $bal){
                            $extra637 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra63_8->isNotEmpty())
                    @php
                        $extra638 = $extra638??0;
                      
                        foreach($extra63_8 as $bal){
                            $extra638 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                
                @if($extra63_9->isNotEmpty())
                    @php
                        $extra639 = $extra639??0;
                      
                        foreach($extra63_9 as $bal){
                            $extra639 += $bal->amt;
                           
                        }

                    @endphp
                @endif
                @if($extra63_10->isNotEmpty())
                    @php
                        $extra6310 = $extra6310??0;
                      
                        foreach($extra63_10 as $bal){
                            $extra6310 += $bal->amt;
                            
                        }

                    @endphp
                @endif
                @if($extra64_1->isNotEmpty())
                @php
                    $extra641 = $extra641??0;
                  
                    foreach($extra64_1 as $bal){
                        $extra641 += $bal->amt;
                        
                    }

                @endphp
            @endif
            @if($extra64_2->isNotEmpty())
                @php
                    $extra642 = $extra642??0;
                
                    foreach($extra64_2 as $bal){
                        $extra642 += $bal->amt;
                       
                    }

                @endphp
            @endif
            @if($extra64_3->isNotEmpty())
                @php
                    $extra643 = $extra643??0;
                
                    foreach($extra64_3 as $bal){
                        $extra643 += $bal->amt;
                        
                    }

                @endphp
            @endif
            @if($extra64_4->isNotEmpty())
                @php
                    $extra644 = $extra644??0;
                  
                    foreach($extra64_4 as $bal){
                        $extra634 += $bal->amt;
                        
                    }

                @endphp
            @endif
            @if($extra64_5->isNotEmpty())
                @php
                    $extra645 = $extra645??0;
                
                    foreach($extra64_5 as $bal){
                        $extra645 += $bal->amt;
                       
                    }

                @endphp
            @endif
            @if($extra64_6->isNotEmpty())
                @php
                    $extra646 = $extra646??0;
                  
                    foreach($extra64_6 as $bal){
                        $extra646 += $bal->amt;
                        
                    }

                @endphp
            @endif
            @if($extra64_7->isNotEmpty())
                @php
                    $extra647 = $extra647??0;
                  
                    foreach($extra64_7 as $bal){
                        $extra647 += $bal->amt;
                        
                    }

                @endphp
            @endif
            @if($extra64_8->isNotEmpty())
                @php
                    $extra648 = $extra648??0;
                
                    foreach($extra64_8 as $bal){
                        $extra648 += $bal->amt;
                       
                    }

                @endphp
            @endif
            @if($extra64_9->isNotEmpty())
                @php
                    $extra649 = $extra649??0;
                  
                    foreach($extra64_9 as $bal){
                        $extra649 += $bal->amt;
                       
                    }

                @endphp
            @endif
            @if($extra64_10->isNotEmpty())
                @php
                    $extra6410 = $extra6410??0;
                  
                    foreach($extra64_10 as $bal){
                        $extra6410 += $bal->amt;
                       
                    }

                @endphp
            @endif
            @php
                $sixthreetwo = $third_less + $extra641 + $extra642 + $extra643 + $extra644 + $extra645 + $extra646 + $extra647 + $extra648;
                $sixthreeone = $fixed_asset_cost + $extra631 + $extra632 + $extra633 + $extra634 + $extra635 + $extra636 + $extra637 + $extra638 + $extra639 + $extra6310 + $extra649 + $extra6410;
                $sixthree = $sixthreetwo + $sixthreeone;
            @endphp
                <th class="text-right border" style="text-align: right;">6.00</th>
                <th class="text-left border" style="text-align: left;">Property and Equipment</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{$land + $sixthree + $building }}
                </th>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1</td>
                <td class="text-left border" style="padding-left: 20px;">Land</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $land??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1.1</td>
                <td class="text-left border" style="padding-left: 40px;">Land at cost</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $land_cost??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.1.2</td>
                <td class="text-left border" style="padding-left: 40px;">Less:accumulated Depreciation</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $less??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2</td>
                <td class="text-left border" style="padding-left: 20px;">Buildings</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $building??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2.1</td>
                <td class="text-left border" style="padding-left: 40px;">Building at cost</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $building_cost??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.2.2</td>
                <td class="text-left border" style="padding-left: 40px;">Less:accumulated Depreciation</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $second_less??0 }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.3</td>
                <td class="text-left border" style="padding-left: 20px;">Other Fixed Assets</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $sixthreeone + $sixthreetwo }}
                </td>
            </tr>
            <tr class="border">
                <td class="text-right border">6.3.1</td>
                <td class="text-left border" style="padding-left: 40px;">Other Fixed Assets at cost</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{$sixthreeone}}
                </td>

            </tr>
            
               
            <tr class="border">
               
                <td class="text-right border">6.3.2</td>
                <td class="text-left border" style="padding-left: 40px;">Less:accumulated Depreciation</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{$sixthreetwo}}
                </td>

            </tr>
            
            <tr class="border">
                @if($bs_7->isNotEmpty())
                    @php
                        
                        $asset = $asset??0;
                        $other_asset = $other_asset??0;
                        foreach($bs_7 as $bal){
                            $other_asset += $bal->amt;
                            $asset  += $bal->amt;
                        }

                    @endphp
                @endif
                <th class="text-right border" style="text-align: right;">7</th>
                <th class="text-left border" style="text-align: left;">Other Assets</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ $other_asset??0 }}
                </th>

            </tr>
            <tr class="border">
                @if($bs_8->isNotEmpty())
                    @php
                        
                        $asset = $asset??0;
                        $rec = $rec??0;
                        foreach($bs_8 as $bal){
                            $rec += $bal->amt;
                            $asset  += $bal->amt;
                        }

                    @endphp
                @endif
                <th class="text-right border" style="text-align: right;">8.00</th>
                <th class="text-left border" style="text-align: left;">Interest Receivable</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ $rec??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                   $total_all =  $gg + $balance_bank + $extra1 + $extra2 + $loan_to_cus + $pre + $extra4 + $long_term + $land + $sixthree + $building  + $rec;
                @endphp
                <th class="text-right border" style="text-align: right;"></th>
                <th class="text-left border">Total Assets (1+3+4+5+6+8)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: center;">
                    {{ $total_all??0 }}
                </th>
            </tr>
            <tr class="border">
                <th colspan="3" class="text-center border">Liabilities</th>
            </tr>
            <tr class="border">
                @php
                    $liability = $liability??0;
                    $saving_depo = $saving_depo??0;
                    $total_plus = $total_plus??0;
                @endphp
                        @if($bs_9_1->isNotEmpty())
                            @php
                                
                                foreach($bs_9_1 as $bal){
                                    $saving_depo += $bal->amt;
                                    $liability  += $bal->amt;
                                    $total_plus += $bal->amt;
                                }

                            @endphp
                        @endif
                        @php
                        $liability = $liability??0;
                        $saving_vol = $saving_vol??0;
                @endphp
                @if($bs_9_2->isNotEmpty())
                    @php
                        
                        
                        foreach($bs_9_2 as $bal){
                            $saving_vol += $bal->amt;
                            $liability  += $bal->amt;
                            $total_plus += $bal->amt;
                        }

                    @endphp
                @endif
                @php
                    
                    $liability = $liability??0;
                    $saving_deposit = $saving_deposit??0;
                @endphp
                @if($bs_9_2_1->isNotEmpty())
                    @php
                        
                        foreach($bs_9_2_1 as $bal){
                            $saving_deposit += $bal->amt;
                            $liability  += $bal->amt;
                            $total_plus += $bal->amt;
                        }

                    @endphp
                @endif
                @php
                     
                    $liability = $liability??0;
                    $demand_deposit = $demand_deposit??0;
                @endphp
            @if($bs_9_2_2->isNotEmpty())
                @php
                    
                    foreach($bs_9_2_2 as $bal){
                        $demand_deposit += $bal->amt;
                        $liability  += $bal->amt;
                        $total_plus += $bal->amt;
                    }

                @endphp
            @endif
            @php
                $liability = $liability??0;
                $term_deposit = $term_deposit??0;
            @endphp
            @if($bs_9_2_3->isNotEmpty())
                @php
                    
                    
                    foreach($bs_9_2_3 as $bal){
                        $term_deposit += $bal->amt;
                        $liability  += $bal->amt;
                        $total_plus += $bal->amt;
                    }

                @endphp
            @endif
            @php
                $liability = $liability??0;
                $other_deposit = $other_deposit??0;
            @endphp
            @if($bs_9_2_4->isNotEmpty())
                @php
                    
                   
                    foreach($bs_9_2_4 as $bal){
                        $other_deposit += $bal->amt;
                        $liability  += $bal->amt;
                        $total_plus += $bal->amt;
                    }

                @endphp
            @endif
            @php
                    $liability = $liability??0;
                    $depo_bank = $depo_bank??0;
                @endphp
            @if($bs_10->isNotEmpty())
                @php
                    
                    
                    foreach($bs_10 as $bal){
                        $depo_bank += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
            @php
                    $liability = $liability??0;
                    $payable = $payable??0;
                @endphp
            @if($bs_10->isNotEmpty())
                @php
                    
                   
                    foreach($bs_10 as $bal){
                        
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">9</th>
                <th class="text-left border" style="text-align: left;">Customers' Deposits</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    @if ($total_plus > 0)
                        {{ $total_plus??0 }}
                    @else
                        {{(-1)*$total_plus??0 }}
                    @endif
                </th>

            </tr>
            <tr class="border">
                
                <td class="text-right border">9.1</td>
                <td class="text-left border" style="padding-left: 20px;">Compulsory Saving deposits from clients</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$saving_depo}}
                </td>

            </tr>
            <tr class="border">
               
                <td class="text-right border">9.2</td>
                <td class="text-left border" style="padding-left: 20px;">Voluntary Savings</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$saving_vol??0 }}
                </td>

            </tr>
            <tr class="border">
                
                <td class="text-right border">9.2.1</td>
                <td class="text-left border" style="padding-left: 40px;">Saving deposits</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$saving_deposit??0 }}
                </td>

            </tr>
            <tr class="border">
               
                <td class="text-right border">9.2.2</td>
                <td class="text-left border" style="padding-left: 40px;">Demand deposits</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$demand_deposit??0 }}
                </td>

            </tr>
            <tr class="border">
                
                <td class="text-right border">9.2.3</td>
                <td class="text-left border" style="padding-left: 40px;">Term deposits</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$term_deposit??0 }}
                </td>

            </tr>
            <tr class="border">
                
                <td class="text-right border">9.2.4</td>
                <td class="text-left border" style="padding-left: 40px;">Other deposits</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$other_deposit??0 }}
                </td>

            </tr>
            <tr class="border">
                
                <th class="text-right border" style="text-align: right;">10</th>
                <th class="text-left border" style="text-align: left;">Deposits from Banks and Other Financial Institutions</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$depo_bank??0 }}
                </th>

            </tr>

            @if($bs_11->isNotEmpty())
                @php
                    
                   
                    foreach($bs_11 as $bal){
                        
                        $payable  += $bal->amt;
                    }

                @endphp
            @endif
            <tr class="border">
                
                <th class="text-right border" style="text-align: right;">11</th>
                <th class="text-left border" style="text-align: left;">Account Payable</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$payable??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                     
                    $liability = $liability??0;
                    $accured = $accured??0;
                @endphp
            @if($bs_12->isNotEmpty())
                @php
                   
                    foreach($bs_12 as $bal){
                        $accured += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">12</th>
                <th class="text-left border" style="text-align: left;">Accrued Expenses</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$accured??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                     $liability = $liability??0;
                     $long_borrow = $long_borrow??0;
                     $fin_borrow = $fin_borrow??0;
                     $nonfin_borrow = $nonfin_borrow??0;
                @endphp
           @if($bs_13_1->isNotEmpty())
                @php
                    
                    
                    foreach($bs_13_1 as $bal){
                        $fin_borrow += $bal->amt;
                        $liability  += $bal->amt;
                        $long_borrow += $bal->amt;
                    }

                @endphp
            @endif
            @if($bs_13_2->isNotEmpty())
                @php
                    
                   
                    foreach($bs_13_2 as $bal){
                        $nonfin_borrow += $bal->amt;
                        $liability  += $bal->amt;
                        //$long_borrow += $bal->amt;
                    }
                    
                @endphp
            @endif
            @if($bs_13->isNotEmpty())
            @php
                
               
                foreach($bs_13 as $bal){
                    // $nonfin_borrow += $bal->amt;
                    // $liability  += $bal->amt;
                    $long_borrow = $bal->amt;
                }
                
            @endphp
        @endif
                <th class="text-right border" style="text-align: right;">13</th>
                <th class="text-left border" style="text-align: left;">Long-term Borrowing</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$long_borrow??0 }}
                </th>

            </tr>
            <tr class="border">
            
                <td class="text-right border">13.1</td>
                <td class="text-left border" style="padding-left: 20px;">Borrowings from Financial Institutions</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$fin_borrow??0 }}
                </td>

            </tr>
            <tr class="border">
            
                <td class="text-right border">13.2</td>
                <td class="text-left border" style="padding-left: 20px;">Borrowings from Non-Financia Institutions</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$nonfin_borrow??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                     $liability = $liability??0;
                    $deferred_income = $deferred_income??0;
                @endphp
            @if($bs_14->isNotEmpty())
                @php
                    
                   
                    foreach($bs_14 as $bal){
                        $deferred_income += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">14</th>
                <th class="text-left border" style="text-align: left;">Deferred Grant Income</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$deferred_income??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                     $liability = $liability??0;
                     $clear = $clear??0;
                @endphp
            @if($bs_15->isNotEmpty())
                @php
                    
                   
                    foreach($bs_15 as $bal){
                        $clear += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">15</th>
                <th class="text-left border" style="text-align: left;">Suspense,Clearing and Inter-branch Account</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$clear??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                    $liability = $liability??0;
                    $other_liabli = $other_liabli??0;
                @endphp
            @if($bs_16->isNotEmpty())
                @php
                    
                    
                    foreach($bs_16 as $bal){
                        $other_liabli += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">16</th>
                <th class="text-left border" style="text-align: left;">Other Liabilities</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$other_liabli??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                     $liability = $liability??0;
                     $interest_payable = $interest_payable??0;
                @endphp
            @if($bs_17->isNotEmpty())
                @php
                    
                   
                    foreach($bs_17 as $bal){
                        $interest_payable += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">17</th>
                <th class="text-left border" style="text-align: left;">Interest Payablel for Deposit</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$interest_payable??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                    $liability = $liability??0;
                    $interest_payable_borrow = $interest_payable_borrow??0;
                @endphp
            @if($bs_18->isNotEmpty())
                @php
                    
                    
                    foreach($bs_18 as $bal){
                        $interest_payable_borrow += $bal->amt;
                        $liability  += $bal->amt;
                    }

                @endphp
            @endif
                <th class="text-right border" style="text-align: right;">18</th>
                <th class="text-left border" style="text-align: left;">Interest Payablel for Borrowing</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    {{ -$interest_payable_borrow??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                    $total_910 = $total_plus + $depo_bank + $payable + $accured + $long_borrow + $deferred_income + $clear + $other_liabli + $interest_payable + $interest_payable_borrow;
                @endphp
                <th class="text-right border"></th>
                <th class="text-left border">Total Liabilities (9+10+11+12+13+14+15+16+17+18)</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$total_910??0 }}
                </th>

            </tr>
            <tr class="border">
                <th colspan="3" class="text-center border">Equity</th>
            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     
                @endphp
                <th class="text-right border" style="text-align: right;">19</th>
                <th class="text-left border" style="text-align: left;">Equity Accounts</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;text-align: right;">
                    
                </th>
            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     $paid_up = $paid_up??0;
                @endphp
            @if($bs_19_1->isNotEmpty())
                @php
                    
                   
                    foreach($bs_19_1 as $bal){
                        $paid_up += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
                <td class="text-right border">19.1</td>
                <td class="text-left border" style="padding-left: 20px;">Paid up Capital</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$paid_up??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     $premium = $premium??0;
                @endphp
            @if($bs_19_2->isNotEmpty())
                @php
                    
                   
                    foreach($bs_19_2 as $bal){
                        $premium += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
                <td class="text-right border">19.2</td>
                <td class="text-left border" style="padding-left: 20px;">Premium on share capital</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$premium??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                    $equitity = $equitity??0;
                    $donated = $donated??0;
                @endphp
            @if($bs_19_3->isNotEmpty())
                @php
                    
                    
                    foreach($bs_19_3 as $bal){
                        $donated += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
                <td class="text-right border">19.3</td>
                <td class="text-left border" style="padding-left: 20px;">Donated Capital</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$donated??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     $hybird = $hybird??0;
                @endphp
            @if($bs_19_4->isNotEmpty())
                @php
                    
                   
                    foreach($bs_19_4 as $bal){
                        $hybird += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
                <td class="text-right border">19.4</td>
                <td class="text-left border" style="padding-left: 20px;">Hybrid Capital Instruments</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$hybird??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     $reserves = $reserves??0;
                @endphp
            @if($bs_19_5->isNotEmpty())
                @php
                    
                   
                    foreach($bs_19_5 as $bal){
                        $reserves += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
                <td class="text-right border">19.5</td>
                <td class="text-left border" style="padding-left: 20px;">Reserves Funds</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ -$reserves??0 }}
                </td>

            </tr>
            <tr class="border">
                @php
                     $equitity = $equitity??0;
                     $retained_earning = $retained_earning??0;
                @endphp
            @if($bs_19_6->isNotEmpty())
                @php
                    
                   
                    foreach($bs_19_6 as $bal){
                        $retained_earning += $bal->amt;
                        $equitity  += $bal->amt;
                    }

                @endphp
            @endif
           
            @php 
                //dd($returnEarningBeg,$branch_id);
                $retain = $retain??0;
            @endphp
           
                <td class="text-right border">19.6</td>
                <td class="text-left border" style="padding-left: 20px;">Retained Earning</td>
                @if($retain > 0)
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ RemoveDecimal(number_format($retain,2)) }}
                </td>
                @else
                    <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                        {{ RemoveDecimal(number_format(-$retain,2)) }}
                    </td>
                @endif

            </tr>
            <tr class="border">
                @php
                    $profit_1 = $arr_profit[$branch_id[0]]??0;
                     $equitity = $equitity??0;
                     $surplus = $surplus??0;
                     if($net_income < 0){
                         $net = -$net_income;
                     }
                     else{
                        $net = $net_income;
                     }

                     if($retain < 0){
                         $retain = -$retain;
                     }
                     else{
                        $retain = $retain;
                     }
                     //dd($net,$retain,$equitity);
                @endphp
            
                <td class="text-right border">19.70</td>
                <td class="text-left border" style="padding-left: 20px;">Year to date undistrubuted surplus</td>
                <td class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    @php
                        $total_eq = $equitity + $net + $retain;
                    @endphp
                    {{ $total_eq }}
                   
                </td>

            </tr>
            <tr class="border">
                <td class="text-right border"></td>
                <td class="text-left border">Total Equity</td>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{-- @php
                        $total_eq = $equitity + $net + $retain;
                    @endphp --}}
                    {{ $net??0 }}
                </th>

            </tr>
            <tr class="border">
                @php
                    $net = $net??0;
                    if($net < 0){
                        $equitity = -1*($equitity);
                    }
                    if($total_910 < 0){
                        $total_910 = -1*($total_910);
                    }
                    if($net < 0){
                        $net = -1*($net);
                    }
                    if($retain < 0){
                        $retain = -1*($retain);
                    }
                    //dd($total_910,$total_eq);
                    $total = $total_910 + $net;
                @endphp
                <th class="text-right border" style="text-align: right;">20</th>
                <th class="text-left border">Total Liabilities and Total Equity</th>
                <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">
                    {{ $total??0 }}
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
