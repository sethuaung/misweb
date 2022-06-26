<div style="text-align: center">
    <button type="button" class="btn btn-app search-data">
        <i class="fa fa-search"></i> Search
    </button>
    <button type="button" class="btn btn-app print-data">
        <i class="fa fa-print"></i> Print
    </button>
    <button type="button" class="btn btn-app" id="normal_btn"
            onclick="exportTableToExcel('table-data', 'Data-{{date('d-m-Y')}}')"  >
        <i class="fa fa-file-excel-o"></i> Excel
    </button>
    <button type="button" class="btn btn-app" id="excel_btn"
            onclick="Excel('table-data', 'Data-{{date('d-m-Y')}}')"  >
        <i class="fa fa-file-excel-o"></i> Excel
    </button>
    <button type="button" class="btn btn-app" id="cash_btn"
            onclick="CashExcel('table-data', 'Data-{{date('d-m-Y')}}')"  >
        <i class="fa fa-file-excel-o"></i> Excel
    </button>
</div>
<div class="show-report"></div>

@push('crud_fields_styles')
    <style>
        /*li.active a{
            background-color: #f5f4bb !important;
        }*/

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
            $('#excel_btn').hide();
            $('#cash_btn').hide();
            $('#generalLeger').click(function(){
                $('#excel_btn').show();
                $('#normal_btn').hide();
                $('#cash_btn').hide();
            });
            $('#account_list').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#trial_balance').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#profit_loss').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#profit_loss_d').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#profit_loss_j').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#profit_loss_c').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#balance_sheet').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#transaction_detail_acc').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#cash_book').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#cash_statement_detail').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#cash_transaction').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#cash_book_summary').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').show();
                $('#cash_btn').hide();
            });
            $('#cash_book_detail').click(function(){
                $('#excel_btn').hide();
                $('#normal_btn').hide();
                $('#cash_btn').show();
            });
            
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

                $("#whole_page_loader").fadeIn(function(){
                    if(report_url != '') {
                    var search_type = $('[name="search_type"]').val();
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    var month = $('[name="month"]').val();
                    var acc_chart_id = $('[name="acc_chart_id[]"]').val();
                    var show_zero = $('[name="show_zero"]').val();
                    var branch_id = $('[name="branch_id[]"]').val();
                    var from_acc = $('[name="account[1]"]').val();
                    var to_acc = $('[name="account[2]"]').val();
                    $.ajax({
                        url: report_url,
                        type: 'GET',
                        async: false,
                        dataType: 'html',
                        data: {
                            search_type:search_type,
                            start_date:start_date,
                            end_date:end_date,
                            month:month,
                            acc_chart_id:acc_chart_id,
                            show_zero:show_zero,
                            branch_id: branch_id,
                            from_acc: from_acc,
                            to_acc: to_acc
                        },
                        success: function (d) {
                            $('.show-report').html(d);
                            $("#whole_page_loader").fadeOut();
                        },
                        error: function (d) {
                            $("#whole_page_loader").fadeOut();
                            alert('error');
                            $('.my-modal').hide();
                        }
                    });

                    } else {
                        $("#whole_page_loader").fadeOut();
                        alert('Please click the report type');
                    }
                });
                


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

            var divToPrint=document.getElementById('DivIdToPrint');

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
                '<body onload="window.print()">'+divToPrint.innerHTML+'</body>' +
                '</html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }
        
        function exportTableToExcel(tableID, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            // console.log(tableID);
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename?filename+'.xls':'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }

        function Excel(tableID, filename = ''){
            $.ajax({
             url: "{{route('export-excel-confirm')}}",
             method: "get",
                data: {
                   
                },
                success: function (data) {
                    var branch_id = $('[name="branch_id[]"]').val();
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    var month = $('[name="month"]').val();
                    var acc_chart_id = $('[name="acc_chart_id[]"]').val();
                    var from_acc = $('#account_1').val();
                    var to_acc = $('#account_2').val();
                    var search_type = $("#search_range").val();

                    var url = "<?php echo route('export-excel')?>";
                   
                    var download_url = url + '?branch_id[]='+branch_id+'&acc_chart_id[]='+acc_chart_id+'&from_acc='+from_acc+'&to_acc='+to_acc+'&search_type='+search_type+'&start_date='+start_date+'&end_date='+end_date+'&month='+month+'';

                    window.open(download_url);
                },
            });
        }
        function CashExcel(tableID, filename = ''){
            $.ajax({
             url: "{{route('export-excel-confirm')}}",
             method: "get",
                data: {
                   
                },
                success: function (data) {
                    var branch_id = $('[name="branch_id[]"]').val();
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    var month = $('[name="month"]').val();
                    var acc_chart_id = $('[name="acc_chart_id[]"]').val();
                    var from_acc = $('#account_1').val();
                    var to_acc = $('#account_2').val();
                    var search_type = $("#search_range").val();

                    var url = "<?php echo route('cash-excel')?>";
                   
                    var download_url = url + '?branch_id[]='+branch_id+'&acc_chart_id[]='+acc_chart_id+'&from_acc='+from_acc+'&to_acc='+to_acc+'&search_type='+search_type+'&start_date='+start_date+'&end_date='+end_date+'&month='+month+'';

                    window.open(download_url);
                },
            });
        }
    </script>

@endpush


