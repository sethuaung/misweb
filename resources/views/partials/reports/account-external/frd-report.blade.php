<?php
    
?>
<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Business Performance Report Summary  For Aug 2020','start_date'=>$start_date,'end_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:1%;">
                    No.
                </th>
                <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:25%;">
                    အကြောင်းအရာ
                </th>
                <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:11%;">
                    Particular
                </th>
                <th class="text-center" colspan="2" style="border: solid windowtext 1.0pt;width:10%;">
                   Opening
                </th>
                <th class="text-center" colspan="3" style="border: solid windowtext 1.0pt;width:12%;">
                    Aug '2020
                </th>
                <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:12%;">
                    from 1 Oct' 2019  to 31 Aug'2020
                </th>
            </tr>
            <tr>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    No  
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    Amount
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    No
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    Amount
                </th>
                <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                    No
                </th>
                
            </tr>
        </thead>
        <tbody>
        @php
            $previous_start_date = date('Y-m-d', strtotime($start_date." -1 month"));
            $previous_end_date = date('Y-m-d', strtotime($end_date." -1 month"));
            //dd($previous_start_date,$previous_end_date);
            $opening = DB::table('clients')
                                ->where('register_date','<=',$previous_end_date)
                                ->distinct('commune_id')
                                ->count('commune_id');
            $township_current = DB::table('clients')
                                ->where('register_date','>=',$start_date)
                                ->where('register_date','<=',$end_date)
                                ->distinct('commune_id')
                                ->count('commune_id');

            $opening_ward = DB::table('clients')
                                ->where('register_date','<=',$previous_end_date)
                                ->distinct('ward_id')
                                ->count('ward_id');
            $ward_current = DB::table('clients')
                                ->where('register_date','>=',$start_date)
                                ->where('register_date','<=',$end_date)
                                ->distinct('ward_id')
                                ->count('ward_id');
            $opening_village = DB::table('clients')
                                ->where('register_date','<=',$previous_end_date)
                                ->distinct('village_id')
                                ->count('village_id');
            $village_current = DB::table('clients')
                                ->where('register_date','>=',$start_date)
                                ->where('register_date','<=',$end_date)
                                ->distinct('village_id')
                                ->count('village_id');                            
            //dd($township);                    
        @endphp
        <tr class="border">
            <th class="text-right border">1</th>
            <th class="text-left border">မြို့နယ်</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Township</th>
            <th class="text-right border">{{$opening}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$township_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$township_current + $opening}}</th>
            <th class="text-left border">-</th>
        </tr>
        <tr class="border">
            <td class="text-right border">2</td>
            <th class="text-left border">ရပ်ကွက်</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Ward</th>
            <th class="text-right border">{{$opening_ward}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$ward_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$ward_current + $opening_ward}}</th>
            <th class="text-left border">-</th>
                
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <th class="text-left border"></th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Sub Ward</th>
            <th class="text-right border"> 0 </th>
            <th class="text-left border">-</th>
            <th class="text-left border"> 0 </th>
            <th class="text-left border">-</th>
            <th class="text-left border"> 0 </th>
            <th class="text-left border">-</th>
                
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border">3</td>
            <th class="text-left border">ကျေးရွာ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Village</th>
            <th class="text-right border">{{$opening_village}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$village_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$opening_village + $village_current}}</th>
            <th class="text-left border">-</th>
                
            </td>
        </tr>
        @php
            $number_of_client = DB::table('clients')
                                ->where('register_date','>=',$start_date)
                                ->where('register_date','<=',$end_date)
                                ->distinct('id')
                                ->count('id');
            $previous_client = DB::table('clients')
                                ->where('register_date','<=',$previous_end_date)
                                ->distinct('id')
                                ->count('id');                    
        @endphp
        <tr class="border">
            <td class="text-right border">4</td>
            <th class="text-left border">အသင်းသားအရေအတွက်</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Number of Client</th>
            <th class="text-right border">{{$previous_client}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$number_of_client}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$number_of_client + $previous_client}}</th>
            <th class="text-left border">-</th>
                
            </td>
        </tr>
        @php
             $capital_now= \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-100',$branch_id,$start_date,$end_date);
             $capital_revious= \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-100',$branch_id,$previous_start_date,$previous_end_date);
             $amount = $amount??0;
             $amount_previous = $amount_previous??0;
             foreach($capital_now as $bal){
                 $amount += $bal->amt;
             }
             foreach($capital_revious as $bal){
                 $amount_previous += $bal->amt;
             }
        @endphp
        <tr class="border">
            <td class="text-right border">5</td>
            <th class="text-left border">မတည်ငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Paid up Capital</th>
            <th class="text-right border">-</th>
            <th class="text-left border">{{$amount_previous}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$amount}}</th>
            <th class="text-left border">{{$amount_previous + $amount}}</th>
            <th class="text-left border">-</th>
                
            </td>
        </tr>
        @php
             $disbursement_amount_current = App\Models\Loan::where('status_note_date_activated','>=',$start_date)->where('status_note_date_activated','<=',$end_date)->sum('loan_amount');
             $disbursement_amount_previous = App\Models\Loan::where('status_note_date_activated','>=',$previous_start_date)->where('status_note_date_activated','<=',$previous_end_date)->sum('loan_amount');
        @endphp
        <tr class="border">
            <td class="text-right border">6</td>
            <th class="text-left border">ထုတ်ချေးငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Disbursement</th>
            <th class="text-right border">-</th>
            <th class="text-left border">{{$disbursement_amount_previous}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$disbursement_amount_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$disbursement_amount_current + $disbursement_amount_previous}}</th>
                
            </td>
        </tr>
        @php
             $loan_collected_current = App\Models\LoanPayment::where('payment_date','>=',$start_date)->where('payment_date','<=',$end_date)->sum('total_payment');
             $loan_collected_previous =  App\Models\LoanPayment::where('payment_date','<=',$previous_end_date)->sum('total_payment');
        @endphp
        <tr class="border">
            <td class="text-right border">7</td>
            <th class="text-left border">ပြန်ဆပ်ငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Repayment</th>
            <th class="text-right border">-</th>
            <th class="text-left border">{{$loan_collected_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$loan_collected_previous}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$loan_collected_current + $loan_collected_previous}}</th>
                
            </td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <th class="text-left border">ပေးလျော်ကြေးငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Compensations</th>
            <th class="text-right border">-</th>
            <th class="text-left border">0</th>
            <th class="text-left border">-</th>
            <th class="text-left border">0</th>
            <th class="text-left border">-</th>
            <th class="text-left border">0</th>
                
            </td>
        </tr>
        @php
            $loan_release_total_previous =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])    
                                                ->where('status_note_date_activated','<=',$previous_end_date)
                                                ->sum('loan_amount');
            $principal_collection_total_previous = \App\Models\LoanPayment::where('payment_date','<=',$previous_end_date)->sum('principle');
            $total_outstanding_previous = ($loan_release_total_previous - $principal_collection_total_previous);

            $loan_release_total_current =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])    
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->where('status_note_date_activated','>=',$start_date)
                                                ->sum('loan_amount');
            $principal_collection_total_current = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('principle');
            $total_outstanding_current = ($loan_release_total_current - $loan_release_total_current);
        @endphp
        <tr class="border">
            <td class="text-right border">8</td>
            <th class="text-left border">ပြန်ဆပ်ရန်ကျန်ငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Outstanding</th>
            <th class="text-right border">-</th>
            <th class="text-left border">{{$total_outstanding_previous}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$total_outstanding_current}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$total_outstanding_current}}</th>
                
            </td>
        </tr>
        @php
             $interest_client_now= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-700',$branch_id,$start_date,$end_date);
             $interest_client_previous= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-700',$branch_id,$previous_start_date,$previous_end_date);
             $interest = $interest??0;
             $interest_previous = $interest_previous??0;
             foreach($interest_client_now as $bal){
                 $interest += $bal->amt;
             }
             foreach($interest_client_previous as $bal){
                 $interest_previous += $bal->amt;
             }
        @endphp
        <tr class="border">
            <td class="text-right border">9</td>
            <th class="text-left border">အသင်းသားများထံမှအတိုးရငွေ</th>
            <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Interest Income From Client</th>
            <th class="text-right border">-</th>
            <th class="text-left border">{{$interest_previous}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$interest}}</th>
            <th class="text-left border">-</th>
            <th class="text-left border">{{$interest + $interest_previous}}</th>
                
            </td>
        </tr>
        @php
           $compulsory_now =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
                                                                ->where(getLoanTable().'.status_note_date_activated','>=',$start_date)
                                                                ->where(getLoanTable().'.status_note_date_activated','<=',$end_date)
                                                                ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
                                                                ->sum(getLoanCompulsoryTable().'.principles');
           $compulsory_previous =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
                                                                ->where(getLoanTable().'.status_note_date_activated','>=',$previous_start_date)
                                                                ->where(getLoanTable().'.status_note_date_activated','<=',$previous_end_date)
                                                                ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
                                                                ->sum(getLoanCompulsoryTable().'.principles'); 
        @endphp
    <tr class="border">
        <td class="text-right border">10</td>
        <th class="text-left border">စုဆောင်းငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Compulsory Saving</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$compulsory_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$compulsory_now}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$compulsory_now + $compulsory_previous}}</th>
            
        </td>
    </tr>
    @php
        $withdraw_now =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
                                                                ->where('tran_date','<=',$end_date)
                                                                ->where('train_type','=','withdraw')
                                                                ->sum('amount');
        $withdraw_previous =\App\Models\CompulsorySavingTransaction::where('tran_date','<=',$previous_end_date)
                                                                ->where('train_type','=','withdraw')
                                                                ->sum('amount');                                                        
    @endphp
    <tr class="border">
        <td class="text-right border"></td>
        <th class="text-left border">စုဆောင်းငွေပြန်ထုတ်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Compulsory Saving Withdraw</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$withdraw_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$withdraw_now}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$withdraw_now + $withdraw_previous}}</th>
            
        </td>
    </tr>
    @php
       $compulsory_now = $compulsory_now??0; 
       $withdraw_now = $withdraw_now??0;
       $balance_now = $compulsory_now + $withdraw_now;

       $compulsory_previous = $compulsory_previous??0; 
       $withdraw_previous = $withdraw_previous??0;
       $balance_previous = $compulsory_now + $withdraw_now;
    @endphp
    <tr class="border">
        <td class="text-right border"></td>
        <th class="text-left border">စုဆောင်းငွေလက်ကျန်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Compulsory Saving Balance</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$balance_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$balance_now}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$balance_now}}</th>
            
        </td>
    </tr>
    @php
        $saving_cp_interest_now =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
            ->where('tran_date','<=',$end_date)
            ->where('train_type','=','accrue-interest')
            ->sum('amount');
        $saving_cp_interest_previous =\App\Models\CompulsorySavingTransaction::where('tran_date','<=',$previous_end_date)
            ->where('train_type','=','accrue-interest')
            ->sum('amount');  
    @endphp
    <tr class="border">
        <td class="text-right border">11</td>
        <th class="text-left border">စုဆောင်းငွေအပေါ်အတိုးပေးရငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Interest on Compulsory Saving</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$saving_cp_interest_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$saving_cp_interest_now}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$saving_cp_interest_now + $saving_cp_interest_previous}}</th>
            
        </td>
    </tr>
    @php
        
    @endphp
    <tr class="border">
        <td class="text-right border">12</td>
        <th class="text-left border">လူမှုရန်ပုံငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Beneficiary Welfare Fund</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
            
        @endphp
        <td class="text-right border"></td>
        <th class="text-left border">လူမှုရန်ပုံငွေမှ ပြန်ပေးငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Beneficiary Walfare Fund Withdraw</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        <td class="text-right border"></td>
        <th class="text-left border">လူမှုရန်ပုံငွေလက်ကျန်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Beneficiary Walfare Fund Balance</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
             $one= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-700',$branch_id,$start_date,$end_date);
             $two= \App\Models\ReportSRD::getAccountBalAllQuicken('5-71-100',$branch_id,$start_date,$end_date);
             $three= \App\Models\ReportSRD::getAccountBalAllQuicken('5-73-100',$branch_id,$start_date,$end_date);
             
             $one_previous= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-700',$branch_id,$previous_start_date,$previous_end_date);
             $two_previous= \App\Models\ReportSRD::getAccountBalAllQuicken('5-71-100',$branch_id,$previous_start_date,$previous_end_date);
             $three_previous = \App\Models\ReportSRD::getAccountBalAllQuicken('5-73-100',$branch_id,$previous_start_date,$previous_end_date);

             $income = $income??0;
             foreach($one as $bal){
                 $income += $bal->amt;
             }
             foreach($two as $bal){
                 $income += $bal->amt;
             }
             foreach($three as $bal){
                 $income += $bal->amt;
             }

             $income_previous = $income_previous??0;
             foreach($one_previous as $bal){
                 $income_previous += $bal->amt;
             }
             foreach($two_previous as $bal){
                 $income_previous += $bal->amt;
             }
             foreach($three_previous as $bal){
                 $income_previous += $bal->amt;
             }
        @endphp
        <td class="text-right border">13</td>
        <th class="text-left border">oင်ငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Income</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{(-1)*($income_previous)}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{(-1)*($income)}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{(-1)*($income_previous + $income)}}</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
             $ot_income= \App\Models\ReportSRD::getAccountBalAllQuicken('5-75-100',$branch_id,$start_date,$end_date);
             $ot_income_previous= \App\Models\ReportSRD::getAccountBalAllQuicken('5-75-100',$branch_id,$previous_start_date,$previous_end_date);
             $other_income = 0;
             $other_income_previous = 0;
             foreach($ot_income as $bal){
                $other_income += $bal->amt;
             }
             foreach($ot_income_previous as $bal){
                $other_income_previous += $bal->amt;
             }
        @endphp
        <td class="text-right border"></td>
        <th class="text-left border">တခြားoင်ငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Other Income</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$other_income_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$other_income}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$other_income_previous + $other_income}}</th>
            
        </td>
    </tr>
    <tr class="border">
        <td class="text-right border"></td>
        <th class="text-left border">oင်ကြေ:</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Other Fees</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
            $loan_writeoff_principle = App\Models\DisburseWrittenOff::where('disbursement_status','Written-Off')->where('status_note_date_activated','>=',$start_date)
                                                            ->where('status_note_date_activated','<=',$end_date)->sum('principle_receivable');
            $loan_writeoff_interest = App\Models\DisburseWrittenOff::where('disbursement_status','Written-Off')->where('status_note_date_activated','>=',$start_date)
                                                            ->where('status_note_date_activated','<=',$end_date)->sum('interest_receivable');
            $loan_writeoff = $loan_writeoff_principle??0 + $loan_writeoff_interest??0;                                                                                               
            $principle_previous = App\Models\DisburseWrittenOff::where('disbursement_status','Written-Off')->where('status_note_date_activated','>=',$previous_start_date)
                                                            ->where('status_note_date_activated','<=',$previous_end_date)->sum('principle_receivable');  
            $interest_previous = App\Models\DisburseWrittenOff::where('disbursement_status','Written-Off')->where('status_note_date_activated','>=',$previous_start_date)
                                                            ->where('status_note_date_activated','<=',$previous_end_date)->sum('interest_receivable');   
            $loan_writeoff_previous = $principle_previous??0 + $interest_previous??0;                                                                                          
        @endphp                                                
        <td class="text-right border">14</td>
        <th class="text-left border">စာရင်းမှပါယ်ဖျက်ချေးငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Write Off</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$loan_writeoff_previous}}</th> 
        <th class="text-left border">-</th>
        <th class="text-left border">{{$loan_writeoff}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$loan_writeoff_previous + $loan_writeoff}}</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
            $expense =\App\Models\GeneralJournalDetail::where('j_detail_date','>=',$start_date)
                        ->where('j_detail_date','<=',$end_date)
                        ->where('tran_type','=','expense')
                        ->sum('cr');
            $expense_previous =\App\Models\GeneralJournalDetail::where('j_detail_date','>=',$previous_start_date)
                        ->where('j_detail_date','<=',$previous_end_date)
                        ->where('tran_type','=','expense')
                        ->sum('cr');
        @endphp
        <td class="text-right border">15</td>
        <th class="text-left border">သုံးငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Expense</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$expense_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$expense}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$expense_previous + $expense}}</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
            $loan_loss = \App\Models\ReportSRD::getAccountBalAllQuicken('6-71',$branch_id,$start_date,$end_date);
            $loan_loss_previous = \App\Models\ReportSRD::getAccountBalAllQuicken('6-71',$branch_id,$previous_start_date,$previous_end_date);
            foreach ($loan_loss as $bal) {
                $amount = $bal->amt;
            }
            foreach ($loan_loss_previous as $bal) {
                $amount_previous = $bal->amt;
            }
        @endphp
        <td class="text-right border">16</td>
        <th class="text-left border">ချေးငွေဆုံးရှုံးလျာထားငွေ</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Loan Loss Provision</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$amount_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$amount}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$amount_previous + $amount}}</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
            $expense = $expense??0;
            $income = $income??0;
            $profit_loss = $income - $expense;

            $expense_previous = $expense_previous??0;
            $income_previous = $income_previous??0;
            $profit_loss_previous = $income_previous - $expense_previous;
        @endphp
        <td class="text-right border">17</td>
        <th class="text-left border">အရှုံး/အမြတ်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Profit / Loss</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$profit_loss_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$profit_loss}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$profit_loss + $profit_loss_previous}}</th>
            
        </td>
    </tr>
    <tr class="border">
        @php
             $cash = \App\Models\ReportSRD::getAccountBalAllQuicken('1-11',$branch_id,$start_date,$end_date);
             $cash_previous = \App\Models\ReportSRD::getAccountBalAllQuicken('1-11',$branch_id,$previous_start_date,$previous_end_date);

             foreach($cash as $bal){
                 $amount = $bal->amt;
             }
             foreach($cash_previous as $bal){
                 $amount_previous = $bal->amt;
             }
        @endphp
        <td class="text-right border">18</td>
        <th class="text-left border">ငွေလက်ကျန်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Cash in Hand</th>
        <th class="text-right border">-</th>
        <th class="text-left border">{{$amount_previous}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$amount}}</th>
        <th class="text-left border">-</th>
        <th class="text-left border">{{$amount + $amount_previous}}</th>
            
        </td>
    </tr>
    <tr class="border">
        <td class="text-right border">19</td>
        <th class="text-left border">ဘဏ်သွင်:</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Bank Deposit</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        <td class="text-right border">20</td>
        <th class="text-left border">ဘဏ်ထုတ်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Bank Withdraw</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
    </tr>
    <tr class="border">
        <td class="text-right border">21</td>
        <th class="text-left border">ဘဏ်လက်ကျန်</th>
        <th class="text-right border" style="border: solid windowtext 1.0pt;width:11%;">Cash at Bank</th>
        <th class="text-right border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
        <th class="text-left border">-</th>
        <th class="text-left border">0</th>
            
        </td>
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
