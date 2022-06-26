
@push('crud_fields_styles')

@endpush

@push('crud_fields_scripts')
    <script>
        $(function () {

            $('body').on('change','[name="sub_section_id"]',function () {
                var d = $(this).val()-0;
                var txt = $('[name="sub_section_id"] option:selected').text();
                var section_id = $('[name="section_id"]').val()-0;
                $('[name="name"]').val(txt);
                $('[name="name_kh"]').val(txt);
                $('[name="code"]').val(section_id*10000 + d*2 + {{ rand(1,2000) }} );

                $.ajax({
                    url:'{{url('api/account-sub-section/')}}/'+d,
                    success:function (res) {
                        $('[name="description"]').val(res.description);
                    }
                });

            });
        });
    </script>
@endpush