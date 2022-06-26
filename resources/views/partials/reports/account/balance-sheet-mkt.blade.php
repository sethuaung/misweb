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
                <button type="button" onclick="excelGeneral('DivIdToPrintPop') " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">EXCEL</button>
            </div>
            <div class="action" style="float: right;">
                <button type="button" onclick="printDiv() " style="cursor: pointer; background: #0b58a2;padding: 10px 20px; color: #fff; margin-bottom: 10px;">PRINT</button>
            </div>
        </div>
    </div>

<script>
    function excelGeneral(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        // console.log(tableID);
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>

</div>
<input type="hidden" id="start_date" value="{{$start_date}}">
<input type="hidden" id="end_date" value="{{$end_date}}">
<input type="hidden" id="company" value="{{companyReportPart()}}">
<style>
    .modal-lg{
        width: 75%;
    }
</style>
<div id="DivIdToPrint">
    @if($bals != null)
        @php
            //dd($bals);
            if(isset($bals[30])){
               
            }
            else{
                array_push($bals,[30]);
                $bals[30][0] = 0.0;
                $bals[30][138] = 0.0;
            }
            //dd($bals);
            $today = date('Y-m-d');
            $net_income = $net_income??null;

            $new_acc = App\Models\AccountChart::where('created_at','>=','2019-09-18 00:00:00')->pluck('code')->toArray();
            //dd($new_acc);
        @endphp
        @include('partials.reports.header',
        ['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
        <div>{{$today}}</div>
        @foreach($branchs as $branch)
        @php
            $a = \App\Models\Branch::find($branch);
        @endphp
            <div><span class="pull-right" style="margin-bottom:4px;">{{optional($a)->title}},</span></div>
        @endforeach
        <table border="1" class="table-data" id="table-data">
            <tbody>
            <tr>
                <td style="color: blue;" colspan="2"><b>Assets</b></td>
                @foreach($branchs as $b)
                    @php
                        $c = \App\Models\BranchU::find($b);
                    @endphp 
                    <td colspan="0" style="color: blue;"><b>{{optional($c)->title}}</b></td>
                @endforeach
            </tr>

            @php
                $total_current_asset = [];
            @endphp
           
            @php
            //dd($net_income);
       @endphp
       @if(isset($bals[16]))
       
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 30px"><b>Fixed Assets</b></td>
       </tr>    
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Intangible Assets</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Formation Expenses</b></td>
       </tr>
           
       @foreach([16] as $sec)
       @if(isset($bals_dr[$sec]))
           @if(count($bals_dr[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals_dr[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                           //dd($chat_account);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);                                                                                           
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['100120','100130','100102','100101'])
                                                               ->first();
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                           <?php
                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                           $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                           $f_exp = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                    $formation_exp = $formation_exp??0;
                                    $formation_exp += $f_exp;
                                
                        
                          
                          
                           ?>
                          
                           <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                         @endif
                       @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                      
                       @endif
                       {{-- Minus from net profit loss start here --}}
                       @if(isset($bals[16]))
           
       @foreach([16] as $sec)
       @if(isset($bals_dr[$sec]))
           @if(count($bals_dr[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals_dr[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                           //dd($chat_account);
                       }
                   }else{
                       $account = \App\Models\AccountChart::find($acc_id);                                                                                           
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['100130','100220','100710','105120','107020','107390','107520','108040','108520'])
                                                               ->first();
                   }
                   @endphp
                   @if($chat_account != null)
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                           <?php
                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                           $minus_solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                           $minus = $minus??0;
                           $minus += $minus_solo;
                            ?>
                          
                           
                         @endif
                       @endforeach
                   
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                      
                       @endif
                {{-- Minus from net profit loss end here --}}

                       @if(isset($bals_dr[16]))
                      
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Software Cap</b></td>
                       </tr>
                           
                       @foreach([16] as $sec)
                       @if(isset($bals_dr[$sec]))
                           @if(count($bals_dr[$sec])>0)
                           @php
                              //dd($minus); 
                           @endphp
                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                   @php
                                       //dd($acc_id);
                                   if($acc_id == 162){
                                       if(!companyReportPart() == "company.moeyan"){
                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                       }
                                   }else{
                                       $account = \App\Models\AccountChart::find($acc_id);
                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                               ->whereIn('code',['100210','100220','100200'])
                                                                               
                                                                               
                                                                               ->first();
                                       //dd($chat_account);
                                   }
                                   @endphp
                                    @if($chat_account != null)
                                    <tr>
                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                                        <td style="padding-left: 60px;">{{ optional($chat_account)->name }}</td>
                                    @endif
                                        @foreach($branchs as $br_id)
                                        @if($chat_account != null) 
                                            <?php
                                            if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                               $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                               $s_cap = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                               
                                                    $software_cap = $software_cap??0;
                                                    $software_cap += $s_cap
                                               
                                               
                                               ?>
                                            
                                               <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                           @endif
                                        @endforeach
                                    </tr>
                                   
                                   @endforeach
                                   @endif
                                   @endif
                                   @endforeach
                                       
                                       @endif
                                        @php
                                            //dd($software_cap);
                                        @endphp
                                       @if(isset($bals_dr[16]))
                      
                                       <tr>
                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Other Intangible Assets</b></td>
                                       </tr>
                                           
                                       @foreach([16] as $sec)
                                       @if(isset($bals_dr[$sec]))
                                           @if(count($bals_dr[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                   if($acc_id == 162){
                                                       if(!companyReportPart() == "company.moeyan"){
                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                       }
                                                   }else{
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->whereIn('code',['100700','100710','100300'])
                                                                                               
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   }
                                                   @endphp
                                                    @if($chat_account != null)
                                                    <tr>
                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->name }}</td>
                                                    @endif
                                                    @foreach($branchs as $br_id)
                                                    @if($chat_account != null)  
                                                    <?php
                                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $intangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $o_asset = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                       
                                                                $other_asset = $other_asset??0;
                                                                $other_asset += $o_asset;
                                                            
                                                      
                                                      
                                                       //dd($software_cap);
                                                       ?>
                                                   
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                @endforeach
                                                    </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                   <tr>
                                                       <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Intangible Assets</b></td>
                                                       <?php $l_current_asset = 0; ?>
                                                       @foreach($branchs as $br_id)
                                                           <?php
                                                           $other_asset = $other_asset??0;
                                                           $software_cap = $software_cap??0;
                                                           $formation_exp = $formation_exp??0;

                                                           $intangible_assets = $other_asset + $software_cap + $formation_exp;
                                                           //$l_current_asset += $total_current_asset[$br_id];
                                                           //dd($intangible_assets);
                                                           ?>
                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($intangible_assets,2) }}</u></td>
                                                       @endforeach
                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                   </tr>
                                                   <tr>
                                                       <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                   </tr>
                                                       @endif
                                                       @if(isset($bals[16]))
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Tangible Assets</b></td>
                                                       </tr>    
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Land</b></td>
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Buildings</b></td>
                                                       </tr>
                                                           
                                                       @foreach([16] as $sec)
                                                       @if(isset($bals_dr[$sec]))
                                                           @if(count($bals_dr[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['105110','105210','105100','105001','105000'])
                                                                                                              
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                      $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $build = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                            
                                                                                    $building = $building??0;
                                                                                    $building += $build;
                                                                            
                                                                      
                                                                      ?>
                                                                    
                                                                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                  @endif
                                                               @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                      
                                                                       @endif
                                                    @if(isset($bals[16]))
                                                      
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Furniture Fixtures & Fittings</b></td>
                                                       </tr>
                                                           
                                                       @foreach([16] as $sec)
                                                       @if(isset($bals_dr[$sec]))
                                                           @if(count($bals_dr[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals_dr[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   if($acc_id == 162){
                                                                       if(!companyReportPart() == "company.moeyan"){
                                                                           $chat_account = \App\Models\AccountChart::find($acc_id);
                                                                       }
                                                                   }else{
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['107010','107020','107000'])
                                                                                                               
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   }
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{optional($chat_account)->name}}</td>
                                                                   @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                      $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                      
                                                                        $furni = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                            
                                                                                    $furniture = $furniture??0;
                                                                                    $furniture += $furni;
                                                                            
                                                                      
                                                                      ?>
                                                                   
                                                                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                  @endif
                                                               @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                      
                                                                       @endif
       @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Electronic and Electrical equipment</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['107310','107390','107300'])
                                                       
                                                      
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
               <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
               $elect = isset($bal[$br_id])?($bal[$br_id]??0):0;

                   
                            $electronic = $electronic??0;
                            $electronic += $elect;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif

       @if(isset($bals_dr[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Computer & IT Equipments</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['107510','107520','107500'])
                                                       
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
               <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
                $com = isset($bal[$br_id])?($bal[$br_id]??0):0;

                            $computer = $computer??0;
                            $computer += $com;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif
       @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Motor Vehicles</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['108010','108040','108000'])
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
               <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null)
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;

             $mot = isset($bal[$br_id])?($bal[$br_id]??0):0;

                    
                            $motor = $motor??0;
                            $motor += $mot;
                    
              
              ?>
            
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @endforeach
               
               @endif
               
           @if(isset($bals[16]))
                                                      
<tr>
   <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Tools & Equipments</b></td>
</tr>
   
@foreach([16] as $sec)
@if(isset($bals_dr[$sec]))
   @if(count($bals_dr[$sec])>0)
   @php
       //dd($bals[$sec]); 
   @endphp
       @forelse($bals_dr[$sec] as $acc_id => $bal)
           @php
               //dd($acc_id);
           if($acc_id == 162){
               if(!companyReportPart() == "company.moeyan"){
                   $chat_account = \App\Models\AccountChart::find($acc_id);
               }
           }else{
               $account = \App\Models\AccountChart::find($acc_id);
               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                       ->whereIn('code',['108510','108520','108500'])
                                                      
                                                       
                                                       ->first();
               //dd($chat_account);
           }
           @endphp
           @if($chat_account != null)
           <tr>
               <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
               <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
           @endif
           @foreach($branchs as $br_id)
           @if($chat_account != null) 
           <?php
           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
              $assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
              $tangible =  isset($bal[$br_id])?($bal[$br_id]??0):0;
              
               $tools = isset($bal[$br_id])?($bal[$br_id]??0):0;

                    
                            $equipment = $equipment??0;
                            $equipment += $tools;
                    
              
              ?>
           
              <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
          @endif
       @endforeach
           </tr>
            
           @endforeach
           @endif
           @endif
           @if(isset($bals_dr[$sec]))
                @if(count($bals_dr[$sec])>0)
                @php
                    //dd($bals[$sec]); 
                @endphp
                    @forelse($bals_dr[$sec] as $acc_id => $bal)
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
                        @foreach($branchs as $br_id)
                        @if($chat_account != null) 
                        <?php
                        if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                            
                            $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                          
                            

                                    
                                            $new_amount = $new_amount??0;
                                            $new_amount += $solo;
                                    
                            
                            ?>
                        
                            <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                        @endif
                    @endforeach
                        </tr>
                            
                        @endforeach
                        @endif
            @endif
           @endforeach
           <tr>
               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Tangible Assets</b></td>
               <?php $l_current_asset = 0; ?>
               @foreach($branchs as $br_id)
                   <?php
                       $furniture = $furniture??0;
                       $building = $building??0;
                       $electronic = $electronic??0;
                       $motor = $motor??0;
                       $computer = $computer??0;
                       $equipment = $equipment??0;
                       $new_amount = $new_amount??0;
                       $tangible_assets = $furniture + $building +  $electronic + $motor + $computer + $equipment + $new_amount;
                   ?>
                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($tangible_assets,2) }}</u></td>
               @endforeach
               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
           </tr>
           <tr>
               <td style="padding-left: 10px" colspan="{{count($branchs)+1}}"><b>Total Fixed Assets</b></td>
               <?php $l_current_asset = 0; ?>
               @foreach($branchs as $br_id)
                   <?php
                       $fixed_assets = $intangible_assets + $tangible_assets;
                   ?>
                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($fixed_assets,2) }}</u></td>
               @endforeach
               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
           </tr>
           <tr>
               <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
           </tr>
               @endif
               
       @if(isset($bals[10]))
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 30px"><b>Current Assets</b></td>
       </tr>    
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Cash And Bank Equivalents</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Bank Account</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   if($acc_id == 162){
                       if(!companyReportPart() == "company.moeyan"){
                           $chat_account = \App\Models\AccountChart::find($acc_id);
                       }
                   }else{
                       
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 150 . '%')
                                                               ->first();
                       //dd($chat_account);
                   }
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                   @endif
                   @foreach($branchs as $br_id)
                   @if($chat_account != null)
                   <?php
                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                      $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $bank = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                      $bank_total = $bank_total??0;
                      $current = $current??0;

                      $current += $current_assets;
                      $bank_total += $bank;
                      ?>
                    
                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                  @endif
               @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Bank</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $bank_total = $bank_total??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($bank_total,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

       @if(isset($bals[10]))
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>CASH IN HAND</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 153 . '%')
                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                               ->first();
                       //dd($chat_account);
                   
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name }}</td>
                   @endif
                   @foreach($branchs as $br_id)
                   @if($chat_account != null)
                   <?php
                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                      $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $cash_in = isset($bal[$br_id])?($bal[$br_id]??0):0;
                      $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                      $cash_in_hand = $cash_in_hand??0;
                      $current = $current??0;
                      $current += $current_assets;
                      $cash_in_hand += $cash_in;
                      ?>
                   
                      <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                  @endif
               @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Cash</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $cash_in_hand = $cash_in_hand??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($cash_in_hand,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       <tr>

                        <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Cash And Bank Equivalents</b></td>
                        <?php $l_current_asset = 0; 
                            $bank_total = $bank_total??0;
                            $cash_in_hand = $cash_in_hand??0;
                        ?>
                        @foreach($branchs as $br_id)
                            <?php
                                $cash_equi = $bank_total + $cash_in_hand;
                            ?>
                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format($cash_equi,2) }}</u></td>
                        @endforeach
                        {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                    </tr>
                    <tr>
                        <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                    </tr>
                       @endif

                       @if(isset($bals[10]))
         
       
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>CASH IN TRANSIT</b></td>
       </tr>
           
       @foreach([10] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                  
                       $account = \App\Models\AccountChart::find($acc_id);
                       $account_transit = [''];
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->where('code', 'LIKE', 1570 . '%')
                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                               ->first();
                       //dd($chat_account);
                   
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $cash_transit = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $cash_in_transit = $cash_in_transit??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $cash_in_transit += $cash_transit;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @if(isset($bals[$sec]))
                        @if(count($bals[$sec])>0)
                        @php
                            //dd($bals[$sec]); 
                        @endphp
                            @forelse($bals[$sec] as $acc_id => $bal)
                                @php
                                    //dd($acc_id);
                                
                                    $account = \App\Models\AccountChart::find($acc_id);
                                    $account_transit = [''];
                                    $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                            ->whereIn('code',$new_acc)
                                                                            //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                            ->first();
                                    //dd($chat_account);
                                
                                @endphp
                                @if($chat_account != null)
                                <tr>
                                    <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                                    <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                @endif
                                    @foreach($branchs as $br_id)
                                    @if($chat_account != null)
                                    <?php
                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                    $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                    $cash_transit = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                    $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                    $cash_in_transit = $cash_in_transit??0;
                                    $current = $current??0;
                                    $current += $current_assets;
                                    $cash_in_transit += $cash_transit;
                                    ?>
                                    
                                    <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                @endif
                                @endforeach
                                </tr>
                                
                                @endforeach
                                @endif
                                @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Cash in Transit</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $cash_in_transit= $cash_in_transit??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($cash_in_transit,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[14]))
         
       
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Inventorys</b></td>
       </tr>
           
       @foreach([14] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                  
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['165000','179900','179910','179940'])
                                                               ->first();
                       //dd($chat_account);
                   
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code }}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $invertory = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $total_inventory = $total_inventory??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $total_inventory += $invertory;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Inventorys</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                $total_inventory = $total_inventory??0;   
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_inventory,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[12]))
         
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>ACCOUNT RECEIVABLES</b></td>
       </tr>
       <tr>
           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Service Receivable</b></td>
       </tr>
           
       @foreach([12] as $sec)
       @if(isset($bals[$sec]))
           @if(count($bals[$sec])>0)
           @php
              //dd($bals[$sec]); 
           @endphp
               @forelse($bals[$sec] as $acc_id => $bal)
                   @php
                       //dd($acc_id);
                   
                       $account = \App\Models\AccountChart::find($acc_id);
                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                               ->whereIn('code',['183000','185000','185810','185820','185830','185840','185850','185860','185910','185920','185930','185950','185960','185965'])
                                                               ->first();
                       //dd($chat_account);
                   
                   @endphp
                   @if($chat_account != null)
                   <tr>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                   @endif
                       @foreach($branchs as $br_id)
                       @if($chat_account != null)
                       <?php
                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $receviable = isset($bal[$br_id])?($bal[$br_id]??0):0;
                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                       $service_receviable = $service_receviable??0;
                       $current = $current??0;
                       $current += $current_assets;
                       $service_receviable += $receviable;
                       ?>
                     
                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                   @endif
                   @endforeach
                   </tr>
                   
                   @endforeach
                   @endif
                   @endif
                   @endforeach
                       <tr>
                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Service Receivable</b></td>
                           <?php $l_current_asset = 0; ?>
                           @foreach($branchs as $br_id)
                               <?php
                                   $service_receviable = $service_receviable??0;
                               ?>
                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($service_receviable,2) }}</u></td>
                           @endforeach
                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                       </tr>
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                       </tr>
                       @endif

                       @if(isset($bals[12]))
         
                       
                       <tr>
                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Interest Receivable</b></td>
                       </tr>
                           
                       @foreach([12] as $sec)
                       @if(isset($bals[$sec]))
                           @if(count($bals[$sec])>0)
                           @php
                              //dd($bals); 
                           @endphp
                               @forelse($bals[$sec] as $acc_id => $bal)
                                   @php
                                       //dd($acc_id);
                                  
                                       $account = \App\Models\AccountChart::find($acc_id);
                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                               ->whereIn('code',['186400','186110','186120','186130','186140','186200','186210','186150','186250','186260','186160','186230','186220','186170'])
                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                               ->first();
                                       //dd($chat_account);
                                   
                                   @endphp
                                   @if($chat_account != null)
                                   <tr>
                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                   @endif
                                       @foreach($branchs as $br_id)
                                       @if($chat_account != null)
                                       <?php
                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                       $interest = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                       $interest_receviable = $interest_receviable??0;
                                       $current = $current??0;
                                       $current += $current_assets;
                                       $interest_receviable += $interest;
                                       ?>
                                     
                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                   @endif
                                   @endforeach
                                   </tr>
                                   
                                   @endforeach
                                   @endif
                                   @endif

                                   @if(isset($bals[$sec]))
                                        @if(count($bals[$sec])>0)
                                        @php
                                            //dd($bals); 
                                        @endphp
                                            @forelse($bals[$sec] as $acc_id => $bal)
                                                @php
                                                    //dd($acc_id);
                                                
                                                    $account = \App\Models\AccountChart::find($acc_id);
                                                    $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                            ->whereIn('code',[$new_acc])
                                                                                            //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                                            ->first();
                                                    //dd($chat_account);
                                                
                                                @endphp
                                                @if($chat_account != null)
                                                <tr>
                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                @endif
                                                    @foreach($branchs as $br_id)
                                                    @if($chat_account != null)
                                                    <?php
                                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                    $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                    $interest = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                    $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                    $interest_receviable = $interest_receviable??0;
                                                    $current = $current??0;
                                                    $current += $current_assets;
                                                    $interest_receviable += $interest;
                                                    ?>
                                                    
                                                    <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                @endif
                                                @endforeach
                                                </tr>
                                                
                                                @endforeach
                                                @endif
                                                @endif
                                   @endforeach
                                       <tr>
                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Interest Receivable</b></td>
                                           <?php $l_current_asset = 0; ?>
                                           @foreach($branchs as $br_id)
                                               <?php
                                                   $interest_receviable = $interest_receviable??0;
                                               ?>
                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($interest_receviable,2) }}</u></td>
                                           @endforeach
                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                       </tr>
                                       <tr>
                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                       </tr>
                                       @endif

                                       @if(isset($bals[14]))
         
                       
                                       <tr>
                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Provisions For Doubtful Debts</b></td>
                                       </tr>
                                           
                                       @foreach([14] as $sec)
                                       @if(isset($bals[$sec]))
                                           @if(count($bals[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                  
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->where('code','189010')
                                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   
                                                   @endphp
                                                   @if($chat_account != null)
                                                   <tr>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                   @endif
                                                       @foreach($branchs as $br_id)
                                                       @if($chat_account != null)
                                                       <?php
                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $debts = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                       $total_debts = $total_debts??0;
                                                       $current = $current??0;
                                                       $current += $current_assets;
                                                       $total_debts += $debts;
                                                       ?>
                                                     
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                   @endforeach
                                                   </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                       <tr>
                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Provisions For Doubtful Debts</b></td>
                                                           <?php $l_current_asset = 0; ?>
                                                           @foreach($branchs as $br_id)
                                                               <?php
                                                                   $total_debts = $total_debts??0;
                                                               ?>
                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_debts,2) }}</u></td>
                                                           @endforeach
                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                       </tr>
                                                       @endif

                                                       @if(isset($bals[14]))
         
                       
                                       <tr>
                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Other Receivables</b></td>
                                       </tr>
                                           
                                       @foreach([14] as $sec)
                                       @if(isset($bals[$sec]))
                                           @if(count($bals[$sec])>0)
                                           @php
                                              //dd($bals[$sec]); 
                                           @endphp
                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                   @php
                                                       //dd($acc_id);
                                                  
                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                               ->whereIn('code',['190000','190030','190090','190100'])
                                                                                               ->first();
                                                       //dd($chat_account);
                                                   
                                                   @endphp
                                                   @if($chat_account != null)
                                                   <tr>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                   @endif
                                                       @foreach($branchs as $br_id)
                                                       @if($chat_account != null) 
                                                       <?php
                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $other = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                       
                                                       $total_receivable = $total_receivable??0;
                                                       $current = $current??0;
                                                       $current += $current_assets;
                                                       $total_receivable += $other;
                                                       ?>
                                                    
                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                   @endif
                                                   @endforeach
                                                   </tr>
                                                   
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                       <tr>
                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Other Receivables</b></td>
                                                           <?php $l_current_asset = 0; ?>
                                                           @foreach($branchs as $br_id)
                                                               <?php
                                                                   $total_receivable = $total_receivable??0;
                                                               ?>
                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_receivable,2) }}</u></td>
                                                             @endforeach
                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                       </tr>
                                                       @endif

                                                       @if(isset($bals[14]))
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Tax Receivables</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['190510','190500'])
                                                                                                               //->selectRaw('*, LEFT (name, 3) as first_char')
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $tax = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $tax_receivable = $tax_receivable??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $tax_receivable += $tax;
                                                                       ?>
                                                                     
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Tax Receivables</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $tax_receivable = $tax_receivable??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($tax_receivable,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                       @endif

                                                                       @if(isset($bals[14]))
         
                                                                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Other Receivable Group</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['190250','190240','190245','192213','192200'])
                                                                                                               ->first();
                                                                       
                                                                    //dd($branchs);
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                       
                                                                       @foreach($branchs as $br_id)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $group = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                                       $other_group = $other_group??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $other_group += $group;
                                                                        ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td> --}}
                                                                       @endforeach
                                                                   @endif
                                                                  
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Other Receivable Group</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                              
                                                                                   $other_group = $other_group??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($other_group,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                       @endif

                                                                       @if(isset($bals[14]))
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Prepayments & Accrued Income</b></td>
                                                       </tr>
                                                           
                                                       @foreach([14] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                  
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['193000','193020','193240','193050','193060','193120','193250'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $prepayment = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                      
                                                                       $prepayments = $prepayments??0;
                                                                       $current = $current??0;
                                                                       $current += $current_assets;
                                                                       $prepayments += $prepayment;
                                                                       ?>
                                                                    
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif

                                                                   @if(isset($bals[$sec]))
                                                                        @if(count($bals[$sec])>0)
                                                                        @php
                                                                            //dd($bals[$sec]); 
                                                                        @endphp
                                                                            @forelse($bals[$sec] as $acc_id => $bal)
                                                                                @php
                                                                                    //dd($acc_id);
                                                                                
                                                                                    $account = \App\Models\AccountChart::find($acc_id);
                                                                                    $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                            ->whereIn('code',$new_acc)
                                                                                                                            ->first();
                                                                                    //dd($chat_account);
                                                                                
                                                                                @endphp
                                                                                @if($chat_account != null)
                                                                                <tr>
                                                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                    <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                                @endif
                                                                                    @foreach($branchs as $br_id)
                                                                                    @if($chat_account != null)
                                                                                    <?php
                                                                                    if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                    $prepayment = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                    $current_assets = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                    $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                    
                                                                                    
                                                                                    $prepayments = $prepayments??0;
                                                                                    $current = $current??0;
                                                                                    $current += $current_assets;
                                                                                    $prepayments += $prepayment;
                                                                                    ?>
                                                                                    
                                                                                    <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                                @endif
                                                                                @endforeach
                                                                                </tr>
                                                                                
                                                                                @endforeach
                                                                                @endif
                                                                                @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Prepayments & Accrued Income</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $prepayments = $prepayments??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($prepayments,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                      
                                                                       
                                                                       @endif
                                                                       <tr>
                                                                        <td style="padding-left: 10px" colspan="{{count($branchs)+1}}"><b>Total Current Assets</b></td>
                                                                        <?php $l_current_asset = 0; ?>
                                                                        @foreach($branchs as $br_id)
                                                                            <?php
                                                                                $cash_in_transit= $cash_in_transit??0;
                                                                                $cash_equi = $cash_equi??0;
                                                                                $total_inventory = $total_inventory??0;
                                                                                $service_receviable = $service_receviable??0;   
                                                                                $interest_receviable = $interest_receviable??0;
                                                                                $total_debts = $total_debts??0;
                                                                                $total_receivable = $total_receivable??0;
                                                                                $tax_receivable = $tax_receivable??0;
                                                                                $other_group = $other_group??0;
                                                                                $prepayments = $prepayments??0;
                                                                                $total_currents = $cash_in_transit + $cash_equi + $total_inventory +  $service_receviable + $interest_receviable + 
                                                                                            + $total_debts + $total_receivable + $tax_receivable +  $other_group + $prepayments;
                                                                            ?>
                                                                            <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total_currents,2) }}</u></td>
                                                                          @endforeach
                                                                        {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                    </tr>
                                                                       <tr>
                                                                        <td style="padding-left: 10px; color: blue;" colspan="{{count($branchs)+1}}"><b>Total Assets</b></td>
                                                                        <?php $l_current_asset = 0; ?>
                                                                        @foreach($branchs as $br_id)
                                                                            <?php
                                                                            $current = $current??0;
                                                                            $fixed_assets = $fixed_assets??0;
                                                                            $total_asset = $current + $fixed_assets;
                                                                            ?>
                                                                            <td style="text-align: right;padding-right: 10px; color: blue;;"><u>{{ number_format($total_asset,2) }}</u></td>
                                                                          @endforeach
                                                                        {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                    </tr>
                                                                       @if(isset($bals[26]))
                                                                       
         
                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 10px"><b>CAPITAL AND LIABILITIES</b></td>
                                                       </tr>
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Long Term Liabilities</b></td>
                                                       </tr> 
                                                       @foreach([26] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                 
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['250000','250030','250040','250050','250080'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $long = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $long_term = $long_term??0;
                                                                       $capitalli = $capitalli??0;
                                                                       $long_term += $long;
                                                                       $capitalli += $capital;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif

                                                                   @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                              //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                 
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',$new_acc)
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $long = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $long_term = $long_term??0;
                                                                       $capitalli = $capitalli??0;
                                                                       $long_term += $long;
                                                                       $capitalli += $capital;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format($solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif

                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Long Term Liabilities</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $long_term = $long_term??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format($long_term,2) }}</u></td>
                                                                             @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                      
                                                                       @endif
                                                                       

                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>CURRENT LIABILITIES</b></td>
                                                                       </tr>  
                                                                       <tr>
                                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Service Creditors(Saving)</b></td>
                                                                       </tr> 
                                                                       @foreach([24] as $sec)
                                                                       @if(isset($bals[$sec]))
                                                                           @if(count($bals[$sec])>0)
                                                                           @php
                                                                              //dd($bals[$sec]); 
                                                                           @endphp
                                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                                   @php
                                                                                       //dd($acc_id);
                                                                                   
                                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                               ->whereIn('code', ['203851','203852','203000','200001'])
                                                                                                                               ->first();
                                                                                       //dd($chat_account);
                                                                                   
                                                                                   @endphp
                                                                                   @if($chat_account != null)
                                                                                   <tr>
                                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                                   @endif
                                                                                       @foreach($branchs as $br_id)
                                                                                       @if($chat_account != null)
                                                                                       <?php
                                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                       $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $saving = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                       
                                                                                       $service_saving = $service_saving??0;
                                                                                       $capitalli = $capitalli??0;
                                                                                       $current_libs = $current_libs??0;

                                                                                       $service_saving += $saving;
                                                                                       $capitalli += $capital;
                                                                                       $current_libs += $current_lib;
                                                                                       ?>
                                                                                   
                                                                                       <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                                   @endif
                                                                                   @endforeach
                                                                                   </tr>
                                                                                   
                                                                                   @endforeach
                                                                                   @endif
                                                                                   @endif
                                                                                   @endforeach
                                                                                       <tr>
                                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total</b></td>
                                                                                           <?php $l_current_asset = 0; ?>
                                                                                           @foreach($branchs as $br_id)
                                                                                               <?php
                                                                                                   $service_saving = $service_saving??0;
                                                                                               ?>
                                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$service_saving,2) }}</u></td>
                                                                                             @endforeach
                                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                       </tr>
                                                                                      
                                                                                       @endif
                                                                                       

                                   @if(isset($bals[24]))
                                   @foreach([24] as $sec)
                                   @if(isset($bals[$sec]))
                                       @if(count($bals[$sec])>0)
                                       @php
                                           //dd($bals[$sec]); 
                                       @endphp
                                           @forelse($bals[$sec] as $acc_id => $bal)
                                               @php
                                                   //dd($acc_id);
                                               
                                                   $account = \App\Models\AccountChart::find($acc_id);
                                                   $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                           ->whereIn('code', ['203853','203854'])
                                                                                           ->first();
                                                   //dd($chat_account);
                                               
                                               @endphp
                                               @if($chat_account != null)
                                               <tr>
                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                               @endif
                                                   @foreach($branchs as $br_id)
                                                   @if($chat_account != null)
                                                   <?php
                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                   $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $total_2 = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                   $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;

                                                   $current_libs = $current_libs??0;
                                                   $total2 = $total2??0;
                                                   $capitalli = $capitalli??0;

                                                   $total2 += $total_2;
                                                   $capitalli += $capital;
                                                   $current_libs += $current_lib;
                                                   ?>
                                               
                                                   <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                               @endif
                                               @endforeach
                                               </tr>
                                               
                                               @endforeach
                                               @endif
                                               @endif
                                               @endforeach
                                                   <tr>
                                                       <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total</b></td>
                                                       <?php $l_current_asset = 0; ?>
                                                       @foreach($branchs as $br_id)
                                                           <?php
                                                               $total2 = $total2??0;
                                                           ?>
                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total2,2) }}</u></td>
                                                           @endforeach
                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                   </tr>
                                                   
                                                   @endif                                            

                                                   @if(isset($bals[24]))
                                                   @foreach([24] as $sec)
                                                   @if(isset($bals[$sec]))
                                                       @if(count($bals[$sec])>0)
                                                       @php
                                                           //dd($bals[$sec]); 
                                                       @endphp
                                                           @forelse($bals[$sec] as $acc_id => $bal)
                                                               @php
                                                                   //dd($acc_id);
                                                               
                                                                   $account = \App\Models\AccountChart::find($acc_id);
                                                                   $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                           ->whereIn('code',['213520','213521'])
                                                                                                           
                                                                                                           ->first();
                                                                   //dd($chat_account);
                                                               
                                                               @endphp
                                                               @if($chat_account != null)
                                                               <tr>
                                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                   <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                               @endif
                                                                   @foreach($branchs as $br_id)
                                                                   @if($chat_account != null)
                                                                   <?php
                                                                   if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                   $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $total_3 = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $capital = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                   
                                                                   $total3 = $total3??0;
                                                                   $capitalli = $capitalli??0;
                                                                   $current_libs = $current_libs??0;

                                                                   $total3 += $total_3;
                                                                   $capitalli += $capital;
                                                                   $current_libs += $current_lib;
                                                                   ?>
                                                               
                                                                   <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                               @endif
                                                               @endforeach
                                                               </tr>
                                                               
                                                               @endforeach
                                                               @endif
                                                               @endif
                                                               @endforeach
                                                                   <tr>
                                                                       <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total</b></td>
                                                                       <?php $l_current_asset = 0; ?>
                                                                       @foreach($branchs as $br_id)
                                                                           <?php
                                                                               $total3 = $total3??0;
                                                                           ?>
                                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format($total3,2) }}</u></td>
                                                                           @endforeach
                                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                   </tr>
                                                                   <tr>
                                                                       <td style="padding-left: 10px" colspan="{{count($branchs)+1}}"><b>Total Service Creditors(Saving)</b></td>
                                                                       <?php $l_current_asset = 0; ?>
                                                                       @foreach($branchs as $br_id)
                                                                           <?php
                                                                               $capitalli = $capitalli??0;
                                                                           ?>
                                                                           <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$capitalli,2) }}</u></td>
                                                                           @endforeach
                                                                       {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                   </tr>
                                                                   <tr>
                                                                       <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                   </tr>
                                                                   
                                                                   @endif

                                                           @if(isset($bals[24]))
                                                           
   
               
                                                               
                                                               <tr>
                                                                   <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Other Current Liabilities</b></td>
                                                               </tr> 
                                                               @foreach([24] as $sec)
                                                               @if(isset($bals[$sec]))
                                                                   @if(count($bals[$sec])>0)
                                                                   @php
                                                                       //dd($bals[$sec]); 
                                                                   @endphp
                                                                       @forelse($bals[$sec] as $acc_id => $bal)
                                                                           @php
                                                                               //dd($acc_id);
                                                                           
                                                                               $account = \App\Models\AccountChart::find($acc_id);
                                                                               $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                       ->whereIn('code',['210000','210001','250020','210010','210030','210040'])
                                                                                                                       ->first();
                                                                               //dd($chat_account);
                                                                           
                                                                           @endphp
                                                                           @if($chat_account != null)
                                                                           <tr>
                                                                               <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                               <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                           @endif
                                                                               @foreach($branchs as $br_id)
                                                                               @if($chat_account != null)
                                                                               <?php
                                                                               if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                               $total_li = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                               
                                                                               $total_other = $total_other??0;
                                                                               $current_libs = $current_libs??0;
                                                                               $total_other += $total_li;
                                                                               $current_libs += $current_lib;
                                                                               ?>
                                                                           
                                                                               <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                           @endif
                                                                           @endforeach
                                                                           </tr>
                                                                           
                                                                           @endforeach
                                                                           @endif
                                                                           @endif
                                                                           @endforeach
                                                                               <tr>
                                                                                   <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Other Current Liabilities</b></td>
                                                                                   <?php $l_current_asset = 0; ?>
                                                                                   @foreach($branchs as $br_id)
                                                                                       <?php
                                                                                           $total_other = $total_other??0;
                                                                                       ?>
                                                                                       <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_other,2) }}</u></td>
                                                                                       @endforeach
                                                                                   {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                               </tr>
                                                                               <tr>
                                                                                    <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>    
                                                                               </tr>
                                                                               @endif
                                                                               

                                                                       @if(isset($bals[24]))

       
                                                       
                                                       <tr>
                                                           <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Tax Payables</b></td>
                                                       </tr> 
                                                       @foreach([24] as $sec)
                                                       @if(isset($bals[$sec]))
                                                           @if(count($bals[$sec])>0)
                                                           @php
                                                               //dd($bals[$sec]); 
                                                           @endphp
                                                               @forelse($bals[$sec] as $acc_id => $bal)
                                                                   @php
                                                                       //dd($acc_id);
                                                                   
                                                                       $account = \App\Models\AccountChart::find($acc_id);
                                                                       $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                               ->whereIn('code',['213020','213000'])
                                                                                                               ->first();
                                                                       //dd($chat_account);
                                                                   
                                                                   @endphp
                                                                   @if($chat_account != null)
                                                                   <tr>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                       <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                   @endif
                                                                       @foreach($branchs as $br_id)
                                                                       @if($chat_account != null)
                                                                       <?php
                                                                       if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                       $tax_p = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                       
                                                                       $tax_pay = $tax_pay??0;
                                                                       $current_libs = $current_libs??0;
                                                                       $tax_pay += $tax_p;
                                                                       $current_libs += $current_lib;
                                                                       ?>
                                                                   
                                                                       <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                   @endif
                                                                   @endforeach
                                                                   </tr>
                                                                   
                                                                   @endforeach
                                                                   @endif
                                                                   @endif
                                                                   @endforeach
                                                                       <tr>
                                                                           <td style="padding-left: 30px" colspan="{{count($branchs)+1}}" ><b>Total Tax Payables</b></td>
                                                                           <?php $l_current_asset = 0; ?>
                                                                           @foreach($branchs as $br_id)
                                                                               <?php
                                                                                   $tax_pay = $tax_pay??0;
                                                                               ?>
                                                                               <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$tax_pay,2) }}</u></td>
                                                                               @endforeach
                                                                           {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                       </tr>
                                                                       <tr>
                                                                        <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                       </tr>
                                                                      
                                                                       @endif

                                           @if(isset($bals[24]))


                                   
                                           <tr>
                                               <td colspan="{{count($branchs)+1}}" style="padding-left: 60px"><b>Other Personnel Payable</b></td>
                                           </tr> 
                                           @foreach([24] as $sec)
                                           @if(isset($bals[$sec]))
                                               @if(count($bals[$sec])>0)
                                               @php
                                                   //dd($bals[$sec]); 
                                               @endphp
                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                       @php
                                                           //dd($acc_id);
                                                       
                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                   ->whereIn('code', ['213500','213510','213570','213580','213611'])
                                                                                                   ->first();
                                                           //dd($chat_account);
                                                       
                                                       @endphp
                                                       @if($chat_account != null)
                                                       <tr>
                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                       @endif
                                                               @foreach($branchs as $br_id)
                                                               @if($chat_account != null)
                                                               <?php
                                                               if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                               $other_p = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                               
                                                               $other_pay = $other_pay??0;
                                                               $current_libs = $current_libs??0;
                                                               $other_pay += $other_p;
                                                               $current_libs += $current_lib;
                                                               ?>
                                                           
                                                               <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                           @endif
                                                           @endforeach
                                                       </tr>
                                                       
                                                       @endforeach
                                                       @endif
                                                       @endif
                                                       @endforeach
                                                           <tr>
                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Other Personnel Payable</b></td>
                                                               <?php $l_current_asset = 0; ?>
                                                               @foreach($branchs as $br_id)
                                                                   <?php
                                                                   
                                                                   $other_pay = $other_pay??0;
                                                                   ?>
                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$other_pay,2) }}</u></td>
                                                                   @endforeach
                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                           </tr>
                                                           <tr>
                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                           </tr>
                                                           
                                                           @endif
                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Accurals</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                       
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',['215080','215120','215000'])
                                                                                                                   
                                                                                                                   ->first();
                                                                          //dd($chat_account);
                                                                       
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $accu = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $accurals = $accurals??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $accurals += $accu;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Accurals</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $accurals = $accurals??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$accurals,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           @endif

                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Deferred Income</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                      
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $code = ['215180','215181','215182','215183','215184','215185','215186','215187','215188','215189','215190','215191','215192','215193','215194','216000'];
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',$code)
                                                                                                                   ->first();
                                                                           //dd($chat_account);
                                                                       
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $defer = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $defer_income = $defer_income??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $defer_income += $defer;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Deferred Income</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $defer_income = $defer_income??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$defer_income,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           @endif

                                                                           @if(isset($bals[24]))


                                   
                                                           <tr>
                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Other Payables</b></td>
                                                           </tr> 
                                                           @foreach([24] as $sec)
                                                           @if(isset($bals[$sec]))
                                                               @if(count($bals[$sec])>0)
                                                               @php
                                                                   //dd($bals[$sec]); 
                                                               @endphp
                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                       @php
                                                                           //dd($acc_id);
                                                                      
                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                   ->whereIn('code',['217000','217130','274030','274040'])
                                                                                                                   ->first();
                                                                           //dd($chat_account);
                                                                       
                                                                       @endphp
                                                                       @if($chat_account != null)
                                                                       <tr>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                       @endif
                                                                           @foreach($branchs as $br_id)
                                                                           @if($chat_account != null)
                                                                           <?php
                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                           $other_pay = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                           
                                                                           $other_pays = $other_pays??0;
                                                                           $current_libs = $current_libs??0;
                                                                           $other_pays += $other_pay;
                                                                           $current_libs += $current_lib;
                                                                           ?>
                                                                       
                                                                           <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                       @endif
                                                                       @endforeach
                                                                       </tr>
                                                                       
                                                                       @endforeach
                                                                       @endif
                                                                       @endif

                                                                       @if(isset($bals[$sec]))
                                                                            @if(count($bals[$sec])>0)
                                                                            @php
                                                                                //dd($bals[$sec]); 
                                                                            @endphp
                                                                                @forelse($bals[$sec] as $acc_id => $bal)
                                                                                    @php
                                                                                        //dd($acc_id);
                                                                                    
                                                                                        $account = \App\Models\AccountChart::find($acc_id);
                                                                                        $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                ->whereIn('code',[$new_acc])
                                                                                                                                ->first();
                                                                                        //dd($chat_account);
                                                                                    
                                                                                    @endphp
                                                                                    @if($chat_account != null)
                                                                                    <tr>
                                                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                        <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                                    @endif
                                                                                        @foreach($branchs as $br_id)
                                                                                        @if($chat_account != null)
                                                                                        <?php
                                                                                        if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                        $other_pay = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                        $current_lib = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                        $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                        
                                                                                        $other_pays = $other_pays??0;
                                                                                        $current_libs = $current_libs??0;
                                                                                        $other_pays += $other_pay;
                                                                                        $current_libs += $current_lib;
                                                                                        ?>
                                                                                    
                                                                                        <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                                    @endif
                                                                                    @endforeach
                                                                                    </tr>
                                                                                    
                                                                                    @endforeach
                                                                                    @endif
                                                                                    @endif
                                                                                    
                                                                       @endforeach
                                                                           <tr>
                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Other Payables</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                       $other_pays = $other_pays??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$other_pays,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total CURRENT LIABILITIES</b></td>
                                                                               <?php $l_current_asset = 0; ?>
                                                                               @foreach($branchs as $br_id)
                                                                                   <?php
                                                                                        $current_libs = $current_libs??0;
                                                                                   ?>
                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$current_libs,2) }}</u></td>
                                                                                   @endforeach
                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                           </tr>
                                                                           <tr>
                                                                            <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total LIABILITIES</b></td>
                                                                            <?php $l_current_asset = 0; ?>
                                                                            @foreach($branchs as $br_id)
                                                                                <?php
                                                                                     $total_lib = $current_libs +  $total_other;
                                                                                ?>
                                                                                <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$total_lib,2) }}</u></td>
                                                                                @endforeach
                                                                            {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                        </tr>
                                                                           <tr>
                                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                           </tr>
                                                                           
                                                                           @endif
                                                                           @if(companyReportPart() == 'company.mkt')
                                                                           @if(isset($bals[30]))


                                   
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Equity</b></td>
                                                                           </tr> 
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>SHAREHOLDERS' EQUITY</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                       
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['300000','310000','310010'])
                                                                                                                                   
                                                                                                                                   ->first();
                                                                                           //dd($chat_account);
                                                                                       
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $share_eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $share_equity = $share_equity??0;
                                                                                           $equity = $equity??0;
                                                                                           $share_equity += $share_eq;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                                       @endif
                                                                                       @endforeach
                                                                                       </tr>
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       
                                                                                       @endforeach
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total SHAREHOLDERS' EQUITY</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $share_equity = $share_equity??0;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$share_equity,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                                           </tr>
                                                                                           
                                                                                           @endif
                                                                                           
                                                                                           @if(isset($bals[30]))
                
                
                                                   
                                                                          
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Reserves</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                      
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['321000','321200','321500','323000'])
                                                                                                                                   ->first(); 
                                                                                           //dd($chat_account);
                                                                                       
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td>
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $re = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $reserve = $reserve??0;
                                                                                           $equity = $equity??0;
                                                                                           $reserve += $re;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td>
                                                                                       @endif
                                                                                       @endforeach
                                                                                          
                                                                                       </tr>
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       @endforeach
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Reserves</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $reserve = $reserve??0;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format(-$reserve,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                                <td colspan="{{count($branchs)+2}}" style="height: 30px; overflow:hidden;"></td>
                                                                                           </tr>
                                                                                         
                                                                                           @endif
                                                                                           <br>
                
                                                                       @if(isset($bals[30]))
                                                                           <tr>
                                                                               <td colspan="{{count($branchs)+2}}" style="padding-left: 60px"><b>Retained Earning</b></td>
                                                                           </tr> 
                                                                           @foreach([30] as $sec)
                                                                           @if(isset($bals[$sec]))
                                                                               @if(count($bals[$sec])>0)
                                                                               @php
                                                                                   //dd($bals[$sec]); 
                                                                               @endphp
                                                                                   @forelse($bals[$sec] as $acc_id => $bal)
                                                                                       @php
                                                                                           //dd($acc_id);
                                                                                       
                                                                                           $account = \App\Models\AccountChart::find($acc_id);
                                                                                           $chat_account = \App\Models\AccountChart::where('id',$acc_id)
                                                                                                                                   ->whereIn('code',['332010','332000','340100'])
                                                                                                                                   ->first();
                                                                                           
                                                                                       
                                                                                       @endphp
                                                                                       @if($chat_account != null)
                                                                                       <tr>
                                                                                           {{-- <td style="padding-left: 60px;">{{ optional($chat_account)->code}}</td>
                                                                                           <td style="padding-left: 60px;">{{ optional($chat_account)->name}}</td> --}}
                                                                                       @endif
                                                                                           @foreach($branchs as $br_id)
                                                                                           @if($chat_account != null)
                                                                                           <?php
                                                                                           if(!isset($total_current_asset[$br_id])) $total_current_asset[$br_id] = 0;
                                                                                           $retain = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $eq = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           $solo_retain = isset($bal[$br_id])?($bal[$br_id]??0):0;
                                                                                           
                                                                                           $retain_earn = $retain_earn??0;
                                                                                           $equity = $equity??0;
                                                                                           $retain_earn += $retain;
                                                                                           $equity += $eq;
                                                                                           ?>
                                                                                       
                                                                                           {{-- <td style="text-align: right">{{ number_format(-$solo??0,2) }}</td> --}}
                                                                                       @endif
                                                                                       @endforeach
                                                                                          
                                                                                       </tr>
                                                                                       
                                                                                       
                                                                                       @endforeach
                                                                                       @endif
                                                                                       @endif
                                                                                       @endforeach
                                                                                       <tr>
                                                                                        <td style="padding-left: 60px;" colspan="{{count($branchs)+1}}">332010 - Retained Earning Previous Year</td>
                                                                                   
                                                                                        @foreach($branchs as $br_id)
                                                                                       @php
                                                                                            //dd($br_id);
                                                                                           $net_income_f = $net_income_f??0;
                                                                                           $solo_retain = $solo_retain??0;
                                                                                           if($end_date >= "2019-10-01"){
                                                                                            //$net_income_plus =  $net_income_f + 60508996.42;
                                                                                            if($br_id == 1){
                                                                                                $net_income_plus =  $net_income_f + 60508996.42;
                                                                                               }
                                                                                               elseif($br_id == 2){
                                                                                                $net_income_plus =  $net_income_f + 28198263.57;
                                                                                               }
                                                                                               elseif($br_id == 3){
                                                                                                $net_income_plus =  $net_income_f + 165159143.12;
                                                                                               }
                                                                                               elseif($br_id == 4){
                                                                                                $net_income_plus =  $net_income_f + 781584281.00;
                                                                                               }
                                                                                               elseif($br_id == 5){
                                                                                                $net_income_plus =  $net_income_f + 498649511.26;
                                                                                               }
                                                                                               elseif($br_id == 6){
                                                                                                $net_income_plus =  $net_income_f + 406069271.00;
                                                                                               }
                                                                                               elseif($br_id == 7){
                                                                                                $net_income_plus =  $net_income_f ;
                                                                                               }
                                                                                               elseif($br_id == 8){
                                                                                                $net_income_plus =  $net_income_f + 540183980.37;
                                                                                               }
                                                                                               elseif($br_id == 9){
                                                                                                $net_income_plus =  $net_income_f + 373876575.50;
                                                                                               }
                                                                                               elseif($br_id == 10){
                                                                                                $net_income_plus =  $net_income_f + 365265138.73;
                                                                                               }
                                                                                               elseif($br_id == 11){
                                                                                                $net_income_plus =  $net_income_f + 452477003.22;
                                                                                               }
                                                                                               elseif($br_id == 12){
                                                                                                $net_income_plus =  $net_income_f + 300929780.43;
                                                                                               }
                                                                                               elseif($br_id == 13){
                                                                                                $net_income_plus =  $net_income_f + 302678893.85;
                                                                                               }
                                                                                               elseif($br_id == 14){
                                                                                                $net_income_plus =  $net_income_f + 69125167.12;
                                                                                               }
                                                                                               elseif($br_id == 15){
                                                                                                $net_income_plus =  $net_income_f + 657436754.00;
                                                                                               }
                                                                                               elseif($br_id == 17){
                                                                                                $net_income_plus =  $net_income_f + 392494224.43;
                                                                                               }
                                                                                               elseif($br_id == 16){
                                                                                                $net_income_plus =  $net_income_f + 257096812.47;
                                                                                               }
                                                                                               elseif($br_id == 18){
                                                                                                $net_income_plus =  $net_income_f + 257361624.34;
                                                                                               }
                                                                                               elseif($br_id == 19){
                                                                                                $net_income_plus =  $net_income_f + 44700770.48;
                                                                                               }
                                                                                               elseif($br_id == 20){
                                                                                                $net_income_plus =  $net_income_f;
                                                                                               }
                                                                                               elseif($br_id == 21){
                                                                                                $net_income_plus =  $net_income_f + 506489376.00;
                                                                                               }
                                                                                               elseif($br_id == 22){
                                                                                                $net_income_plus =  $net_income_f + 572340049.77;
                                                                                               }
                                                                                               elseif($br_id == 23){
                                                                                                $net_income_plus =  $net_income_f + 759222914.29;
                                                                                               }
                                                                                               elseif($br_id == 24){
                                                                                                $net_income_plus =  $net_income_f + 5029811.99;
                                                                                               }
                                                                                               elseif($br_id == 25){
                                                                                                $net_income_plus =  $net_income_f + 351884379.01;
                                                                                               }
                                                                                               elseif($br_id == 26){
                                                                                                $net_income_plus =  $net_income_f + 649198800.13;
                                                                                               }
                                                                                               elseif($br_id == 27){
                                                                                                $net_income_plus =  $net_income_f + 308241722.13;
                                                                                               }
                                                                                               elseif($br_id == 28){
                                                                                                $net_income_plus =  $net_income_f + 5385719206.05;
                                                                                               }
                                                                                               elseif($br_id == 29){
                                                                                                $net_income_plus =  $net_income_f;
                                                                                               }
                                                                                               elseif($br_id == 30){
                                                                                                $net_income_plus =  $net_income_f;
                                                                                               }
                                                                                               elseif($br_id == 31){
                                                                                                $net_income_plus =  $net_income_f;
                                                                                               }
                                                                                           }
                                                                                           elseif($end_date < "2019-10-01" && $start_date >= "2019-09-01"){
                                                                                               
                                                                                                //$net_income_plus = "60508996.42";
                                                                                                if($br_id == 1){
                                                                                                $net_income_plus = 60508996.42;
                                                                                               }
                                                                                               elseif($br_id == 2){
                                                                                                $net_income_plus = 28198263.57;
                                                                                               }
                                                                                               elseif($br_id == 3){
                                                                                                $net_income_plus = 165159143.12;
                                                                                               }
                                                                                               elseif($br_id == 4){
                                                                                                $net_income_plus = 781584281.00;
                                                                                               }
                                                                                               elseif($br_id == 5){
                                                                                                $net_income_plus = 498649511.26;
                                                                                               }
                                                                                               elseif($br_id == 6){
                                                                                                $net_income_plus = 406069271.00;
                                                                                               }
                                                                                               elseif($br_id == 7){
                                                                                                $net_income_plus = 0 ;
                                                                                               }
                                                                                               elseif($br_id == 8){
                                                                                                $net_income_plus = 540183980.37;
                                                                                               }
                                                                                               elseif($br_id == 9){
                                                                                                $net_income_plus = 373876575.50;
                                                                                               }
                                                                                               elseif($br_id == 10){
                                                                                                $net_income_plus = 365265138.73;
                                                                                               }
                                                                                               elseif($br_id == 11){
                                                                                                $net_income_plus = 452477003.22;
                                                                                               }
                                                                                               elseif($br_id == 12){
                                                                                                $net_income_plus = 300929780.43;
                                                                                               }
                                                                                               elseif($br_id == 13){
                                                                                                $net_income_plus = 302678893.85;
                                                                                               }
                                                                                               elseif($br_id == 14){
                                                                                                $net_income_plus = 69125167.12;
                                                                                               }
                                                                                               elseif($br_id == 15){
                                                                                                $net_income_plus =  657436754.00;
                                                                                               }
                                                                                               elseif($br_id == 17){
                                                                                                $net_income_plus =  392494224.43;
                                                                                               }
                                                                                               elseif($br_id == 16){
                                                                                                $net_income_plus =  257096812.47;
                                                                                               }
                                                                                               elseif($br_id == 18){
                                                                                                $net_income_plus = 257361624.34;
                                                                                               }
                                                                                               elseif($br_id == 19){
                                                                                                $net_income_plus = 44700770.48;
                                                                                               }
                                                                                               elseif($br_id == 20){
                                                                                                $net_income_plus = 0;
                                                                                               }
                                                                                               elseif($br_id == 21){
                                                                                                $net_income_plus = 506489376.00;
                                                                                               }
                                                                                               elseif($br_id == 22){
                                                                                                $net_income_plus = 572340049.77;
                                                                                               }
                                                                                               elseif($br_id == 23){
                                                                                                $net_income_plus = 759222914.29;
                                                                                               }
                                                                                               elseif($br_id == 24){
                                                                                                $net_income_plus = 5029811.99;
                                                                                               }
                                                                                               elseif($br_id == 25){
                                                                                                $net_income_plus = 351884379.01;
                                                                                               }
                                                                                               elseif($br_id == 26){
                                                                                                $net_income_plus = 649198800.13;
                                                                                               }
                                                                                               elseif($br_id == 27){
                                                                                                $net_income_plus = 308241722.13;
                                                                                               }
                                                                                               elseif($br_id == 28){
                                                                                                $net_income_plus = 5385719206.05;
                                                                                               }
                                                                                               elseif($br_id == 29){
                                                                                                $net_income_plus =  0;
                                                                                               }
                                                                                               elseif($br_id == 30){
                                                                                                $net_income_plus =  0;
                                                                                               }
                                                                                               elseif($br_id == 31){
                                                                                                $net_income_plus =  0;
                                                                                               }
                                                                                           }
                                                                                           else{
                                                                                                $net_income_plus =  $net_income_f;
                                                                                           }
                                                                                           
                                                                                           //dd($net_income_plus);
                                                                                           //dd($end_date);
                                                                                       @endphp
                                                                                       
                                                                                     
                                                                                        <td style="text-align: right">{{ number_format($net_income_plus??0,2) }}</td>
                                                                                       
                                                                                        
                                                                                        
                                                                                  
                                                                                    @endforeach
                                                                                       
                                                                                    </tr>
                                                                                       <tr>
                                                                                        <td style="padding-left: 60px;" colspan="{{count($branchs)+1}}">332020 - Retained Earning Current Year</td>
                                                                                   
                                                                                        @foreach($branchs as $br_id)
                                                                                       @php
                                                                                           $net_income = $net_income??0;
                                                                                           if($end_date >= "2019-10-01"){
                                                                                            $current_year =  $net_income;
                                                                                           }
                                                                                           elseif($end_date < "2019-10-01" && $start_date >= "2019-09-01"){
                                                                                            $current_year =  $net_income;
                                                                                           }
                                                                                           else{
                                                                                               if($br_id == 1){
                                                                                                $current_year =  $net_income + 60508996.42;
                                                                                               }
                                                                                               elseif($br_id == 2){
                                                                                                $current_year =  $net_income + 28198263.57;
                                                                                               }
                                                                                               elseif($br_id == 3){
                                                                                                $current_year =  $net_income + 165159143.12;
                                                                                               }
                                                                                               elseif($br_id == 4){
                                                                                                $current_year =  $net_income + 781584281.00;
                                                                                               }
                                                                                               elseif($br_id == 5){
                                                                                                $current_year =  $net_income + 498649511.26;
                                                                                               }
                                                                                               elseif($br_id == 6){
                                                                                                $current_year =  $net_income + 406069271.00;
                                                                                               }
                                                                                               elseif($br_id == 7){
                                                                                                $current_year =  $net_income ;
                                                                                               }
                                                                                               elseif($br_id == 8){
                                                                                                $current_year =  $net_income + 540183980.37;
                                                                                               }
                                                                                               elseif($br_id == 9){
                                                                                                $current_year =  $net_income + 373876575.50;
                                                                                               }
                                                                                               elseif($br_id == 10){
                                                                                                $current_year =  $net_income + 365265138.73;
                                                                                               }
                                                                                               elseif($br_id == 11){
                                                                                                $current_year =  $net_income + 452477003.22;
                                                                                               }
                                                                                               elseif($br_id == 12){
                                                                                                $current_year =  $net_income + 300929780.43;
                                                                                               }
                                                                                               elseif($br_id == 13){
                                                                                                $current_year =  $net_income + 302678893.85;
                                                                                               }
                                                                                               elseif($br_id == 14){
                                                                                                $current_year =  $net_income + 69125167.12;
                                                                                               }
                                                                                               elseif($br_id == 15){
                                                                                                $current_year =  $net_income + 657436754.00;
                                                                                               }
                                                                                               elseif($br_id == 17){
                                                                                                $current_year =  $net_income + 392494224.43;
                                                                                               }
                                                                                               elseif($br_id == 16){
                                                                                                $current_year =  $net_income + 257096812.47;
                                                                                               }
                                                                                               elseif($br_id == 18){
                                                                                                $current_year =  $net_income + 257361624.34;
                                                                                               }
                                                                                               elseif($br_id == 19){
                                                                                                $current_year =  $net_income + 44700770.48;
                                                                                               }
                                                                                               elseif($br_id == 20){
                                                                                                $current_year =  $net_income;
                                                                                               }
                                                                                               elseif($br_id == 21){
                                                                                                $current_year =  $net_income + 506489376.00;
                                                                                               }
                                                                                               elseif($br_id == 22){
                                                                                                $current_year =  $net_income + 572340049.77;
                                                                                               }
                                                                                               elseif($br_id == 23){
                                                                                                $current_year =  $net_income + 759222914.29;
                                                                                               }
                                                                                               elseif($br_id == 24){
                                                                                                $current_year =  $net_income + 5029811.99;
                                                                                               }
                                                                                               elseif($br_id == 25){
                                                                                                $current_year =  $net_income + 351884379.01;
                                                                                               }
                                                                                               elseif($br_id == 26){
                                                                                                $current_year =  $net_income + 649198800.13;
                                                                                               }
                                                                                               elseif($br_id == 27){
                                                                                                $current_year =  $net_income + 308241722.13;
                                                                                               }
                                                                                               elseif($br_id == 28){
                                                                                                $current_year =  $net_income + 5385719206.05;
                                                                                               }
                                                                                               elseif($br_id == 29){
                                                                                                $current_year =  $net_income;
                                                                                               }
                                                                                            
                                                                                           }
                                                                                          
                                                                                          

                                                                                           
                                                                                       @endphp
                                                                                       
                                                                                    
                                                                                        <td style="text-align: right">{{ number_format($current_year??0,2) }}</td>
                                                                                  
                                                                                    @endforeach
                                                                                       
                                                                                    </tr>
                                                                                           <tr>
                                                                                               <td style="padding-left: 30px" colspan="{{count($branchs)+1}}"><b>Total Retained Earning</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $retain_earn = $retain_earn??0;
                                                                                                       $retain_earn = $current_year + $net_income_plus;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($retain_earn,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                           <tr>
                                                                                               <td style="padding-left: 10px" colspan="{{count($branchs)+1}}"><b>Total Equity</b></td>
                                                                                               <?php $l_current_asset = 0; ?>
                                                                                               @foreach($branchs as $br_id)
                                                                                                   <?php
                                                                                                       $equity = $equity??0;
                                                                                                       $equity = $equity - $current_year - $net_income_plus;
                                                                                                   ?>
                                                                                                   <td style="text-align: right;padding-right: 10px"><u>{{ number_format($equity,2) }}</u></td>
                                                                                                   @endforeach
                                                                                               {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                                           </tr>
                                                                                          
                                                                                          
                                                                                           
                                                                                           @endif
                                                                          

                                                                           <tr>
                                                                            <td style="padding-left: 0px; color: blue;" colspan="{{count($branchs)+1}}"><b>TOTAL CAPITAL AND LIABILITIES</b></td>
                                                                            <?php $l_current_asset = 0; ?>
                                                                            @foreach($branchs as $br_id)
                                                                                <?php
                                                                                     $current_libs = $current_libs??0;
                                                                                     $equity = $equity??0;
                                                                                     $total_cap_li = isset($total_lib) ?  $total_lib - $equity : $equity;
                                                                                ?>
                                                                                <td style="text-align: right;padding-right: 10px;color: blue;"><u>{{ number_format(-$total_cap_li,2) }}</u></td>
                                                                                @endforeach
                                                                            {{--<td style="text-align: right;padding-right: 10px"><u>{{ number_format($l_current_asset,2) }}</u></td>--}}
                                                                        </tr>
                                                                            

       {{-- @endif --}}

       </tbody>
   </table>

@else
   <h1>No data</h1>
@endif
</div>

<script>
    $(".general-leg").click(function(){
        var branch = $(this).find('.branch').val();
        getReport($(this).attr("id"), branch);
    });


    function getReport(id, branch_id){
        $("#whole_page_loader").fadeIn(function(){
            var acc_chart_id= [id];
            var start_date = $('[name="start_date"]').val();
            var end_date = $('[name="end_date"]').val();
            var month = $('[name="month"]').val();
            var show_zero = $('[name="show_zero"]').val();
            var company = $('#company').val();
            var url = company == 'company.moeyan' ? "{{ url('report/general-leger-moeyan')}}" : "{{ url('report/general-leger')}}";
            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                dataType: 'html',
                data: {
                    start_date:start_date,
                    end_date:end_date,
                    month:month,
                    acc_chart_id:acc_chart_id,
                    show_zero:show_zero,
                    branch_id: [branch_id]
                },
                success: function (d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').html(d);
                    $('#show-detail-modal').modal("show");
                },
                error: function (d) {
                    $("#whole_page_loader").fadeOut();
                    $('.modal-body').hide();
                    $('#show-detail-modal').modal("hide");
                    alert('error');
                }
            });
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

