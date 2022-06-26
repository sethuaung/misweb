<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Monthlly Progress','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>
    <div class="col-md-12">
        <table class="border table border-less borderless">
            <thead>
            <tr class="text-center no-border">
                <td class="text-right no-border" style="padding: 0;">Date (dd-mmm-yyyy) :</td>
                <td class="text-center no-border" style="padding: 0;width: 180px;">
                    <div style="border-bottom: 1px solid #333;">{{date('d-m-Y')}}</div>
                </td>
            </tr>
            <tr class="text-center no-border">
                <td class="text-right no-border" style="padding: 0;">Reporting Period :</td>
                <td class="text-center no-border" style="padding: 0;width: 180px;">
                    <div style="border-bottom: 1px solid #333;"></div>
                </td>
            </tr>
            </thead>
        </table>
        <div class="row">&nbsp;</div>
        <div class="zui-wrapper">
            <div class="zui-scroller">
                <table class="border table-data" id="table-data table border-table zui-table">
                    <thead class="border">
                    <tr class="border">
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-center border" style="width: 25%;">Description</th>
                        <th class="text-right border" style="width: 14%;">Total(during the Month)</th>
                    </tr>
                    </thead>
                    <tbody class="border table-data" id="table-data">
                    <tr class="border">
                        <td class="text-center border">1</td>
                        <td class="text-left border">Paid up Capital</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$capital}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">2</td>
                        <td class="text-left border">Number of Clients</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$clients}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">3</td>
                        <td class="text-left border">Number of Active Borrowers (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$loans}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">4</td>
                        <td class="text-left border">Number of Depositors (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$deposits}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">5</td>
                        <td class="text-left border">Loan Disbursed (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$disbursement_amount}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">6</td>
                        <td class="text-left border">Loan Collected (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$loan_collected}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">7</td>
                        <td class="text-left border">Loan Outstanding</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$loan_release_total}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">8</td>
                        <td class="text-left border">Saving Deposit (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$compulsory}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">9</td>
                        <td class="text-left border">Saving Withdrawal (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$withdraw}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">10</td>
                        <td class="text-left border">Saving Balance</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$remain}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">11</td>
                        <td class="text-left border">Interest Income (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$interest_income}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">12</td>
                        <td class="text-left border">Other Income except Interest Income (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$other_income}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">13</td>
                        <td class="text-left border">Total Income (11+12)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$total_income}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">14</td>
                        <td class="text-left border">Interest on Customers' Deposit (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$compulsory_interest}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">15</td>
                        <td class="text-left border">Other Expense except Interest on Customers' Deposit (during the Month)</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$other_expense}}
                        </td>
                    </tr>
                    <tr class="border">
                        <td class="text-center border">16</td>
                        <td class="text-left border">Profit And Loss  (13-(14+15))</td>
                        <td class="text-right border" style="width: 14%;">
                            {{$profit}}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table border-table zui-table">
                    <tfoot>
                    <tr>
                        <td colspan="3" style="font-size: 10px;">* Monthly Amount</td>
                    </tr>
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
        </div>
        <div class="row">&nbsp;</div>
    </div>


</div>
