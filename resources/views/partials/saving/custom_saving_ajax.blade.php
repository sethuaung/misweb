<?php
    $_e = isset($entry)?$entry:null;
    //dd($_e);
?>


@push('crud_fields_scripts')
    <script>
        $(function () {

            $('body').on('change', '.saving_id', function () {

                var s_id = $('[name="saving_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get-saving-ajax/')}}'+ '/' + s_id,
                    async: false,
                    data: {
                        s_id: s_id,
                    },
                    success: function (res) {
                        //console.log(res);



                        $('[name="reference"]').val(res.saving_deposit_no);
                        $('[name="saving_type"]').val(res.saving_type);
                        $('[name="client_name"]').val(res.client_name);
                        $('[name="available_balance"]').val(res.available_balance);


                    }

                });


            });
        });

    </script>
@endpush
