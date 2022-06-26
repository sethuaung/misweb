<!DOCTYPE html>
<?php 
    $loan = \App\Models\Loan::find($row->contract_id);
   // dd($loan);
    
	$group = \App\Models\Loan::where('group_loan_id', $loan->group_loan_id)->where('disbursement_status','Activated')->get();
	$group_id = \App\Models\GroupLoan::find($loan->group_loan_id);
	
	$group_loans= '';
	if($group_id){
		$group_loans = $group;
	} else{
		$group_loans = \App\Models\Loan::where('id',$row->contract_id)->get();
	}
	
    $group_leader = array();

    foreach($group_loans as $loan){

        if($loan->you_are_a_group_leader = "YES"){
            $group_leader = $loan;
        }
    }
 ?>
<html>
	<head>
		<title>{{$loan->disbursement_number}}</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
	<style type="text/css">
        html, body {
            height: 80%;
            width: 100%;

        }
		.contain-wrapper {
		width: 95%;
		
		
		background: white;
		
		font-family: 'Zawgyi-One', Times New Roman;
		}
		
		
		.thb{
			font-weight: bold;
		}
		th{
			padding: 3px !important;
			text-align: center !important;
		}
		
		
		#logo img{
			width:100px;
			margin-left:15%;			
			opacity: 0.8; 
			
		}
	</style>
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo', $m);
    $company = getSettingKey('company-name', $m);
    ?>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:">
			<div style="margin-top:10px; margin-left:15px;">
				<div class="header" style="width:100%;float:left; ">
					<div class="brand-name" style="width:30%; float:left; margin-left:40%;">
						<div id="logo">
                            <span>
                            <img src="{{asset($logo)}}" width="200"/>
                            </span>
						</div>
					</div>									
				</div>
				<div class="header" style="width:100%;float:left;margin-top:20px;">								
					<div style="text-align:center">							 
						<span style=" font-size:12px;font-family:Zawgyi-One;">လိုင်စင်ရငွေရေးကြေးရေးလုပ်ငန်း</span> 
						<br>
						<span style=" font-size:12px;font-family:Zawgyi-One;">ငွေပေး/ ငွေရပြေစာ</span>							 					
					</div>
					<div style="text-align:right">
							<span style="float: right; font-family:Pyidaungsu !important;">ရက်စွဲ(Disbursement Date):
							{{ $row->paid_disbursement_date != null ? date('d-m-Y', strtotime($row->paid_disbursement_date)) : ''}}
							</span>						 
					</div>
				</div>
				<div>
					<table width="100%" style="font-size:12px; ">
						<tr>
							<td style="width: 15%;padding-bottom: 8px;" class="thb">
								ချေးငွေ အမျိုးအစား<br>
								(Loan Type)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">: 
                                {{$loan->loan_product->name}}
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								ဝိုင်းကြီးချုပ်နံပါတ်<br>(Group No.)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">:
							@php
								if($group_id){
									echo $group_id->group_code;
								}
							@endphp
							</td>
						</tr>
						<tr>
							<td style="width: 15%;padding-bottom: 8px; " class="thb">
								ချေးငွေ အရာရှိ ID <br>
								(Credit Officer ID)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">:
                                {{$loan->officer_name->name}}
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								ချေးငွေကာလ<br>(Loan term)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">: 
								{{round($loan->loan_term_value)}} လ
							</td>
						</tr>
						<tr>
							<td style="width: 15%;padding-bottom: 8px;  font-family:Pyidaungsu !important;" class="thb">
								လိပ်စာ <br>(Clients Address)
							</td>
							<td colspan="3" style="width: 25%;padding-bottom: 8px; ">:
								{{$loan->client_name->address1}}
							</td>
						</tr>						
					</table>
				</div>
				<div style="overflow:hidden;width:100%;clear:both;padding:10px 0px 20px 0px;font-size:9px; ">
					<div>						
						<span style="font-size:13px;">
							 ထုတ်ချေးငွေ၏ ၁% ကိုစီမံခန့်ခွဲရန် ဝန်ဆောင်ခ အနေဖြင့်လည်းကောင်း၊ ဝ.၅% ကို လူမှုထောက်ပံ့ရေး ရန်ပုံငွေ အဖြစ်လည်းကောင်း ၊ ၅% ကို မဖြစ်မနေ စုဆောင်းငွေ အဖြစ် လည်းကောင်း ကောက်ခံမည်ဖြစ်ပါသည်။ စုငွေအရင်းများကို လူကြီးမင်းကုမ္ပဏီမှ ဆက်လက်ထုတ်ချေးရန် စိတ်ဆန္ဒမရှိတော့သည့် အချိန်တွင် ပြန်လည်ထုတ်ပေးသွားမည်ဖြစ်သည်။
						</span>						
					</div>					
				</div>
				<div>
					<table width="100%" border="solid" style="text-align:center;font-size:12px; ">						 
							<tr style="border-width: 5px;">
								<th>No<br>စဉ်</th>
								<th >Name<br>အမည်</th>
								<th >NRC<br>နိုင်ငံသားမှတ်ပုံတင်အမှတ်</th>
								<th>Loan ID<br>ချေးငွေ အမှတ်</th>
								<th>Disbursement <br>Amount<br>ချေးငွေပမာဏ</th>
								<?php foreach(\App\Models\Charge::all() as $charge){ ?>
								<th>{{$charge->name}}</th> 
								<?php } ?>
								<th>Compulsory <br>Saving 5%<br>မဖြစ်မနေ စုဆောင်းရမည့်ငွေ ၅%</th>	
								<th>Received <br>Amount<br>လက်ခံရရှိမည့်ငွေပမာဏ</th>
								<th>Clients Signature<br>လက်မှတ်</th>
							</tr>						 
							<?php 
							$i = 1; 
								foreach($group_loans as $grouploan){
								?>
								<tr>
									<td><?= $i ?></td>
									<td style="white-space: nowrap"><?= $grouploan->client_name->name ?></td>
									<td><?= $grouploan->client_name->nrc_number ?></td>
									<td><?= $grouploan->disbursement_number ?></td>
									<td>{{number_format($grouploan->loan_amount,0)}}</td>
									
									<?php 
										$total_service_charge = 0;
                                        $service_amount = 0;
										// $loan_charge = \App\Models\LoanCharge::where(['loan_id' => $grouploan->id])->get();
                                    //dd($grouploan);
										$disburse = \App\Models\PaidDisbursement::where('contract_id',$grouploan->id)->first();
										//dd($disburse);
										$loan_charge = \App\Models\DisbursementServiceCharge::where(['loan_disbursement_id' => $disburse->id])->orderBy('charge_id')->get();
										
										foreach($loan_charge as $charge){
											$service_amount = $charge->service_charge_amount;
										
									?>								 
									<td>{{number_format($service_amount,0)}}</td>
									<?php 
										}
										$total_service_charge = $total_service_charge;
                                    	$saving = \App\Models\LoanCompulsory::where('loan_id' , $grouploan->id)->where('compulsory_status','Active')->first();
                                    	$total_saving = 0;
										$total_saving = ($saving->saving_amount * $grouploan->loan_amount)/100;
									?>
									
									<td>{{number_format($total_saving,0)}}</td>
									<td> {{number_format($grouploan->loan_amount - ($total_service_charge + $total_saving),0)}} </td>
									<td> </td>
									 
								</tr>
							<?php
								
								$i++;
								}
								 
							?>						 
					</table>
				</div>
				<div style="width:100%;text-align:center;">					
					<div style="margin-left: 80%; float:left;font-weight: bold;font-size:12px; padding-top: 10px;">
						<p>Teller(Payer/Receiver) :...............<br>ငွေကိုင်</p>						
					</div>				
				</div>
			</div>
		</div>	
	</body>
</html>
