<?php
$sup_id = isset($id) ? $id : null;
?>

@if($sup_id != null && $sup_id > 0)
    <?php
    $row = \App\Models\ApTrain::selectRaw("supplier_id,currency_id,sum(amount) as bal")
        ->where('supplier_id', $sup_id)
        ->first();
    $s = \App\Models\Supply::find($sup_id);
    $credit = \App\Models\ApTrain::selectRaw("supplier_id,sum(amount_deduct) as balance")
        //->where('supplier_id',$supplier_id)
        ->where('supplier_id', $sup_id)
        ->havingRaw('abs(sum(amount_deduct)) >=0.5')
        ->first();
    ?>

    <li>
        <table class="table">
            @php
                $s = \App\Models\Supply::find($sup_id);
            @endphp

            @if($row->bal > 0)
                <tr>
                    <td style="padding: 5px">
                        <a href="#" class="show-detail-supply" data-supply_id="{{$sup_id}}">
                            {{optional($s)->name}}
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
                        </a>
                    </td>
                    <td>
                        <?php $bbal = 0; ?>
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
                                       style="">{{numb_format($row->bal,2)}}{{optional(optional($row)->currency)->currency_symbol}}
                                    </p>
                                @endif
                            @endif
                            <?php $bbal = $row->bal; ?>
                            <br>
                    </td>
                </tr>
                <?php
                $cdit = optional($credit)->balance>0?optional($credit)->balance:0;
                ?>
                <tr>
                    <td style="text-align: right"><span class="show-detail-supply">Deposit</span></td>
                    <td style="text-align: right">
                                    <span class="show-detail-supply">
                                    @if (companyReportPart() == 'company.theng_hok_ing')
                                            {{ numb_format($cdit,0) }}{{optional(optional($row)->currency)->currency_symbol}}
                                        @else
                                            {{ numb_format($cdit,2) }}{{optional(optional($row)->currency)->currency_symbol}}
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
                                            <b>{{ numb_format($bbal-$cdit,2) }}{{optional(optional($row)->currency)->currency_symbol}}</b>
                                        @endif
                                    </span>
                    </td>
                </tr>
            @endif
        </table>
    </li>
 @else
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
                                               style="">{{numb_format($row->bal,2)}}{{optional(optional($row)->currency)->currency_symbol}}
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
                                            {{ numb_format($cdit,2) }}{{optional(optional($row)->currency)->currency_symbol}}
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
                                            <b>{{ numb_format($bbal-$cdit,2) }}{{optional(optional($row)->currency)->currency_symbol}}</b>
                                        @endif
                                    </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </li>
    @endif
@endif
