
@if(count($rows)>0)
<table class="table table-bordered" style="width: 100%">
    <thead>
        <tr  style="text-align: center;background:#03a9f4;color: white">
            <th>{{_t('Group Name')}}</th>
            <th>{{_t('Reference No')}}</th>
            <th>{{_t('Name')}}</th>
            <th>{{_t('Co')}}</th>
            <th>{{_t('Center')}}</th>
            <th>{{_t('Branch')}}</th>
            <th>{{_t('Product')}}</th>
            <th>{{_t('House and Street')}}</th>
            <th>{{_t('Phone')}}</th>
            <th>{{_t('Due Date')}}</th>
            <th>{{_t('Owed')}}</th>
            <th>{{_t('Payments')}}</th>
        </tr>
    </thead>
    <tbody>
       <?php
         $old_owed = 0;
         $payment = 0;
       ?>
         @if($rows != null)
             @foreach($rows as $row)
                 <tr>
                     <td></td>
                     <td>{{$row->payment_number}}</td>
                     <td>{{optional($row->client_name)->name}}</td>
                     <td>{{optional($row->credit_officer)->name}}</td>
                     <td></td>
                     <td></td>
                     <td>{{optional($row->loan_product)->name}}</td>
                     <td>{{optional($row->client_name)->address1}}</td>
                     <td>{{optional($row->client_name)->primary_phone_number}}</td>
                     <td>{{$row->payment_date}}</td>
                     <td>{{number_format($row->old_owed,2)}}</td>
                     <td>{{number_format($row->payment,2)}}</td>

                     <?php

                         $old_owed +=numb_format($row->old_owed,2);
                         $payment +=numb_format($row->payment,2);
                     ?>
                 </tr>
             @endforeach
         @endif

         <tr>
             <td colspan="10" style="text-align: right"><b>{{_t('Sub Total')}}</b></td>
             <td><b>{{$old_owed}}</b></td>
             <td><b>{{$payment}}</b></td>
         </tr>


    </tbody>
</table>

@else

     <h1 style="text-align: center;font-weight: bold">Data Not Fund</h1>
@endif