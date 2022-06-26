<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <div class="col-md-12">
            <table class="table border-less borderless">
                <thead>
                <tr style="border-bottom:double windowtext 2.25pt !important;">
                    <th style="width: 8%; white-space: nowrap;">Commune Code</th>
                    <th style="width: 20%;">Commune Name</th>
                    <th style="width: 17%;" class="text-left">Loan Type</th>
                    <th style="width: 10%;" class="text-right">Total Active Borrowers</th>
                    <th style="width: 15%;" class="text-right">Total Active Borrowers (Female)</th>
                    <th style="width: 13%;" class="text-right">Loan Disbursed</th>
                    <th style="width: 15%;" class="text-right">Loan Outstanding</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-left"></td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                </tr>

                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <th class="text-right" style="border-top:solid windowtext 1.0pt !important;border-bottom:double windowtext 2.25pt !important;">0</th>
                    <th class="text-right" style="border-top:solid windowtext 1.0pt !important;border-bottom:double windowtext 2.25pt !important;">0</th>
                    <th class="text-right" style="border-top:solid windowtext 1.0pt !important;border-bottom:double windowtext 2.25pt !important;">0</th>
                    <th class="text-right" style="border-top:solid windowtext 1.0pt !important;border-bottom:double windowtext 2.25pt !important;">0</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </table>


</div>
