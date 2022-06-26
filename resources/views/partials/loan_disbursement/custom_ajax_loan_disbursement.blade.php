<?php
    $_e = isset($entry)?$entry:null;
?>

<div class="col-md-6">

    <label>Guarantor Photo</label>

    <div>
        <img width="300" height="300" class="g_image" src="{{ asset('No_Image_Available.jpg')}}"/>
    </div>
</div>

@push('crud_fields_scripts')
    <script>
        $(function () {
            $('body').on('change', '.guarantor', function () {

                var g_id = $('[name="guarantor_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get-guarantor/')}}' + '/' + g_id,
                    data: {
                        g_id: g_id,
                    },
                    success: function (res) {
                        $('[name="g_nrc_number"]').val(res.nrc_number);
                        $('[name="g_name"]').val(res.full_name_mm);
                        $('[name="g_id"]').val(res.id);
                        if(res.photo) {
                            $('.g_image').prop('src', '{{ asset('/') }}' + '/' + res.photo);
                        }

                    }

                });


            });

            $('body').on('change', '.guarantor2', function () {

                var g_id = $('[name="guarantor2_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get-guarantor/')}}' + '/' + g_id,
                    data: {
                        g_id: g_id,
                    },
                    success: function (res) {
                        $('[name="g_nrc_number2"]').val(res.nrc_number);
                        $('[name="g_name2"]').val(res.full_name_mm);
                        $('[name="g_id2"]').val(res.id);

                    }

                });


            });
        });

        @if(optional($_e) != null)
            $(function () {
            $('[name="guarantor_id"]').trigger('change');
            //$('[name="client_id"]').trigger('change');
            $('[name="guarantor2_id"]').trigger('change');
        });
        @endif
        <?php
        $disbursement_number = old('disbursement_number');
        ?>
        @if($disbursement_number != null)
        window.location.reload();
        @endif
    </script>
@endpush
