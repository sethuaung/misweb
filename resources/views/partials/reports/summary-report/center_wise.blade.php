<div class="col-sm-12">
    <table id="table-data" class="table-data">
        <thead>
            <tr>
                <th colspan="19" style="text-align: center">Myat Kyun Thar Micreofinance Coltd-Collection Target/Sheet</th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="4">Branch Name</th>
                <th style="text-align: left" colspan="15">
                    @foreach ($branch_ids as $branch_id)
                        @php $branch = \App\Models\Branch::find($branch_id); @endphp
                        {{ $branch->title . ',' }}
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="4">Branch Code</th>
                <th style="text-align: left" colspan="15">
                    @foreach ($branch_ids as $branch_id)
                        @php $branch = \App\Models\Branch::find($branch_id); @endphp
                        {{ $branch->code . ',' }}
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="4">Collection Week</th>
                <th style="text-align: left" colspan="15">Week ({{$week_num}}), {{date('M Y')}}</th>
            </tr>

            <tr>
                <th rowspan="2"> Collection Date</th>
                <th rowspan="2"> Collection Day</th>
                <th rowspan="2">LO Name</th>
                <th rowspan="2"> Centre Code</th>
                <th rowspan="2"> Total client</th>
                <th colspan="3"> Saving Community</th>
                <th rowspan="2"> Saving Collection</th>
                <th colspan="3"> collection Target</th>
                <th rowspan="2"> Over- dued AMT</th>
                <th rowspan="2"> Total Collection Amount</th>
                <th colspan="3"> Outstanding Balance/Realizable amount</th>
            </tr>
            <tr>
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
                <th>Print</th>
                <th>Int</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if($branch_ids)
                    @foreach ($branch_ids as $branch_id)
                        @php
                            $period = \Carbon\CarbonPeriod::create($start_date, $end_date);
                            $dates = $period->toArray();
                            $active_loans = \App\Models\Loan::where('disbursement_status','Activated')
                                    ->when($branch_id,function($query) use ($branch_id){
                                        return $query->where('branch_id',$branch_id);
                                    })
                                    ->when($center_id,function($query) use ($center_id){
                                        return $query->where('center_leader_id',$center_id);
                                    })
                                    ->when($group_id,function($query) use ($group_id){
                                        return $query->where('group_loan_id',$group_id);
                                    })
                                    ->get()->pluck('id')->toArray();
                        @endphp
                        @foreach ($dates as $date)
                            @php
                                $client_total = 0;
                                $saving_principle_total = 0;
                                $saving_interest_total = 0;
                                $saving_t_total = 0;
                                $savingTotal = 0;
                                $collection_principal_total = 0;
                                $collection_interest_total = 0;
                                $collection_total = 0;
                                $over_dued_total = 0;
                                $collection_amount_total = 0;
                                $realizable_principal_total = 0;
                                $realizable_interest_total = 0;
                                $realizable_total_total = 0;
                                $over_dued_principle_total = 0;
                                $over_dued_interest_total = 0;
                                $over_dued_amount_total = 0;
                                $shedule_loans = [];
                                foreach ($active_loans as $active_loan) {
                                    $first_schedule = \App\Models\LoanCalculate::where('disbursement_id',$active_loan)
                                                    ->where('payment_status','pending')->first();
                                    if($first_schedule != Null){
                                        $dis_loan = \App\Models\LoanCalculate::where('id',$first_schedule->id)->
                                            where('disbursement_id',$active_loan)
                                            ->where('payment_status','pending')
                                            ->whereDate('date_s',$date)
                                            ->first();
                                        if($dis_loan != null){
                                            array_push($shedule_loans,$dis_loan);
                                        }
                                    }
                                    
                                };
                            @endphp
                            @foreach ($shedule_loans as $disbursement_loan)
                                @php
                                    $disbursement_principal = [];
                                    $disbursement_interest = [];
                                    $disbursement_total = [];
                                    $saving_principle = [];
                                    $saving_interest = [];
                                    $saving_total = [];
                                    $saving = [];
                                    $over_dued_amount = [];
                                    $total_collection_amount = [];
                                    $realizable_principal = [];
                                    $realizable_interest = [];
                                    $realizable_total = [];
                                    $client_arr = [];
                                    $loan = \App\Models\Loan::find($disbursement_loan->disbursement_id);
                                    $loan_officer = \App\User::find(optional($loan)->loan_officer_id);
                                    $center = \App\Models\CenterLeader::find(optional($loan)->center_leader_id);

                                    $compulsory_saving = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->first();
                                    if($compulsory_saving){
                                        $total_collection_target = ($disbursement_loan->total_s??0) + ($compulsory_saving->saving_amount == 5 ? 0: $compulsory_saving->saving_amount);
                                    }else{
                                        $total_collection_target = 0;
                                    }


                                    $late_total = \App\Models\LoanCalculate::where('id', $disbursement_loan->id)
                                            ->whereDate('date_s',$date)
                                            ->sum('total_s');
                                    $late_total2 = \App\Models\LoanCalculate::where('id', $disbursement_loan->id)
                                            ->whereDate('date_s',$date)
                                            ->selectRaw('sum(principle_pd+interest_pd) as total')->first();

                                    $over_dued = ($late_total??0)-($late_total2->total??0);
                                    
                                    array_push($client_arr,$disbursement_loan);
                                    array_push($over_dued_amount,($over_dued??0));
                                    array_push($disbursement_principal,($disbursement_loan->principal_s??0));
                                    array_push($disbursement_interest,($disbursement_loan->interest_s??0));
                                    array_push($disbursement_total,($disbursement_loan->total_s??0));
                                    if($compulsory_saving){
                                        
                                        array_push($saving,($compulsory_saving->saving_amount == 5 ? 0 : $compulsory_saving->saving_amount));
                                        array_push($saving_principle,($compulsory_saving->principles??0));
                                        array_push($saving_interest,($compulsory_saving->interests??0));
                                        array_push($saving_total,($compulsory_saving->available_balance??0));
                                    }
                                    array_push($total_collection_amount,($total_collection_target + $over_dued));
                                    array_push($realizable_principal,(($loan->loan_amount - $loan->principle_repayment) ?? 0));
                                    array_push($realizable_interest,($loan->interest_receivable ?? 0));
                                    array_push($realizable_total,(($loan->principle_receivable + $loan->interest_receivable)?? 0));
                                @endphp
                                @if(count($shedule_loans))
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse(optional($disbursement_loan)->date_s)->format('d-m-Y') }}</td>
                                        <td>{{ $week[\Carbon\Carbon::parse(optional($disbursement_loan)->date_s)->format('N')] }}</td>
                                        <td>{{ optional($loan_officer)->name }}</td>
                                        <td style="text-align: right">{{ optional($center)->code }}</td>
                                        @php
                                            $client_total += count($client_arr);
                                            $collection_principal_total += array_sum($disbursement_principal);
                                            $collection_interest_total += array_sum($disbursement_interest);
                                            if($compulsory_saving){
                                                $saving_principle_total += array_sum($saving_principle);
                                                $saving_interest_total += array_sum($saving_interest);
                                                $saving_t_total += array_sum($saving_total);
                                                $savingTotal += array_sum($saving);
                                            }
                                            $collection_total += array_sum($disbursement_total);
                                            $over_dued_amount_total += array_sum($over_dued_amount);
                                            $collection_amount_total += array_sum($total_collection_amount);
                                            $realizable_principal_total += array_sum($realizable_principal);
                                            $realizable_interest_total += array_sum($realizable_interest);
                                            $realizable_total_total += array_sum($realizable_total);
                                        @endphp
                                        <td  style="text-align: right">{{ count($client_arr) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($saving_principle??0)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($saving_interest??0)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($saving_total??0)) }}</td>
                                        <td  style="text-align: right">{{ $saving ? number_format(array_sum($saving)) : 0 }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($disbursement_principal)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($disbursement_interest)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($disbursement_total)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($over_dued_amount)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($total_collection_amount)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($realizable_principal)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($realizable_interest)) }}</td>
                                        <td  style="text-align: right">{{ number_format(array_sum($realizable_total)) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            @if(count($shedule_loans))
                                <tr>
                                    <td colspan="4"  style="text-align: center;font-weight:bold;font-size:18px;">Total</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($client_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ $saving_principle_total ? number_format($saving_principle_total) : 0 }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ $saving_interest_total ? number_format($saving_interest_total) : 0 }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ $saving_t_total ? number_format($saving_t_total) : 0 }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ $savingTotal ? number_format($savingTotal) : 0 }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($collection_principal_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($collection_interest_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($collection_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($over_dued_amount_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($collection_amount_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($realizable_principal_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($realizable_interest_total??0) }}</td>
                                    <td  style="text-align: right;font-weight:bold;font-size:18px;">{{ number_format($realizable_total_total??0) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
            @else
                <h1>No Data</h1>
            @endif
        </tbody>
    </table>
</div>