<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Date of Valuation</th>
        <th>Value Amount</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody class="show-asset-detail">

    </tbody>
</table>
@push('crud_fields_scripts')
    <script>
        $(function () {
            $('body').on('click', '.add-charge-string', function () {
                var charge_id = $('[name="charge_id"]').val();
                var charge_id = $('[name="charge_id"]').val();

                $.ajax({
                    type: 'get',
                    url: '{{ url('/api/add-charge') }}',
                    data: {
                        charge_id: charge_id
                    },
                    success: function (d) {
                        var tr = '';
                        tr += '<tr>' +
                            '<td>' + d.name + '</td>' +
                            '<td>' + d.amount + '% ' + d.charge_option + '</td>' +
                            '<td>' + d.charge_type + '</td>' +
                            '<td><a href="#" class="remove btn btn-sm btn-default">Remove</a></td>' +
                            '</tr>';

                        $('.show-asset-detail').append(tr);
                    }

                });
            });


        });// ready
    </script>
@endpush
