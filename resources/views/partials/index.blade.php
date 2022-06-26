@extends('backpack::layout')
@push('after_styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">
@endpush
@section('header')

@endsection
@section('content')
    <table class="table table-striped table-bordered" data-page-size="10" id="general-journal">
        <thead>
        <tr>
            <th>Reference</th>
            <th>Tran Type</th>
            <th>Tran Date</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
@endsection


@push('after_scripts')
    <script src="{{asset('js')}}/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('js')}}/datatables/dataTables.treeGrid.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script>
        $(function () {
            var general_journal =$("#general-journal").DataTable({
                "responsive": true,
                "paging": true,
                "lengthChange": true,
                "searching": false,
                "ordering": true,
                orderCellsTop: true,
                "bSortCellsTop": true,
                language: {
                    "url": "{{asset('dataformat.json')}}"
                },
                "columnDefs": [
                    {orderable: false, targets: -1}
                ],
                "info": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{url('general-datable')}}",
                    "data": function (d) {

                    }
                },
                "deferRender": true,
                "orderClasses": false,
                "render": true,
                "columns": [
                    {
                        "data": "reference", "render": function (data,type,full) {
                            return data;
                        }
                    },
                    {
                        "data": "type", "render": function (data,type,full) {
                            return data;
                        }
                    },
                    {
                        "data": "date", "render": function (data,type,full) {
                            return data;
                        }
                    },

                ],

            });
        });
    </script>
@endpush
