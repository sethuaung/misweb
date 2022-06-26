<?php
    $_e = isset($entry)?$entry:null;
    //dd($_e);
?>
<div class="col-md-6">
    <label>Client Photo</label>
    <div>
        <img width="300" height="300"  class="c_image" src="{{asset('No_Image_Available.jpg')}}"/>
    </div>
</div>

@push('crud_fields_scripts')
    <script>
        $(function () {

            $('body').on('change', '.client_id', function () {

                var c_id = $('[name="client_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get-client-g/')}}'+ '/' + c_id,
                    async: false,
                    data: {
                        g_id: c_id,
                    },
                    success: function (res) {
                        // console.log(res);
                        var html = '<option value='+res.loan_officer_id+'>' +res.loan_officer_name+
                            '</option>';

                        var  you_are_a_center_leader =  '<option value='+res.you_are_a_center_leader+'>' +res.you_are_a_center_leader+
                            '</option>'  ;

                        var you_are_a_group_leader  =  '<option value='+res.you_are_a_group_leader+'>' +res.you_are_a_group_leader+
                            '</option>';

                        var center = '<option value='+res.center_id+'>' +res.center_name+
                            '</option>';

                        var branch = '<option value='+res.branch_id+'>' +res.branch_name+
                            '</option>';

                        if (res.you_are_a_center_leader != null){
                            $('[name="you_are_a_center_leader"]').html(you_are_a_center_leader);
                        }

                        if (res.you_are_a_group_leader != null){
                            $('[name="you_are_a_group_leader"]').html(you_are_a_group_leader);
                        }


                        $('[name="client_nrc_number"]').val(res.nrc_number);
                        $('[name="client_phone"]').val(res.primary_phone_number);
                        $('[name="client_name"]').val(res.name_other);

                        if(res.loan_officer_id >0){
                            $('[name="loan_officer_id"]').html(html);
                        }
                        if(res.center_id >0){
                            $('[name="center_leader_id"]').html(center);
                        }
                        if(res.branch_id >0){
                            $('[name="branch_id"]').html(branch);
                        }
                        $('[name="available_balance"]').val(res.saving_amount);
                        if(res.photo_of_client) {
                            $('.c_image').prop('src', '{{ asset('/') }}' + '/' + res.photo_of_client);//group_loan_id

                            // if(res.group_id >0){
                            //     $('[name="group_loan_id"]').html('<option value="'+res.group_id+'">'+res.group_code+'-'+res.group_name+'</option>');
                            //     $('[name="group_loan_id"]').trigger('change');
                            // }

                        }


                    }

                });


            });
        });

        @if(optional($_e) != null)
        $(function () {
            $('[name="client_id"]').trigger("change");
            $('[name="branch_id"]').trigger("change");


            var branch = '<option value={{optional(optional($_e)->branch_name)->id}}>' +'{{optional(optional($_e)->branch_name)->title}}'+ '</option>';

            $('[name="branch_id"]').html(branch);

        });



        @endif
    </script>
@endpush
