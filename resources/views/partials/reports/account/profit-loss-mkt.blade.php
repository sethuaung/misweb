@php
    $new_acc = App\Models\AccountChart::where('created_at','>=','2019-09-18 00:00:00')->pluck('code')->toArray();
@endphp
<div class="modal fade" id="show-detail-modal" tabindex="-1" role="dialog" aria-labelledby="show-detail-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="DivIdToPrintPop">
        
      </div>
      <div class="action" style="float: right;">
        <button type="button" onclick="printDiv() " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">PRINT</button>
    </div>
    </div>
  </div>
</div>
<style>
a{
    text-decoration:underline;
    cursor: pointer;
}
</style>
<input type="hidden" id="start_date" value="{{$start_date}}">
<input type="hidden" id="end_date" value="{{$end_date}}">
<div id="DivIdToPrint">
@if($bals != null)
    <?php

    ?>

    @include('partials.reports.header',
    ['report_name'=>'Profit Loss','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table border="1" class="table-data" id="table-data">
    @if(companyReportPart()!='company.mkt')
        <thead>
        <th></th>
        <?php $sp = count($branches) +1; ?>
        
        @foreach($branches as $branch)
            <th>{{ optional(\App\Models\Branch::find($branch))->title  }}</th>
        @endforeach
        </thead>
        <tbody>
        @endif
        @if(companyReportPart()=='company.mkt')
        <thead>
        <th></th>
        <?php $sp = count($branches) +1; ?>
        
        @foreach($branches as $branch)
            <th colspan="3">{{ optional(\App\Models\Branch::find($branch))->title  }}</th>
        @endforeach
        </thead>
        <tbody>
           
            
        @endif
        {{--Ordinary Income--}}
       
            <tr>
                <td colspan="4" style="color: blue;"><b>Ordinary Income / Expense</b></td>
            </tr>
     
            @php
                //$total_income += ($bal??0);
                $total_income = [];
            @endphp

            @if(isset($bals))
                @if(isset($bals[40]))

                    <tr>
                    @if(companyReportPart()!='company.mkt')
                        <td colspan="{{count($branches)}}" style="padding-left: 30px"><b>Income</b></td>
                        @endif
                        @if(companyReportPart()=='company.mkt')
                        <td style="padding-left: 10px;" colspan="4"><b>Turnover</b></td>
                        
                        @foreach($branches as $b_id)
                       
                        @endforeach
                        @endif
                    </tr>
                    <tr>
                        <td style="padding-left: 30px;"><b>436000</b></td>
                        <td style="padding-left: 30px;"><b>Operating Revenue </b></td>
                        
                        @foreach($branches as $b)
                        
                        <td style="text-align:center;">Sub Total</td>
                        <td style="text-align:center;">Total</td>
                        @endforeach
                    </tr>
                    
                    @forelse($bals[40] as $acc_id => $bal)
                            @php
                            //dd($bals[40]);
                                $acc_revenue = ['437801','437802','437803','437804','437805','437806','437807','437808','437809','437810','437811','437812','437813','437814','437815','437816','437817','437818','437819','437820','437821','437822','437823','437824','437825','436000','710120'];
                                $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                    ->whereIn('code', $acc_revenue)
                                                                    ->first();
                                                                    //dd($cash_account);
                            @endphp
                            @if($cash_account != null)
                        <tr>
                            <td style="padding-left: 60px;"> 
                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                            {{ $cash_account->code }}
                        {{-- </a> --}}
                            </td>
                            <td style="padding-left: 60px;"> 
                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                            {{ $cash_account->name }}
                        {{-- </a> --}}
                            </td>
                            @foreach($branches as $b_id)
                                <?php

                                if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;

                                    $b = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                    $total_income[$b_id] += ($b??0);
                                    $total_revenue = $total_revenue??0;
                                    $total_revenue += $b;

                                ?>
                                
                            @endforeach

{{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                
                                @foreach($branches as $b_id)
                                <td style="text-align: right">
                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ number_format(-$b??0,2) }}
                                    {{-- </a> --}}
                                </td>
                                <td style="text-align: right">
                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                </td>
                                @endforeach
                                
                        </tr>
                        @endif
                        @endforeach


                        <tr>
                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Operating Revenue</b></td>
                            @foreach($branches as $b_id)
                            @php
                                 $total_revenue = $total_revenue??0;
                            @endphp
                                <td style="text-align: right">{{ number_format(-$total_revenue??0,2) }}</td>
                               

                            @endforeach
                            @if(companyReportPart()=='company.mkt')
                            @foreach($branches as $b_id)
                            @isset($total_income[$b_id])
                                <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format(-$total_income[$b_id],2) }}</u></td>
                            @endisset
                          
                           
                            @endforeach
                            @endif
                        </tr>
                        <tr>

                            <?php
                            $n=0;
                            ?>
                            @foreach($branches as $b_id)

                                <?php
                                    if(!isset($gross_profit[$b_id])) $gross_profit[$b_id] = 0;
                                    $gross_profit[$b_id] = ($total_income[$b_id]??0) + ($total_cogs[$b_id]??0);
                                    $n++;
                                ?>
                                @if ($n > 1)
                                    <td style="color: green;padding-left: 20px" colspan="2"><b>{{ $gross_profit[$b_id] < 0?'Gross Profit':'Gross Loss' }}</b> <u>{{ $gross_profit[$b_id] < 0?number_format($gross_profit[$b_id],2):number_format(-$gross_profit[$b_id],2) }}</u></td>
                                @else
                                    <td style="color: green;padding-left: 20px" colspan="2"><b>{{ $gross_profit[$b_id] < 0?'Gross Profit':'Gross Loss' }}</b> </td>
                                    
                                @endif

                            @endforeach
                            @if(companyReportPart()=='company.mkt')
                            @foreach($branches as $b_id)
                            <td style="color: green;padding-left: 20px"><u>{{ $gross_profit[$b_id] < 0?number_format($gross_profit[$b_id],2):number_format(-$gross_profit[$b_id],2) }}</u></td>
                            <td style="color: green;text-align: right;padding-right: 20px"><u>{{ $gross_profit[$b_id] < 0?number_format($gross_profit[$b_id],2):number_format(-$gross_profit[$b_id],2) }}</u></td>
                            @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                        </tr>

                        {{-- Next --}}
                        <tr>
                            
                               
                            </tr>
                            <tr>
                                <td style="padding-left: 30px;"><b>460000</b></td>
                                <td style="padding-left: 30px;"><b>Other Operating Income </b></td>
                                
                                @foreach($branches as $b)
                                
                                <td style="text-align:center;">Sub Total</td>
                                <td style="text-align:center;">Total</td>
                                @endforeach
                            </tr>
                            
                            @forelse($bals[40] as $acc_id => $bal)
                                    @php
                                        $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                            ->whereIn('code', ['460140','490070','460030','460000','460040'])
                                                                            ->first();
                                    @endphp
                                    @if($cash_account != null)
                                <tr>
                                    <td style="padding-left: 60px;"> 
                                    {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                    {{ $cash_account->code }}
                                {{-- </a> --}}
                                    </td>
                                    <td style="padding-left: 60px;"> 
                                    {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                    {{ $cash_account->name }}
                                {{-- </a> --}}
                                    </td>
                                    @foreach($branches as $b_id)
                                        <?php
        
                                        if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
        
                                            $operation_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                            $total_income[$b_id] += ($operation_income??0);

                                            $income = $income??0;
                                            $income += $operation_income;
        
                                        ?>
                                        
                                    @endforeach
        
        {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                        
                                        @foreach($branches as $b_id)
                                        
                                        <td style="text-align: right">
                                            {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ number_format(-$operation_income??0,2) }}
                                            {{-- </a> --}}
                                        </td>
                                        <td style="text-align: right">
                                            {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                        </td>
                                        @endforeach
                                        
                                </tr>
                                @endif
                                @endforeach
                                @if(isset($bals[40]))
                                    @if(count($bals[40])>0)
                                    @php
                                        //dd($bals[$sec]); 
                                    @endphp
                                        @forelse($bals[40] as $acc_id => $bal)
                                            @php
                                                //dd($acc_id);
                                            if($acc_id == 162){
                                                if(!companyReportPart() == "company.moeyan"){
                                                    $chat_account = \App\Models\AccountChart::find($acc_id);
                                                }
                                            }else{
                                                $account = \App\Models\AccountChart::find($acc_id);
                                                $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$new_acc)
                                                                                        
                                                                                        
                                                                                        ->first();
                                                //dd($chat_account);
                                            }
                                            @endphp
                                            @if($chat_account != null)
                                                <tr>
                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                            @endif
                                                @foreach($branches as $br_id)
                                                    @if($chat_account != null) 
                                                    <?php
        
                                                        if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                        
                                                            $operation_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                            $total_income[$b_id] += ($operation_income??0);
                
                                                            $income = $income??0;
                                                            $income += $operation_income;
                    
                                                    ?>
                                                        
                                                        <td style="text-align: right">
                                                            {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                                {{ number_format(-$operation_income??0,2) }}
                                                            {{-- </a> --}}
                                                        </td>
                                                        <td style="text-align: right">
                                                            {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                        </td>
                                                    @endif
                                                @endforeach
                                                </tr>
                                                
                                            @endforeach
                                            @endif
                                @endif
                                @php
                                            $income = $income??0;
                                @endphp
                                <tr>
                                    <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Operating Income</b></td>
                                    @foreach($branches as $b_id)
                                        {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                        <td style="text-align: right;padding-right: 10px"><u></u></td>
        
                                    @endforeach
                                    @if(companyReportPart()=='company.mkt')
                                    @foreach($branches as $b_id)
                                    <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format(-$income,2) }}</u></td>
                                   
                                    @endforeach
                                    @endif
                                </tr>
                                @endif
                                
                                @if(isset($bals[70]))

                                
                                @forelse($bals[70] as $acc_id => $bal)
                                        @php
                                            $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                ->where('code','460040')
                                                                                ->first();
                                        @endphp
                                        @if($cash_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->code }}
                                    {{-- </a> --}}
                                        </td>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->name }}
                                    {{-- </a> --}}
                                        </td>
                                        @foreach($branches as $b_id)
                                            <?php
            
                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
            
                                                $service_fee = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                $total_income[$b_id] += ($operation_income??0);
    
                                                $income = $income??0;
                                                $income += $service_fee;
            
                                            ?>
                                            
                                        @endforeach
            
            {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                            @foreach($branches as $b_id)
                                                                        
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                    {{ number_format(-$service_fee??0,2) }}
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                            </td>
                                            @endforeach
                                           
                                            
                                    </tr>
                                    @endif
                                    @endforeach
                                    
                                @endif
            
                                    @php
                                                $income = $income??0;
                                    @endphp
                                   
                                <tr>
                                    <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Income</b></td>
                                    @php
                                        $total_revenue = $total_revenue??0;
                                        $income = $income??0;
                                        $total_income_new = $total_revenue + $income;
                                    @endphp
                                    @foreach($branches as $b_id)
                                        {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                        <td style="text-align: right;padding-right: 10px"><u></u></td>
        
                                    @endforeach
                                    @if(companyReportPart()=='company.mkt')
                                    @foreach($branches as $b_id)
                                    <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format(-$total_income_new,2) }}</u></td>
                                   
                                    @endforeach
                                    @endif
                                </tr> 
                                <tr>
                                    <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                </tr>
                            
                                
                                {{-- Next --}}
                                <tr>
                                    
                                    <td style="padding-left: 30px;"><b>700000</b></td>
                                    <td style="padding-left: 30px;"><b>Other Income </b></td>
                                    @foreach($branches as $b)
                                    
                                    <td style="text-align:center;">Sub Total</td>
                                    <td style="text-align:center;">Total</td>
                                    @endforeach
                                </tr>
                                @if(isset($bals[70]))
                                @forelse($bals[70] as $acc_id => $bal)
                                        @php
                                            $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                ->whereIn('code', ['710100','710120','710140','710150'])
                                                                                ->first();
                                        @endphp
                                        @if($cash_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->code }}
                                    {{-- </a> --}}
                                        </td>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->name }}
                                    {{-- </a> --}}
                                        </td>
                                        @foreach($branches as $b_id)
                                            <?php
            
                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
            
                                                $other_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                $total_income[$b_id] += ($b??0);

                                                 $other =  isset($other)?$other:0;
                                                 $other += $other_income;
            
                                            ?>
                                            
                                        @endforeach
            
            {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                            
                                            @foreach($branches as $b_id)
                                            @php
                                               $other =  $other??0;
                                             @endphp
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                    {{ number_format(-$other_income??0,2) }}
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                            </td>
                                            @endforeach
                                            
                                    </tr>
                                    @endif
                                    @endforeach
            
                                    @if(isset($bals[70]))
                                        @if(count($bals[70])>0)
                                        @php
                                            //dd($bals[$sec]); 
                                        @endphp
                                            @forelse($bals[70] as $acc_id => $bal)
                                                @php
                                                    //dd($acc_id);
                                                if($acc_id == 162){
                                                    if(!companyReportPart() == "company.moeyan"){
                                                        $chat_account = \App\Models\AccountChart::find($acc_id);
                                                    }
                                                }else{
                                                    $account = \App\Models\AccountChart::find($acc_id);
                                                    $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                            ->whereIn('code',$new_acc)
                                                                                            
                                                                                            
                                                                                            ->first();
                                                    //dd($chat_account);
                                                }
                                                @endphp
                                                @if($chat_account != null)
                                                    <tr>
                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                @endif
                                                    @foreach($branches as $br_id)
                                                        @if($chat_account != null) 
                                                        <?php
            
                                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                            
                                                                $other_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                                $total_income[$b_id] += ($b??0);

                                                                $other =  isset($other)?$other:0;
                                                                $other += $other_income;
                            
                                                        ?>
                                                            
                                                            <td style="text-align: right">
                                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                                    {{ number_format(-$other_income??0,2) }}
                                                                {{-- </a> --}}
                                                            </td>
                                                            <td style="text-align: right">
                                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                            </td>
                                                        @endif
                                                    @endforeach
                                                    </tr>
                                                    
                                                @endforeach
                                                @endif
                                    @endif
                                    <tr>
                                        <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Other Income</b></td>
                                        @foreach($branches as $b_id)
                                        @php
                                            $other =  $other??0;
                                        @endphp
                                            {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                            <td style="text-align: right;padding-right: 10px;"><u></u></td>
            
                                        @endforeach
                                        @if(companyReportPart()=='company.mkt')
                                        @foreach($branches as $b_id)
                                        <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format(-$other,2) }}</u></td>
                                       
                                        @endforeach
                                        @endif
                                    </tr>
                                    <tr>
                                        <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                    </tr>
                                    {{-- Next --}}
                                    <tr>
                                        <td style="padding-left: 10px;" colspan="2"><b>Expenses </b></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 30px;"><b>646001 </b></td>
                                        <td style="padding-left: 30px;"><b>Interst Result </b></td>
                                        @foreach($branches as $b)
                                        
                                        <td style="text-align:center;">Sub Total</td>
                                        <td style="text-align:center;">Total</td>
                                        @endforeach
                                    </tr>
                                    @endif
                                   @isset($bals[60])
                                    
                                        @forelse($bals[60] as $acc_id => $bal)
                                        @php
                                            //dd($bals);
                                            $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                ->whereIn('code', ['646040','646050','610710','600002'])
                                                                                ->first();
                                        @endphp
                                        @if($cash_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->code }}
                                    {{-- </a> --}}
                                        </td>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->name }}
                                    {{-- </a> --}}
                                        </td>
                                        @foreach($branches as $b_id)
                                            <?php
            
                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
            
                                                $i_result = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                $total_income[$b_id] += ($b??0);

                                                $inter_result = $inter_result??0;
                                                $inter_result += $i_result;
            
                                            ?>
                                            
                                        @endforeach
                                  
                
                {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                
                                                @foreach($branches as $b_id)
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                        {{ number_format($i_result??0,2) }}
                                                    {{-- </a> --}}
                                                </td>
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                </td>
                                                @endforeach
                                                
                                        </tr>
                                        @endif
                                        @endforeach
                                       
                                        
                                       
                                       
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Interest Result</b></td>
                                            @foreach($branches as $b_id)
                                            @php
                                                $other =  $other??0;
                                            @endphp
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                $inter_result = $inter_result??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($inter_result,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                        </tr>
                                        {{-- Next --}}
                                        <tr>
                                            <td style="padding-left: 10px;" colspan="4"><b>Provision For Loan Loss</b></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px;"><b>635000 </b></td>
                                            <td style="padding-left: 30px;"><b>Provision for doubtful debt </b></td>
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                            @forelse($bals[60] as $acc_id => $bal)
                                            @php
                                                $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                    ->where('code','635010')
                                                                                    ->first();
                                            @endphp  
                                            @if($cash_account != null)
                                        <tr>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->code }}
                                        {{-- </a> --}}
                                            </td>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->name }}
                                        {{-- </a> --}}
                                            </td>
                                            @foreach($branches as $b_id)
                                                <?php
                
                                                if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                
                                                    $debt = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                    $total_income[$b_id] += ($b??0);

                                                    $d_debt = $d_debt??0;
                                                    $d_debt += $debt;
                
                                                ?>
                                                
                                            @endforeach
                
                {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                
                                                @foreach($branches as $b_id)
                                                @php
                                                    $debt = $debt??0;
                                                @endphp
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                        {{ number_format($debt??0,2) }}
                                                    {{-- </a> --}}
                                                    </td>
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                </td>
                                                @endforeach
                                                
                                        </tr>
                                        @endif
                                    @endforeach
            
            
                                   
                                   
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Doubtful Debt</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                $d_debt = $d_debt??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($d_debt,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                        </tr>
                                        
                                            {{-- Next --}}

                                            {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>610005 </b></td>
                                            <td style="padding-left: 30px;"><b>Salary & Wages </b></td>
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_wages = ['610010','610011','610012','610013','610019','610020','610030','610040','610050','610100','610110','610120','610130','610135','610005'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_wages)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $wages = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $salary = $salary??0;
                                                        $salary += $wages;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($wages??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                    
                    
                                        
                                            
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Salary and Wages</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                 $salary = $salary??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($salary,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                        </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>610200  </b></td>
                                            <td style="padding-left: 30px;"><b>HR Expenses </b></td>
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_hr = ['610320','610345','610350','610355','610360','610450','610500','610520','610530','610535','610650','610660','610990','610523'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_hr)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $hr = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $expense = $expense??0;
                                                        $expense += $hr;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($hr??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total HR Expenses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                 $expense = $expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                             {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>611000 </b></td>
                                            <td style="padding-left: 30px;"><b>Training Expenses </b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_train = ['610410','610420','610425'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_train)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $training = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $t_expense = $t_expense??0;
                                                        $t_expense += $training;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                            
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($training??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Training Expenses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                 $t_expense = $t_expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($t_expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                             {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>615000 </b></td>
                                            <td style="padding-left: 30px;"><b>Administration Expenses </b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_admin = ['600202','610400','610430','610435','610510','610991','615015','615016','615017','616530','616540','616510','616560','616570','617000','617520','617530','625000','625020','625030','625040','625080','630020','630025'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_admin)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $admin = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $a_expense = $a_expense??0;
                                                        $a_expense += $admin;
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($admin??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Administration Expenses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                 $a_expense = $a_expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($a_expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                            {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>621000 </b></td>
                                            <td style="padding-left: 30px;"><b>Maintenance & Repairs </b></td>
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                        @forelse($bals[60] as $acc_id => $bal)
                                        @php
                                            $account_maintain = ['621010','621020','621030','621040','621050'];
                                            $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                ->whereIn('code',$account_maintain)
                                                                                ->first();
                                        @endphp
                                        @if($cash_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->code}}
                                    {{-- </a> --}}
                                        </td>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->name }}
                                    {{-- </a> --}}
                                        </td>
                                        @foreach($branches as $b_id)
                                            <?php
            
                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
            
                                                $maintain = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                $total_income[$b_id] += ($b??0);

                                                $repair = $repair??0;
                                                $repair += $maintain;
                                            ?>
                                            
                                        @endforeach
            
            {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                            
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                    {{ number_format($maintain??0,2) }}
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                            </td>
                                            @endforeach
                                            
                                    </tr>
                                    @endif
                                    @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Maintenance & Repairs</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                 $repair = $repair??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($repair,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                             {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>621100</b></td>
                                            <td style="padding-left: 30px;"><b>Utilities & Supplies</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                            @forelse($bals[60] as $acc_id => $bal)
                                            @php
                                                $account_supply = ['621110','621120','621130'];
                                                $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                    ->whereIn('code',$account_supply)
                                                                                    ->first();
                                            @endphp
                                            @if($cash_account != null)
                                        <tr>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->code}}
                                        {{-- </a> --}}
                                            </td>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->name }}
                                        {{-- </a> --}}
                                            </td>
                                            @foreach($branches as $b_id)
                                                <?php
                
                                                if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                
                                                    $ult = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                    $total_income[$b_id] += ($b??0);

                                                    $supply = $supply??0;
                                                    $supply += $ult;
                
                                                ?>
                                                
                                            @endforeach
                
                {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                
                                                @foreach($branches as $b_id)
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                        {{ number_format($ult??0,2) }}
                                                    {{-- </a> --}}
                                                </td>
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                </td>
                                                @endforeach
                                                
                                        </tr>
                                        @endif
                                        @endforeach 
                                        @endisset
                                        
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Utilities & Supplies</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $supply = $supply??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($supply,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                            {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>615110</b></td>
                                            <td style="padding-left: 30px;"><b>Professional Fee</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_professional = ['615120','615130','615140','615150','615160','615170'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_professional)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $pro = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $fee = $fee??0;
                                                        $fee += $pro;
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($pro??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal">
                                                        </a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Professional Fee</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $fee = $fee??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;">
                                                <u>{{ number_format($fee,2) }}</u>
                                            </td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                            
                                            {{-- Next --}}
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>615200</b></td>
                                            <td style="padding-left: 30px;"><b>IT Expenses</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                            @forelse($bals[60] as $acc_id => $bal)
                                            @php
                                                $account_it = ['610325','615180','615300','615310','615315','615318','615320'];
                                                $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                    ->whereIn('code',$account_it)
                                                                                    ->first();
                                            @endphp
                                            @if($cash_account != null)
                                        <tr>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->code }}
                                        {{-- </a> --}}
                                            </td>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->name }}
                                        {{-- </a> --}}
                                            </td>
                                            @foreach($branches as $b_id)
                                                <?php
                
                                                if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                
                                                    $b = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                    $total_income[$b_id] += ($b??0);
                                                    $it_expense = $it_expense??0;
                                                    $it_expense += $b;
                
                                                ?>
                                                
                                            @endforeach
                
                {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                
                                                @foreach($branches as $b_id)
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                        {{ number_format($b??0,2) }}
                                                    {{-- </a> --}}
                                                </td>
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                </td>
                                                @endforeach
                                                
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total IT Expenses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $it_expense = $it_expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($it_expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                            {{-- Next --}}
                                        <tr>
                                            <td style="padding-left: 30px;"><b>615400</b></td>
                                            <td style="padding-left: 30px;" colspan="34"><b>Travel & Entertainment</b></td>
                                        </tr>
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>615401</b></td>
                                            <td style="padding-left: 30px;"><b>Travelling (Local)</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_local = ['615410','615430','615490'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_local)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $local = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $travel_local = $travel_local??0;
                                                        $travel_local += $local;
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($local??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Travelling (Local)</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $travel_local = $travel_local??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($travel_local,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            
                                            {{-- Next --}}
                                        
                                        <tr>
                                            <td style="padding-left: 30px;"><b>616000</b></td>
                                            <td style="padding-left: 30px;"><b>Travelling (Oversea)</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                        @forelse($bals[60] as $acc_id => $bal)
                                        @php
                                            $account_oversea = ['616010','616030','616090'];
                                            $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                ->whereIn('code',$account_oversea)
                                                                                ->first();
                                        @endphp
                                        @if($cash_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->code }}
                                    {{-- </a> --}}
                                        </td>
                                        <td style="padding-left: 60px;"> 
                                        {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                        {{ $cash_account->name }}
                                    {{-- </a> --}}
                                        </td>
                                        @foreach($branches as $b_id)
                                            <?php
            
                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
            
                                                $over = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                $total_income[$b_id] += ($b??0);

                                                $travel_over = $travel_over??0;
                                                $travel_over += $over;
            
                                            ?>
                                            
                                        @endforeach
            
            {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                            
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                    {{ number_format($over??0,2) }}
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align: right">
                                                {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                            </td>
                                            @endforeach
                                            
                                    </tr>
                                    @endif
                                    @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Travelling (Oversea)</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $travel_over = $travel_over??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($travel_over,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}
                                            {{-- Next --}}
                                        <tr>
                                            <td style="padding-left: 30px;"><b>628000 </b></td>
                                            <td style="padding-left: 30px;" colspan="3"><b>Operation Expeneses </b></td>
                                        </tr>
                                       
                                       
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_operation = ['600010','616580','630040'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_operation)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $operation = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $o_expense = $o_expense??0;
                                                        $o_expense += $operation;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($operation??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Operation Expeneses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $o_expense = $o_expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($o_expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                        
                                        <tr>
                                            <td style="padding-left: 30px;"><b>622000 </b></td>
                                            <td style="padding-left: 30px;"><b>Upkeep of Vehicles</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_oversea = ['622010','622020','622030'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_oversea)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $vehicle = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $upkeep = $upkeep??0;
                                                        $upkeep += $vehicle;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($vehicle??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Upkeep of Vehicles</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $upkeep = $upkeep??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($upkeep,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                        
                                        <tr>
                                            <td style="padding-left: 30px;"><b>623000</b></td>
                                            <td style="padding-left: 30px;"><b>Fuel</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_fuel = ['623010','623020','623030'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_fuel)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code}}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $oil = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $fuel = $fuel??0;
                                                        $fuel += $oil;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($oil??0,2) }}
                                                        {{-- </a> --}}
                                                        </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Fuel</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $fuel = $fuel??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($fuel,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                        
                                        <tr>
                                            <td style="padding-left: 30px;"><b>629000</b></td>
                                            <td style="padding-left: 30px;"><b>Rental Expenses</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_rental = ['615023','615021','615022','615025'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_rental)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $rental = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $r_expense = $r_expense??0;
                                                        $r_expense += $rental;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($rental??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Rental Expenses</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $r_expense = $r_expense??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($r_expense,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                        <tr>
                                            <td style="padding-left: 30px;" ><b>641000 </b></td>
                                            <td style="padding-left: 30px;" colspan="3"><b>DEPRECIATION & AMORTIZATION</b></td>
                                        </tr>
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>641001</b></td>
                                            <td style="padding-left: 30px;"><b>Depreciation</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                   $account_depre = ['641040','641050','641060','641070','641075','641200'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_depre)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                               
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $dep = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $depre = $depre??0;
                                                        $depre += $dep;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($dep??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Depreciation</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $depre = $depre??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($depre,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                      
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>642000</b></td>
                                            <td style="padding-left: 30px;"><b>Amortization</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_amor = ['642001','642030','642060'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_amor)
                                                                                        ->first();
                                                    
                                                @endphp
                                                @if($cash_account != null)
                                               
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                                                        $amo = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $amorti = $amorti??0;
                                                        $amorti += $amo;
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($amo??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        <tr>
                                            <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Amortization</b></td>
                                            @foreach($branches as $b_id)
                                           
                                                {{--<td style="text-align: right">{{ number_format(-$b??0,2) }}</td>--}}
                                                <td style="text-align: right;padding-right: 10px"><u></u></td>
                
                                            @endforeach
                                            @if(companyReportPart()=='company.mkt')
                                            @php
                                                  $amorti = $amorti??0;
                                            @endphp
                                            @foreach($branches as $b_id)
                                            <td style="text-align: right;padding-right: 10px;color: green;"><u>{{ number_format($amorti,2) }}</u></td>
                                           
                                            @endforeach
                                            @endif
                                        </tr>
                    
                                            @php
                                                $amorti = $amorti??0;
                                                $depre = $depre??0;
                                                $r_expense = $r_expense??0;
                                                $fuel = $fuel??0;
                                                $upkeep = $upkeep??0;
                                                $o_expense = $o_expense??0;
                                                $travel_over = $travel_over??0;
                                                $travel_local = $travel_local??0;
                                                $it_expense = $it_expense??0;
                                                $fee = $fee??0;
                                                $supply = $supply??0;
                                                $repair = $repair??0;
                                                $a_expense = $a_expense??0;
                                                $t_expense = $t_expense??0;
                                                $expense = $expense??0;
                                                $salary = $salary??0;
                                                $d_debt = $d_debt??0;
                                                $inter_result = $inter_result??0;
                                                $total_cost = $amorti + $depre + $r_expense + $fuel + $upkeep + $o_expense + $travel_over + $travel_local + $it_expense +
                                                              $fee + $supply + $repair + $a_expense + $t_expense + $expense + $salary + $d_debt + $inter_result ;
                                                $earn_tax = ($total_income_new + $other) + $total_cost;  
                                                
                                                $before_tax = -($earn_tax);
                                            @endphp
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;color: green;" colspan="2"><b>Total Operating Cost</b></td>
                                                <td></td>
                                                <td style="text-align: right;padding-right: 10px;color: green;">{{ number_format($total_cost,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;color: green;" colspan="2"><b>Earning Before Interest and Tax</b></td>
                                                <td></td>
                                                <td style="text-align: right;padding-right: 10px;color: green;">{{ number_format($before_tax,2) }}</td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                       
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>630000</b></td>
                                            <td style="padding-left: 30px;"><b>Financial Expenses</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>
                                        @isset($bals[60])
                                            @forelse($bals[60] as $acc_id => $bal)
                                            @php
                                                $account_finan = ['646030','630010'];
                                                $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                    ->whereIn('code',$account_finan)
                                                                                    ->first();
                                            @endphp
                                            @if($cash_account != null)
                                        <tr>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->code }}
                                        {{-- </a> --}}
                                            </td>
                                            <td style="padding-left: 60px;"> 
                                            {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                            {{ $cash_account->name }}
                                        {{-- </a> --}}
                                            </td>
                                            @foreach($branches as $b_id)
                                                <?php
                
                                                if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                
                                                    $f_expense = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                    $total_income[$b_id] += ($b??0);

                                                    $finicial = $finicial??0;
                                                    $finicial += $f_expense;
                                                ?>
                                                
                                            @endforeach
                
                {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                
                                                @foreach($branches as $b_id)
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                        {{ number_format($f_expense??0,2) }}
                                                    {{-- </a> --}}
                                                </td>
                                                <td style="text-align: right">
                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                </td>
                                                @endforeach
                                                
                                        </tr>
                                        @endif
                                        @endforeach
                
                                        @endisset
                                        
                    
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            @php
                                                $finicial = $finicial??0;
                                                $total_cost = $total_cost??0;
                                                //dd($earn_tax);
                                                $before_tax = $before_tax??0;
                                                $finicial = $finicial??0;
                                                $earn_b_tax = -$before_tax - $finicial;
                                                //$tax_all = ($before_tax*0.25);
                                            @endphp
                                            <tr>
                                                <td style="padding-left: 30px;color: green;" colspan="2"><b>Earning  Before Tax</b></td>
                                                <td></td>
                                                <td style="color: green;text-align: right">{{ number_format(-$earn_b_tax,2) }}</td>
                                            </tr>
                                            {{-- Next --}}

                                            {{-- Next --}}
                                       
                                       
                                        <tr>
                                            <td style="padding-left: 30px;"><b>805000</b></td>
                                            <td style="padding-left: 30px;"><b>Income Taxes</b></td>
                                            
                                            @foreach($branches as $b)
                                            
                                            <td style="text-align:center;">Sub Total</td>
                                            <td style="text-align:center;">Total</td>
                                            @endforeach
                                        </tr>

                                        @isset($bals[60])
                                                @forelse($bals[60] as $acc_id => $bal)
                                                @php
                                                    $account_income = ['805010','805020','805000'];
                                                    $cash_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                        ->whereIn('code',$account_income)
                                                                                        ->first();
                                                @endphp
                                                @if($cash_account != null)
                                            <tr>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->code }}
                                            {{-- </a> --}}
                                                </td>
                                                <td style="padding-left: 60px;"> 
                                                {{-- <a class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                {{ $cash_account->name }}
                                            {{-- </a> --}}
                                                </td>
                                                @foreach($branches as $b_id)
                                                    <?php
                    
                                                    if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                    
                                                        $tax_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                        $total_income[$b_id] += ($b??0);

                                                        $income_tax = $income_tax??0;
                                                        $income_tax += $tax_income;
                    
                                                    ?>
                                                    
                                                @endforeach
                    
                    {{--                            <td style="text-align: right">{{ number_format(-$bal??0,2) }}</td>--}}
                                                    
                                                    @foreach($branches as $b_id)
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                            {{ number_format($tax_income??0,2) }}
                                                        {{-- </a> --}}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                    </td>
                                                    @endforeach
                                                    
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endisset
                                        @php
                                            $income_tax = $income_tax??0;
                                            $earn_b_tax = $earn_b_tax??0;
                                            $tax_after = $earn_b_tax + $income_tax;
                                           
                                        @endphp
                    
                                        @if(isset($bals[60]))
                                            @if(count($bals[60])>0)
                                            @php
                                                //dd($bals[$sec]); 
                                            @endphp
                                                @forelse($bals[60] as $acc_id => $bal)
                                                    @php
                                                        //dd($acc_id);
                                                    if($acc_id == 162){
                                                        if(!companyReportPart() == "company.moeyan"){
                                                            $chat_account = \App\Models\AccountChart::find($acc_id);
                                                        }
                                                    }else{
                                                        $account = \App\Models\AccountChart::find($acc_id);
                                                        $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                ->whereIn('code',$new_acc)
                                                                                                
                                                                                                
                                                                                                ->first();
                                                        //dd($chat_account);
                                                    }
                                                    @endphp
                                                    @if($chat_account != null)
                                                        <tr>
                                                            <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                            <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                    @endif
                                                        @foreach($branches as $br_id)
                                                            @if($chat_account != null) 
                                                            <?php
                    
                                                            if(!isset($total_income[$b_id])) $total_income[$b_id] = 0;
                            
                                                                $tax_income = isset($bal[$b_id])?($bal[$b_id]??0):0;
                                                                $total_income[$b_id] += ($b??0);

                                                                $income_tax = $income_tax??0;
                                                                $income_tax += $tax_income;
                            
                                                            ?>
                                                                
                                                                <td style="text-align: right">
                                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"> --}}
                                                                        {{ number_format(-$tax_income??0,2) }}
                                                                    {{-- </a> --}}
                                                                </td>
                                                                <td style="text-align: right">
                                                                    {{-- <a  class="general-leg40" id="{{ $acc_id }}" data-toggle="modal" data-target="#show-detail-modal"></a> --}}
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                        </tr>
                                                        
                                                    @endforeach
                                                    @endif
                                        @endif
                                           
                                            <tr>
                                                <td colspan="{{count($branches)+3}}" style="height: 30px; overflow:hidden;"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;color: green;" colspan="2"><b>Earning After Interest and Tax </b></td>
                                                <td></td>
                                                <td style="color: green;text-align: right">{{ number_format(-$tax_after,2) }}</td>
                                            </tr>
                                            {{-- Next --}}

                                            


                @endif
                
            {{--  @endif  --}}



                       



                                    
        </tbody>
    </table>
<script>
    $(".general-leg40, .general-leg50, .general-leg60, .general-leg70, .general-leg80").click(function(){
        getReport($(this).attr("id"));
    });

    function getReport(id){
        var acc_chart_id= [id];
        var start_date = $('[name="start_date"]').val();
        var end_date = $('[name="end_date"]').val();
        var month = $('[name="month"]').val();
        var show_zero = $('[name="show_zero"]').val();
        var branch_id = $('[name="branch_id[]"]').val();
        var branch_id = 1;
        $.ajax({
            url: "{{ url('report/general-leger')}}",
            type: 'GET',
            async: false,
            dataType: 'html',
            data: {
                start_date:start_date,
                end_date:end_date,
                month:month,
                acc_chart_id:acc_chart_id,
                show_zero:show_zero,
                branch_id: branch_id
            },
            success: function (d) {
                $('.modal-body').html(d);
            },
            error: function (d) {
                alert('error');
                $('.modal-body').hide();
            }
        });
    }

    function printDiv() {

        var DivIdToPrintPop = document.getElementById('DivIdToPrintPop');//DivIdToPrintPop
        if(DivIdToPrintPop != null) {
            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + DivIdToPrintPop.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function () {
                newWin.close();
            }, 10);
        }
    }
</script>
@else
<h1>No data</h1>
@endif
</div>
