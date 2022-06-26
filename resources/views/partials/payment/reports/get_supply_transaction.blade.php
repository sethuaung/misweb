<table class="table table-bordered responsive">
    <thead>
    <tr>
        <th>{{_t('No')}}</th>
        <th nowrap>{{_t('Purchase Type')}}</th>
        <th>{{_t('Supplier')}}</th>
        <th nowrap>{{_t('Reference No')}}</th>
        <th>{{_t('Warehouse')}}</th>
        <th>{{_t('Date')}}</th>
        <th>{{_t('Quantity')}}</th>
        <th>{{_t('G-Total')}}</th>
        <th>{{_t('View')}}</th>
    </tr>
    </thead>
    <tbody>
    <?php $nno = 0; ?>
    @if($rows != null)
    @foreach($rows as $row)
        @php
            $date = new DateTime($row->p_date);
        $nno++;
        @endphp
        <tr>
            <td class="center">{{$nno}}</td>
            <td nowrap>{{$row->purchase_type}}</td>
            <td nowrap>{{optional($row->supplier)->name}}</td>
            <td class="center">{{$row->reference_no}}</td>
            <td nowrap class="center">{{optional($row->warehouse)->name}}</td>
            <td nowrap class="center">{{$date->format('d/M/Y H:i A')}}</td>
            <td class="center">{{$row->total_qty}}</td>
            <td class="right">{{numb_format($row->grand_total,2)}}{{optional($row->currencies)->currency_symbol}}</td>
            <td class="center">

                <a href="{{url("report/bill-list-pop/{$row->id}")}}" data-remote="false" data-toggle="modal"
                                  data-target="#show-purchase-detail" class="btn btn-default btn-xs"><span><i class="fa fa-eye"></i></span></a>
            </td>
        </tr>
    @endforeach
    @endif

    @if($s_payment != null)
    @foreach($s_payment as $row)
        @php
            $nno++;
                $date = new DateTime($row->payment_date);
        @endphp
        <tr>
            <td class="center">{{$nno}}</td>
            <td nowrap>Payment</td>
            <td nowrap>{{optional(\App\Models\Supply::find($row->supplier_id))->name}}</td>
            <td class="center">{{$row->reference_no}}</td>
            <td nowrap class="center">-</td>
            <td nowrap class="center">{{$date->format('d/M/Y H:i A')}}</td>
            <td class="center">-</td>
            <td class="right">{{numb_format($row->total_amount_to_used,2)}}</td>
            <td class="center">

                <a href="{{url("admin/pay-bill-receipt/{$row->id}")}}" data-remote="false" data-toggle="modal"
                                  data-target="#show-purchase-detail" class="btn btn-default btn-xs"><span><i class="fa fa-eye"></i></span></a>
            </td>
        </tr>
    @endforeach
    @endif

    @if($s_deposit != null)
    @foreach($s_deposit as $row)
        @php
            $nno++;
                $date = new DateTime($row->deposit_date);
        @endphp
        <tr>
            <td class="center">{{$nno}}</td>
            <td nowrap>Deposit</td>
            <td nowrap>{{optional(\App\Models\Supply::find($row->supplier_id))->name}}</td>
            <td class="center">{{$row->reference}}</td>
            <td nowrap class="center">-</td>
            <td nowrap class="center">{{$date->format('d/M/Y H:i A')}}</td>
            <td class="center">-</td>
            <td class="right">{{numb_format($row->balance,2)}}</td>
            <td class="center">

              {{--  <a href="{{url("admin/pay-bill-receipt/{$row->id}")}}" data-remote="false" data-toggle="modal"
                                  data-target="#show-purchase-detail" class="btn btn-default btn-xs"><span><i class="fa fa-eye"></i></span></a>--}}
            </td>
        </tr>
    @endforeach
    @endif


    </tbody>
</table>

<!-- Default bootstrap modal example -->
<div class="modal fade" id="show-purchase-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Purchase</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default glyphicon glyphicon-print"></button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#show-purchase-detail").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-body").load(link.attr("href"));
    });
</script>