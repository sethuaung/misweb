<div id="DivIdToPrint">
@if($rows != null)
    @include('partials.reports.header',
    ['report_name'=>'Sale Orders','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])


    @foreach($rows as $row)
        <?php
            $loan_d_c = \App\Models\LoanCalculate::where('disbursement_id',$row->id)->get();
            $client = \App\Models\Client::find($row->client_id);
            $guarantor = \App\Models\Guarantor::find($row->guarantor_id);
            //dd($client)
        ?>
            <table style="width: 100%">
                <tr>
                    <td>
                        Contract No<br>
                        Client Name<br>
                        NRC<br>
                        Date of Birth<br>
                        Position<br>
                        Address<br>
                        Guarantor Name<br>
                        NRC<br>
                        Phone No<br>
                        Interest Rate<br>
                        Repayment Schedule
                    </td>
                    <td>
                        <br>
                        {{optional($client->name)}}<br>
                        {{optional($client->nrc_number)}}<br>
                        {{optional($client->dob)}}<br>
                        {{optional($client->address)}}<br>
                        {{optional($guarantor->nrc_number)}}<br>
                        <br>
                        {{optional($client->primary_phone_number)}}<br>
                        {{optional($guarantor->full_name_en)}}<b style="font-size: 18px;text-align: center;">{{$row->interest_rate}}%</b><br>
                        <b style="font-size: 18px">End date of month</b><br>

                    </td>

                    <td>
                        Member ID<br>
                        Disbursement Date<br>
                        Father's Name<br>
                        Phone No<br>
                        School Name<br>
                        Position<br>
                        School Name<br>
                        Duration<br>
                        First Repayment Month<br>
                        Last Repayment Month<br>
                    </td>
                    <td>
                        <br>
                        {{$row->loan_application_date}}<br>
                        {{optional($client->father_name)}}<br>
                        {{optional($guarantor->phone)}}<br>
                        <br>
                        <br>
                        <br>
                        <b style="font-size: 18px;text-align: center;">6 months</b><br>
                        <br>
                        <br>
                    </td>
                </tr>
            </table>
                <table class="table-data" id="table-data" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Date</th>
                            <th colspan="3">Repayment</th>
                            <th rowspan="2">Balance</th>
                            <th rowspan="2">Sign</th>
                            <th rowspan="2">Remark</th>
                        </tr>
                    <tr>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($loan_d_c != null)
                        @foreach($loan_d_c as $r)
                            <tr>
                                <td>{{$r->no}}</td>
                                <td>{{$r->date_s}}</td>
                                <td>{{$r->principal_s}}</td>
                                <td>{{$r->interest_s}}</td>
                                <td>{{$r->total_s}}</td>
                                <td>{{$r->balance_s}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div></div>

    @endforeach
@else
<h1>No data</h1>
@endif
</div>
