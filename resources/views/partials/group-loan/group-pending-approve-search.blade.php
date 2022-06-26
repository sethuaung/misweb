
<?php $base=asset('vendor/adminlte') ?>
<link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
<link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Theme style -->
<link rel="stylesheet"
      href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<style>
    .pg-d url{
        display: block !important;
    }
</style>

<table class="table table-bordered b-group " style="width: 100%;background-color: white">
    <thead>
    <tr>
        <th></th>
        <th>CenterID-Name</th>
        <th>Group ID</th>
        <th>Group Name</th>
        <th>Total Loans</th>
        <th><input type="checkbox" id="check_all_group"></th>
    </tr>
    </thead>
    <tbody class="group-list">
    <?php
    $total = 0;
    ?>
    @if($g_pending != null)
        @foreach($g_pending as $row)
            <?php
            $rand_id = rand(1,1000).time().rand(1,1000);
            $group = \App\Models\GroupLoan::find($row->group_loan_id);
            $center = null;
            if($group != null){
                $center = \App\Models\CenterLeader::find($group->center_id);
            }
            $total += $row->amount;
            ?>
            <tr id="p-{{$rand_id}}">
                <td>{{$loop->index +1}}</td>
                <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                <td>{{optional($group)->group_code}}</td>
                <td>{{optional($group)->group_name}}</td>
                <td>{{$row->amount}}</td>
                <td>
                    <a href="{{url("admin/list-member-pending?group_loan_id={$row->group_loan_id}&rand_id={$rand_id}")}}"
                       data-remote="false" data-toggle="modal" data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                    <input type="checkbox" class="form-check-input c-checked-group" name="approve_check[{{$rand_id}}]" value="{{$row->group_loan_id}}"/>
                    <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$row->group_loan_id}}">
                </td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td colspan="3" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
        <td colspan="2">{{$total}}</td>
    </tr>
    </tbody>

</table>
<div class="my-p-p-p">
    {!! $g_pending->links() !!}
</div>
<script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        $('.my-p-p-p .pagination').show();
    });
</script>




