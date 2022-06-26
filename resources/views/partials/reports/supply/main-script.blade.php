<div style="text-align: center">
    <button type="button" class="btn btn-app search-data">
        <i class="fa fa-search"></i> Search
    </button>
    <button type="button" class="btn btn-app print-data">
        <i class="fa fa-print"></i> Print
    </button>

    <button type="button" class="btn btn-app"
            onclick="exportTableToExcel('table-data', 'Data-{{date('d-m-Y')}}')"  >
        <i class="fa fa-file-excel-o"></i> Excel
    </button>
</div>
<div class="show-report"></div>

<form action="{{ url('exx') }}" method="post" enctype="multipart/form-data" class="excel-form" target="_blank">
    {!! csrf_field() !!}
    <textarea name="excel_date" class="excel_date" style="display: none;"></textarea>
</form>

@push('crud_fields_styles')
    <style>
        li.active a{
            background-color: #f5f4bb !important;
        }

        .table-data
        {
            width:100%;
        }

        .table-data,.table-data  th, .table-data  td
        {
            border-collapse:collapse;
            border: 1px solid #a8a8a8;
        }

        .table-data  th
        {
            text-align: center;
            padding: 5px;
        }

        .table-data  td
        {
            padding: 5px;
        }

        .table-data  tbody > tr:nth-child(odd)
        {
            background-color: #f4f4f4;
            color: #606060;
        }
    </style>
@endpush

@push('crud_fields_scripts')

    <script>
        var report_url = '';
        $(function () {

            $('body').on('click','.report-url',function (e) {
                e.preventDefault();
                report_url = $(this).data('href');

                $('.report-url').each(function () {
                    $(this).parent().removeClass('active')
                });

                $(this).parent().addClass('active');
            });
            $('.print-data').on('click',function () {
                printDiv();
            });
            $('.search-data').on('click', function (e) {
                e.preventDefault();
                if(report_url != '') {
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    var month = $('[name="month"]').val();
                    var acc_chart_id = $('[name="acc_chart_id[]"]').val();
                    $.ajax({
                        url: report_url,
                        type: 'GET',
                        async: false,
                        dataType: 'html',
                        data: {
                            start_date:start_date,
                            end_date:end_date,
                            month:month,
                            acc_chart_id:acc_chart_id,
                        },
                        success: function (d) {
                            $('.show-report').html(d);
                        },
                        error: function (d) {
                            alert('error');
                            $('.my-modal').hide();
                        }
                    });

                } else {
                    alert('Please click the report type');
                }


            });

/*

            $('body').delegate('.my-paginate ul li a', 'click', function (e) {
                e.preventDefault();

                var report_url = $(this).prop('href');


                $.ajax({
                    url: report_url,
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        report_option: report_option,
                        currency: currency,
                        emp_id: emp_id,
                        department_id: department_id,
                        duty_station_id: duty_station_id,
                        position_id: position_id,
                        q: q,
                        finger_print_no: finger_print_no,
                        emp_status: emp_status,
                    },
                    success: function (d) {
                        $('.show-report').html(d);
                        $('.my-modal').hide();

                    },
                    error: function (d) {
                        alert('error');

                        $('.my-modal').hide();
                    }
                });

            });
*/


        });

        function printDiv()
        {

            var DivIdToPrintPop=document.getElementById('DivIdToPrint');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html>' +
                '<head>' +
                '<style>\n' +
                '    .table-data\n' +
                '    {\n' +
                '        width:100%;\n' +
                '    }\n' +
                '\n' +
                '    .table-data,.table-data  th, .table-data  td\n' +
                '    {\n' +
                '        border-collapse:collapse;\n' +
                '        border: 1px solid #a8a8a8;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  th\n' +
                '    {\n' +
                '        text-align: center;\n' +
                '        padding: 5px;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  td\n' +
                '    {\n' +
                '        padding: 5px;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  tbody > tr:nth-child(odd)\n' +
                '    {\n' +
                '       background-color: #f4f4f4;\n' +
                '        color: #606060;\n' +
                '    }\n' +
                '</style>' +
                '</head>'+
                '<body onload="window.print()">'+DivIdToPrintPop.innerHTML+'</body>' +
                '</html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }

        function exportTableToExcel(tableID){

            var DivIdToPrintPop = document.getElementById(tableID);

            $('.excel_date').val(DivIdToPrintPop.innerHTML);

            $('.excel-form').submit();


        }
    </script>

@endpush


