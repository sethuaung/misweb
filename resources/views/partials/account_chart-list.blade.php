
<table style="width: 100%" class="table table-bordered">
    <thead>
         <tr>
             <th>{{_t('Code')}}</th>
             <th>{{_t('Name')}}</th>
             <th>{{_t('Name Other')}}</th>
             <th>{{_t('Type')}}</th>
             <th>{{_t('Action')}}</th>
         </tr>
    </thead>
    <tbody  class="account-list">

      @if(isset($id))
          <?php

                 $g_de=\App\Models\AccountChartExternalDetails::where('external_acc_id',$id)->get();

          ?>
        @foreach($g_de as $row)
            <?php
                $acc_id = optional($row)->main_acc_id ;
                $main_acc =null;
                $acc_sec = null;
                $ext_acc = null;
                if ($acc_id > 0){
                    $ext_acc = \App\Models\AccountChartExternal::where('id',$id)->first();
                    $main_acc = \App\Models\AccountChart::where('id',$acc_id)->first();

                    $acc_sec = optional($main_acc)->section_id ;
                    $main_acc_sec = \App\Models\AccountSection::where('id',$acc_sec)->first();
                    //dd($main_acc_sec);
                }
            ?>
            <tr>



                <td>{{ optional($main_acc)->code }}</td>
                <td>{{ optional($main_acc)->name }}</td>
                <td>{{ optional($main_acc)->name_kh }}</td>
                <td>{{ optional($main_acc_sec)->description}}</td>
                <td>

                    <input type="hidden" value="{{optional($main_acc)->id}}" name="main_acc_id[]">
                    {{--<input type="t" value="{{optional($main_acc)->code}}" name="main_acc_code[]">--}}
                    {{--<input type="t" value="{{optional($main_acc)->section_id}}" name="section_id[]">--}}
                    {{--<input type="t" value="{{optional($main_acc)->parent_id}}" name="parent_id[]">--}}
                    {{--<input type="t" value="{{optional($main_acc)->sub_section_id}}" name="sub_section_id[]">--}}
                    <button type="button" class="btn btn-danger remove-acc">-</button>
                </td>
            </tr>
        @endforeach

      @endif
    </tbody>
</table>
@push('crud_fields_scripts')
    <script>
         $(function () {
             $('[name="main_chart_account"]').on('change',function () {
                 var main_chart_account =  $('[name="main_chart_account"]').val()-0;
                 //alert(main_chart_account);

                 if(main_chart_account >0) {
                     $.ajax({
                         url: "{{url('api/find_chart_acc')}}",
                         method: "get",
                         data: {
                             main_chart_account: main_chart_account
                         },
                         success: function (res) {
                             //alert(res.id);


                             if(res.error-0 == 0) {
                                 var html = ' <tr>' +



                                     '            <td>'+res.code+'</td>' +
                                     '            <td>'+res.name+'</td>' +
                                     '            <td>' + res.name_kh + '</td>' +
                                     '            <td>' + res.sec_type + '</td>' +
                                     '            <td> ' +
                                     '            <input type="hidden" value="' + res.id + '" name="main_acc_id[]"> ' +
                                     // '            <input type="t" value="' + res.code + '" name="main_acc_code[]">' +
                                     // '            <input type="t" value="' + res.section_id + '" name="section_id[]">' +
                                     // '            <input type="t" value="' + res.parent_id + '" name="parent_id[]">' +
                                     // '            <input type="t" value="' + res.sub_section_id + '" name="sub_section_id[]">' +
                                     '            <button type="button" class="btn btn-danger remove-acc">-</button>' +
                                     '            </td>' +
                                     '        </tr>';

                                 $('.account-list').append(html);
                             }
                         }

                     })

                 }


             });

             $('body').on('click','.remove-acc',function () {
                 if(confirm('Are you sure')){
                     var tr=$(this).parent().parent();
                     tr.remove();
                 }

             });

             $('body').on('change','[name="section_id"]',function () {
                 $('[name="main_chart_account"]').html('<option value="0"></option>');
                 $('.account-list').html('');
             });


         })
    </script>
@endpush
