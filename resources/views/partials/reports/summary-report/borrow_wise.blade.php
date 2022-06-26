<div class="col-sm-12">
    @php 
    //dd($schedule_loans);
        $i = 0;
        $no = 1;
        $compulsory_principle_total = 0;
        $compulsory_interest_total = 0;
        $compulsory_balance_total = 0;
        $saving_total = 0;
        $disbursement_amount_total = 0;
        $disbursement_total = 0;
        $disbursement_principle = 0;
        $disbursement_interest = 0;
        $collection_target_total = 0;
        $over_dued_total = 0;
        $collection_amount_total = 0;
        $realizable_principle_total = 0;
        $realizable_interest_total = 0;
        $realizable_total = 0;
        $over_dued_principle_total = 0;
        $over_dued_interest_total = 0;
        $over_t_total = 0;
        $saving_late_total = 0;
    @endphp
    <table id="table-data"  class="table-data">
        <thead>
            <tr>
                <th colspan="25" style="text-align: center">Myat Kyun Thar Micreofinance Coltd-Collection Target/Sheet</th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="13">Branch Name :</th>
                <th style="text-align: center"  colspan="13">
                    @foreach ($branch_ids as $branch_id)
                        @php $branch = \App\Models\Branch::find($branch_id); @endphp
                        {{ $branch->title . ',' }}
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="4">Branch Code :</th>
                <th style="text-align: left"  colspan="5">
                    @foreach ($branch_ids as $branch_id)
                        @php $branch = \App\Models\Branch::find($branch_id); @endphp
                        {{ $branch->code . ','}}
                    @endforeach
                </th>
                <th style="text-align: center" colspan="11">Day : {{ $week[date('N')] }}</th>
            </tr>
            <tr>
                <th style="text-align: left" colspan="4">Centre No :</th>
                @if($center)
                    <th style="text-align: left" colspan="5">
                        {{ $center->code . ','}}
                @else
                    <th style="text-align: left" colspan="5">-</th>
                @endif
                <th style="text-align: left" colspan="8"></th>
                <th style="text-align: left" colspan="3">Loan Officer Name</th>
                <th style="text-align: left" colspan="6">
                    @foreach ($branch_ids as $branch_id)
                        @php
                            $user = \App\Models\UserBranch::where('branch_id',$branch_id)->first();
                            $loan_officer = \App\User::find($user->user_id);
                        @endphp
                        {{ optional($loan_officer)->name . "," }}
                    @endforeach
                </th>
            </tr>
            <tr>
                <th rowspan="2"> No</th>
                <th rowspan="2">Collection Date</th>
                <th rowspan="2"> Client Name</th>
                <th rowspan="2"> Client Code</th>
                <th rowspan="2">Group No</th>
                <th rowspan="2">Center</th>
                <th rowspan="2">Loan Type</th>
                <th colspan="3"> Saving Commulity</th>
                <th rowspan="2"> Saving Collection</th>
                <th colspan="2"> Disbursement</th>
                <th colspan="3"> collection Target</th>
                <th rowspan="2"> Total Collection Target</th>
                <th colspan="4"> Over- dued</th>
                <th rowspan="2"> Total Collection Amount</th>
                <th colspan="3" style="text-align: center"> Outstanding Balance/Realizable amount</th>
            </tr>
            <tr>
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
                <th>Prin</th>
                <th>Int</th>
                <th>Saving</th>
                <th>Total</th>
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if($schedule_loans)
                @foreach ($branch_ids as $branch_id)
                    @foreach($schedule_loans as $disbursement_loan)
                        @php
                            
                            $loan = \App\Models\Loan::find($disbursement_loan->disbursement_id);
                            $loan_type = \App\Models\LoanProduct::find($loan->loan_production_id);
                            $center = \App\Models\CenterLeader::find($loan->center_leader_id);
                            $client_id = \App\Models\Client::find($loan->client_id);
                            $group_loan = \App\Models\GroupLoan::find($loan->group_loan_id);
                            $compulsory_saving = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->first();
                            $compulsory_saving_transaction = 0;
                            $total_principle = \App\Models\LoanCalculate::where('disbursement_id',$loan->id)
                                            ->sum('principal_s');
                            $over_dued_principle = ($total_principle) - ($loan->principle_repayment??0);
                                        
                            $total_interest = \App\Models\LoanCalculate::where('disbursement_id',$loan->id)
                                            ->sum('interest_s');
                            $over_dued_interest = ($total_interest??0) - ($loan->interest_repayment??0);

                            //$over_dued_total = ($center_loan->principle_receivable??0) + ($center_loan->interest_receivable??0);

                            $late_total = \App\Models\LoanCalculate::where('disbursement_id', $loan->id)
                                        ->whereDate('date_s', '<=', $end_date)
                                        ->sum('total_s');
                            $late_total2 = \App\Models\LoanCalculate::where('disbursement_id', $loan->id)
                                        ->where('payment_status','pending')
                                        ->whereDate('date_s', '<=', $end_date)
                                        ->selectRaw('sum(principle_pd+interest_pd) as total')
                                        ->first();
                            $over_total = ($over_dued_principle??0) + ($over_dued_interest??0) + ($compulsory_saving_transaction??0);
                            $over_dued_principle_total += ($over_dued_principle??0);
                            $over_dued_interest_total += ($over_dued_interest??0);
                            $over_t_total += $over_total??0;
                            $over_dued_amount = ($late_total??0)-($late_total2->total??0);
                            $over_dued_total += ($over_dued_amount??0) + ($compulsory_saving_transaction??0);
                            $disbursement_amount_total += $loan->loan_amount;
                            $realizable_principle_total += $loan->loan_amount- $loan->principle_repayment;
                            $realizable_interest_total += $loan->interest_receivable??0;
                            $realizable_total += $loan->principle_receivable??0 + $loan->interest_receivable??0;
                            $disbursement_total += optional($disbursement_loan)->total_s??0; 
                            $disbursement_principle += optional($disbursement_loan)->principal_s??0;
                            $disbursement_interest += optional($disbursement_loan)->interest_s??0;

                            if($compulsory_saving){
                                $compulsory_principle_total += $compulsory_saving->principles;
                                $compulsory_interest_total += $compulsory_saving->interests;
                                $compulsory_balance_total += $compulsory_saving->available_balance;
                            }
                        @endphp
                        <tr>
                            <td  style="text-align: right">{{ $no++ }}</td>
                            <td>{{ \Carbon\Carbon::parse(optional($disbursement_loan)->date_s)->format('d-m-Y') }}</td>
                            <td  style="text-align: right">
                            @if($client_id)
                                {{ $client_id->name_other??$client_id->name }}
                            @endif
                            </td>
                            <td  style="text-align: right">{{ $client_id->client_number }} </td>
                            <td  style="text-align: right">{{ $group_loan->group_code??"-" }}</td>
                            <td  style="text-align: right">{{optional($center)->title}}</td>
                            <td  style="text-align: right">{{optional($loan_type)->name}}</td>
                            <td  style="text-align: right">{{ RemoveDecimal(number_format($compulsory_saving->principles??0)) }}</td>
                            <td  style="text-align: right">{{ RemoveDecimal(number_format($compulsory_saving->interests??0)) }}</td>
                            <td  style="text-align: right">{{ RemoveDecimal(number_format($compulsory_saving->available_balance??0)) }}</td>
                        
                            @if($compulsory_saving)
                                @php
                                    $total_collection_target = optional($disbursement_loan)->total_s??0 + $compulsory_saving->saving_amount??0;
                                    $saving_total += $compulsory_saving->saving_amount == 5 ? 0:$compulsory_saving->saving_amount;
                                    $saving_late_total += $compulsory_saving_transaction??0;
                                @endphp
                                
                                <td  style="text-align: right">{{ $compulsory_saving->saving_amount == 5? "5 %" : $compulsory_saving->saving_amount }}</td>
                            @else
                                <td  style="text-align: right">0</td>
                            @endif

                            <td  style="text-align: right">{{ date("d-m-Y", strtotime($loan->status_note_date_activated))}}</td>
                            <td  style="text-align: right">{{ number_format($loan->loan_amount??0) }}</td>
                            <td  style="text-align: right">{{ number_format(optional($disbursement_loan)->principal_s??0) }}</td>
                            <td  style="text-align: right">{{ number_format(optional($disbursement_loan)->interest_s??0) }}</td>
                            <td  style="text-align: right">{{ number_format(optional($disbursement_loan)->total_s??0) }}</td>
                            
                            
                            @php 
                                optional($compulsory_saving)->saving_amount != 5 ? $collection_target_total += optional($disbursement_loan)->total_s +         optional($compulsory_saving)->saving_amount : $collection_target_total += optional($disbursement_loan)->total_s;

                                $total_collection_target = optional($disbursement_loan)->total_s??0 + optional($compulsory_saving)->saving_amount??0;
                                $collection_amount_total += $total_collection_target + $over_dued_amount;
                                $plus_saving = 0;
                                $notAdd_saving = 0;
                                if(optional($compulsory_saving)->saving_amount != 5){
                                    $add = optional($disbursement_loan)->total_s + optional($compulsory_saving)->saving_amount;
                                }else{
                                    $notAdd_saving = (optional($disbursement_loan)->total_s??0);
                                }
                            @endphp

                            <td  style="text-align: right">{{ optional($compulsory_saving)->saving_amount != 5 ? number_format($add??0) : number_format($notAdd_saving??0) }}</td>

                            <td  style="text-align: right">{{ number_format($over_dued_principle??0) }}</td>
                            <td  style="text-align: right">{{ number_format($over_dued_interest??0) }}</td>
                            <td  style="text-align: right">{{ number_format($compulsory_saving_transaction??0) }}</td>
                            <td  style="text-align: right">{{ number_format($over_total??0) }}</td>

                            <td  style="text-align: right">{{ number_format($total_collection_target + $over_dued_amount ?? 0) }}</td>
                        
                            <td  style="text-align: right">{{ number_format(($loan->loan_amount - $loan->principle_repayment) ?? 0) }}</td>
                            <td  style="text-align: right">{{ number_format($loan->interest_receivable ?? 0) }}</td>
                            <td  style="text-align: right">{{ number_format(($loan->principle_receivable + $loan->interest_receivable) ?? 0) }}</td>
                        </tr>
                        @php
                           
                            //dd($over_dued_principle);
                        @endphp
                    @endforeach
                @endforeach 
            @else
                <h1>No Data</h1>
            @endif
                <tr>
                    <td  colspan="7" style="text-align: center;font-weight:bold;">Total</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($compulsory_principle_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($compulsory_interest_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($compulsory_balance_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($saving_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">-</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($disbursement_amount_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($disbursement_principle??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($disbursement_interest??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($disbursement_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($collection_target_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($over_dued_principle_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($over_dued_interest_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($saving_late_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($over_t_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($collection_amount_total??0) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($realizable_principle_total) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($realizable_interest_total) }}</td>
                    <td  style="text-align: right;font-weight:bold;">{{ number_format($realizable_total) }}</td>
                </tr>
               
        </tbody>
    </table>
</div>
