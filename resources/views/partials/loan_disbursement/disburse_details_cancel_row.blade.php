{{--@php($lang_file = 'product')--}}

<style>
    .form-inline .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
        width: 100%;
    }
</style>

<link rel="stylesheet"
      href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

<?php
//$arr = ['Pending-Approval', 'Awaiting-Disbursement', 'Loan-Declined', 'Loan-Withdrawn', 'Loan-Written-Off', 'Loan-Closed'];
$arr = ['Pending','Declined'];
?>
<div class="box">
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-responsive table-bordered table-show-detail">

            <tr>

                <th>Approve Date</th>
                <th>Status</th>
                <th></th>

            </tr>
            <tr>

                <input type="hidden" class="id" value="{{$row->id}}"/>

                <td width="200px">
                    <input type="text" value="{{$row->status_note_date_approve??date('Y-m-d')}}" name="status_note_date_approve"
                           class="form-control datepicker status_note_date_approve">
                </td>

                <td width="200px">
                    <select type="text" name="" class="form-control disbursement_status">

                        @foreach($arr as $op)
                            <option
                                {{ $row->disbursement_status == $op?'selected':'' }} value="{{$op}}">{{$op}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary btn_save" type="button">
                        Save
                    </button>

                </td>
            </tr>


        </table>
    </div>
</div>


<script
    src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>


<script>


    $(function () {

        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true
            //startDate: '-3d'
        });

        $('.btn_save').click(function (e) {                     //add e param

            e.preventDefault(); //or return false;

            var id = <?php echo $row->id;?>;
            var status_note_date_approve = $('.status_note_date_approve').val();
            var disbursement_status = $('.disbursement_status').val();
            var disburse_date = $('.plan_disbursement_date').val();

            $.ajax({
                type: 'post',
                url: '{{url('/admin/update-loan-disbursement-cancel-status')}}',
                data: {
                    id: id,
                    status_note_date_approve: status_note_date_approve,
                    disbursement_status: disbursement_status,
                    plan_disbursement_date: disburse_date
                },
                success: function (res) {
                    //console.log(res);
                    new PNotify({
                        title: ("Success"),
                        text: "Successfully",
                        type: "success"
                    });
                    window.location.reload();

                },
                error: function () {
                    console.log('error')
                }

            });
        });


    });

    // jQuery(document).ready(function($){
    //     $('.data-bs-datepicker').each(function(){
    //
    //         var $fake = $(this),
    //             $field = $fake.parents('.form-group').find('input[type="hidden"]'),
    //             $customConfig = $.extend({
    //                 format: 'dd/mm/yyyy'
    //             }, $fake.data('bs-datepicker'));
    //         $picker = $fake.bootstrapDP($customConfig);
    //
    //         var $existingVal = $field.val();
    //
    //         if( $existingVal.length ){
    //             // Passing an ISO-8601 date string (YYYY-MM-DD) to the Date constructor results in
    //             // varying behavior across browsers. Splitting and passing in parts of the date
    //             // manually gives us more defined behavior.
    //             // See https://stackoverflow.com/questions/2587345/why-does-date-parse-give-incorrect-results
    //             var parts = $existingVal.split('-')
    //             var year = parts[0]
    //             var month = parts[1] - 1 // Date constructor expects a zero-indexed month
    //             var day = parts[2]
    //             preparedDate = new Date(year, month, day).format($customConfig.format);
    //             $fake.val(preparedDate);
    //             $picker.bootstrapDP('update', preparedDate);
    //         }
    //
    //         $fake.on('keydown', function(e){
    //             e.preventDefault();
    //             return false;
    //         });
    //
    //         $picker.on('show hide change', function(e){
    //             if( e.date ){
    //                 var sqlDate = e.format('yyyy-mm-dd');
    //             } else {
    //                 try {
    //                     var sqlDate = $fake.val();
    //
    //                     if( $customConfig.format === 'dd/mm/yyyy' ){
    //                         sqlDate = new Date(sqlDate.split('/')[2], sqlDate.split('/')[1] - 1, sqlDate.split('/')[0]).format('yyyy-mm-dd');
    //                     }
    //                 } catch(e){
    //                     if( $fake.val() ){
    //                         PNotify.removeAll();
    //                         new PNotify({
    //                             title: 'Whoops!',
    //                             text: 'Sorry we did not recognise that date format, please make sure it uses a yyyy mm dd combination',
    //                             type: 'error',
    //                             icon: false
    //                         });
    //                     }
    //                 }
    //             }
    //
    //             $field.val(sqlDate);
    //         });
    //     });
    // });


</script>
