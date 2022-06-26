@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')

    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

@endsection

@section('content')
    <div class="container-fluid">
        <div class="form-group col-md-3">
            <select class="form-control select2_field-center" data-source="{{url("api/get-center-leader-name-id")}}" multiple name="center_id[]"  >
                @if(isset($_REQUEST['center_id']))
                    <?php
                        $cen = \App\Models\CenterLeader::find($_REQUEST['center_id']);
                    ?>
                    @if($cen != null)
                    <option value="{{$cen->id}}">
                        {{$cen->code}}
                    </option>
                    @endif
               @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <select class="form-control select2_field-group" data-source="{{url("api/get-group-loan2")}}" multiple name="group_id[]" >
                @if(isset($_REQUEST['group_id']))
                    <?php
                    $l_p = \App\Models\GroupLoan::find($_REQUEST['group_id']);
                    ?>
                    @if($l_p != null)
                        <option value="{{$l_p->id}}">
                            {{$l_p->group_code}}
                        </option>
                    @endif
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <select class="form-control select2_field-loan-product" data-source="{{url("api/get-loan-product")}}" multiple name="loan_product[]" >
                @if(isset($_REQUEST['loan_product_id']))
                    <?php
                    $gr = \App\Models\LoanProduct::find($_REQUEST['loan_product_id']);
                    ?>
                    @if($gr != null)
                        <option value="{{$gr->id}}">
                            {{$gr->name}}
                        </option>
                    @endif
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <button class="btn btn-danger remove-filter">Clear</button>
        </div>
            <div class="form-group col-md-4">

            </div>

        <a hidden href="javascript:void(0)" id="remove-filter"><i class="fa fa-eraser"></i> Remove Filters</a>

    </div>
    <form method="post" action="{{url('admin/group-repayment')}}" >
        {!! csrf_field() !!}
    <div class="shadow" style="background: #fff; padding: 10px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="b-group">
                   <table class="table table-bordered" style="width: 100%;background-color: white">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="check_all_group"></th>
                        <th>CenterID-Name</th>
                        <th>Group ID</th>
                        <th>Group Name</th>
                        <th>Payment Date</th>
                       {{-- <th>Term No</th>--}}
                        <th>Loan Product</th>
                        <th>Service</th>
                        <th>Compulsory</th>
                        <th>Principle</th>
                        <th>Interest</th>
                        <th>Total Payment</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($g_pending != null)
                            <?php
                                $total_service = 0;
                                $total_compulsory = 0;
                                $total_interest = 0;
                                $total_principle = 0;
                                $total_payment = 0;
                            ?>
                            @foreach($g_pending as $r)
                                @if(isset($date_s[$r->group_id]))
                                <?php
                                $loan_d = $gg[$r->group_id];
                                $rand_id = rand(1,1000).time().rand(1,1000);
                                $_principle = $loan_d->principal_s - ($loan_d->principle_pd ?? 0);
                                $_interest = $loan_d->interest_s - ($loan_d->interest_pd ?? 0);
                                $charge = $loan_d->charge_schedule - $loan_d->service_pd;
                                $compulsory = $loan_d->compulsory_schedule - $loan_d->compulsory_pd;
                                $group = \App\Models\GroupLoan::find($r->group_id);
                                $center = \App\Models\CenterLeader::find(optional($group)->center_id);
                                $total = $_principle + $_interest + $charge +$compulsory;
                                $loan = \App\Models\Loan::where('group_loan_id',$r->group_id)->first();
                                $loan_product = \App\Models\LoanProduct::find(optional($loan)->loan_production_id);
                                $date = $date_s[$r->group_id];
                                $total_service += $charge;
                                $total_compulsory += $compulsory;
                                $total_interest += $_interest;
                                $total_principle += $_principle;
                                $total_payment += $total;
                                ?>
                                <tr>
                                    <td><input type="checkbox" data-payment="{{$total}}" class="form-check-input c-checked-group" name="approve_check[{{$rand_id}}]" value="{{$r->group_id}}"/></td>
                                    <td>{{optional($center)->code}}</td>
                                    <td>{{optional($group)->group_code}}</td>
                                    <td>{{optional($group)->group_name}}</td>
                                    <td>{{\Carbon\Carbon::parse($date)->format('Y-m-d')}}</td>
                                    <td>{{optional($loan_product)->name}}</td>
                                    <td>{{numb_format($charge,2)}}</td>
                                    <td>{{numb_format($compulsory,2)}}</td>
                                    <td>{{numb_format($_principle,2)}}</td>
                                    <td>{{numb_format($_interest,2)}}</td>
                                    <td>{{numb_format($total,2)}}</td>
                                    <td>
                                        <a href="{{url("admin/list-member-repayment?group_loan_id={$r->group_id}&rand_id={$rand_id}")}}"
                                           data-remote="false" data-toggle="modal" data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>

                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right; padding-right: 50px;"><b>Total</b></td>

                        <td>{{numb_format($total_service,2)}}</td>
                        <td>{{numb_format($total_compulsory,2)}}</td>

                        <td>{{numb_format($total_principle,2)}}</td>
                        <td>{{numb_format($total_interest,2)}}</td>
                        <td>{{numb_format($total_payment,2)}}</td>
                    </tr>
                    </tfoot>
                </table>
<div class="my-p-p-p">
    {!! $g_pending->links() !!}
</div>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Reference</label>
                    <input class="form-control" name="reference" value="{{\App\Models\GroupLoanTranSaction::grouppaymentSeqRef('group_repayment')}}">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Note</label>
                    <input class="form-control" name="approve_note">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Date</label>
                    <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                    <span></span>
                </div>
            </div>
            <div class="col-sm-4">
                <label>Cash Out *</label>
                <select class="form-control sel-cash-out" name="cash_out" required>
                    <?php
                    $branch_id = session('s_branch_id');
                    $br = \App\Models\BranchU::find($branch_id);
                    ?>
                    @if(optional($br)->cash_account_id >0)
                        <?php
                        $acc = \App\Models\AccountChart::find(optional($br)->cash_account_id);
                        ?>
                        @if($acc != null)
                            <option value="{{$acc->id}}">
                               {{$acc->code}}-{{$acc->name}}
                            </option>
                        @endif
                    @endif
                </select>
            </div>
            <div class="col-sm-4">
                <label>Cash Payment</label>
                <input type="number" class="form-control cash-topay" name="" value="" required>
            </div>
            <div class="col-sm-3">
                <input type="button" class="btn btn-sm btn-primary btn-pay-group" style="margin-top: 28px;" value="Payment" />
            </div>
        </div>

    </div>




    </form>

    <div class="modal fade" id="show-detail-modal-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel2"></h4>
                </div>
                <div class="modal-body">
                    <iframe id="iframe" style="width: 100%;height:500px"></iframe>
                </div>
                <div class="modal-footer">
                   {{-- <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('after_scripts')
    <!-- date-range-picker -->
    <script src="{{$base}}/bower_components/moment/min/moment.min.js"></script>
    <script src="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("#show-detail-modal-group").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $('#iframe').prop('src',link.attr("href"));
        });
        $(function () {
            $('.approve_date').datepicker(
                { format: 'yyyy-mm-dd'}
            );
            $('.remove-filter').on('click',function () {
                window.location.href = "{{url('admin/group-repayment-new')}}";
            });
            $('.btn-pay-group').on('click',function () {
                var cash_out = $('[name="cash_out"]').val()-0;
                if(cash_out >0){
                    $(this).hide();
                    $('.c-checked-group').each(function () {

                        if($(this).is(':checked')){
                            var group_id = $(this).val();
                            get_loan_by_group(group_id);
                        }
                        window.location.reload();
                    });
                }else {
                    alert('Please Select Cash Account!!');
                }

            });
        });
        function get_loan_by_group(group_id) {
            $.ajax({
                type: 'POST',
                url: '{{url('admin/get-loan-by-group')}}',
                dataType: 'JSON',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                async: false,
                data: {
                    group_id: group_id,
                },
                success: function (res) {
                    $.each(res,function (i,data) {
                        save_group_payment(data.client_id,data.client_number,
                        data.priciple,data.interest,data.payment,data.payment,data.id,data.disbursement_id,data.service,data.compulsory,data.priciple_balance,data.service_charge,data.charge_id);
                    })
                }

            });
        }


        function save_group_payment(client_id,client_number,priciple,interest,total_payment,payment,schedule_id,disbursement_id,service,compulsory,priciple_balance,service_charge,charge_id) {
            var reference = $('[name="reference"]').val();
            var approve_date = $('[name="approve_date"]').val();
            var cash_out = $('[name="cash_out"]').val();
            $.ajax({
                type: 'POST',
                url: '{{url('admin/loanpayment')}}',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                async: false,
                data: {
                    client_id: client_id,
                    client_number_: client_number,
                    principle: priciple,
                    interest: interest,
                    total_payment:total_payment,
                    payment: payment,
                    disbursement_detail_id: schedule_id,
                    disbursement_id: disbursement_id,
                    payment_number: reference,
                    cash_acc_id: cash_out,
                    payment_method: "cash",
                    owed_balance: 0,
                    payment_date: approve_date,
                    compulsory_saving: compulsory,
                    total_service_charge: service,
                    priciple_balance: priciple_balance,
                    service_charge: service_charge,
                    charge_id: charge_id,
                },
                success: function (res) {
                    //window.location.reload();
                }

            });
        }
    </script>

    <script>
        function check_b(){
            $('#check_all_group').on('change',function(event) {

                if(this.checked) { // check select status
                    $('.c-checked-group').each(function() {
                        this.checked = true;  //select all
                        sum_payment();
                    });
                }else{
                    $('.c-checked-group').each(function() {
                        this.checked = false; //deselect all
                        sum_payment();
                    });
                }
            });
            $('.c-checked-group').on('change',function () {
                sum_payment();
            });
        }
        $(function () {
            // $('.select-province').select2();
            check_b();

            $('.sel-cash-out').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/account-cash")}}',
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page, // pagination
                        };
                    },
                    processResults: function (data, params) {
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        params.page = params.page || 1;
                        var result = {
                            results: $.map(data.data, function (item) {

                                return {
                                    text: item["code"]+'-'+item["name"],
                                    id: item["id"]
                                }
                            }),
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        };
                        return result;
                    }
                }
            });

        });
        $(function () {
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
        });
        function sum_payment() {
            var total = 0;

            $('.c-checked-group').each(function () {

                if($(this).is(':checked')){
                    var payment = $(this).data('payment');
                    total += payment;
                }
            });

            $('.cash-topay').val(round(total,2));
        }



    </script>
    <script>
        $(".select2_field-group").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Group",
                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "group_code";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });
        $(".select2_field-center").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Center",

                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "code";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });
        $(".select2_field-loan-product").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Loan Product",
                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "name";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });

        $(function () {
            $('body').on('change','.select2_field-group,.select2_field-center,.select2_field-loan-product',function () {
                repay_filter();
            });

            $('#remove-filter').on("click", function () {
                $('[name="center_id[]"]').val('').trigger("change");
                $('[name="group_id[]"]').val('').trigger("change");
                $('#remove-filter').hide();
                window.location.reload();
            });

        });

    </script>
    <script>
        function repay_filter() {
            $('#remove-filter').show();

            var center_id = $('.select2_field-center').val();
            var group_id = $('.select2_field-group').val();
            var loan_product_id = $('.select2_field-loan-product').val();


            window.location.href = "{{url('admin/group-repayment-new')}}?group_id="+group_id+"&center_id="+center_id+"&loan_product_id="+loan_product_id;

            //alert("hello Ork");
          /*  $.ajax({
                url: "",
                method: "get",
                data: {
                    group_id:group_id,
                    center_id:center_id,
                    loan_product_id:loan_product_id
                },
                success: function (res) {

                    // console.log(res);
                    $('.b-group').html();
                    $('.b-group').html(res);
                    check_b();
                }
            });*/
        }
        $(function () {
            $('.my-p-p-p .pagination').show();
        });
    </script>


@endsection




