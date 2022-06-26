
@push('crud_fields_styles')

@endpush

@push('crud_fields_scripts')
    <script>
        $(function () {

            $('body').on('click','.gg-click',function () {
               var id = $(this).data('id');
               $('.gg-check-'+id).each(function () {
                   this.checked = !this.checked;
               });
            });

        });
    </script>
@endpush