<?php
$uid = time() . rand(1, 9999) . rand(1, 9999);
?>
<style>
    table {
        border-collapse: collapse;
    }

    .my-table tr td{
        border: 1px solid rgba(188, 188, 188, 0.96);
        padding: 5px;
    }

    .my-table tr td {
        font-size: 13px;
    }
</style>

@if($trans_lists != null)

    <div class="clear-{{  $uid }}">
        <table width="100%" class="my-table">
            <tr>
                <th style="text-align: center;width:100%;" colspan="6">{{ date('F') }} Transaction List</th>
            </tr>
            <tr>
                <td style="text-align: center;">Date</td>
                <td style="text-align: center;">Code</td>
                <td style="text-align: center;">Deposit</td>
                <td style="text-align: center;">Withdrawal</td>
                <td style="text-align: center;">Balance</td>
                <td style="text-align: center;">Status</td>
            </tr>
        @foreach ($trans_lists as $saving_tran)
					<tr>
						<td style="text-align:center;width:90px;">
							{{date("n/d/Y", strtotime($saving_tran->date))}}
						</td>
						<td style="text-align: center;width:40px;">
							{{optional($saving_tran)->tran_type == 'deposit'?'CD':'CW'}}
						</td>
						@if(optional($saving_tran)->tran_type == 'deposit')
							<td style="text-align: right;" class="with_amount">{{number_format($saving_tran->amount,0)}}</td>
						@else
							<td></td>
						@endif
						@if(optional($saving_tran)->tran_type == 'withdrawal')
							<td style="text-align: right;" class="amount">{{number_format(-$saving_tran->amount,0)}}</td>
						@else
							<td></td>
						@endif
						<td style="text-align: right;" class="amount">{{numb_format($saving_tran->available_balance??0,0)}}</td>
                        @if($saving_tran->print == 1)
						    <td style="text-align: center;">Old</td>
                        @else
                            <td style="text-align: center;">Update</td>
                        @endif
					</tr>
				@endforeach
        </table>
    </div>
@endif





