<link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    jQuery(document).ready(function($) {
        $('.date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });

        $(document).on('click','.save-data',function (e) {
            e.preventDefault();
            var tr = $(this).parent().parent();
            var note = tr.find('.note').val();
            var date = tr.find('.date').val();
            var status = tr.find('.status').val();
            var client_id = tr.find('.client_id').val();
            $.ajax({
                type:'GET',
                url:'{{url('api/authorize_client_pending_status')}}',
                data:{
                    note : note,
                    date : date,
                    status : status,
                    client_id : client_id
                },
                success:function(res){
                    if(res['error'] - 0==0){
                        new PNotify({
                            title: ("Client"),
                            text: "this client check already",
                            type: "success",
                            icon:false
                        });

                        window.location.reload();

                    }
                }

            });
        });
    });
</script>
