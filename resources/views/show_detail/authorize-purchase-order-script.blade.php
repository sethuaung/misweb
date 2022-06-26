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
            var purchase_id = tr.find('.purchase_id').val();
            $.ajax({
                type:'GET',
                url:'{{url('api/authorize-purchase-order-status')}}',
                data:{
                    note : note,
                    date : date,
                    status : status,
                    purchase_id : purchase_id
                },
                success:function(res){
                    if(res - 0==0){
                        new PNotify({
                            title: (""),
                            text: "ត្រូវបានពិនិត្យ",
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
