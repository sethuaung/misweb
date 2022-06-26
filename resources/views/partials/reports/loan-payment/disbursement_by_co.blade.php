<div class="#">

    @if($rows != null)

        <?php
        $i = 0;
        $u = $rows->groupBy('loan_officer_id');
        ?>
        <table class="table-data" id="table-data">
            <thead>
            <tr>
                <th>Branch</th>
                <th>Disbursement No</th>
                <th>Disbursement Date</th>
                <th>Client</th>
                <th>Interest</th>
                <th>Term</th>
                <th>Repayment</th>
                <th>Loan Amount</th>
            </tr>
            </thead>

            <tbody>

            @foreach($u as $co_id => $co)
                <tr>
                    <td colspan="8"><b>{{ optional(\App\User::find($co_id))->name }}</b></td>
                </tr>
                @php
                    $date = $co->groupBy('ld');
                //dd($co);
                @endphp
                @foreach($date as $d => $row_d)
                    <tr>
                        <td colspan="5" style="padding-left: 30px;"><b>{{ $d }}</b></td>
                    </tr>
                    @foreach($row_d as $row)

                        {{--{{dd($row)}}--}}
                        <tr>
                            <td style="padding-left: 60px;">{{ $loop->index +1 }}</td>
                            <td>{{ optional(\App\Models\Branch::find($row->branch_id))->title}}</td>
                            <td>{{$row->ld}}</td>
                            <td>test</td>
                            <td>{{$row->interest_rate}}</td>
                            <td>{{$row->loan_term_value}}</td>
                            <td>{{$row->repayment_term}}</td>
                            <td>{{$row->loan_amount}}</td>
                        </tr>
                    @endforeach
                @endforeach


                {{--<tr>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}

                {{--</tr>--}}

            @endforeach
            <tr>
                <td>total: 100</td>
            </tr>
            </tbody>


        </table>

    @endif

</div>
