<link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    jQuery(document).ready(function($) {
        $(document).on('change','.update-branch',function (e) {

            var branch_id = $(this).val();
            var loan_id = $(this).data('id');


            if (branch_id > 0 && loan_id > 0){
                $.ajax({
                    type:'GET',
                    url:'{{url('api/update-branch')}}',
                    data:{
                        branch_id : branch_id,
                        loan_id: loan_id,
                    },
                    success:function(res) {
                        if(res['error'] - 0==0){
                            new PNotify({
                                title: (""),
                                text: "Update Branch Successfully",
                                type: "success"
                            });
                            //location.reload();
                        }
                        else {
                            new PNotify({
                                title: (""),
                                text: "Update Branch Unsuccessfully",
                                type: "error"
                            });
                        }
                    }

                });
            }
        });
    });
</script>
