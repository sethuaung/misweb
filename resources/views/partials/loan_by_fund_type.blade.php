<?php
$is_edit = isset($is_edit) ? $is_edit : false;
$fund_type = $is_edit ? $type : $fund_type;
$client_id = $is_edit ? $l_client : $client_id;
//dd($l_client);
?>
@if($fund_type != null && $client_id != null)
    @if($fund_type == 'dead_supporting_funds')
        <?php
        $loans = \App\Models\Loan::where('client_id', $client_id)->whereIn('disbursement_status', ['Activated','Written-off'])->get();
        $total_loan_outstanding = 0;
        $loans = $is_edit ? $f_detail : $loans;
        //dd($f_detail);
        ?>
        @if(count($loans)>0)
            @foreach($loans as $row)
                <?php
                    $loan_product = \App\Models\LoanProduct::find($is_edit?$row->loan_product_id:$row->loan_production_id);
                    $dead_writeoff = $loan_product != null ? $loan_product->dead_writeoff_status:'';
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id',$is_edit?$row->loan_id:$row->id)->sum('interest_s');
                    //dd($fund_type);
                ?>
                {{--@if($dead_writeoff == "Yes")--}}
                <?php
                $rand_id = rand(1, 1000) . time() . rand(1, 1000);
                ?>

                <input type="hidden" value="{{$is_edit?$row->loan_id:$row->id}}" name="loan_id[{{$rand_id}}]"/>
                <input type="hidden" value="{{$is_edit?$row->id:0}}" name="fund_detail_id[{{$rand_id}}]"/>
                <input type="hidden" value="{{$is_edit?$row->status:$row->disbursement_status}}"
                       name="status[{{$rand_id}}]"/>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Loan ID</label>
                        <input type="text" class="form-control" name="loan_number[{{$rand_id}}]" readonly="readonly"
                               value="{{$is_edit?$row->loan_number:$row->disbursement_number}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Loan Amount</label>
                        <input type="text" class="form-control" readonly="readonly" name="loanamount" value="{{numb_format($row->loan_amount??0,0)}}">
                        <input type="hidden" class="form-control" readonly="readonly" name="loan_amount[{{$rand_id}}]" value="{{$row->loan_amount}}">
                    </div>
                </div>
                
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Loan Principle Outstanding</label>
                        <input type="text" class="form-control" name="loanoutstanding"
                               readonly="readonly"
                               value="{{$is_edit?numb_format($row->principle_outstanding??0,0):numb_format($row->principle_receivable??0,0)}}">
                        <input type="hidden" class="form-control" name="principle_outstanding[{{$rand_id}}]"
                               readonly="readonly"
                               value="{{$is_edit?$row->principle_outstanding:$row->principle_receivable}}"> 
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Loan Product</label>
                        <input type="text" class="form-control" name="loanproduct" readonly="readonly" value="{{optional($loan_product)->name}}">
                        <input type="hidden" name="product_id[{{$rand_id}}]" readonly="readonly" value="{{optional($loan_product)->id}}">
                    </div>
                </div>
                <?php
                    $write_of_yes = $loan_product->dead_writeoff_status == "Yes"? "selected":'';
                    $write_of_no = $loan_product->dead_writeoff_status == "No"? "selected":'';
                    if($loan_product->dead_writeoff_status == "Yes"){
                        $total_loan_outstanding += $row->principle_receivable;
                    }
                ?>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Write Off Status</label>
                        <select class="form-control dead_writeoff_status"  name="dead_writeoff_status[{{$rand_id}}]">
                            <option {{$write_of_yes}} value="Yes-{{$row->principle_receivable}}">Yes</option>
                            <option {{$write_of_no}} value="No-{{$row->principle_receivable}}">No</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Interest Amount</label>
                                <input type="text" class="form-control" readonly="readonly" name="interestamount"
                                       value="{{numb_format($total_interest??0,0)}}">
                                <input type="hidden" class="form-control" name="interest_rate[{{$rand_id}}]" readonly="readonly"
                                       value="{{$total_interest}}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Dead Date</label>
                                <input type="date" class="form-control" name="dead_date">
                            </div>
                        </div>
                    </div>
                </div>



                {{--@endif--}}
                
            @endforeach
            <?php //dd($_e) ?>
            <div class="col-sm-4">
                <label>Total Principle Outstanding</label>
                <input type="text" class="form-control" name="total_loan_outstanding" readonly="readonly"
                       value="{{$is_edit?optional($_e)->total_loan_outstanding:$total_loan_outstanding}}">
            </div>
            <div class="col-sm-4">
                <label>Cash Support Fund</label>
                <input type="text" class="form-control" name="cash_support_fund"
                       value="{{$is_edit?optional($_e)->cash_support_fund:''}}">
            </div>
            <div class="col-sm-4">
                <label>Cash Out Acc</label>
                <select class="form-control js-cash-out" name="cash_acc_id"></select>
            </div>
            <div class="col-sm-12">
                <label>Note</label>
                <textarea rows="3" class="form-control" name="note">{{$is_edit?optional($_e)->note:''}}</textarea>
            </div>
           
        @endif
    @elseif($fund_type == 'child_birth_supporting_funds')
        <?php
        $row = \App\Models\Loan2::where('client_id', $client_id)->orderBy('id', 'DESC')->first();
        $loan_product = \App\Models\LoanProduct::find($row->loan_production_id);
        $dead_writeoff = $loan_product->dead_writeoff_status;
        ?>

        @if($row != null && $dead_writeoff == "Yes")
            <?php
            $rand_id = rand(1, 1000) . time() . rand(1, 1000);
            ?>
            <input type="hidden" value="{{$row->id}}" name="loan_id[{{$rand_id}}]"/>
            <input type="hidden" value="{{$is_edit?$row->id:0}}" name="fund_detail_id[{{$rand_id}}]"/>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Loan ID</label>
                    <input type="text" class="form-control" name="loan_number[{{$rand_id}}]" readonly="readonly"
                           value="{{$is_edit?$row->loan_number:$row->disbursement_number}}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Loan Amount</label>
                    <input type="text" class="form-control" name="loan_amount[{{$rand_id}}]" readonly="readonly"
                           value="{{$row->loan_amount}}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" class="form-control" status[{{$rand_id}}] readonly="readonly"
                           value="{{$is_edit?$row->status:$row->disbursement_status}}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Loan Product</label>
                    <input type="text" class="form-control" readonly="readonly" value="{{$loan_product->name}}">
                </div>
            </div>
            <div class="col-sm-4">
                <label>Cash Support Fund</label>
                <input type="text" class="form-control" name="cash_support_fund"
                       value="{{$is_edit?optional($_e)->cash_support_fund:''}}">
            </div>
            <div class="col-sm-4">
                <label>Cash Out Acc</label>
                <select class="form-control js-cash-out" name="cash_acc_id" value="{{$is_edit?optional($_e)->cash_acc_id:''}}"></select>
            </div>
            <div class="col-sm-12">
                <label>Note</label>
                <textarea rows="3" class="form-control" name="note"> {{$is_edit?optional($_e)->note:''}}</textarea>
            </div>
            
        @endif
    @endif
@endif
<script>
    $(function () {
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
                                text: item["code"] + '-' + item["name"],
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
</script>
