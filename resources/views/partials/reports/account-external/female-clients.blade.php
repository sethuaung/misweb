<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Female Clients'])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>


        <table>
                <thead class="no-border">
                <tr class="text-center no-border">
                    <th colspan="5" class="text-right no-border"></th>
                    <th class="text-right no-border">Date (dd-mmm-yyyy) :</th>
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
                    <th class="text-center border" style="width:22%;">Number of Clients(Female)</th>
                    <th class="text-center border" style="width:11%;">Number of Clients(Male)</th>
                    <th class="text-center border" style="width:10%;"> Disbursement Amount</th>
                    <th class="text-center border" style="width:15%;">Loan Outstanding</th>
                    <th class="text-center border" style="width:24%;" colspan="2">Remark</th>
                    {{-- <th class="text-center border" style="width:12%;"></th> --}}
                </tr>
                </thead>
                @php
                    $i = 1;
                @endphp 
                <tbody class=" border table-data" id="table-data">
                
                    <tr>
                        <td class="text-center border">1</td>
                        <td>{{$female_loans}}</td>
                        <td class="text-right border">
                            -
                        </td>
                        <td class="text-right border">
                            {{$female_amount}}
                        </td>
                        <td class="text-right border">
                            {{$total_outstanding_female}} 
                        </td>
                        <td class="text-right border" colspan="2"></td>
                        {{-- <td class="text-right border"></td> --}}
                    </tr>
                    <tr>
                        <td class="text-center border">2</td>
                        <td>-</td>
                        <td class="text-right border">
                            {{$male_loans}}
                        </td>
                        <td class="text-right border">
                            {{$male_amount}}
                        </td>
                        <td class="text-right border">
                            {{$total_outstanding_male}} 
                        </td>
                        <td class="text-right border" colspan="2"></td>
                        {{-- <td class="text-right border"></td> --}}
                    </tr>
                   
               


                </tbody>
                <tfoot>
                <tr class=" border table-data" id="table-data">
                    <th class="text-center border"></th>
                    <th class="text-center border">Total</th>
                    <th class="text-right border" style="text-align: right"></th>
                    <th class="text-right border" style="text-align: right">-</th>
                    <th class="text-right border" style="text-align: right"></th>
                    {{-- <th class="text-right border" style="text-align: right">
                        100%
                    </th> --}}
                    <th class="text-right border" style="text-align: right" colspan="2">
                        
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
