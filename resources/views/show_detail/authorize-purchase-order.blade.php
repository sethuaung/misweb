
<?php

$status = array(
    'pending' => 'Pending',
    'approved' => 'Approve',
    'reject'  => 'Reject'
);
?>

<div class="row">
    <div class="col-md-12">
        @php
            $id = isset($entry->id)?$entry->id:0;
            $w = \App\Models\Purchase::find($id);
        @endphp
        @if($id > 0)
            <table  class="table table-bordered">
                <thead>
                <tr>
                    <th>{{_t('Note')}}</th>
                    <th>{{_t('Check Date')}}</th>
                    <th>{{_t('Status')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                   <tr>

                    <input type="hidden" value="{{$id}}" class="purchase_id">
                    <td style="width:60%">
                        <input class="form-control note" value="{{ optional($w)->order_status_note}}" type="text" style="width: 100%">
                    </td>
                    <td style="width: 20%">
                        <div class="input-group"  style="width: 100%">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text"   value="{{ optional($w)->oder_status_date!=null?optional($w)->oder_status_date:date('Y-m-d')}}"  class="form-control pull-right date" >
                        </div>
                    </td>
                    <td style="width:20%">
                        <select class="form-control status" style="width:100%" >
                            @foreach($status as $key => $val )
                                <option value="{{$key}}"  {{optional($w)->order_status==$key?'selected = "selected"':''}} >{{$val}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width: 40px;"><a href="#" class="btn btn-info save-data">{{_t('Save')}}</a></td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>
<script>
    $('.date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true
    });
</script>


