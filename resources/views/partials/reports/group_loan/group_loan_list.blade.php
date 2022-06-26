


<div class="col-sm-12">
    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead>
        <tr role="row">
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 182px;">Branch Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;">Center ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Group ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Number Of Member</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 156px;">Total Loan Amount</th>

        </tr>
        </thead>
        <tbody>

        @foreach($grouploanlist as $row)
            <?php
            $group = \App\Models\GroupLoan::find($row->group_loan_id);
            $center=\App\Models\CenterLeader::find(optional($group)->center_id);
            $loan=\App\Models\Loan2::where('group_loan_id','>',0)->where('group_loan_id',$row->group_loan_id)->sum('loan_amount');
//
            ?>
        <tr role="row" class="odd">

           @if ($center != Null)
            <td >{{optional(\App\Models\Branch::find($center->branch_id))->title}}</td>
            @else
             <td>No Data</td>
        @endif     
            <td>{{optional($center)->title}}</td>
            <td>{{optional($group)->group_code}}</td>
            <td>{{$row->num_client}}</td>
            <td>{{$loan}}</td>
        </tr>
         @endforeach

        </tbody>
        <tfoot>
        <tr role="row">
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 182px;">Branch Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;">Center ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Group ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Number Of Member</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 156px;">Total Loan Amount</th>

        </tr>
        </tfoot>
    </table>
</div>