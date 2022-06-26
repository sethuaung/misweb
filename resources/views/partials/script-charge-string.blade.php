<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Amount</th>
            <th>Collected on</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody class="show-charge-string">

    </tbody>
</table>
@push('crud_fields_scripts')
    <script>
        $(function () {
            $('body').on('click', '.add-charge-string', function () {
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
                                    '<td><input type="hidden" class="id" name="id[]" value="'+ d.id +'">' + d.name + '</td>' +
                                    '<td>' + d.amount + '% ' + d.charge_option + '</td>' +
                                    '<td>' + d.charge_type + '</td>' +
                                    '<td><a href="javascript:void(0)" class="remove btn btn-sm btn-default">Remove</a></td>' +
                                '</tr>';

                        $('.show-charge-string').append(tr);
                    }

                });
            });//

            /* remove row */
            $('body').on('click', '.remove', function () {
                removeRow(this);
            });


        });// ready

        function removeRow(selector) {
            selector.closest('tr').remove();
        }
    </script>
@endpush