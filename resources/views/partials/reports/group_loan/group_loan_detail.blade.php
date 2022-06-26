


<div class="col-sm-12">
    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead>
        <tr role="row">
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 182px;">Branch Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;">Center ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Group ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Apply Date</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Loan ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Member Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">status</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 156px;">Loan Amount</th>

        </tr>
        </thead>
        <tbody>

@foreach($grouploandetail as $row)

    <?php

            $group = \App\Models\GroupLoan::find($row->group_loan_id);
            $center=\App\Models\CenterLeader::find(optional($group)->center_id);
            $loan=\App\Models\Loan2::where('group_loan_id','>',0)->where('group_loan_id',$row->group_loan_id)->get();
    ?>
            @foreach ($loan as $rr)

        <tr role="row" class="odd">
        @if ($center != Null)
            <td >{{optional(\App\Models\Branch::find($center->branch_id))->title}}</td>
            @else
             <td>No Data</td>
        @endif     
            <td>{{optional($center)->title}}</td>
            <td>{{optional($group)->group_code}}</td>
            <td>{{$rr->loan_application_date}}</td>
            <td>{{$rr->disbursement_number}}</td>
            @php
                $eng_name = Null;
                $mm_name = Null;
                $eng_name = \App\Models\Client::find($rr->client_id)->name;
                $mm_name =  \App\Models\Client::find($rr->client_id)->name_other;
            @endphp
            @if($eng_name != NULL)
            <td>{{$eng_name}}</td>
            @else
             <td>{{$mm_name}}</td>
             @endif
            <td>{{$rr->disbursement_status}}</td>
            <td>{{$rr->loan_amount}}</td>
        </tr>
           @endforeach
@endforeach

        </tbody>
        <tfoot>
        <tr role="row">
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 182px;">Branch Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;">Center ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Group ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Apply Date</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Loan ID</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">Member Name</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 199px;">status</th>
            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 156px;">Loan Amount</th>

        </tr>
        </tfoot>
    </table>
</div>