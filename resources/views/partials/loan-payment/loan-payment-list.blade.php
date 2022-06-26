@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <table class="table table-bordered" style="width: 100%;background-color: white">
                <thead>
                <tr>
                    <th>Payment number</th>
                    <th>Client name</th>
                    <th>Receipt No</th>
                    <th>Over days</th>
                    <th>Principle</th>
                    <th>Interest</th>
                    <th>Old Owed</th>
                    <th>Other payment</th>
                    <th>Total payment</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @if($rows != null)
                    @foreach($rows as $row)
                       <tr>
                           <td>{{optional($row->loan_disbursement)->disbursement_number}}</td>
                           <td>{{optional($row->client_name)->name}}</td>
                           <td>{{$row->payment_number}}</td>
                           <td>{{$row->over_days}}</td>
                           <td>{{$row->principle}}</td>
                           <td>{{$row->interest}}</td>
                           <td>{{$row->old_owed}}</td>
                           <td>{{$row->other_payment}}</td>
                           <td>{{$row->total_payment}}</td>
                           <td>{{$row->payment}}</td>
                           <td>
                               <a href="{{url('api/delete-payment/?id='.$row->id.'&del_id='.$row->disbursement_detail_id)}}" class="btn btn-danger btn-sm">Remove</a>
                           </td>
                       </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            {{$rows->links()}}
        </div>
    </div>
@endsection
