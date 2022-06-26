<div class="col-sm-12">
        <table id="table-data"  class="table-data">
            <thead>
            <tr>
                <th style="text-align: center" rowspan="2">No</th>
                <th style="text-align: center" rowspan="2">Branch/Month</th>
                <th style="text-align: center" rowspan="2">Loan Product</th>
                @foreach ($monthArr as $mArr)
                    <th style="text-align: center" colspan="3">{{ $months[$mArr] }}</th>
                @endforeach
            </tr>
            <tr>
              @for($j = 1; $j <= count($monthArr); $j++)
                <th>Prin</th>
                <th>Int</th>
                <th>Total</th>
              @endfor
            </tr>
            </thead>
            @php 
                $no = 1;
                $jan_pri = $jan_int = $jan_tot = $feb_pri = $feb_int = $feb_tot = $mar_pri = $mar_int = $mar_tot = 
                $apr_pri = $apr_int = $apr_tot = $may_pri = $may_int = $may_tot = $jun_pri = $jun_int = $jun_tot = 
                $jul_pri = $jul_int = $jul_tot = $aug_pri = $aug_int = $aug_tot = $sep_pri = $sep_int = $sep_tot = 
                $oct_pri = $oct_int = $oct_tot = $nov_pri = $nov_int = $nov_tot = $dec_pri = $dec_int = $dec_tot = [];
                $jan = 1; $feb = 2; $mar = 3; $apr = 4; $may = 5; $jun = 6; $jul = 7; $aug = 8; $sep = 9; $oct = 10;
                $nov = 11; $dec = 12;
            @endphp
            <tbody>
                @if($branch_ids)
                    @foreach($branch_ids as $branch_id)
                        @php
                            $start    = new DateTime($start_date); // Today date
                            $end      = new DateTime($end_date);
                            $interval = DateInterval::createFromDateString('1 month'); // 1 month interval
                            $period   = new DatePeriod($start, $interval, $end); // Get a set of date beetween the 2 period
                            
                            $months = array();
                            $time = array();

                            foreach ($period as $dt) {
                                $months[] = $dt->format("Y-m");
                            }
                            
                            foreach($months as $m){
                                $arr = explode('-', $m);
                                array_push($time, $arr);
                            }
                            
                            $vals = [];
                            $loanProducts = [];
                            $branch = \App\Models\Branch::find($branch_id);
                            foreach($time as $t){
                                    $loans = \App\Models\Loan::where('disbursement_status','Activated')
                                            ->whereYear('status_note_date_activated',$t[0])
                                            ->whereMonth('status_note_date_activated',$t[1])

                                            ->when($branch_id,function($query) use ($branch_id){
                                                return $query->where('branch_id',$branch_id);
                                            })
                                            ->when($center_id,function($query) use ($center_id){
                                                return $query->where('center_leader_id',$center_id);
                                            })
                                            ->when($group_id,function($query) use ($group_id){
                                                return $query->where('group_loan_id',$group_id);
                                            })
                                            ->when($loan_products,function($query) use ($loan_products){
                                                return $query->whereIn('loan_production_id',$loan_products);
                                            })
                                            ->get();
                                
                                $outstanding_principle = $outstanding_interest = $outstanding_total = 0;

                                 if($loans){
                                    foreach($loans as $loan){

                                        $outstanding_principle += $loan->loan_amount - $loan->principle_repayment;
                                        $outstanding_interest += $loan->interest_receivable;
                                        $outstanding_total += $loan->principle_receivable + $loan->interest_receivable;

                                        array_push($loanProducts,$loan->loan_production_id);
                                            
                                    }
                                }
                                $vals[$t[0]][$t[1]]['outstanding_principle'] = $outstanding_principle;
                                $vals[$t[0]][$t[1]]['outstanding_interest'] = $outstanding_interest;
                                $vals[$t[0]][$t[1]]['outstanding_total'] = $outstanding_total;
                            }
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $branch->title }}</td>
                            <td>
                                @if($loan_products)
                                    @foreach ($loan_products as $loan_product)
                                        @php
                                            $loanProduct = \App\Models\LoanProduct::find($loan_product);
                                        @endphp
                                        {{ $loanProduct->name }}, <br>
                                    @endforeach
                                @elseif(count($loanProducts))
                                    @foreach (array_unique($loanProducts) as $loanProduct)
                                        @php
                                            $loanProduct = \App\Models\LoanProduct::find($loanProduct);
                                        @endphp
                                        {{ $loanProduct->name }}, <br>
                                    @endforeach
                                @endif
                            </td>
                            @foreach ($vals as $val)
                                    @for ($i = 01; $i <= 12; $i++)
                                        @php
                                            $month = str_pad($i,2,'0',STR_PAD_LEFT);
                                            //dd(count($monthArr));
                                        @endphp
                                        @if(isset($val[$month]))
                                            @php
                                                switch ($month) {
                                                    case '01':
                                                        array_push($jan_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($jan_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($jan_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '02':
                                                        array_push($feb_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($feb_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($feb_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '03':
                                                        array_push($mar_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($mar_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($mar_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '04':
                                                        array_push($apr_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($apr_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($apr_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '05':
                                                        array_push($may_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($may_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($may_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '06':
                                                        array_push($jun_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($jun_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($jun_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '07':
                                                        array_push($jul_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($jul_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($jul_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '08':
                                                        array_push($aug_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($aug_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($aug_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '09':
                                                        array_push($sep_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($sep_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($sep_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '10':
                                                        array_push($oct_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($oct_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($oct_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '11':
                                                        array_push($nov_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($nov_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($nov_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                    case '12':
                                                        array_push($dec_pri,($val[$month]['outstanding_principle'] ?? 0));
                                                        array_push($dec_int,($val[$month]['outstanding_interest'] ?? 0));
                                                        array_push($dec_tot,($val[$month]['outstanding_total'] ?? 0));
                                                        break;
                                                }
                                            @endphp
                                            <td style="text-align:right;">{{ number_format($val[$month]['outstanding_principle'] ?? 0) }}</td>
                                            <td style="text-align:right;">{{ number_format($val[$month]['outstanding_interest'] ?? 0) }}</td>
                                            <td style="text-align:right;">{{ number_format($val[$month]['outstanding_total'] ?? 0) }}</td>
                                        @endif
                                    @endfor
                            @endforeach
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="3" style="font-weight:bold;color:black;text-align:center;">Conso</td>
                    @foreach($monthArr as $m)
                        @if(count($jan_pri) && $jan == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jan_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jan_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jan_tot)) }}</td>
                        @endif
                        @if(count($feb_pri) && $feb == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($feb_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($feb_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($feb_tot)) }}</td>
                        @endif
                        @if(count($mar_pri) && $mar == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($mar_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($mar_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($mar_tot)) }}</td>
                        @endif
                        @if(count($apr_pri) && $apr == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($apr_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($apr_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($apr_tot)) }}</td>
                        @endif
                        @if(count($may_pri) && $may == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($may_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($may_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($may_tot)) }}</td>
                        @endif
                        @if(count($jun_pri) && $jun == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jun_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jun_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jun_tot)) }}</td>
                        @endif
                        @if(count($jul_pri) && $jul == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jul_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jul_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($jul_tot)) }}</td>
                        @endif
                        @if(count($aug_pri) && $aug == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($aug_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($aug_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($aug_tot)) }}</td>
                        @endif
                        @if(count($sep_pri) && $sep == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($sep_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($sep_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($sep_tot)) }}</td>
                        @endif
                        @if(count($oct_pri) && $oct == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($oct_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($oct_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($oct_tot)) }}</td>
                        @endif
                        @if(count($nov_pri) && $nov == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($nov_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($nov_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($nov_tot)) }}</td>
                        @endif
                        @if(count($dec_pri) && $dec == $m)
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($dec_pri)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($dec_int)) }}</td>
                            <td style="font-weight:bold;color:black;text-align:right;">{{ number_format(array_sum($dec_tot)) }}</td>
                        @endif
                    @endforeach
                </tr>
            </tbody>
        </table>
</div>