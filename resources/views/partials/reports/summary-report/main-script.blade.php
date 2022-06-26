<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')

    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

@endsection

<div class="container-fluid">
        <div class="form-group col-md-3">
            <label for="">Branch</label>
            <select class="form-control select2_field-branch" data-source="{{url("api/get-branch")}}" multiple name="branch_id[]" >

            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="">Center</label>
            <select class="form-control select2_field-center" name="center_id[]">
                
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="">Group</label>
            <select class="form-control select2_field-group" name="group_id[]" >
                
            </select>
        </div>
        <div class="form-group col-md-3 loan_product">
            <label for="">Loan Product</label>
            <select class="form-control select2_field-loan-product" data-source="{{url("api/get-loan-product")}}" multiple name="loan_product_id[]"  >
                @if(isset($_REQUEST['loan_product_id']))
                    <?php
                        $loan_product = \App\Models\LoanProduct::find($_REQUEST['loan_product_id']);
                    ?>
                    @if($loan_product != null)
                    <option value="{{$loan_product->id}}" id="loan_product_id">
                        {{$loan_product->name}}
                    </option>
                    @endif
               @endif
            </select>
        </div>
        <div class="col-md-12">
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
        </div>
</div>
{{--<div class="show-report"></div>--}}
<div class="col-md-12 show-report" style="min-height: 650px; width: 100%; background-color: #ffffff; padding: 20px; -webkit-box-shadow: 0px 6px 26px -6px rgba(0,0,0,0.48);
-moz-box-shadow: 0px 6px 26px -6px rgba(0,0,0,0.48);
box-shadow: 0px 6px 26px -6px rgba(0,0,0,0.48); border-radius: 5px;overflow: scroll;">
@push('crud_fields_styles')
    <style>
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
            $('.loan_product').hide();
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

                    var branch_ids = $('[name="branch_id[]"]').val();
                    var center_id = $('[name="center_id[]"]').val();
                    var group_id = $('[name="group_id[]"]').val();
                    var loan_product_id = $('[name="loan_product_id[]"]').val();
                    var start_date = $('[name="start_date"]').val();
                    var end_date = $('[name="end_date"]').val();
                    $.ajax({
                        url: report_url,
                        type: 'GET',
                        async: false,
                        dataType: 'html',
                        data: {
                            branch_ids:branch_ids,
                            center_id:center_id,
                            group_id:group_id,
                            loan_product_id:loan_product_id,
                            start_date:start_date,
                            end_date:end_date,
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
        $('#outstanding').click(function(){
            $('.loan_product').show();
        });
        $('#borrower_wise').click(function(){
            $('.loan_product').hide();
        });
        $('#center_wise').click(function(){
            $('.loan_product').hide();
        });
    </script>
    <script src="{{$base}}/bower_components/moment/min/moment.min.js"></script>
    <script src="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(".select2_field-branch").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: true,
                    placeholder: "Select Branch",
                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "title";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });

        $(".select2_field-loan-product").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: true,
                    placeholder: "Select Loan Product",
                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "name";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });
        // $(".select2_field-center").each(function (i, obj) {
        //     var form = $(obj).closest('form');
        //     var url = $(this).data('source');
        //     if (!$(obj).hasClass("select2-hidden-accessible"))
        //     {

        //         $(obj).select2({
        //             theme: 'bootstrap',
        //             multiple: false,
        //             placeholder: "Select Center",

        //             ajax: {
        //                 url: url,
        //                 type: 'GET',
        //                 dataType: 'json',
        //                 quietMillis: 250,
        //                 data: function (params) {
        //                     return {
        //                         q: params.term, // search term
        //                         page: params.page, // pagination
        //                         //form: form.serializeArray()  // all other form inputs
        //                     };
        //                 },
        //                 processResults: function (data, params) {
        //                     params.page = params.page || 1;

        //                     var result = {
        //                         results: $.map(data.data, function (item) {
        //                             textField = "code";
        //                             return {
        //                                 text: item[textField],
        //                                 id: item["id"]
        //                             }
        //                         }),
        //                         pagination: {
        //                             more: data.current_page < data.last_page
        //                         }
        //                     };

        //                     return result;
        //                 },
        //                 cache: true
        //             },
        //         })
        //         ;

        //     }
        // });

        $(function () {
            $('.select2_field-branch').change(function(){
                $(".select2_field-center").val("");
                var branch_ids = $('[name="branch_id[]"]').val();
                $.ajax({
                        url: "{{route('center-leader-branch')}}",
                        method: "get",
                        data: {
                            branch_ids:branch_ids
                        },
                success: function (data) {
                        $(".select2_field-center option").remove();
                            $(".select2_field-center").append('<option>'+"Select Center"+'</option>');
                        $.each(data.data, function(index, obj){
                            $(".select2_field-center").append('<option value="'+obj.id+'">'+obj.code+'</option>');
                        });
                        }
                 });
                 $.ajax({
                        url: "{{route('group-loan-center')}}",
                        method: "get",
                        data: {
                            branch_ids:branch_ids
                        },
                success: function (data) {
                        $(".select2_field-group option").remove();
                        $(".select2_field-group").append('<option>'+"Select Group"+'</option>');
                        $.each(data.data, function(index, obj){
                            $(".select2_field-group").append('<option value="'+obj.id+'">'+obj.group_code+'</option>');
                        });
                        }
                 });
            });

        });
        $(function () {
            $('.select2_field-center').change(function(){
                var center_id = $('[name="center_id[]"]').val();
                $.ajax({
                        url: "{{route('group-loan-center')}}",
                        method: "get",
                        data: {
                            center_id:center_id
                        },
                success: function (data) {
                        $(".select2_field-group option").remove();
                        $(".select2_field-group").append('<option>'+"Select Group"+'</option>');
                        $.each(data.data, function(index, obj){
                            $(".select2_field-group").append('<option value="'+obj.id+'">'+obj.group_code+'</option>');
                        });
                        },
                error: function (response) {
                        alert("Wrong") // I'm always get this.
                        },
                 });
            });

        });

    </script>

@endpush