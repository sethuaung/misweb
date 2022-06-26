<div id="DivIdToPrint">
    @if($row != null)
        @include('partials.reports.header',
        ['report_name'=>'Invoice By Item Summary','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])



        <table class="table-data" id="table-data">
            <thead>
                <tr>
                    <th>Account No</th>
                    <th>NRC Number</th>
                    <th>Name(Eng)</th>
                    <th>Name(MM)</th>
                    <th>C.O Name</th>
                    <th>Branches</th>
                    <th>Center</th>
                    <th>Apply Date</th>
                    <th>Addresses</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    @else
        <h1>No data</h1>
    @endif
</div>
