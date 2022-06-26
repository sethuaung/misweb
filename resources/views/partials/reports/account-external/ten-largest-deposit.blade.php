<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Saving Deposit'])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>


        <table>
                <thead class="no-border">
                <tr class="text-center no-border">
                    <th colspan="5" class="text-right no-border"></th>
                    <th class="text-right no-border">Date :</th>
                    <th class="text-left no-border">
                        <div style="border-bottom: 1px solid #333;">{{date("d/m/Y")}}</div>
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th colspan="5" class="text-right no-border"></th>
                    <th class="text-right no-border">Reporting Period :</th>
                    <th class="text-left no-border">
                        <div style="border-bottom: 1px solid #333;">now</div>
                    </th>
                </tr>
                <tr class=" border table-data" id="table-data">
                    <th class="text-center border" style="width:1%;">No.</th>
                    <th class="text-center border" style="width:22%;">Depositors' Name</th>
                    <th class="text-center border" style="width:11%;">Compulsory Saving</th>
                    <th class="text-center border" style="width:10%;">Voluntary  Saving</th>
                    <th class="text-center border" style="width:15%;">Customer's Deposits (Compulsory Saving + Voluntary Saving)</th>
                    <th class="text-center border" style="width:12%;">Compulsory Saving Over Customer's Deposits (%)</th>
                    <th class="text-center border" style="width:12%;">Voluntary  Saving Over Customer's Deposits (%)</th>
                </tr>
                </thead>
                @php
                    $i = 1;
                @endphp
                <tbody class=" border table-data" id="table-data">
                @foreach ($largest_loans as $largest_loan)
                @php
                    $client = App\Models\Client::find($largest_loan->client_id);
                    $compulsory_saving = ($largest_loan->loan_amount * 4) / 100;
                    $voluntary_saving = 0;
                    $total_saving =  $compulsory_saving + $voluntary_saving;
                    $total_compulsory = $total_compulsory??0;
                    $sum_total_saving = $sum_total_saving??0;
                    $total_compulsory += $compulsory_saving;
                    $sum_total_saving += $total_saving;
                @endphp
                    <tr>
                        <td class="text-center border">{{$i}}</td>
                        <td>{{$client->name}}</td>
                        <td class="text-right border">
                            {{$compulsory_saving}}
                        </td>
                        <td class="text-right border">
                            -
                        </td>
                        <td class="text-right border">
                            {{$total_saving}}
                        </td>
                        <td class="text-right border">100%</td>
                        <td class="text-right border">0%</td>
                    </tr>
                    @php
                        ++$i;
                    @endphp
                @endforeach


                </tbody>
                <tfoot>
                <tr class=" border table-data" id="table-data">
                    <th class="text-center border"></th>
                    <th class="text-center border">Total</th>
                    <th class="text-right border" style="text-align: right">{{$total_compulsory}}</th>
                    <th class="text-right border" style="text-align: right">-</th>
                    <th class="text-right border" style="text-align: right">{{$sum_total_saving}}</th>
                    <th class="text-right border" style="text-align: right">
                        100%
                    </th>
                    <th class="text-right border" style="text-align: right">
                        0%
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th colspan="4" class="text-right no-border"></th>
                    <th colspan="2" class="text-right no-border">Prepared by (Name/Signature) :</th>
                    <th class="text-center no-border">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th colspan="4" class="text-right no-border"></th>
                    <th colspan="2" class="text-right no-border">Checked by (Name/Signature) :</th>
                    <th class="text-center no-border">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th colspan="4" class="text-right no-border"></th>
                    <th colspan="2" class="text-right no-border">Approved by (Name/Signature) :</th>
                    <th class="text-center no-border">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                </tfoot>
        </table>

</div>
