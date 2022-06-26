@extends('backpack::layout')
<?php $base = asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Inbox</h3>

                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        @if($notifications != null)
                            <table class="table table-hover table-striped">
                                <tbody>
                                @foreach ($notifications as $notification)
                                    <?php
                                    $details = null;
                                    if ($notification->data != null) {
                                        $data = json_decode($notification->data);

                                        if ($data != null) {
                                            $details = $data->details;
                                        }
                                    }

                                    ?>
                                    @if($notification->type=="App\Notifications\LatePaymentNotification" && $details != null)
                                        <?php


                                                $loan=  \App\Models\Loan::find($details->disbursement_id);
                                                $client = '';
                                                if ($loan != null){
                                                    $client = \App\Models\Client::find(optional($loan)->client_id);
                                                }
                                        ?>
                                        <tr>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a>
                                            </td>
                                            <td class="mailbox-name"><a
                                                        href="{{url("/read_notification/{$notification->id}")}}">{{optional($loan)->disbursement_number}}</a></td>
                                            <td class="mailbox-subject">
                                                <b>Customer Name :</b> {{"  ".isset($client)?optional($client)->name:''}}
                                                <b style="padding-left: 20px">Late Day   :</b> {{"  ".abs($notification->day_num)}}
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">{{ $notification->created_at->diffForHumans()}}</td>
                                        </tr>
                                    @endif
                                @endforeach

                                </tbody>
                            </table>

                            {{$notifications->links()}}

                    @endif
                    <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
@endsection
