<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


    <?php $base=asset('vendor/adminlte') ?>
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<body>
    <?php

    ?>
<div class="container">
    <div class="row" style="margin-top: 20px">

           <input type="hidden" name="_token" value="{{csrf_token()}}">
           <table class="table table-bordered" style="width: 100%">
               <thead>
               <tr>
                   <th><input type="checkbox" value="" id="check_all_group" class="check_all_group"></th>
                   <th>Payment Date</th>
                   <th>Applicant No</th>
                   <th>Branch Name</th>
                   <th>Client ID</th>
                   <th>Client Name</th>
                   <th>Loan Product</th>
                   <th>Center ID</th>
                   <th>Group ID</th>
                   <th>Service</th>
                   <th>Compulsory</th>
                   <th>Principle</th>
                   <th>Interest</th>
                   <th>Total Payment</th>

               </tr>
               </thead>
               <tbody>
               @if($loan_detail != null)
                    <?php
                        $total_service = 0;
                        $total_compulsory = 0;
                        $total_interest = 0;
                        $total_priciple = 0;
                        $total_payment = 0;
                    ?>
                   @foreach($loan_detail as $r)
                       @if(isset($date_s[$r->disbursement_id]))
                       <?php
                       $loan_d = $gg[$r->disbursement_id];
                       $ls = $jj[$r->disbursement_id];
                       $dd = $date_s[$r->disbursement_id];

                      // $ls = $arr_schedule;

                       $rand_id = rand(1,1000).time().rand(1,1000);
                       $_principle = $loan_d->principal_s - ($loan_d->principle_pd ?? 0);
                       $_interest = $loan_d->interest_s - ($loan_d->interest_pd ?? 0);
                       $charge = $loan_d->charge_schedule - $loan_d->service_pd;
                       $compulsory = $loan_d->compulsory_schedule - $loan_d->compulsory_pd;
                       $group = \App\Models\GroupLoan::find($r->group_id);
                       $total = $_principle + $_interest + $charge +$compulsory;
                       $loan = \App\Models\Loan::find($r->disbursement_id);
                       $branch = \App\Models\BranchU::find($r->branch_id);
                       $center = \App\Models\CenterLeader::find($r->center_id);
                       $client = \App\Models\Client::find(optional($loan)->client_id);
                       $loan_product = \App\Models\LoanProduct::find(optional($loan)->loan_production_id);
                       $principle_paid = \App\Models\LoanCalculate::where('disbursement_id',optional($loan)->id)
                           ->sum('principal_p');
                       $priciple_balance = optional($loan)->loan_amount - ($principle_paid + $loan_d->principal_s);
                       $total_service += $charge;
                       $total_compulsory += $compulsory;
                       $total_interest += $_interest;
                       $total_priciple += $_principle;
                       $total_payment += $total;

                       $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',optional($loan)->id)->get();
                       $service_charge = [];
                       $charge_id = [];
                       if($charges != null){
                           foreach ($charges as $c){
                               $service_charge[$c->id] = ($c->charge_option == 1 ? $c->amount: ((optional($loan)->loan_amount * $c->amount) / 100));
                               $charge_id[$c->id] = $c->charge_id;
                           }
                       }


                       ?>

                       <tr>
                           <td><input type="checkbox" data-payment="{{$total}}" class="form-check-input c-checked" name="approve_check[{{$rand_id}}]" value="{{optional($loan)->id}}"

                              data-client_id = "{{optional($client)->id}}"
                              data-client_number_ = "{{optional($client)->client_number}}"
                              data-principle = "{{$_principle}}"
                              data-interest = "{{$_interest}}"
                              data-total_payment = "{{$total}}"
                              data-payment = "{{$total}}"
                              data-principle_balance = "{{$priciple_balance}}"
                              data-disbursement_detail_id = "{{$ls}}"
                              data-disbursement_id = "{{$r->disbursement_id}}"
                              data-compulsory = "{{$compulsory}}"
                              data-service = "{{$charge}}"
                              data-service_charge = "{{json_encode($service_charge)}}"
                              data-charge_id = "{{json_encode($charge_id)}}"
                           /></td>
                           <td>{{\Carbon\Carbon::parse($dd)->format('Y-m-d')}}</td>
                           <td>{{optional($loan)->disbursement_number}}</td>
                           <td>{{optional($branch)->code}}-{{optional($branch)->title}}</td>
                           <td>{{optional($client)->client_number}}</td>
                           <td>{{optional($client)->name}}</td>
                           <td>{{optional($loan_product)->name}}</td>
                           <td>{{optional($center)->code}}</td>
                           <td>{{optional($group)->group_code}}</td>
                           <td>{{numb_format($charge,2)}}</td>
                           <td>{{numb_format($compulsory,2)}}</td>
                           <td>{{numb_format($_principle,2)}}</td>
                           <td>{{numb_format($_interest,2)}}</td>
                           <td>{{numb_format($total,2)}}</td>

                       </tr>
                        @endif
                   @endforeach

               @endif
               </tbody>
               <tfoot>
               <tr>
                   <td colspan="9" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
                   <td>{{numb_format($total_service,2)}}</td>
                   <td>{{numb_format($total_compulsory,2)}}</td>
                   <td>{{numb_format($total_priciple,2)}}</td>
                   <td>{{numb_format($total_interest,2)}}</td>
                   <td>{{numb_format($total_payment,2)}}</td>
               </tr>
               </tfoot>
           </table>
           <div class="row">
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>Reference</label>
                       <input class="form-control" name="reference" value="{{\App\Models\PaidDisbursement::getSeqRef('group_repayment')}}">
                   </div>
               </div>
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>Note</label>
                       <input class="form-control" name="approve_note">
                   </div>
               </div>
               <div class="col-sm-2">
                   <div class="form-group">
                       <label>Date</label>
                       <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                       <span></span>
                   </div>
               </div>
               <div class="col-sm-2">
                   <div class="form-group">
                       <label>Cash Payment</label>
                       <input type="text" class="form-control cash-topay" name="" value="">
                   </div>
               </div>
               <div class="col-sm-2">
                   <label>Cash Out *</label>
                   <select class="form-control js-cash-out" name="cash_out">
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
               <div class="row">
                   <div class="col-sm-6">
                        <input type="button" class="btn btn-sm btn-primary btn-payment" style="margin-top: 28px;" value="Payment" />
                   </div>
               </div>
           </div>

    </div>
</div>
    <script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        // $('.select-province').select2();
        $('.c-checked').on('change',function () {
            sum_payment();
        });
        $('.js-cash-out').select2({
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
                                text: item["name"],
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
        $('.btn-payment').on('click',function () {
            var cash_out = $('[name="cash_out"]').val()-0;
            if(cash_out>0) {
                $(this).hide();
                $('.c-checked').each(function () {

                    if ($(this).is(':checked')) {
                        var d = $(this);
                        save_payment(d);
                    }
                });

                parent.location.reload();
            }else {
                alert('Please Select Cash Account!!');
            }
        });
    });

    function sum_payment() {
        var total = 0;
        $('.c-checked').each(function () {

            if($(this).is(':checked')){
                var payment = $(this).data('payment')-0;
                total += payment;
            }
        });
        $('.cash-topay').val(total);
    }
    
    function save_payment(d) {

        var reference = $('[name="reference"]').val();
        var approve_date = $('[name="approve_date"]').val();
        var cash_out = $('[name="cash_out"]').val();
        $.ajax({
            type: 'POST',
            url: '{{url('admin/loanpayment')}}',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            async: false,
            data: {

                client_id: d.data('client_id'),
                client_number_: d.data('client_number_'),
                principle: d.data('principle'),
                interest: d.data('interest'),
                total_payment: d.data('total_payment'),
                payment: d.data('payment'),
                disbursement_detail_id: d.data('disbursement_detail_id'),
                disbursement_id: d.data('disbursement_id'),
                payment_number: reference,
                cash_acc_id: cash_out,
                payment_method: "cash",
                owed_balance: 0,
                payment_date: approve_date,
                compulsory_saving: d.data('compulsory'),
                total_service_charge: d.data('charge'),
                principle_balance: d.data('principle_balance'),
                service_charge: d.data('service_charge'),
                charge_id: d.data('charge_id'),
            },
            success: function (res) {
                //window.location.reload();
            }

        });
    }

</script>
    <script>
        $(function () {
            // $('.select-province').select2();
            $('#check_all_group').on('change',function(event) {

                if(this.checked) { // check select status
                    $('.c-checked').each(function() {
                        this.checked = true;  //select all
                        sum_payment();

                    });
                }else{
                    $('.c-checked').each(function() {
                        this.checked = false; //deselect all

                        sum_payment();
                    });
                }
            });
           /* $('.c-checked-group').on('change',function () {
                sum_payment();
            });*/

        });
        $(function () {
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
        });
    </script>
</body>
</html>
