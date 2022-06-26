
@push('crud_fields_scripts')
    <script>

        $(function () {
            @if(companyReportPart() == 'company.myanmarelottery')
            $('[href="#account"]').hide();
            @endif

        });
    </script>
@endpush
