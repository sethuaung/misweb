<?php
$rows = \App\Models\ApTrain::selectRaw("supplier_id,currency_id,sum(amount) as bal")
    ->groupBy('supplier_id', 'currency_id')->get();

$credits = \App\Models\ApTrain::selectRaw("supplier_id,sum(amount_deduct) as balance")
    //->where('supplier_id',$supplier_id)
    //->whereIn('train_type',['order','purchase-return'])
    ->groupBy('supplier_id')
    ->havingRaw('abs(sum(amount_deduct)) >=0.5')
    ->get();

$arr_cd = [];
if($credits != null){
foreach ($credits as $cd){
    $arr_cd[$cd->supplier_id] = $cd->balance;
}
}
$sup_id = isset($sup_id) ? $sup_id : null;
?>
<aside class="main-sidebar tab-pane fade in active" id="home">
    <section class="sidebar box">
        <ul class="nav nav-pills nav-stacked">

            <li>
                <select class="form-control js-select-supplier select2_field-supplier" id="js-select-supplier"
                        data-source="{{url("api/supplier-f")}}" name="s_supplier_id[]" tabindex="-1" aria-hidden="true">

                </select>
            </li>
{{--            <li class="list-sup" id="list-sup">--}}
{{--                @include('payment.inc.list-sup')--}}
{{--            </li>--}}
            <ul class="nav nav-pills nav-stacked" id="supplier-result">
            @if(count($rows)>0)
                <li>
                <table class="table">
                @php
                    $sups = $rows->groupBy('supplier_id');
                @endphp

                @foreach($sups as $s_id => $sup)
                    @if($sup[0]['bal'] > 0)
                            <tr>
                                <td style="padding: 5px">
                                    <a href="#" class="show-detail-supply" data-supply_id="{{$s_id}}">
                                        {{optional(optional($sup[0])->suppliers)->name}}
                                        @foreach($sup as $row)
                                            @if($row->bal < 0)
                                                @if (companyReportPart() == 'company.theng_hok_ing')
                                                    <p  class="pull-right bal" data-amount="{{$row->bal}}"
                                                        style="color: #0d6aad;display: none;">{{numb_format($row->bal,0)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @else
                                                    <p  class="pull-right bal" data-amount="{{$row->bal}}"
                                                        style="color: #0d6aad;display: none;">{{numb_format($row->bal,2)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @endif

                                            @else
                                                @if (companyReportPart() == 'company.theng_hok_ing')
                                                    <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                       style="color: red;display: none;">{{numb_format($row->bal,0)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @else
                                                    <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                       style="color: red;display: none;">{{numb_format($row->bal,2)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @endif

                                            @endif
                                            <br>
                                        @endforeach
                                    </a>
                                </td>
                                <td>
                                    <?php $bbal = 0; ?>
                                    @foreach($sup as $row)
                                        @if($row->bal < 0)
                                                @if (companyReportPart() == 'company.theng_hok_ing')
                                                    <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                       style="">{{numb_format($row->bal,0)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @else
                                                    <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                       style="">{{numb_format($row->bal,2)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                    </p>
                                                @endif

                                        @else
                                            @if (companyReportPart() == 'company.theng_hok_ing')
                                                <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                   style="">{{numb_format($row->bal,0)}}{{optional(optional($row)->currency)->currency_symbol}}
                                                </p>
                                            @else
                                                <p class="pull-right bal" data-amount="{{$row->bal}}"
                                                   style="">{{numb_format($row->bal,isRound())}}{{optional(optional($row)->currency)->currency_symbol}}
                                                </p>
                                            @endif
                                        @endif
                                        <?php $bbal = $row->bal; ?>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                            <?php
                            $cdit = isset($arr_cd[$s_id])?$arr_cd[$s_id]:0;
                            ?>
                            <tr>
                                <td style="text-align: right"><span class="show-detail-supply">Deposit</span></td>
                                <td style="text-align: right">
                                    <span class="show-detail-supply">
                                    @if (companyReportPart() == 'company.theng_hok_ing')
                                        {{ numb_format($cdit,0) }}{{optional(optional($row)->currency)->currency_symbol}}
                                    @else
                                        {{ numb_format($cdit,isRound()) }}{{optional(optional($row)->currency)->currency_symbol}}
                                    @endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;border-bottom: 1px solid #ccc;"><span class="show-detail-supply">Ending</span></td>
                                <td style="text-align: right; border-bottom: 1px solid #ccc; color: red;">
                                    <span class="show-detail-supply">
                                    @if (companyReportPart() == 'company.theng_hok_ing')
                                        <b>{{ numb_format($bbal-$cdit,0) }}{{optional(optional($row)->currency)->currency_symbol}}</b>
                                    @else
                                        <b>{{ numb_format($bbal-$cdit,isRound()) }}{{optional(optional($row)->currency)->currency_symbol}}</b>
                                    @endif
                                    </span>
                                </td>
                            </tr>
                    @endif
                @endforeach
                </table>
                 </li>
            @endif
            </ul>
        </ul>
    </section>

    <!-- /.sidebar -->
</aside>

<aside class="main-sidebar tab-pane fade" id="menu1" style="display: none">
    <section class="sidebar box">
        <ul class="nav nav-pills nav-stacked">
            <li><a class="l-tran-type" href="#" data-type="order">order</a></li>
            <li><a class="l-tran-type" href="#" data-type="bill">bill</a></li>
            <li><a class="l-tran-type" href="#" data-type="bill-received">bill-received</a></li>
            <li><a class="l-tran-type" href="#" data-type="purchase-return">purchase-return</a></li>
            <li><a class="l-tran-type" href="#" data-type="deposit">deposit</a></li>
            <li><a class="l-tran-type" href="#" data-type="open">open</a></li>
            <li><a class="l-tran-type" href="#" data-type="payment">payment</a></li>
        </ul>
    </section>

    <!-- /.sidebar -->
</aside>
