<div id="DivIdToPrintPop">
<style>
    table {
        border-collapse: collapse;
    }

    .border th, .border td {
        border: 1px solid rgba(188, 188, 188, 0.96);
        padding: 5px;
    }

    .right {
        text-align: right;
    }

    tr td {
        font-size: 12px;
    }

</style>
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);
    ?>

@if($row != null)
    {{--{{dd($row)}}--}}

    <div class="#">

        <table width="100%">
            <tr>
                <td>
                    <img src="{{asset($logo)}}" width="100" height="100"/>
                </td>

                <td style="font-size:20px; text-align: center;">
                    <?php
                        $product = \App\Models\LoanProduct::find($row->loan_production_id);

                    ?>

                        {{$company}} <br>
                    Repayment Card <br>

                </td>
            </tr>
        </table>

        <br>
        <br>


        <table width="100%">

            <?php
            $client = optional(\App\Models\Client::find($row->client_id));
            $guarantor = \App\Models\Guarantor::find($row->guarantor_id);

            $dc = \App\Models\LoanCalculate::where('disbursement_id', $row->id)->orderBy('date_s','ASC')->get();
            $dis = \App\Models\PaidDisbursement::where('contract_id', $row->id)->first();
            //dd($dis);

            ?>

           <!-- <tr>

                <td>CMember ID</td>
                <td>xc</td>
            </tr>
            <tr>

            </tr>


            <tr>
                <td>Position</td>
                <td>{{optional($client)->occupation_of_husband}}</td>




            </tr>
            <tr>
                <td>Address</td>
                <td>{{optional($client)->address1}}</td>

                <td>{{optional($client)->occupation_of_husband}}</td>
            </tr>
            <tr>
                <td>Guarantor Name</td>
                <td>{{optional($guarantor)->full_name_mm}}</td>

            </tr>
            <tr>
                <td>NRC</td>
                <td>{{optional($guarantor)->nrc_number}}</td>
                <td>Duration</td>
                <td>{{$row->loan_term_value}}</td>
            </tr>
            <tr>
                <td>Phone No</td>
                <td>{{optional($guarantor)->phone}}</td>
                <td>First Repayment Month</td>
                <td>qwewqe</td>
            </tr>
            <tr>
                <td>Interest Rate</td>
                <td>{{$row->interest_rate}}</td>
                <td>Last Repayment Month</td>
                <td>31232</td>
            </tr>
            <tr>
                <td>Repayment Schedule</td>
                <td>{{$row->repayment_term}}</td>
            </tr> -->







            <tr height="30px;">
                <td>Client ID</td>
                <td>{{$client->client_number}}</td>
                <td>Loan No</td>
                <td>{{$row->disbursement_number}}</td>
            </tr>

            <tr height="30px;">
                <td>Client Name</td>
                <td>{{optional($client)->name}}</td>
                <td>NRC</td>
                <td>{{optional($client)->nrc_number}}</td>

                <!--<td>Father's Name</td>
                <td>{{optional($client)->father_name}}</td>-->
            </tr>

            <tr height="30px;">
                <td>Date of Birth</td>
                <td>{{optional($client)->dob}}</td>
                <td>Phone No</td>
                <td>{{optional($client)->primary_phone_number}}</td>
            </tr>

            <tr height="30px;">
                <td>Loan Amount</td>
                <td>{{number_format($row->loan_amount,0)}}</td>
                <td>Interest</td>
                <td>{{optional($row)->interest_rate}}%</td>
            </tr>

            <tr>
                <td>Repayment Terms</td>
                <td>{{optional($row)->repayment_term}}</td>
                <td>Phone</td>
                <td>{{optional($client)->primary_phone_number}}</td>

            </tr>

            <tr height="30px;">
                <td>Disbursement Date</td>
                <td>{{ optional($dis)->paid_disbursement_date != null ? date('d-m-Y', strtotime(optional($dis)->paid_disbursement_date)):''}}</td>
                <td>First Payment Date</td>
                <td>{{ $row->first_installment_date != null ? date('d-m-Y', strtotime($row->first_installment_date)) : ''}}</td>
            </tr>
                @if(companyReportPart() == 'company.system')
            <tr>
                <?php
                $dis = \App\Models\DisburseLoanItem::where('contract_id',$row->id)->first();
                ?>
                <td><b>Deposit</b></td>
                <td>{{number_format(optional($dis)->deposit),2}}</td>
                <td><b>Item</b></td>
                <td>{{optional(\App\Models\Product::find($row->product_id))->product_name}}</td>
            </tr>
            @endif

        </table>
        <br>
        <br>
        <br>
        <table style="width: 100%;">
            <thead class="border">
            <tr>
                <th>No</th>
                <th>Month</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Exact Interest</th>
                <th>Total</th>
                <th>Balance</th>
                <th>Sign</th>
                <th>Remark</th>
            </tr>
            </thead>


            <tbody class="border">

            @if($dc != null)

                @php
                    {{
                        $i = 1;
                        $total_prin = 0;
                        $total_int = 0;
                        $ex_total_int = 0;
                        $total_pay = 0;
                    }}
                @endphp

                @foreach($dc as $c)
                    @if($c->total_p >0)
                    <tr style="background: #fffbe8;">
                        <td>{{$c->no}}</td>
                        <td>{{date('d-m-Y', strtotime($c->date_s))}}</td>
                        <td class="right">{{number_format($c->principal_s,0)}}</td>
                        <td class="right">{{number_format($c->interest_s,0)}}</td>
                        @php
                            $exact_interest = (($c->balance_s + $c->principal_s) * $row->interest_rate) / 100
                        @endphp
                        <td class="right">{{number_format($exact_interest,0)}}</td>
                        <td class="right">{{number_format($c->total_s,0)}}</td>
                        <td class="right">{{number_format($c->balance_s,0)}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                        <tr>
                            <td>{{$c->no}}</td>
                            <td>{{date('d-m-Y', strtotime($c->date_s))}}</td>
                            <td class="right">{{number_format($c->principal_s,0)}}</td>
                            <td class="right">{{number_format($c->interest_s,0)}}</td>
                            @php
                                $exact_interest = (($c->balance_s + $c->principal_s) * $row->interest_rate) / 100
                            @endphp
                            <td class="right">{{number_format($exact_interest,0)}}</td>
                            <td class="right">{{number_format($c->total_s,0)}}</td>
                            <td class="right">{{number_format($c->balance_s,0)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif

                    @php
                        {{
                            $i++;
                            $total_prin += $c->principal_s;
                            $total_int += $c->interest_s;
                            $ex_total_int += $exact_interest;
                            $total_pay += $c->total_s;

                         }}
                    @endphp

                @endforeach
                <tr>
                    <td> </td>
                    <td> </td>
                    <td class="right">{{number_format($total_prin,0)}}</td>
                    <td class="right">{{number_format($total_int,0)}}</td>
                    <td class="right">{{number_format($ex_total_int,0)}}</td>
                    <td class="right">{{number_format($total_pay,0)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            @endif


            </tbody>
        </table>

        <table style="width: 100%">

            <tr style="height: 50px;">

            </tr>


            <tr style="alignment: center">
                <td colspan="2">Manager Sign</td>
                <td colspan="2">Loan Officer Sign</td>
                <td colspan="2">Guarantor Sign</td>
                <td colspan="2">Client Sign</td>
            </tr>

            <tr style="height: 50px;">
                <td colspan="8"></td>
            </tr>


            <tr style="alignment: center">
                <td>Name</td>
                <td>------------</td>

                <td>Name</td>
                <td>------------</td>

                <td>Name</td>
                <td>------------</td>

                <td>Name</td>
                <td>------------</td>


            </tr>

            <tr style="height: 20px;"></tr>


            <tr style="alignment: center">
                <td>Position</td>
                <td>------------</td>
                <td>Position</td>
                <td>------------</td>
                <td>Position</td>
                <td>------------</td>
                <td>Position</td>
                <td>------------</td>


            </tr>
        </table>
    </div>

@endif
</div>
