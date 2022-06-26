<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
 <!-- Font Awesome -->

 <!-- Bootstrap 3.3.7 -->
 <link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
 <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style type="text/css">
	tr td{
		width: 70px;
		font-size: 12px;
		padding-top: 5px;
	}
	@media print {
		@page { margin: 0; }
	}
	.amount{
		width: 130px;
	}
	.with_amount{
		width: 100px;
	}
	.top td{
		padding-bottom:100px;
	}
	.middle td{
		padding-bottom:30px;
	}
	.bottom td{
		padding-bottom:250px;
	}
</style>
	<table>
		<tbody>
			@php $i = 0; @endphp
			@if($saving_trans->isNotEmpty())
				@foreach ($saving_trans as $saving_tran)
					@php  
						++$i;
						$reminder = fmod($i,22);
					@endphp
					@if($i == 1) 
						<tr class="top"><td></td></tr>
					@elseif($reminder == 1) 
						<tr class="bottom"><td></td></tr>
					@elseif($i == 12 || $reminder == 12)
						<tr class="middle"><td></td></tr>
					@else
						<div></div>
					@endif
					<tr>
						@if($saving_tran->print == 0)
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
							<td style="text-align: center;"></td>
						@else
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						@endif
					</tr>
				@endforeach
			@else
				<tr>
					<td style="width: 100%;font-size:32px;">No Update Data</td>
				</tr>
			@endif
		</tbody>
	</table>

<script src="{{asset('')}}vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
	$(document).ready(function(){
		var pwd = prompt("Please Enter Password!\npwd:printbook");
		if (pwd == "printbook"){
			setTimeout(function () { window.print(); }, 100);
			window.onload = function () { setTimeout(function () { window.close(); }, 500); }
		
			var print_ids = [];
			print_ids = @php echo json_encode($print_ids); @endphp;
			if(print_ids.length > 0){
				$.ajax({
					type: 'GET',
					url: '{{url('admin/print-record')}}',
					data: {
						print_ids: print_ids
					},
					success: function (res) {

					}

				});
			}
		}else{
			alert("Wrond Password!!!");
		}
	});
</script>