<div style="text-align: center;">
    <button type="button" class="btn btn-default show-report">{{_t('Show')}}</button>
    <button type="button" class="btn btn-default print-report">{{_t('Print')}}</button>
    <button type="button" class="btn btn-default excel-report">{{_t('Excel')}}</button>
</div>




@push('crud_fields_styles')
    <style>


    </style>
@endpush


@push('crud_fields_scripts')
    <script>

        $(function () {

            $('.show-report').on('click',function () {
                getResult();
            });

            $('.print-report').on('click',function () {
                getResult();
                $('#show-purchase-detail').hide();



                var contents = $(".body-report-print").html();
                var frame1 = $('<iframe />');
                frame1[0].name = "frame1";
                frame1.css({"position": "absolute", "top": "-1000000px"});
                $("body").append(frame1);
                var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
                frameDoc.document.open();
                //Create a new HTML document.
                frameDoc.document.write('<html><head><title>Print Report</title>');

                frameDoc.document.write('<style>\n' +
                    '    table{\n' +
                    '        border-collapse: collapse;\n' +
                    '    }\n' +
                    '    .border th, .border td {\n' +
                    '        border: 1px solid rgba(188, 188, 188, 0.96);\n' +
                    '        padding: 5px;\n' +
                    '    }\n' +
                    '</style>');

                frameDoc.document.write('</head><body>');
                //Append the external CSS file.

//                frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
                //Append the DIV contents.
                frameDoc.document.write(contents);
                frameDoc.document.write('</body></html>');
                frameDoc.document.close();
                setTimeout(function () {
                    window.frames["frame1"].focus();
                    window.frames["frame1"].print();
                    frame1.remove();

                }, 500);


            });

            $('.excel-report').on('click',function () {
                getResult();
            });

        });


        function getResult() {
            var report_type = $('[name="report_type"]').val();
            var start_date = $('[name="start_date"]').val();
            var end_date = $('[name="end_date"]').val();
            var reference_no = $('[name="id[]"]').val();
            var use_date = $('[name="use_date"]').val();

            if(report_type != null && report_type != '') {
                $.ajax({
                    type: 'POST',
                    url: report_type,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        reference_no: reference_no,
                        use_date: use_date,
                    },
                    success: function (res) {
                        $('.show-result-report').html(res);
                    }

                });
            }

        }
    </script>
@endpush
