<div style="text-align: center">
    <button type="button" class="btn btn-app search-data">
        <i class="fa fa-search"></i> Search
    </button>
    <button type="button" class="btn btn-app print-data">
        <i class="fa fa-print"></i> Print
    </button>
</div>
<div class="show-report" style="width: 100%;text-align: center;margin: 0 auto;" >

</div>

@push('crud_fields_styles')
<style>

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

                    var category_id = $('[name="category_id[]"]').val();
                    var barcode_type = $('[name="barcode_type"]').val();
                    $.ajax({
                        url: report_url,
                        type: 'GET',
                        async: false,
                        dataType: 'html',
                        data: {
                            category_id:category_id,
                            barcode_type:barcode_type,
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

            var DivIdToPrintPop=document.getElementById('DivIdToPrint');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html>' +
                '<head>' +
                '<style>' +
                'body {\n' +
                '            width: 8.5in;\n' +
                '            margin: 0in .1875in;\n' +
                '        }\n' +
                '        .my-label{\n' +
                '            /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */\n' +
                '            width: 2.025in; /* plus .6 inches from padding */\n' +
                '            height: .875in; /* plus .125 inches from padding */\n' +
                '            padding: .125in .3in 0;\n' +
                '            margin-right: .125in; /* the gutter */\n' +
                '\n' +
                '            float: left;\n' +
                '\n' +
                '            text-align: center;\n' +
                '            overflow: hidden;\n' +
                '\n' +
                '            outline: 1px dotted; /* outline doesn\'t occupy space like border does */\n' +
                '        }\n' +
                '        .page-break  {\n' +
                '            clear: left;\n' +
                '            display:block;\n' +
                '            page-break-after:always;\n' +
                '        }' +
                '</style>' +
                '</head>'+
                '<body onload="window.print()">'+DivIdToPrintPop.innerHTML+'</body>' +
                '</html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }

    </script>

@endpush


