
@if($g_journals != null)
    <?php
    $total_dr = 0;
    $total_cr = 0;
    $journal_id=0;
    //$array=json_decode($_GET);
    //dd($_GET);
    //dd($g_journals);
    ?>
    @foreach($g_journals as $g)
        <?php
            $data = \App\Models\GeneralJournalDetail::where('journal_id',$g->id)->get();
        //    $data = \App\Models\GeneralJournalDetail::where('journal_id',$g->id)->get();
        //dd($g);
        ?>

        @foreach($data as $g)


            <?php

            if(isset($_REQUEST['client_id']) && $_REQUEST['client_id'] != 0){
                $client_id = $_REQUEST['client_id'];
                $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->where('name', $client_id)->first();
            }
            else {
                $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->first();
            }

            $branch_id = optional($g_d)->branch_id;
            if($g_d != null){
            if(companyReportPart() == "company.moeyan" && $g_d->tran_type == "payment"){
                $payment = \App\Models\LoanPayment::find($g_d->tran_id);
                if($payment){
                    $loan = \App\Models\Loan::find($payment->disbursement_id);
                    if(isset($_REQUEST['branch_id']) && $_REQUEST['branch_id'] != 0){
                        if($g_d->branch_id != $loan->branch_id){
                            if($g_d->dr > 0){
                                if($g_d->branch_id != $_REQUEST['branch_id']){
                                    $g_d = null;
                                }
                                else{
                                    $branch_id = $g_d->branch_id;
                                }
                            }
                            else if($g_d->cr > 0){
                                if($loan->branch_id != $_REQUEST['branch_id']){
                                    $g_d = null;
                                }else{
                                    $branch_id = $loan->branch_id;
                                }
                            }
                        }
                        else{
                            if($g_d->branch_id != $_REQUEST['branch_id']){
                                $g_d = null;
                            }
                        }
                    }else{
                        if($g_d->branch_id != $loan->branch_id){
                            if($g_d->dr > 0){
                                $branch_id = $g_d->branch_id;
                            }
                            else if($g_d->cr > 0){
                                $branch_id = $loan->branch_id;
                            }
                        }
                    }
                }
            }
        }

            $t_dr = 0;
            $t_cr = 0;

            ?>
            @if($g_d != null)
                <?php
                    $ref = '';
                    $type = $g_d->tran_type;
                    $cus = optional(\App\Models\Customer::find(optional($g_d)->name));
                    $acc_chart_name = \App\Models\AccountChart::where('code',$g_d->acc_chart_code)->first();
                    if($type == 'loan-deposit'){
                        $deposit = \App\Models\LoanDeposit::find($g_d->tran_id);
                        $ref = optional($deposit)->referent_no;
                    }
                    else if($type == 'loan-disbursement'){
                        $deposit = \App\Models\PaidDisbursement::find($g_d->tran_id);
                        $ref = optional($deposit)->reference;
                    }
                    else if($type == 'payment'){
                        $deposit = \App\Models\LoanPayment::find($g_d->tran_id);
                        $ref = optional($deposit)->payment_number;
                    }
                    else if($type == 'transfer'){
                        $deposit = \App\Models\Transfer::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else if($type == 'expense'){
                        $deposit = \App\Models\Expense::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else if($type == 'profit'){
                        $deposit = \App\Models\JournalProfit::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else {
                        $deposit = \App\Models\GeneralJournal::find($g_d->journal_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    $j_id=$g_d->journal_id;
                    $branch = \App\Models\Branch::find($branch_id);
                    $branch_acc_code = \App\Models\AccountChart::find(optional($branch)->cash_account_id);
                    //dd($j_id,$journal_id);
                ?>
                <tr>
                    <td style="white-space: nowrap;text-transform: capitalize">
                        @if ($j_id != $journal_id)
                            {{$g_d->tran_type}}
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @if ($j_id != $journal_id)
                            {{$g_d->j_detail_date->format('Y-m-d')}}
                        @endif
                    </td>
                    <td style="text-align: center;white-space: nowrap;">
                        @if ($j_id != $journal_id)
                            {{$ref}}
                        @endif
                    </td>
                    <td style="white-space: nowrap;">

                        {{$ref}}</td>
                    <td style="white-space: nowrap;">

                        {{optional($branch)->code}} - {{optional($branch)->title}}</td>
                    @if(companyReportPart() == 'company.mkt')
                        <td style="white-space: nowrap;">

                            {{Auth()->user()->user_code}}
                        </td>
                    @endif
                    <td style="white-space: nowrap;">
                        {{optional($cus)->client_number}}
                    </td>
                    <td style="white-space: nowrap;">
                        {{optional($cus)->name}}
                    </td>
                    <td style="white-space: nowrap;">{{$g_d->external_acc_chart_code}}</td>
                    @if (isset($g_d->acc_chart_code))
                        <td style="white-space: nowrap;">{{$g_d->acc_chart_code}}</td>
                    @else
                        <td style="white-space: nowrap;">{{$branch_acc_code->code}}</td>
                    @endif
                    @if (isset($acc_chart_name->name))
                        <td>{{$acc_chart_name->name}}</td>
                    @else
                        <td style="white-space: nowrap;">{{optional($branch_acc_code)->name}}</td>
                    @endif
                    <td>{!! $g_d->description !!}</td>
                    <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,2):''}}</td>
                    <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,2):''}}</td>

                    <td style="text-align: center; white-space: nowrap;">
                        @if ($j_id != $journal_id)
                            @if($g_d->tran_type == 'journal')

                                @if(_can('update-general-journal'))
                                    <a href="{{url('admin/general-journal/'.$g->journal_id.'/edit')}}" class="btn btn-xs btn-warning" ><i class="fa fa-edit"></i> </a>
                                @endif

                                @if(_can('update-general-journal'))
                                    <a href="{{url('admin/delete-journal/'.$g->journal_id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i> </a>
                                @endif
                            @endif
                            @php
                                $generalJournal = \App\Models\GeneralJournal::find($g_d->journal_id);
                                $ref_no  = explode('-', $generalJournal->reference_no, 2);
                            @endphp
                            @if($ref_no[0] == "JRN" && companyReportPart() == 'company.moeyan' && $g)
                                <input type="hidden" id="ledger_id" value="{{$g->id}}">
                                <a href="" class="modalId btn btn-xs btn-primary open-modal" ><i class="fa fa-print"></i></a>
                                @php
                                    $general = \App\Models\GeneralJournal::find($g->journal_id);
                                @endphp
                                @if($general->attach_document)
                                    <a href="{{url('admin/attach-document/'.$g->journal_id)}}" class="btn btn-xs btn-success" ><i class="fa fa-cloud-download"></i> </a>
                                @endif
                            @endif

                        @endif
                    </td>
                    @if($g_d->tran_type == 'payment' && $g_d->description == "Payment")
                        <td style="text-align: center; white-space: nowrap;">

                            @if(_can('delete-general-journal') && $deposit== null)
                                <a href="{{url('admin/delete-journal/'.$g->journal_id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i></a>
                            @endif

                        </td>
                    @endif
                </tr>
                @if ($j_id != $journal_id)
                    <?php
                    $journal_id=$j_id;
                    ?>
                @endif
                <?php
                $total_dr += $g_d->dr;
                $total_cr += $g_d->cr;
                ?>
            @endif


        @endforeach


    @endforeach

    <tr>
        @if(companyReportPart() == 'company.mkt')
            <td colspan="12"><b>Total</b></td>
        @else
            <td colspan="11"><b>Total</b></td>
        @endif
        <td style="text-align: right"><b>{{$total_dr>0?numb_format($total_dr,2):''}}</b></td>
        <td style="text-align: right"><b>{{$total_cr>0?numb_format($total_cr,2):''}}</b></td>
        <td></td>
        <td></td>
    </tr>

@endif
@if(companyReportPart() == 'company.moeyan')
<div class="modal fade" id="show-detail-print"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="container" id="DivIdToPrintPop">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" id="hidden_id">
                                <label for="reference"><h3>Reference No</h3></label>
                                <input type="text" id="reference" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date"><h3>Date</h3></label>
                                <input type="text" id="date" class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="currency"><h3>Currency</h3></label>
                                <input type="text" id="currency" class="form-control" readonly>
                            </div>
                            <div class="col-md-11 mt-2">
                                <label for="branch"><h3>Branch</h3></label>
                                <input type="text" id="branch" class="form-control" readonly>
                            </div>
                            <div class="col-md-11 mt-2">
                                <label for="note"><h3>Note</h3></label>
                                <input type="text" id="note" class="form-control" readonly>
                            </div>
                            <div class="col-md-11 mt-2">
                                <label for="ch_acc"><h3>Chart Account</h3></label>
                                <input type="text" id="ch_acc" class="form-control" readonly>
                            </div>
                            <div class="col-md-4" style="margin-top:20px;">
                                <input type="text" value="Chart Account" class="form-control" readonly style="background-color:#3c8dbc;color:white;font-size:24px;">
                            </div>
                            <div class="col-md-3" style="margin-top:20px;">
                                <input type="text" value="Note" class="form-control" readonly style="background-color:#3c8dbc;color:white;font-size:24px;text-align:center;">
                            </div>
                            <div class="col-md-2" style="margin-top:20px;">
                                <input type="text" value="Debit" class="form-control" readonly style="background-color:#3c8dbc;color:white;font-size:24px;text-align:center;">
                            </div>
                            <div class="col-md-2" style="margin-top:20px;">
                                <input type="text" value="Credit" class="form-control" readonly style="background-color:#3c8dbc;color:white;font-size:24px;text-align:center;">
                            </div>

                            <div class="col-md-4" id="acc_res" style="margin-top:10px;">
                                
                            </div>
                            <div class="col-md-3" id="note_res" style="margin-top:10px;">
                                
                            </div>
                            <div class="col-md-2" id="debit" style="margin-top:10px;">
                                
                            </div>
                            <div class="col-md-2" id="credit" style="margin-top:10px;">
                                
                            </div>
                            <div class="col-md-7">
                                <input type="text" value="Total" style="font-size:16px;text-align:right;border:none;background-color:white;" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="debit_total" style="text-align:left;border:none;background-color:white;" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="credit_total" style="text-align:left;border:none;background-color:white;" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @isset($g)
                    <input type="hidden" id="journal-id">
                    <a href="#" onclick="changeLink()" class="link btn btn-md btn-info"><i class="fa fa-print"></i></a>
                    @endisset
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
</div>
@endif
<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
<script>
    $(document).ready(function () {
    $(".open-modal").click(function () {
        $('#journal-id').val($("#ledger_id").val());
    });
});
    function changeLink(){
        var jid = $('#journal-id').val();
        location.replace("{{url('admin/print_journal/')}}" +"/"+ jid);
    }
    
    $(function(){
        $(document).on("click", ".modalId", function (e) {
        e.preventDefault();
        $('#show-detail-print').modal("show");
        
        var id=$(this).attr('data-id');
        $("#acc_res").empty();
        $("#note_res").empty();
        $("#debit").empty();
        $("#credit").empty();
        $.ajax({
                method: 'GET',
                url: '{{url('admin/print-journal/')}}'+ '/' + id,
                data: {
                        id: id,
                },
                success: function (res) {
                    //console.log(res);
                    var branch_obj = res[3];
                    var branch = branch_obj.code + "-" + branch_obj.title;
                    var acc_arr = res[5];
                    var acc_note = res[4];
                    var debit = res[6];
                    var credit = res[7];
                    var debit_total = debit.reduce(function(a,b){
                        return a+b;
                    });
                    var credit_total = credit.reduce(function(a,b){
                        return a+b;
                    });
                    
                    for (var i = 0;i < acc_arr.length ; i++) {
                        $("#acc_res").append(acc_arr[i].code + "-" + acc_arr[i].name + "<br>" + "<br>");  
                        $('#note_res').append(acc_note + "<br>" + "<br>");
                        $('#debit').append(debit[i] + "<br>" + "<br>");
                        $('#credit').append(credit[i] + "<br>" + "<br>");
                    }
                    $('#reference').val(res[0]);
                    $('#date').val(res[1]);
                    $('#currency').val(res[2]);
                    $('#branch').val(branch);
                    $('#note').val(res[4]);
                    $('#debit_total').val(debit_total);
                    $('#credit_total').val(credit_total);
                }
        });
    });
    });
</script>