<?php
$uid = time() . rand(1, 9999) . rand(1, 9999);
?>
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

    .my-table tr td {
        font-size: 12px;
    }
</style>
<style>
    #popup{{  $uid }} {
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
    #iframe{{  $uid }} {
        border: 0;
        width: 100%;
        height: 100%;
        /*margin: 30px auto;*/
        position: relative;
        display: block;
        box-shadow: 0 2px 3px rgba(0,0,0,0.125);
    }

     #page{{  $uid }} { height: 100%; }

</style>

@if($row != null)
    {{--{{dd($row)}}--}}

    <div class="clear-{{  $uid }}">
        <!--<table width="100%" class="my-table">
{{--        vyrom--}}

            <?php
            $client = optional(\App\Models\Client::find($row->client_id));
            $guarantor = optional(\App\Models\Guarantor::find($row->guarantor_id));

            $disburse_detail = \App\Models\LoanCalculate::where('disbursement_id', $row->id)->orderBy('id')->get();
            $c = $disburse_detail != null?count($disburse_detail):0;
            ?>

            <tr>
                <td>Contract No</td>
                <td>{{$row->disbursement_number}}</td>
                <td>CMember ID</td>
                <td>xc</td>
            </tr>
            <tr>
                <td>Client Name</td>
                <td>{{$client->name_other}}</td>
            </tr>
            <tr>
                <td>NRC</td>
                <td>{{$client->nrc_number}}</td>

                <td>Father's Name</td>
                <td>{{$client->father_name}}</td>


            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>{{$client->dob}}</td>
                <td>Phone No</td>
                <td>{{$client->primary_phone_number}}</td>
            </tr>
            <tr>
                <td>Position</td>
                <td>{{$client->occupation_of_husband}}</td>

                <td>School Name</td>
                <td>xxx</td>


            </tr>
            <tr>
                <td>Address</td>
                <td>{{$client->address1}}</td>
                <td>Position</td>
                <td>{{$client->occupation_of_husband}}</td>
            </tr>
            <tr>
                <td>Guarantor Name</td>
                <td>{{$guarantor->full_name_mm}}</td>
                <td>School Name</td>
                <td>97890</td>
            </tr>
            <tr>
                <td>NRC</td>
                <td>{{$guarantor->nrc_number}}</td>
                <td>Duration</td>
                <td>{{$row->loan_term_value}}</td>
            </tr>
            <tr>
                <td>Phone No</td>
                <td>{{$guarantor->phone}}</td>
                <td>First Repayment Month</td>
                <td>qwewqe</td>
            </tr>
            <tr>
                <td>Interest Rate</td>
                <td>{{$row->interest_rate}}</td>
                <td>Last Repayment Month</td>
                <td>31232</td>
            </tr>
            <tr>
                <td>Repayment Schedule</td>
                <td>{{$row->repayment_term}}</td>
            </tr>


        </table> -->
          <b> Repayment Form </b>
        <br>
        <br>
        <br>
        <table width="100%" class="my-table">
            <thead class="border">
            <tr>
                <th></th>
                <th>No</th>
                <td>Month</td>
                <td>Principal</td>
                <td>Interest</td>
                <td>Total</td>
                <td>Balance</td>
                <td></td>
            </tr>
            </thead>


            <tbody class="border">

            @if($disburse_detail != null)

                @php
                    {{
                        $i = 1;
                        $total_prin = 0;
                        $total_int = 0;
                        $total_pay = 0;
                    }}
                    
                @endphp

                @foreach($disburse_detail as $r)
                    <tr>
                        <?php
                        $total_p = $r->principal_p+$r->interest_p+$r->compulsory_p+$r->service_charge_p;
                        ?>
                        <td>
                            @if($r->payment_status == 'paid')
                                <i class="fa fa-check"></i>
                            @else
                                <input data-index="{{ $loop->index }}" id="payment-checked-{{  $uid }}-{{ $loop->index }}" type="checkbox" class="payment-checked-{{  $uid }}" data-id="{{$r->id}}">
                            @endif
                        </td>
                        <td>{{$r->no}}</td>
                        <td>{{date('d-m-Y', strtotime($r->date_s))}}</td>
                        <td class="right">{{number_format($r->principal_s)}}</td>
                        <td class="right">{{number_format($r->interest_s)}}</td>
                        <td class="right">{{number_format($r->total_s)}}</td>
                        <td class="right">{{number_format($r->balance_s)}}</td>
                        @if($total_p >0)
                            <td><a href="{{url("/admin/view-payment?schedule_id={$r->id}")}}" data-remote="false" data-toggle="modal" data-target="#show-view-payment-d" class="btn btn-xs btn-info" data-backdrop="false"><i class="fa fa-eye"></i></a></td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                    @php
                        {{
                            $i++;
                            $total_prin += $r->principal_s;
                            $total_int += $r->interest_s;
                            $total_pay += $r->total_s;

                         }}
                    @endphp
                @endforeach
                <th></th>
                <th> </th>
                <td> </td>
                <td class="right">{{number_format($total_prin,0)}}</td>
                <td class="right">{{number_format($total_int,0)}}</td>
                <td class="right">{{number_format($total_pay,0)}}</td>
                <td></td>
                <td></td>
            @endif


            </tbody>

            <tfoot>

            </tfoot>


        </table>

            <div class="modal fade" id="show-view-payment-d" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
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
        <div class="row">
            <div class="col-md-12" style="margin: 0;padding-right: 0">
                <button class="btn btn-primary btn-flat btn-block btn-payment-{{  $uid }}"><i class="fa fa-money"></i>Add Payment</button>
            </div>
            <!--<div class="col-md-4" style="margin: 0;padding: 0;">
                <a class="btn btn-primary btn-flat btn-block"><i class="fa fa-money"></i>Customer Promise</a>
            </div>
            <div class="col-md-4" style="margin: 0;padding-left: 0;">
                <a class="btn btn-primary btn-flat btn-block"><i class="fa fa-edit"></i>Change Date</a>
            </div>-->
        </div>
        <div id="page{{  $uid }}" style="width: 100%;">
            <div id="popup{{  $uid }}">
                <iframe id="iframe{{  $uid }}"></iframe>
            </div>
        </div>
    </div>
@endif
    <script>

        $(function () {
            $('.payment-checked-{{  $uid }}').attr("disabled", true);
            $('.payment-checked-{{  $uid }}:first').removeAttr("disabled");

            $('body').on('change','.payment-checked-{{  $uid }}',function () {
                var index = $(this).data('index')-0 + 1;
                if ( this.checked ) {
                    $('#payment-checked-{{  $uid }}-' + index).removeAttr("disabled");
                }else {
                    var cc = '{{ $c }}' - 0;
                    for ( ii = index; ii < cc; ii++) {
                        $('#payment-checked-{{  $uid }}-' + ii).prop('checked', false);
                        $('#payment-checked-{{  $uid }}-' + ii).attr("disabled", true);
                    }
                }
            });
            $("#show-view-payment-d").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);

                $(this).find(".modal-body").load(link.attr("href"));
            });


        });

        $(function () {


            $('body').on('click','.btn-payment-{{  $uid }}',function (e) {

                e.preventDefault();
                var i = 0;
                var param = '';
                $('.payment-checked-{{  $uid }}:checked').each(function () {
                    var id_c = $(this).data('id') -0;
                    param += ((i>0?'x':'') + '"' +id_c + '"');
                    i++;
                });

                if(param != '' && i >0){

                    var src = '{!! url('admin/loanpayment/create?is_frame=1&id='.$row->id) !!}&param=' + param ;

                    $('#popup{{  $uid }}').show();
                    $('#iframe{{  $uid }}').prop('src',src);
                    //document.getElementById('page').className = "darken";

                    document.getElementById('page{{  $uid }}').onclick = function() {
                        $('#popup{{  $uid }}').hide();
                        $('#iframe{{  $uid }}').prop('src','');
                    };


                    return false;

                }



            });
        });

        function myIframe{{  $uid }}() {
            $('#popup{{  $uid }}').hide();
            $('#clear-{{  $uid }}').remove();
        }
    </script>





