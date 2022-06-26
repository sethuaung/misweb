@extends('vendor.backpack.base.layout')

@section('content')
    <style>
        table {
            border-collapse: collapse;
        }

        .border th, .border td {
            border: 1px solid rgba(188, 188, 188, 0.96);
            padding: 5px;
        }

        .right {
            text-align: right;
        }

        h3 {

            font-size: 18px;
        }

        h4 {

            font-size: 15px;
        }

        tr td {

            font-size: 16px;
        }

    </style>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="#">
                    @if(count($rows)> 0)
                        <table width="100%">
                            <thead class="border">
                            <tr>
                                <td>Applicant Number</td>
                                <td>Branch</td>
                                <td>Leader</td>
                                <td>Client</td>
                                <td>Principal Amount</td>
                                <td>Overdue</td>
                                <td>Client's address</td>
                            </tr>
                            </thead>
                            <tbody class="border">
                            @foreach($rows as $row)

                                <?php
                                $loan = optional(\App\Models\Loan::find($row->disbursement_id));
                               // dd($loan);
                                $branch = optional(\App\Models\Branch::find($loan->branch_id));
                                $center = optional(\App\Models\CenterLeader::find($loan->center_leader_id));
                                $client = optional(\App\Models\Client::find($loan->client_id));
                                ?>

                                <tr>
                                    <td>{{$loan->getOriginal('disbursement_number')}}</td>
                                    <td>{{$branch->title}}</td>
                                    <td>{{$center->title}}</td>
                                    <td>{{$client->name}}</td>
                                    <td>{{$loan->loan_amount}}</td>
                                    <td>2</td>

                                    <td>{{$client->address}}</td>
                                </tr>

                            @endforeach
                            </tbody>


                        </table>

                    @endif
                </div>
            </div>
        </div>

    </section>

@endsection