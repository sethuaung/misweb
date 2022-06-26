<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel</title>
</head>
<body>
    <textarea style="width: 100%;" placeholder="Note" class="note" rows="2"></textarea>
    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Payment Date</th>
            <th>Cash In</th>
            <th>Payment Number</th>
            <th>Principle</th>
            <th>Interest</th>
            <th>Service</th>
            <th>Penalty</th>
            <th>Saving</th>
            <th>Owed Balance</th>
            <th style="width: 80px">Action</th>
        </tr>
        @if($payments != null)
            <?php
                $total_principle = 0;
                $total_interest = 0;
                $total_charge = 0;
                $total_penalty = 0;
                $total_compulsory = 0;
            $last_schedule_id = 0;
            //dd($payments);
            ?>
        @foreach($payments as $report)
            <?php
                    if($loop->last){
                        //dd($all_payments);
                        $pay_arr = array();
                        foreach($all_payments as $p){
                            $pay_arr[] = $p->payment_id;
                        }
                        $payment_ids = implode ('x', $pay_arr);
                    }
                    if ($loop->first){
                    $last_schedule_id = \App\Models\LoanCalculate::where('disbursement_id',$report->loan_id)
                        ->where(function ($w){
                            $w->orWhere('principal_p','>',0)
                                ->orWhere('interest_p','>',0)
                                ->orWhere('penalty_p','>',0)
                                ->orWhere('service_charge_p','>',0)
                                ->orWhere('compulsory_p','>',0)
                            ;
                        })->max('id');
                        //dd($report);
                    }
                    $loan = \App\Models\Loan::find($report->loan_id);
                    //dd($report->payment_id);
                    $payment = \App\Models\LoanPayment::find($report->payment_id,['payment_number']);
                    
                    if($payment != Null){
                        $acc = \App\Models\GeneralJournal::where('reference_no',optional($payment)->payment_number)->get()->toArray();
                        $result = array();
                        foreach ($acc as $ac){
                            $result[] =$ac['id'];
                        } ;
                        //dd($loan->client_id);
                    $acc_detail = \App\Models\GeneralJournalDetail::whereIn('journal_id',$result)->where('description','Payment')->where('name',optional($loan)->client_id)->first();
                    if($acc_detail == Null){
                        $acc_detail = \App\Models\GeneralJournalDetail::where('tran_id',$report->payment_id)->where('description','Payment')->first();
                    }
                     //dd($acc_detail);
                    $cash_acc = \App\Models\AccountChart::where('code',optional($acc_detail)->acc_chart_code)->first();
                    }
                    else{
                       $acc_detail = \App\Models\GeneralJournalDetail::where('tran_id',$report->payment_id)->where('description','Payment')->first();
                       $payment_num = \App\Models\GeneralJournal::where('id',optional($acc_detail)->journal_id)->first();
                       $cash_acc = \App\Models\AccountChart::where('code',optional($acc_detail)->acc_chart_code)->first();  
                    }
                    
                    $schedule_backup = \App\Models\ScheduleBackup::where('loan_id',$report->loan_id)->first();

                    $total_principle += $report->principal_p;
                    $total_interest += $report->interest_p;
                    $total_charge += $report->service_charge_p;
                    $total_penalty += $report->penalty_p;
                    $total_compulsory += $report->compulsory_p;
            ?>
            <tr>
                <td>{{ \Carbon\Carbon::parse($report->payment_date)->format('Y-m-d') }}</td>
                <td>{{ optional($cash_acc)->name}}</td>
                @if($payment != Null)
                <td>{{ optional($payment)->payment_number}}</td>
                @else
                <td>{{ optional($payment_num)->reference_no}}</td>
                @endif
                <td>{{ $report->principal_p }}</td>
                <td>{{ $report->interest_p }}</td>
                <td>{{ $report->service_charge_p }}</td>
                <td>{{ $report->penalty_p }}</td>
                <td>{{ $report->compulsory_p }}</td>
                <td>{{ $report->owed_balance }}</td>
                <?php 
                    $schedule_last = $last_schedule_id == $report->schedule_id ? "" : "| Not Last Schedule!";
                    $last = $loop->last ? "" : "| Not Last Payment";
                ?>
                @if(companyReportPart() == "company.moeyan" && backpack_user()->can('delete-loan-payment'))
                    @if($last_schedule_id == $report->schedule_id && $loop->last)
                        <td style="width: 80px"><a href="{{url("/api/delete-payment")}}" data-payment_id="{{$report->payment_id}}" class="btn btn-xs btn-danger roll-back">Roll Back</a>
                        <a href="{{url("/api/delete-all-payments")}}" data-loan_id="{{$report->loan_id}}" data-payment_ids="{{$payment_ids}}" class="btn btn-xs btn-danger roll-back-all">Roll Back All</a></td>
                    @else
                        <td style="width: 80px"><span class="badge badge-danger">{{$schedule_last}} {{$last}}</span></td>
                    @endif
                @elseif($last_schedule_id == $report->schedule_id && $loop->last && $schedule_backup != null && companyReportPart() != "company.moeyan")
                    <td style="width: 80px"><a href="{{url("/api/delete-payment")}}" data-payment_id="{{$report->payment_id}}" class="btn btn-xs btn-danger roll-back">Roll Back</a></td>
                @else
                    <td></td>
                @endif

            </tr>
        @endforeach
            <tr>
                <td colspan="3" style="text-align: right;"><b>Total</b></td>
                <td>{{$total_principle}}</td>
                <td>{{$total_interest}}</td>
                <td>{{$total_charge}}</td>
                <td>{{$total_penalty}}</td>
                <td>{{$total_compulsory}}</td>
            </tr>
        @endif
    </table>
    <script>
        $('.roll-back').on('click',function (e) {
            e.preventDefault();
            var url = $(this).prop('href');
            var payment_id = $(this).data('payment_id');
            var note = $('.note').val();

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    payment_id: payment_id,
                    note: note,
                },
                success: function (res) {
                    window.location.reload();
                }

            });

        });
        $('.roll-back-all').on('click',function (e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete all Payments?!")) {
                var url = $(this).prop('href');
                var payment_ids = $(this).data('payment_ids');
                var loan_id = $(this).data('loan_id');
                var note = $('.note').val();
            
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        payment_ids: payment_ids,
                        loan_id: loan_id,
                        note: note,
                    },
                    success: function (res) {
                        window.location.reload();
                    }

                });
            } 

        });
    </script>
</body>
</html>
