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
$arr = ['Approved','Declined'];
?>
<div class="box">
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-responsive table-bordered table-show-detail">

            <tr>

                <th>Loan Request</th>
                <th>Loan Approval</th>
                <th>Approve Date</th>
                <th>Plan Disburse date</th>
                <th>Status</th>
                <th></th>

            </tr>
            <tr>

                <input type="hidden" class="id" value="{{$row->id}}"/>

                {{--<td><input type="text" value="{{ $row->status_note_approve}}" name="status_note_approve"--}}
                           {{--class="form-control status_note_approve"></td>--}}
                <td><input type="text" readonly="readonly" value="{{ $row->loan_amount}}" name="status_note_approve"
                           class="form-control loan_request"></td>
                <td><input type="text" value="{{ $row->loan_amount}}" name="status_note_approve"
                           class="form-control loan_amount"></td>
                <td><input type="text" value="{{$row->status_note_date_approve??date('Y-m-d')}}" name="status_note_date_approve"
                           class="form-control datepicker status_note_date_approve"></td>
                <td><input type="text" value="{{$row->plan_disbursement_date??date('Y-m-d')}}" name="plan_disbursement_date"
                           class="form-control datepicker plan_disbursement_date"></td>
                <td>
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
<div class="modal fade" id="DeclinedModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remark for Loan Declined</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{url('/admin/update-loan-disbursement-status')}}">
        @csrf
          <div class="form-group">
            <input type="hidden" name="loan_id" value="{{$row->id}}">
            <label for="remark" class="col-form-label">Remark:</label>
            <input class="form-control remark" type="name" name="remark" id="remark" required>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary declined">Declined</button>
        </div>
        </form>
    </div>
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

        $('.btn_save').click(function (e) {
                                 //add e param
        
            e.preventDefault(); //or return false;

            var id = <?php echo $row->id;?>;
            var status_note_approve = $('.status_note_approve').val();
            var status_note_date_approve = $('.status_note_date_approve').val();
            var disbursement_status = $('.disbursement_status').val();
            var loan_request = $('.loan_request').val();
            var loan_amount = $('.loan_amount').val();
            var disburse_date = $('.plan_disbursement_date').val();
            if(disbursement_status == 'Approved'){
                $.ajax({
                type: 'post',
                url: '{{url('/admin/update-loan-disbursement-status')}}',
                data: {
                    id: id,
                    status_note_approve: status_note_approve,
                    status_note_date_approve: status_note_date_approve,
                    disbursement_status: disbursement_status,
                    loan_request: loan_request,
                    loan_amount: loan_amount,
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
            };
            if(disbursement_status == 'Declined'){
                $('#DeclinedModel').modal('show');

                $('.declined').click(function (e) {
                e.preventDefault();
                var remark = $('.remark').val();
                if(remark != ''){
                $.ajax({
                type: 'post',
                url: '{{url('/admin/update-loan-disbursement-status')}}',
                data: {
                    id: id,
                    status_note_approve: status_note_approve,
                    status_note_date_approve: status_note_date_approve,
                    disbursement_status: disbursement_status,
                    loan_request: loan_request,
                    loan_amount: loan_amount,
                    plan_disbursement_date: disburse_date,
                    remark: remark
                },
                success: function (res) {
                    console.log(res);
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
                }
                else{
                    alert('Remark Required');
                }
                });
            };
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
