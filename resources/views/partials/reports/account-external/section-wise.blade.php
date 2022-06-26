<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <div class="col-md-12" style="text-align: center">
            <table class="table border-less borderless">
                <thead>
                <tr class="text-center no-border">
                    <th colspan="13" class="text-right no-border"></th>
                    <th class="text-right no-border">Date :</th>
                    <th class="text-center no-border">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                </thead>
            </table>
            <div class="row">&nbsp;</div>
            <div class="table-responsive">
                <table class="table border-table">
                    <thead>
                    <tr class="border">
                        <th colspan="3" class="text-center border" style="width: 1%;">Business</th>
                        <th colspan="3" class="text-center border" style="width: 1%;">To Prepare House</th>
                        <th colspan="3" class="text-center border" style="width: 1%;">Education Expense</th>
                        <th colspan="3" class="text-center border" style="width: 1%;">To Rent House</th>
                        <th colspan="3" class="text-center border" style="width: 1%;">Livestock &amp; Fishery</th>
                    </tr>
                    <tr class="border">
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-left border" style="width: 15%;">Name</th>
                        <th class="text-right border" style="width: 5%;">Amount</th>
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-left border" style="width: 15%;">Name</th>
                        <th class="text-right border" style="width: 5%;">Amount</th>
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-left border" style="width: 15%;">Name</th>
                        <th class="text-right border" style="width: 5%;">Amount</th>
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-left border" style="width: 15%;">Name</th>
                        <th class="text-right border" style="width: 5%;">Amount</th>
                        <th class="text-center border" style="width: 1%;">No.</th>
                        <th class="text-left border" style="width: 15%;">Name</th>
                        <th class="text-right border" style="width: 5%;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border">
                        <td class="text-center border">1</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">1</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">1</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">1</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">1</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                    </tr>
 <tr class="border">
                        <td class="text-center border">2</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">2</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">2</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">2</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">2</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                    </tr>
 <tr class="border">
                        <td class="text-center border">3</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">3</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">3</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">3</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                        <td class="text-center border">3</td>
                        <td class="text-left border" style="white-space: nowrap;"></td>
                        <td class="text-right border" style="white-space: nowrap;">0</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="row">&nbsp;</div>
            <table class="table border-less borderless">
                <thead>
                <tr class="text-center no-border">
                    <th class="text-right no-border" style="width: 70%;padding: 5px 0 !important;">&nbsp;</th>
                    <th class="text-right no-border" style="width: 20%;padding: 5px 0 !important;">Prepared by (Name/Signature) :</th>
                    <th class="text-center no-border" style="width: 10%;padding: 5px 0 !important;">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th class="text-right no-border" style="width: 70%;padding: 5px 0 !important;">&nbsp;</th>
                    <th class="text-right no-border" style="width: 20%;padding: 5px 0 !important;">Checked by (Name/Signature) :</th>
                    <th class="text-center no-border" style="width: 10%;padding: 5px 0 !important;">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                <tr class="text-center no-border">
                    <th class="text-right no-border" style="width: 70%;padding: 5px 0 !important;">&nbsp;</th>
                    <th class="text-right no-border" style="width: 20%;padding: 5px 0 !important;">Approved by (Name/Signature) :</th>
                    <th class="text-center no-border" style="width: 10%;padding: 5px 0 !important;">
                        <div style="border-bottom: 1px solid #333;">&nbsp;</div>
                    </th>
                </tr>
                </thead>
            </table>
            <div class="row">&nbsp;</div>
        </div>
    </table>


</div>
