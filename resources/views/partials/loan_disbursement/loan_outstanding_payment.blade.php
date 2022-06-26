<?php
    $disburse_detail = \App\Models\LoanCalculate::where('disbursement_id',$row->id)->orderBy('id')->get();
    //$double_check = \App\Models\LoanPayment::where('disbursement_id')
    $c = $disburse_detail != null?count($disburse_detail):0;
    //dd($disburse_detail);
?>
<div id="clear-{{ $row->id }}">
<table border="1" class="" style="border-collapse: collapse;min-width: 1000px;">
    <thead>
        <tr class="bg-success" style="height: 25px;padding: 5px">
            <th style="padding: 5px;"></th>
            <th style="padding: 5px;">No</th>
            <th style="padding: 5px;">Date</th>
            <th style="padding: 5px;">Principal</th>
            <th style="padding: 5px;">Interest</th>
            <th style="padding: 5px;">Payment</th>
            <th style="padding: 5px;">Balance</th>
            <th style="padding: 5px;">Remark</th>
            <th style="padding: 5px;text-align:center">Action</th>
        </tr>
    </thead>
    @if($disburse_detail != null)
    @php
        //dd($disburse_detail);
    @endphp
        @foreach($disburse_detail as $r)
        @php
              //dd($r);
        @endphp
      
            <tr  style="height: 25px;">
                <?php
                    $total_p = $r->principal_p+$r->interest_p+$r->compulsory_p+$r->service_charge_p;
                ?>
                <td style="padding: 5px;">
                    @if($r->payment_status == 'paid')
                        <i class="fa fa-check"></i>
                    @else
                        <input data-index="{{ $loop->index }}" id="payment-checked-{{ $row->id }}-{{ $loop->index }}" type="checkbox" class="payment-checked-{{ $row->id }}" data-id="{{$r->id}}">
                    @endif
                </td>
                <td style="padding: 5px;">{{$r->no}}</td>
                <td style="padding: 5px;">{{\Carbon\Carbon::parse($r->date_s)->format('Y-m-d')}}</td>
                <td style="text-align: left;padding: 5px;">{{number_format($r->principal_s,-2)}}</td>
                <td style="text-align: left;padding: 5px;">{{number_format($r->interest_s,-2)}}</td>
                <td style="text-align: left;padding: 5px;">{{number_format($r->total_s,-2)}}</td>
                <td style="text-align: left;padding: 5px;">{{number_format($r->balance_s,-2)}}</td>
                <td style="text-align: left;padding: 5px;"> <input type="text" class="remark" style="width:100%" data-id="{{$r->id}}" value="{{$r->remark}}"></td>
                @php
                    $r_id = \App\Models\PaymentHistory::where('schedule_id',$r->id)->orderBy('id', 'DESC')->first();
                @endphp
                <td style="padding: 5px;">
                @if (companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.mkt')
                <button class="btn btn-xs btn-success change_date  m-3" data-date="{{$r->id}}" data-toggle="modal" data-target="#change-date" title="Schedule Date"><i class="fa fa-calendar"></i></button>
                @endif
                @if($total_p >0 && $r_id)
                <a href="{{url("api/print-loan-payment?schedule_id={$r->id}&id={$r_id->payment_id}")}}" data-remote="false" data-toggle="modal" data-target="#show-view-payment" class="btn btn-xs btn-info" title="Print Payment"><i class="fa fa-print"></i></a>
                @else
                @endif
                @if($total_p >0 && $r)
                <a href="{{url("/admin/view-payment?schedule_id={$r->id}&loan_id={$r->disbursement_id}")}}" data-remote="false" data-toggle="modal" data-target="#show-view-payment" class="btn btn-xs btn-info" title="View Payment"><i class="fa fa-eye"></i></a>
                @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="9" style="padding: 20px;border: none">
                <button class="btn btn-info btn-flat btn-block btn-payment-{{ $row->id }}"><i class="fa fa-money"></i>Add Payment</button>
            </td>
        </tr>

    @endif
</table>

</div>
<div id="page{{ $row->id }}" style="width: 100%; height: 80%">
    <div id="popup{{ $row->id }}">
        <iframe id="iframe{{ $row->id }}"></iframe>
    </div>
</div>
<div class="modal fade" id="show-view-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-x" style="width: 100%;" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                {{-- <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>--}}
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="change-date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Schedule Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="message"></div>
          <div class="display-inline">
          <input type="hidden" class="date_id form-control">
          <input type="date" class="form-control" style="width:100%;" id="changeDate">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary change">Change</button>
      </div>
    </div>
  </div>
</div>
<style>
    #popup{{ $row->id }} {
        background-color: white;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 9999;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0;
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4)
    }
    #iframe{{ $row->id }} {
        border: 0;
        width: 70%;
        height: 80%;
        margin: 30px auto;
        position: relative;
        display: block;
        box-shadow: 0 2px 3px rgba(0,0,0,0.125);
    }

    html, body, #page{{ $row->id }} { height: 100%; }



</style>
<script>

    $(function () {

        $('.payment-checked-{{ $row->id }}').attr("disabled", true);
        $('.payment-checked-{{ $row->id }}:first').removeAttr("disabled");

        $('body').on('change','.payment-checked-{{ $row->id }}',function () {
            var index = $(this).data('index')-0 + 1;
            if ( this.checked ) {
                $('#payment-checked-{{ $row->id }}-' + index).removeAttr("disabled");
            }else {
                var cc = '{{ $c }}' - 0;
                for ( ii = index; ii < cc; ii++) {
                    $('#payment-checked-{{ $row->id }}-' + ii).prop('checked', false);
                    $('#payment-checked-{{ $row->id }}-' + ii).attr("disabled", true);
                }
            }
        });


    });

    $(function () {
        $(document).ready(function(){
            $(".remark").change(function(){
                var s_id = $(this).data('id');
                var remark = $(this).val();
                var url = "/admin/schedule-remark"
                $.ajax({
                    url : url,
                    type : "GET",
                    data: {
                        id : s_id,
                        remark : remark
                    },
                    dataType : "json",
                    success:function(data)
                    {
                        if(data){
                            
                        }
                    }
                });
            });
        });
        $("#show-view-payment").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);

            $(this).find(".modal-body").load(link.attr("href"));
        });
        $('body').on('click','.btn-payment-{{ $row->id }}',function (e) {
            e.preventDefault();
            var i = 0;
            var param = '';
            $('.payment-checked-{{ $row->id }}:checked').each(function () {
                var id_c = $(this).data('id') -0;
                param += ((i>0?'x':'') + '"'+id_c+'"');
                i++;
            });

            if(param != '' && i >0){

                var src = '{!! url('admin/loanpayment/create?is_frame=1&id='.$row->id) !!}&param=' + param ;

                $('#popup{{ $row->id }}').show();
                $('#iframe{{ $row->id }}').prop('src',src);
                //document.getElementById('page').className = "darken";

                document.getElementById('page{{ $row->id }}').onclick = function() {
                    $('#popup{{ $row->id }}').hide();
                    window.location.reload();
                    $('#iframe{{ $row->id }}').prop('src','');
                };


                return false;

            }
        });
            $('.change_date').click(function(e){
                e.preventDefault();
                var date_id = $(this).data('date');
                $('.date_id').val(date_id);
            });
            $('.change').click(function(){
                //alert('hello');
                var id = $('.date_id').val();
                var changeDate = $('#changeDate').val();
                    if(changeDate != ""){
                $.ajax({
                   method : "GET",
                    url: "{{route('change_date')}}",
                    data: {changeDate:changeDate,id:id},
                    success:function(data){
                        if(data == "Error"){
                        $('#message').fadeIn().html("<div class='alert alert-danger'>"+data+"!</div>");
                            setTimeout(function() {
                                $('#message').fadeOut("slow");
                        }, 2000 );
                    }
                    else{
                        $('#message').fadeIn().html("<div class='alert alert-success'>Date Changed Successfully!</div>");
                            setTimeout(function() {
                                $('#message').fadeOut("slow");
                        }, 2000 );
                        window.location.reload();
                    }
                    }
                });
                }
                });
        
    });
    function myIframe{{ $row->id }}() {
        $('#popup{{ $row->id }}').hide();
        window.location.reload();
        $('#clear-{{ $row->id }}').remove();
    }
</script>
