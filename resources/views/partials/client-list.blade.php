
<table style="width: 100%" class="table table-bordered">
    <thead>
         <tr>
             <th>{{_t('Client Number')}}</th>
             <th>{{_t('Loan Number')}}</th>
             <th>{{_t('NRC')}}</th>
             <th>{{_t('Full Name')}}</th>
             <th>{{_t('Action')}}</th>
         </tr>
    </thead>
    <tbody  class="client-list">

      @if(isset($id))
          <?php
                 $g_de=\App\Models\GroupLoanDetail::where('group_loan_id',$id)->get();
          ?>
        @foreach($g_de as $row)
            <?php
                $client_id = optional($row)->client_id ;
                $loan =null;
                if ($client_id > 0){
                    $loan = \App\Models\Loan::where('client_id',$client_id)->where('group_loan_id',$id)->first();
                }
            ?>
            <tr>
                <td>
                    <input type="hidden" value="{{optional($row)->client_id}}" name="line_client_id[]">
                    <input type="hidden" value="{{optional($row)->loan_id}}" name="loan_disbursement_id[]">
                    {{optional(optional($row)->client)->client_number}}</td>
                <td>{{ optional($loan)->disbursement_number }}</td>
                <td>{{optional(optional($row)->client)->nrc_number}}</td>
                <td>{{optional(optional($row)->client)->name}}</td>
                <td><button type="button" class="btn btn-danger remove-client">-</button></td>
            </tr>
        @endforeach

      @endif
    </tbody>
</table>
@push('crud_fields_scripts')
    <script>
         $(function () {
             $('body').on('change', '[name="center_id"]', function () {
                 var center_id = $(this).val();
                 // alert(center_id);
                 $.ajax({
                     type: 'GET',
                     url: '{{url('api/get-group-loan-code')}}',
                     data: {
                         center_id: center_id,
                     },
                     success: function (res) {
                         var code = res.code;

                         $('[name="group_code"]').val(code);
                     }

                 });
             });

             $('[name="loan_disbursement_id"]').on('change',function () {
                 var loan_disbursement_id =  $('[name="loan_disbursement_id"]').val()-0;
                 if(loan_disbursement_id >0) {
                     $.ajax({
                         url: "{{url('api/find_client_loan')}}",
                         method: "get",
                         data: {
                             loan_disbursement_id: loan_disbursement_id
                         },
                         success: function (res) {
                             // alert(res.loan_id);
                            /* 'error' => 0,
                                 'client_id' => $c->id,
                                 'loan_id' => $loan_disbursement_id,
                                 'disbursement_number' => $m->disbursement_number,
                                 'client_number' => $c->client_number,
                                 'name' => $c->name,*/

                             if(res.error-0 == 0) {
                                 var html = ' <tr>' +
                                     '            <td>' +
                                     '<input type="hidden" value="' + res.loan_id + '" name="loan_disbursement_id[]">' +
                                     '<input type="hidden" value="' + res.client_id + '" name="line_client_id[]">'
                                     + res.client_number +
                                     '</td>' +
                                     '            <td>'+res.disbursement_number+'</td>' +
                                     '            <td>' + res.nrc_number + '</td>' +
                                     '            <td>' + res.name + '</td>' +
                                     '            <td><button type="button" class="btn btn-danger remove-client">-</button></td>' +
                                     '        </tr>';

                                 $('.client-list').append(html);
                             }



                         }

                     })

                 }

             });

             $('body').on('click','.remove-client',function () {
                 if(confirm('Are you sure')){
                     var tr=$(this).parent().parent();
                     tr.remove();
                     //window.location.reload();
                 }

             });


         })
    </script>
@endpush
