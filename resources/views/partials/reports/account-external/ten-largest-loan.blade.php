<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Ten Largest Loan'])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>
    @php
        $i =1;
    @endphp

    <table class="table-data" id="table-data">
        <thead>
        <tr class="text-center no-border">
        </tr>
        <tr class="text-center no-border">
            <th colspan="6" class="text-right no-border"></th>
            <th class="text-right no-border">Date (dd-mmm-yyyy) :</th>
            <th class="text-left no-border">
                <div style="border-bottom: 1px solid #333;">{{date("d/m/Y")}}</div>
            </th>
        </tr>
        <tr class="text-center no-border">
            <th colspan="6" class="text-right no-border"></th>
            <th class="text-right no-border">Reporting Period :</th>
            <th class="text-left no-border">
                <div style="border-bottom: 1px solid #333;">Now</div>
            </th>
        </tr>
        <tr class="border">
            <th class="text-center border" style="width: 1%;">No.</th>
            <th class="text-center border" style="width: 25%;">Borrowers'Name</th>
            <th class="text-center border" style="width: 17%;">Original Loan Amount</th>
            <th class="text-center border" style="width: 17%;">Loan Outstanding</th>
            <th class="text-center border" style="width: 16%;">Performing Per Agreement? (Yes/No)</th>
            <th class="text-center border" style="width: 10%;">Number of Rescheduled Times</th>
            {{-- <th class="text-center border" style="width: 13%;">Last Rescheduled Date (:dd-mmm-yyyy:)</th> --}}
            <th class="text-center border" style="width: 14%;">Loan Outstanding Over Original Loan Amount (%)</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($largest_loans as $largest_loan)
            @php
                $client = App\Models\Client::find($largest_loan->client_id);
                $loan_outstanding = $largest_loan->principle_repayment + $largest_loan->interest_repayment;
                $over = ($loan_outstanding / $largest_loan->loan_amount) * 100;
                $total_outstanding = $total_outstanding??0;
                $total_loan_amount = $total_loan_amount??0;
                $total_outstanding += $loan_outstanding;
                $total_loan_amount += $largest_loan->loan_amount;
            @endphp
                <tr>
                    <td class="text-center border">{{$i}}</td>
                    <td class="text-left border">{{$client->name}}</td>
                    <td class="text-right border">{{$largest_loan->loan_amount}}</td>
                    <td class="text-right border">{{$loan_outstanding}}</td>
                    <td class="text-right border"> </td>
                    <td class="text-right border">No</td>
                    {{-- <td class="text-right border"></td> --}}
                    <td class="text-right border">{{round($over)}}%</td>
                </tr>
            @php
                ++$i
            @endphp     
            @endforeach

        </tbody>
        <tfoot>
        <tr>
            <th class="text-center border"></th>
            <th class="text-center border">Total</th>
            <th class="text-right border" style="text-align: right">{{$total_loan_amount}}</th>
            <th class="text-right border" style="text-align: right">{{$total_outstanding}}</th>
            <th class="text-right border"></th>
            <th class="text-right border"></th>
            {{-- <th class="text-right border"></th> --}}
            <th class="text-right border">
                
            </th>
        </tr>
        <tr class="text-center no-border">
            <th colspan="5" class="text-right no-border"></th>
            <th colspan="2" class="text-right no-border">Prepared by (Name/Signature) :</th>
            <th class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </th>
        </tr>
        <tr class="text-center no-border">
            <th colspan="5" class="text-right no-border"></th>
            <th colspan="2" class="text-right no-border">Checked by (Name/Signature) :</th>
            <th class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </th>
        </tr>
        <tr class="text-center no-border">
            <th colspan="5" class="text-right no-border"></th>
            <th colspan="2" class="text-right no-border">Approved by (Name/Signature) :</th>
            <th class="text-center no-border">
                <div style="border-bottom: 1px solid #333;">&nbsp;</div>
            </th>
        </tr>
        </tfoot>
    </table>
</div>
