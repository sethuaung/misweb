<?php
$profit_details = \App\Models\GeneralJournalDetail::where('journal_id', $profit_id)
			 ->where('cr', '>' ,0) 
			 ->get();
			 
$total = \App\Models\GeneralJournalDetail::where('journal_id', $profit_id)
			 ->where('dr', '>' ,0) 
			 ->first();			 

$profit_general = \App\Models\JournalProfit::find($profit_id);

?>
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('')}}vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">


<link rel="stylesheet" href="{{ asset('css/custom.css') }}">


<style type="text/css">

	body{
		font-size:13px;
		font-family: "Poppins" !important;
		text-align: center;
	}

	td {
		padding-top: 5px;
	}
	#data{
		margin-top:10px;

	}

	#data td{
		font-size:13px;
		font-weight:normal;
		padding:5px;
	}

	th{
		padding:5px;
		font-size:13px;
	}

	.bor{
		border:1px solid black;
	}

	@media print {
		@page { margin: 0; }
	}

	table {
		border-collapse: collapse;
	}

</style>



<div class="container " style="padding-left: 30px;padding-top: 0px;margin-top:10px;margin-left: 0px">
		<span >
				Star Moe Yan Microfinance Cash In
			</span>
		<br><br>
		Date :
		<strong>{{date('d-m-Y', strtotime(optional($profit_general)->date_general))}}</strong>
		&nbsp;&nbsp;
		Voucher No :
		<strong>{{optional($profit_general)->reference_no}}</strong>

		  <table id="data" width="100%">
			  <thead>
			  <tr>
				  <th class="bor">Ac Code</th>
				  <th class="bor">Branch</th>
				  <th class="bor">Description</th>
				  <th class="bor">Amount</th>
			  </tr>
			  </thead>

			  <tbody>
			  @foreach ($profit_details as $profit_detail)
				  @php
					  $profit = \App\Models\JournalProfit::find($profit_id);
					  $branch = \App\Models\Branch::find($profit->second_branch_id);
				  @endphp
				  <tr>
					  <td class="bor">
						  {{optional($profit_detail)->acc_chart_code}}
					  </td>
					  <td class="bor">
						{{optional($branch)->title}}
					</td>
					  <td class="bor" width="200">
						  {{optional($profit)->note}}
					  </td>

					  <td class="bor">{{number_format(optional($profit_detail)->cr)}}</td>
				  </tr>
			  @endforeach


			  <tr>
				  <td colspan="3" class="bor">Total</td>
				  <td  class="bor">{{number_format(optional($total)->dr)}}</td>
			  </tr>
			  </tbody>


		  </table>

	<div class=" my_font" style="font-size:13px;text-align: left; padding-top: 15px;padding-left: 5px; text-transform: uppercase">AMOUNT IN WORDS : <b>{{ strtoupper(\App\Helpers\S::convert_number_to_words(optional($profit_detail)->cr))}} KYATS</b></div>


	<table width="100%">
				<tbody>
					{{--<tr>
						<td style="padding-top:20px">AMOUNT IN WORDS : &nbsp; </td>
						<td colspan="2" style="padding-top:20px"><strong>{{ strtoupper(\App\Helpers\S::convert_number_to_words(optional($profit_detail)->cr))}} KYATS</strong></td>
					</tr>--}}

					<tr>
						<th style="padding-top:10px;width: 90px">ငွေပေးသူ:</th>
						<td style="padding-top:10px;">..........................................................</td>
					</tr>
					<tr>
						<th style="width: 90px">ငွေလက်ခံသူ</th>
						<td>..........................................................</td>
					</tr>
					<tr>
						<th style="width: 90px">အတည်ပြုသူ</th>
						<td>...........................................................</td>
					</tr>
				</tbody>
			</table>

		  <script src="{{asset('')}}vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>

		  <!-- Bootstrap 3.3.7 -->
		  <script src="{{asset('')}}vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		  <script>

              $(document).ready(function(){
                  setTimeout(function () { window.print(); }, 100);
                  window.onload = function () { setTimeout(function () { window.close(); }, 500); }
              });

		</script>
</div>