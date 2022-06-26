<?php
    $start_date = $start_date ?? null;
    $end_date = $end_date ?? null;
    $branch_id = $branch_id ?? [];
?>
<div id="DivIdToPrint">
    @include('partials.reports.header',['report_name'=>'Balance Sheet','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @foreach($branch_id as $b_id)
                    <?php
                        $branch = \App\Models\Branch::find($b_id);
                    ?>
                    <th>{{optional($branch)->title}}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>100001</td>
                <td>Fixed Assets</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>100101</td>
                <td>Intangible Assets</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>100102</td>
                <td></td>
                <td style="color: red">Formation Expenses</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-95-100-001</td>
                <td style="color: red">100120</td>
                <td>Capitalised Business Expansion Costs</td>
                <?php
                    $arr1 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-95-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr1,$branch_id,1); !!}

            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-95-100-001</td>
                <td style="color: red">100130</td>
                <td>Capitalised Formation acct amortization</td>
                <?php
                $arr2 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-95-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr2,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>100200</td>
                <td></td>
                <td style="color: red">Software Cap</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-05-200-001</td>
                <td style="color: red">100210</td>
                <td >Capitalised Software - Cost</td>
                <?php
                $arr3 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-05-200-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr3,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-920-002</td>
                <td style="color: red">100220</td>
                <td>Capitalised Software - acct amortization</td>
                <?php
                $arr4 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-94-920-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr4,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>100300</td>
                <td></td>
                <td style="color: red">Other Intangible Assets</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-95-300-001</td>
                <td style="color: red">100700</td>
                <td>Other Intangible Assets</td>
                <?php
                $arr5 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-95-300-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr5,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-910-002</td>
                <td style="color: red">100710</td>
                <td>Other Intangible Assets-acct amortization</td>
                <?php
                $arr6 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-910-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr6,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Intangible Assets</td>
                <td></td>
                <?php
                    $total_arr1 = [$arr1,$arr2,$arr3,$arr4,$arr5,$arr6];
                    $t1 = \App\Helpers\ACC::groupTotal($total_arr1,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t1,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>105000</td>
                <td>Tangible Assets</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>105001</td>
                <td></td>
                <td style="color: red">Land</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>105100</td>
                <td></td>
                <td style="color: red">Buildings</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-93-100-001</td>
                <td style="color: red">105110</td>
                <td>Buildings - Cost</td>
                <?php
                $arr7 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-93-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr7,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-001</td>
                <td style="color: red">105120</td>
                <td>Building - acct depreciation</td>
                <?php
                $arr8 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr8,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>107000</td>
                <td></td>
                <td style="color: red">Furniture Fixtures & Fittings</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-93-200-001</td>
                <td style="color: red">107010</td>
                <td>Furnitures Fixtures & Fittings - Cost</td>

                <?php
                $arr9 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-93-200-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr9,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-002</td>
                <td style="color: red">107020</td>
                <td>Furnitures  Furniture & Fittings - acct depreciation</td>
                <?php
                $arr10 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr10,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>107300</td>
                <td></td>
                <td style="color: red">Electronic and Electrical equipment</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-93-300-001</td>
                <td style="color: red">107310</td>
                <td>General Electronics - Cost</td>
                <?php
                $arr11 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-93-300-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr11,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-003</td>
                <td style="color: red">107390</td>
                <td>General Electronics - accumulated depreciation</td>
                <?php
                $arr12 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr12,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>107500</td>
                <td></td>
                <td style="color: red">Computer & IT Equipments</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-93-400-001</td>
                <td style="color: red">107510</td>
                <td>Computer & IT Equipments - Cost</td>

                <?php
                $arr13 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-93-400-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr13,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-004</td>
                <td style="color: red">107520</td>
                <td>Computer & IT Equipments - acct depreciation</td>
                <?php
                $arr14 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr14,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>108000</td>
                <td></td>
                <td style="color: red">Motor Vehicles</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">108010</td>
                <td>Motor Vehicles - Cost</td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-005</td>
                <td style="color: red">108040</td>
                <td>Motor Vehicles - acct depreciation</td>
                <?php
                $arr15 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr15,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td>108500</td>
                <td></td>
                <td style="color: red">Tools & Equipments</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-93-600-001</td>
                <td style="color: red">108510</td>
                <td>Tools & Equipment - Cost</td>

                <?php
                $arr16 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-93-600-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr16,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-94-400-006</td>
                <td style="color: red">108520</td>
                <td>Tools & Equipment - acct depreciation</td>
                <?php
                $arr17 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-94-400-006',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr17,$branch_id,1); !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Tangible Assets</td>
                <td></td>
                <?php
                $total_arr2 = [$arr7,$arr8,$arr9,$arr10,$arr11,$arr12,$arr13,$arr14,$arr15,$arr16,$arr17];
                $t2 = \App\Helpers\ACC::groupTotal($total_arr2,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t2,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>Total Fixed Assets</td>
                <td></td>
                <td></td>
                <td></td>
                <?php
                $total_f = [$t1,$t2];
                $t_f = \App\Helpers\ACC::groupTotal($total_f,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_f,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>150000</td>
                <td>Current Assets</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>150001</td>
                <td></td>
                <td style="color: red">Cash And Bank Equivalents</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>150100</td>
                <td></td>
                <td style="color: red">Bank Account</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">150101</td>--}}
{{--                <td>KBZ Bank (519-503-000-017-608-2)(HO) (USD)</td>--}}
{{--                <td colspan="{{count($branch_id)+1}}"></td>--}}
{{--            </tr>--}}
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-001</td>
                <td style="color: red">150102</td>
                <td style="color: red">KBZ Bank (CC) (027-103-027-030-800-01) HO</td>
                <?php
                $arr18 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr18,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-002</td>
                <td style="color: red">150103</td>
                <td style="color: red">KBZ Bank (169-503-169-000-551-01)(HO) </td>
                <?php
                $arr19 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr19,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-003</td>
                <td style="color: red">150153</td>
                <td style="color: red">KBZ Bank (CC)(169-103-169-000-551-01)(HO) </td>
                <?php
                $arr20 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr20,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-004</td>
                <td style="color: red">150110</td>
                <td style="color: red">KBZ Bank (049-503-049-007-888-01) </td>
                <?php
                $arr21 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr21,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-005</td>
                <td style="color: red">150111</td>
                <td style="color: red">KBZ Bank (CC) (049-103-049-007-872-01) </td>
                <?php
                $arr22 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr22,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-006</td>
                <td style="color: red">150137</td>
                <td style="color: red">KBZ Bank (056-113-000-007-7)</td>
                <?php
                $arr23 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-006',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr23,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-007</td>
                <td style="color: red">150115</td>
                <td style="color: red">KBZ Bank (164-502-999-336-419-01)</td>
                <?php
                $arr24 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr24,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-008</td>
                <td style="color: red">150116</td>
                <td style="color: red">KBZ Bank (CC) (164-103-164-006-936-01)</td>
                <?php
                $arr25 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-008',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr25,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-009</td>
                <td style="color: red">150127</td>
                <td style="color: red">KBZ Bank (164-503-164-006-936-01)</td>
                <?php
                $arr26 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-009',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr26,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-010</td>
                <td style="color: red">150108</td>
                <td style="color: red">KBZ Bank (352-503-352-000-779-01) </td>
                <?php
                $arr27 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-010',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr27,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-011</td>
                <td style="color: red">150109</td>
                <td style="color: red">KBZ Bank (CC) (352-103-352-000-779-01)</td>
                <?php
                $arr28 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-011',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr28,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-012</td>
                <td style="color: red">150120</td>
                <td style="color: red">KBZ Bank (CC) (025-103-025-015-841-01) </td>

                <?php
                $arr29 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-012',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr29,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-013</td>
                <td style="color: red">150121</td>
                <td style="color: red">KBZ Bank (025-113-000-039-0)</td>
                <?php
                $arr30 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-013',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr30,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-014</td>
                <td style="color: red">150122</td>
                <td style="color: red">KBZ Bank (025-503-025-015-841-01)</td>
                <?php
                $arr31 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-014',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr31,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-015</td>
                <td style="color: red">150106</td>
                <td style="color: red">KBZ Bank(312-503-174-002-497-01)</td>
                <?php
                $arr32 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-015',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr32,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-016</td>
                <td style="color: red">150107</td>
                <td style="color: red">KBZ Bank(CC) (312-103-174-002-497-01)</td>
                <?php
                $arr33 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-016',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr33,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-017</td>
                <td style="color: red">150125</td>
                <td style="color: red">KBZ Bank (174-503-174-002-500-01)</td>
                <?php
                $arr34 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-017',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr34,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-018</td>
                <td style="color: red">150126</td>
                <td style="color: red">KBZ Bank (CC) (174-103-174-002-497-01)</td>
                <?php
                $arr35 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-018',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr35,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-019</td>
                <td style="color: red">150131</td>
                <td style="color: red">KBZ Bank (168-173-000-001-9)</td>
                <?php
                $arr36 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-019',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr36,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-020</td>
                <td style="color: red">150132</td>
                <td style="color: red">KBZ Bank (168-503-168-003-751-01)</td>
                <?php
                $arr37 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-020',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr37,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-021</td>
                <td style="color: red">150133</td>
                <td style="color: red">KBZ Bank (CC) (168-103-168-003-749-01)</td>
                <?php
                $arr38 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-021',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr38,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-022</td>
                <td style="color: red">150135</td>
                <td style="color: red">KBZ Bank (067-503-067-004-937-01)</td>
                <?php
                $arr39 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-022',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr39,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-023</td>
                <td style="color: red">150136</td>
                <td style="color: red">KBZ Bank (CC)  (067-103-067-004-924-01)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr40 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-023',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr40,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-024</td>
                <td style="color: red">150138</td>
                <td style="color: red">KBZ Bank (CC) (056-113-000-007-7)</td>
                <?php
                $arr41 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-024',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr41,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-025</td>
                <td style="color: red">150139</td>
                <td style="color: red">KBZ Bank (056-503-056-005-467-01)</td>
                <?php
                $arr42 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-025',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr42,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-026</td>
                <td style="color: red">150140</td>
                <td style="color: red">KBZ Bank (092-503-092-004-060-01)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr43 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-026',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr43,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-027</td>
                <td style="color: red">150141</td>
                <td style="color: red">KBZ Bank (CC) (092-103-092-004-056-01) </td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr44 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-027',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr44,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-028</td>
                <td style="color: red">150145</td>
                <td style="color: red">KBZ Bank (307-503-307-000-574-01)</td>
                <?php
                $arr45 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-028',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr45,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-029</td>
                <td style="color: red">150146</td>
                <td style="color: red">KBZ Bank (CC) (307-103-307-000-574-01)</td>
                <?php
                $arr46 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-029',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr46,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-030</td>
                <td style="color: red">150147</td>
                <td style="color: red">KBZ Bank (031-503-031-010-203-01)</td>
                <?php
                $arr47 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-030',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr47,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-031</td>
                <td style="color: red">150148</td>
                <td style="color: red">KBZ Bank (CC) (031-103-031-010-197-01)</td>
                <?php
                $arr48 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-031',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr48,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-032</td>
                <td style="color: red">150150</td>
                <td style="color: red">KBZ Bank (129-503-129-002-722-01)</td>
                <?php
                $arr49 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-032',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr49,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-033</td>
                <td style="color: red">150151</td>
                <td style="color: red">KBZ Bank (CC) (129-103-129-002-720-01)</td>
                <?php
                $arr50 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-033',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr50,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-034</td>
                <td style="color: red">150142</td>
                <td style="color: red">KBZ Bank (058-503-058-011-982-01) </td>
                <?php
                $arr51 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-034',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr51,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-035</td>
                <td style="color: red">150143</td>
                <td style="color: red">KBZ Bank (CC) (058-103-058-011-982-01)</td>
                <?php
                $arr52 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-035',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr52,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-036</td>
                <td style="color: red">150105</td>
                <td style="color: red">KBZ Bank (341-503-341-000-709-01)</td>
                <?php
                $arr53 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-036',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr53,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-037</td>
                <td style="color: red">150104</td>
                <td style="color: red">KBZ Bank (CC) (341-103-341-000-709-01)</td>
                <?php
                $arr54 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-037',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr54,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-038</td>
                <td style="color: red">150152</td>
                <td style="color: red">KBZ Bank (035-503-035-007-946-01)</td>
                <?php
                $arr55 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-038',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr55,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-039</td>
                <td style="color: red">150154</td>
                <td style="color: red">KBZ Bank (CC) (035-103-035-007-946-01)</td>
                <?php
                $arr56 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-039',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr56,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-040</td>
                <td style="color: red">150361</td>
                <td style="color: red">KBZ Bank (196-503-196-003-904-01)</td>
                <?php
                $arr57 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-040',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr57,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-041</td>
                <td style="color: red">150362</td>
                <td style="color: red">KBZ Bank (CC)  (196-103-196-003-904-01)</td>
                <?php
                $arr58 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-041',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr58,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-042</td>
                <td style="color: red">150112</td>
                <td style="color: red">KBZ (087-503-087-015-045-01)</td>
                <?php
                $arr59 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-042',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr59,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-043</td>
                <td style="color: red">150113</td>
                <td style="color: red">KBZ (CC) (087-103-087-015-045-01</td>
                <?php
                $arr60 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-043',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr60,$branch_id,1); !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-045</td>
                <td style="color: red">150510</td>
                <td style="color: red">AYA Bank (007-822-401-000-884-5) (HO)</td>
                <?php
                $arr61 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-045',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr61,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-046</td>
                <td style="color: red">150511</td>
                <td style="color: red">AYA Bank (CC) (007-810-301-000-755-4) (HO)</td>
                <?php
                $arr62 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-046',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr62,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-047</td>
                <td style="color: red">150512</td>
                <td style="color: red">AYA Bank (015-122-401-000-116-5)</td>
                <?php
                $arr63 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-047',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr63,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-048</td>
                <td style="color: red">150513</td>
                <td style="color: red">AYA Bank (CC) (015-110-301-000-034-0)</td>
                <?php
                $arr64 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-048',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr64,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-049</td>
                <td style="color: red">150160</td>
                <td style="color: red">AYA Bank (016-222-401-000-055-9)</td>
                <?php
                $arr65 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-049',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr65,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-050</td>
                <td style="color: red">150161</td>
                <td style="color: red">AYA Bank (CC) (016-210-301-000-010-6)</td>
                <?php
                $arr66 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-050',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr66,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-051</td>
                <td style="color: red">150190</td>
                <td style="color: red">AYA Bank (000-722-401-000-491-9)</td>
                <?php
                $arr67 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-051',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr67,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-052</td>
                <td style="color: red">150519</td>
                <td style="color: red">AYA Bank (CC) (000-710-201-000-725-4)</td>
                <?php
                $arr68 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-052',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr68,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-053</td>
                <td style="color: red">150514</td>
                <td style="color: red">AYA Bank (005-822-401-000-150-1)</td>
                <?php
                $arr69 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-053',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr69,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-054</td>
                <td style="color: red">150515</td>
                <td style="color: red">AYA Bank (CC) (005-810-301-000-003-4)</td>
                <?php
                $arr70 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-054',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr70,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-055</td>
                <td style="color: red">150501</td>
                <td style="color: red">AYA Bank (008-822-401-000-138-3)</td>
                <?php
                $arr71 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-055',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr71,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-056</td>
                <td style="color: red">150502</td>
                <td style="color: red">AYA Bank (CC) (008-810-201-000-120-7)</td>
                <?php
                $arr72 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-056',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr72,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-057</td>
                <td style="color: red">150503</td>
                <td style="color: red">AYA Bank (008-820-201-001-948-3)</td>
                <?php
                $arr73 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-057',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr73,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-058</td>
                <td style="color: red">150520</td>
                <td style="color: red">AYA Bank (002-810-301-000-032-4)</td>
                <?php
                $arr74 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-058',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr74,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-059</td>
                <td style="color: red">150521</td>
                <td style="color: red">AYA Bank (CC) (002-822-401-000-094-0)</td>
                <?php
                $arr75 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-059',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr75,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-060</td>
                <td style="color: red">150506</td>
                <td style="color: red">AYA Bank (002-622-401-000-188-8)</td>
                <?php
                $arr76 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-060',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr76,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-061</td>
                <td style="color: red">150507</td>
                <td style="color: red">AYA Bank (CC) (002-610-301-000-098-8)</td>

                <?php
                $arr77 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-061',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr77,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-062</td>
                <td style="color: red">150179</td>
                <td style="color: red">AYA Bank (018-622-401-000-036-0)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr78 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-062',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr78,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-063</td>
                <td style="color: red">150180</td>
                <td style="color: red">AYA Bank (CC) (018-610-301-000-016-4)</td>
                <?php
                $arr79 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-063',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr79,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-064</td>
                <td style="color: red">150508</td>
                <td style="color: red">AYA Bank (014-722-401-000-092-4)</td>

                <?php
                $arr80 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-064',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr80,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-065</td>
                <td style="color: red">150509</td>
                <td style="color: red">AYA Bank (CC) (014-710-301-000-027-8)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr81 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-065',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr81,$branch_id,1); !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">150350</td>--}}
{{--                <td style="color: red">AGD Bank (325-121-003-863-4) (HO)</td>--}}
{{--                <td colspan="{{count($branch_id)+1}}"></td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">150351</td>--}}
{{--                <td style="color: red">AGD Bank (CC) (325-111-001-346-9) (HO)</td>--}}
{{--                <td colspan="{{count($branch_id)+1}}"></td>--}}
{{--            </tr>--}}
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-068</td>
                <td style="color: red">151502</td>
                <td style="color: red">GTB Bank-C-(070-113-000-002-8) BR002</td>
                <?php
                $arr82 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-068',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr82,$branch_id,1); !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-069</td>
                <td style="color: red">150201</td>
                <td style="color: red">CB Bank(011-660-080-000-007-8)</td>
                <?php
                $arr83 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-069',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr83,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-070</td>
                <td style="color: red">150202</td>
                <td style="color: red">CB Bank (CC) (011-610-050-000-001-9)</td>
                <?php
                $arr84 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-070',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr84,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-071</td>
                <td style="color: red">150205</td>
                <td style="color: red">CB Bank (009-160-080-000-096-5)</td>
                <?php
                $arr85 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-071',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr85,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-072</td>
                <td style="color: red">150206</td>
                <td style="color: red">CB Bank (CC) (009-110-050-000-012-4)</td>
                <?php
                $arr86 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-072',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr86,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-073</td>
                <td style="color: red">150203</td>
                <td style="color: red">CB Bank(007-060-080-000-081-7)</td>
                <?php
                $arr87 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-073',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr87,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-074</td>
                <td style="color: red">150204</td>
                <td style="color: red">CB Bank (CC) (007-010-050-000-005-1)</td>
                <?php
                $arr88 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-074',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr88,$branch_id,1); !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-075</td>
                <td style="color: red">150252</td>
                <td style="color: red">MOB Bank(000-260-030-000-1098) (HO)</td>
                <?php
                $arr89 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-075',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr89,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-076</td>
                <td style="color: red">150251</td>
                <td style="color: red">MOB Bank (CC) (000-210-030-001-2475) (HO) </td>
                <?php
                $arr90 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-076',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr90,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-077</td>
                <td style="color: red">150250</td>
                <td style="color: red">MOB Bank(CC)(203-113-000-002-2)</td>
                <?php
                $arr91 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-077',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr91,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-078</td>
                <td style="color: red">150260</td>
                <td style="color: red">MOB Bank(203-123-000-003-8)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr92 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-078',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr92,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-079</td>
                <td style="color: red">150253</td>
                <td style="color: red">MOB Bank (003-260-530-000-001-9)</td>
                <?php
                $arr93 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-079',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr93,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-080</td>
                <td style="color: red">150254</td>
                <td style="color: red">MOB Bank (CC) (003-210-030-000-002-7)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr94 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-080',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr94,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-081</td>
                <td style="color: red">150255</td>
                <td style="color: red">MOB Bank(000-210-110-000-136-8) (HO)Overdraft</td>
                <?php
                $arr95 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-081',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr95,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Bank </td>
                <td></td>
                <?php
                $total_arr3 = [
                    $arr18,$arr19,$arr20,$arr21,$arr22,$arr23,$arr24,$arr25,$arr26,$arr27,$arr28,$arr29,$arr30,
                    $arr31,$arr32,$arr33,$arr34,$arr35,$arr36,$arr37,$arr38,$arr39,$arr40,$arr41,$arr42,$arr43,
                    $arr44,$arr45,$arr46,$arr47,$arr48,$arr49,$arr50,$arr51,$arr52,$arr53,$arr54,$arr55,$arr56,
                    $arr57,$arr58,$arr59,$arr60,$arr61,$arr62,$arr63,$arr64,$arr65,$arr66,$arr67,$arr68,$arr69,
                    $arr70,$arr71,$arr72,$arr73,$arr74,$arr75,$arr76,$arr78,$arr79,$arr80,$arr81,$arr82,$arr83,
                    $arr84,$arr85,$arr86,$arr87,$arr88,$arr89,$arr90,$arr91,$arr92,$arr93,$arr94,$arr95
                ];
                $t_c = \App\Helpers\ACC::groupTotal($total_arr3,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_c,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>153500</td>
                <td></td>
                <td style="color: red">CASH IN HAND</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-001</td>
                <td style="color: red">153501</td>
                <td style="color: red">Cash Account (HO)</td>
                <?php
                $arr96 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr96,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-002</td>
                <td style="color: red">153510</td>
                <td style="color: red">01 Cash Account (Kyauk Se-2)</td>
                <?php
                $arr97 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr97,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-003</td>
                <td style="color: red">153515</td>
                <td style="color: red">02 Cash Account(Kyaung Gone)</td>
                <?php
                $arr98 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr98,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-004</td>
                <td style="color: red">153520</td>
                <td style="color: red">03 Cash Account(Shwe Bo-1)</td>
                <?php
                $arr99 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr99,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-005</td>
                <td style="color: red">153525</td>
                <td style="color: red">04 Cash Account(Maddaya-1)</td>
                <?php
                $arr100 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr100,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-006</td>
                <td style="color: red">153530</td>
                <td style="color: red">05 Cash Account(Kyauk Se-1)</td>
                <?php
                $arr101 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-006',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr101,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-007</td>
                <td style="color: red">153535</td>
                <td style="color: red">06 Cash Account(Kyone Pyaw)</td>
                <?php
                $arr102 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr102,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-008</td>
                <td style="color: red">153540</td>
                <td style="color: red">07 Cash Account(MonYwar)</td>
                <?php
                $arr103 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-008',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr103,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-009</td>
                <td style="color: red">153545</td>
                <td style="color: red">08 Cash Account(WetLet)</td>
                <?php
                $arr104 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-009',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr104,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-010</td>
                <td style="color: red">153550</td>
                <td style="color: red">09 Cash Account(Sint Kaing)</td>
                <?php
                $arr105 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-010',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr105,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-011</td>
                <td style="color: red">153555</td>
                <td style="color: red">10 Cash Account(KuMe)</td>
                <?php
                $arr106 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-011',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr106,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-012</td>
                <td style="color: red">153560</td>
                <td style="color: red">11 Cash Account(Maddaya-2)</td>
                <?php
                $arr107 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-012',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr107,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-013</td>
                <td style="color: red">153565</td>
                <td style="color: red">12 Cash Account(Pyin Oo Lwin)</td>
                <?php
                $arr108 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-014',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr108,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-014</td>
                <td style="color: red">153570</td>
                <td style="color: red">13 Cash Account(Nyaung  Oo)</td>
                <?php
                $arr109 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-014',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr109,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-015</td>
                <td style="color: red">153575</td>
                <td style="color: red">14 Cash Account(Shwe Bo-2)</td>
                <?php
                $arr110 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-015',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr110,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-016</td>
                <td style="color: red">153580</td>
                <td style="color: red">15 Cash Account(Meik Hti La)</td>
                <?php
                $arr111 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-016',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr111,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-017</td>
                <td style="color: red">153585</td>
                <td style="color: red">16 Cash Account(Eain Me)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr112 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-017',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr112,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-018</td>
                <td style="color: red">153590</td>
                <td style="color: red">17 Cash Account(Myin Gyan 1)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr113 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-018',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr113,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-019</td>
                <td style="color: red">153595</td>
                <td style="color: red">18 Cash Account(Wun Dwin)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr114 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-019',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr114,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-020</td>
                <td style="color: red">153600</td>
                <td style="color: red">19 Cash Account(TaDaOo)</td>
                <td class="text-center">-</td>
            </tr>
            <?php
            $arr115 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-020',$branch_id,$end_date);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr115,$branch_id,1); !!}
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-021</td>
                <td style="color: red">153605</td>
                <td style="color: red">20 Cash Account(Sagaing)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr116 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-021',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr116,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-022</td>
                <td style="color: red">153610</td>
                <td style="color: red">21 Cash Account(Pyay)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr117 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-022',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr117,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-024</td>
                <td style="color: red">153620</td>
                <td style="color: red">22 Cash Account(Aung Ban)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr118 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-024',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr118,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-025</td>
                <td style="color: red">153625</td>
                <td style="color: red">23 Cash Account(Taungoo)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr119 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-025',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr119,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-026</td>
                <td style="color: red">153626</td>
                <td style="color: red">24 Cash Account(Paungde)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr120 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-026',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr120,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-11-100-027</td>
                <td style="color: red">153627</td>
                <td style="color: red">25 Cash Account(Amayapura)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr121 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-11-100-027',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr121,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Cash </td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr4 = [
                    $arr96,$arr97,$arr98,$arr99,$arr100,$arr101,$arr102,$arr103,$arr104,$arr105,$arr106,$arr107,$arr108,$arr109,$arr110,
                    $arr112,$arr113,$arr114,$arr115,$arr116, $arr117,$arr118,$arr119,$arr120,$arr121
                ];
                $t_b = \App\Helpers\ACC::groupTotal($total_arr4,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_b,$branch_id,1) !!}
            </tr>
            <tr>
                <td></td>
                <td>Total Cash And Bank Equivalents</td>
                <td></td>
                <td></td>
                <td></td>
                <?php
                $total_b_c = [
                    $t_b,
                    $t_c
                ];
                $t_b_c = \App\Helpers\ACC::groupTotal($total_b_c,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_b_c,$branch_id,1) !!}

            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>157000</td>
                <td></td>
                <td style="color: red">CASH  IN  TRANSIT</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-001</td>
                <td style="color: red">157001</td>
                <td style="color: red">01 Cash Transit  (Kyauk Se-2)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr122 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr122,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-002</td>
                <td style="color: red">157002</td>
                <td style="color: red">02 Cash Transit (Kyaung Gone)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr123 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr123,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-003</td>
                <td style="color: red">157003</td>
                <td style="color: red">03 Cash Transit (Shwe Bo-1)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr124 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr124,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-004</td>
                <td style="color: red">157004</td>
                <td style="color: red">04 Cash Transit (Maddaya-1)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr125 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr125,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-006</td>
                <td style="color: red">157005</td>
                <td style="color: red">05 Cash Transit (Kyauk Se-1)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr126 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr126,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-009</td>
                <td style="color: red">157006</td>
                <td style="color: red">06 Cash Transit (Kyone Pyaw)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr127 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-009',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr127,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-007</td>
                <td style="color: red">157007</td>
                <td style="color: red">07 Cash Transit (MonYwar)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr128 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr128,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-008</td>
                <td style="color: red">157008</td>
                <td style="color: red">08 Cash Transit (WetLet)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr129 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-008',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr129,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-005</td>
                <td style="color: red">157009</td>
                <td style="color: red">09 Cash Transit (Sint Kaing)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr130 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr130,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-010</td>
                <td style="color: red">157010</td>
                <td style="color: red">10 Cash Transit (KuMe)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr131 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-010',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr131,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-011</td>
                <td style="color: red">157011</td>
                <td style="color: red">11 Cash Transit (Maddaya-2)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr132 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-011',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr132,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-012</td>
                <td style="color: red">157012</td>
                <td style="color: red">12 Cash Transit (Pyin Oo Lwin)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr133 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-012',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr133,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-013</td>
                <td style="color: red">157013</td>
                <td style="color: red">13 Cash Transit (Nyaung  Oo)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr134 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-013',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr134,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-014</td>
                <td style="color: red">157014</td>
                <td style="color: red">14 Cash Transit (Shwe Bo-2)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                 $arr135= \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-014',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr135,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-015</td>
                <td style="color: red">157015</td>
                <td style="color: red">15 Cash Transit (Meik Hti La)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr136 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-015',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr136,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-016</td>
                <td style="color: red">157016</td>
                <td style="color: red">16 Cash Transit (Eain Me)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr137 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-016',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr137,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-017</td>
                <td style="color: red">157017</td>
                <td style="color: red">17 Cash Transit (Myin Gyan 1)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr138 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-017',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr138,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-018</td>
                <td style="color: red">157018</td>
                <td style="color: red">18 Cash Transit (Wun Dwin)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr139 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-018',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr139,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-019</td>
                <td style="color: red">157019</td>
                <td style="color: red">19 Cash Transit (TaDaOo)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr140 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-019',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr140,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-020</td>
                <td style="color: red">157020</td>
                <td style="color: red">20 Cash Transit (Sagaing)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr141 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-020',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr141,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-021</td>
                <td style="color: red">157021</td>
                <td style="color: red">21 Cash Transit (Pyay)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr142 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-021',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr142,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-022</td>
                <td style="color: red">157022</td>
                <td style="color: red">BR022 Cash Transit (Myin gyan 2)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr143 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-022',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr143,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-023</td>
                <td style="color: red">157023</td>
                <td style="color: red">22 Cash Transit (Aung Ban)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr144 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-023',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr144,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-024</td>
                <td style="color: red">157024</td>
                <td style="color: red">23 Cash Transit (Taungoo)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr145 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-024',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr145,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-025</td>
                <td style="color: red">157025</td>
                <td style="color: red">24 Cash Transit (Amarapura)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr146 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-025',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr146,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-026</td>
                <td style="color: red">157026</td>
                <td style="color: red">25 Cash Transit (Paungde )</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr147 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-026',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr147,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-96-500-050</td>
                <td style="color: red">157099</td>
                <td style="color: red">Cash Transit (H.O)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr148 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-96-500-050',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr148,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Cash  in Transit</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr5 = [
                    $arr122,$arr123,$arr124,$arr125,$arr126,$arr127,$arr128,$arr129,$arr130,$arr131,$arr132,$arr133,$arr134,$arr135,$arr136,$arr137,
                    $arr138,$arr139,$arr140,$arr141,$arr142, $arr143,$arr144,$arr145,$arr145,$arr146,$arr147,$arr148
                ];
                $t_c_t = \App\Helpers\ACC::groupTotal($total_arr5,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_c_t,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>165000</td>
                <td></td>
                <td style="color: red">INVENTORYS</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-045</td>
                <td style="color: red">179900</td>
                <td style="color: red">Inventories in transit</td>
                {{--                <td class="text-center">-</td>--}}
                <?php
                $arr011 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-045',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr011,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-046</td>
                <td style="color: red">179910</td>
                <td style="color: red">Inventories in transit</td>
                {{--                <td class="text-center">-</td>--}}
                <?php
                $arr012 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-046',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr012,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-15-100-047</td>
                <td style="color: red">179940</td>
                <td style="color: red">Stationery Supply and Inventory</td>
                {{--                <td class="text-center">-</td>--}}
                <?php
                $arr013 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-15-100-047',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr013,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Inventorys</td>
                <td></td>
                <?php
                $total_arr011 = [
                    $arr011, $arr012, $arr013
                ];
                $t011 = \App\Helpers\ACC::groupTotal($total_arr011,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t011,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>183000</td>
                <td>ACCOUNT  RECEIVABLES</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>185000</td>
                <td></td>
                <td style="color: red">Service Receivbale</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-001</td>
                <td style="color: red">185810</td>
                <td style="color: red">Service Receivable(General Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr149 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr149,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-002</td>
                <td style="color: red">185820</td>
                <td style="color: red">Service Receivable(Special Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr150 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr150,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-003</td>
                <td style="color: red">185830</td>
                <td style="color: red">Service Receivable(Agri Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr151 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr151,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-004</td>
                <td style="color: red">185840</td>
                <td style="color: red">Service Receivable(Teacher Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr152 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr152,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-005</td>
                <td style="color: red">185850</td>
                <td style="color: red">Service Receivable(Staff Loan Internal)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr153 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr153,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-006</td>
                <td style="color: red">185860</td>
                <td style="color: red">Service Receivable - Staff Loan (External)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr154 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-006',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr154,$branch_id,1); !!}
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">185910</td>--}}
{{--                <td style="color: red">Service Receivable(SME Loan)</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">185920</td>--}}
{{--                <td style="color: red">Service Receivable(Company Loan)</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">185930</td>--}}
{{--                <td style="color: red">Service Receivable(Business  Loan)</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">185950</td>--}}
{{--                <td style="color: red">Service Receivable(Hire Purchase)</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
            <tr>
                <td></td>
                <td></td>
                <td>1-31-100-007</td>
                <td style="color: red">185960</td>
                <td style="color: red">Service Receivable(Micro-Enterprise Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr155 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-31-100-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr155,$branch_id,1); !!}
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">185965</td>--}}
{{--                <td style="color: red">Service Receivable(MKT-Aung Gyi)</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Service Receivbale</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr6 = [
                    $arr149,$arr150,$arr151,$arr152,$arr153,$arr154,$arr55
                ];
                $t_r1 = \App\Helpers\ACC::groupTotal($total_arr6,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r1,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>186400</td>
                <td></td>
                <td style="color: red">Interst Receivable</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-001</td>
                <td style="color: red">186110</td>
                <td style="color: red">Interest Receivbale(General Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr156 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr156,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-002</td>
                <td style="color: red">186120</td>
                <td style="color: red">Interest Receivbale(Special Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr157 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr157,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-003</td>
                <td style="color: red">186130</td>
                <td style="color: red">Interest Receivbale(Agri Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr158 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr158,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-004</td>
                <td style="color: red">186140</td>
                <td style="color: red">Interest Receivbale(Teacher Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr159 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr159,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-007</td>
                <td style="color: red">186150</td>
                <td style="color: red">Interest Receivbale(Staff Loan Internal)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr160 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-007',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr160,$branch_id,1); !!}
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-009</td>
                <td style="color: red">186260</td>
                <td style="color: red">Interest Receivbale(Micro-Enterprise Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr161 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-009',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr161,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-51-100-010</td>
                <td style="color: red">186160</td>
                <td style="color: red">Interest Receivable - Staff Loan (External)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr162 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-51-100-010',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr162,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Interst Receivable</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr7 = [
                    $arr156,$arr157,$arr158,$arr159,$arr160,$arr161,$arr162
                ];
                $t_r2 = \App\Helpers\ACC::groupTotal($total_arr7,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r2,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>189000</td>
                <td></td>
                <td style="color: red">Provisions For Doubtful Debts</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">1-71-200-001</td>
                <td style="color: red">189010</td>
                <td style="color: red">Provisions For Doubtful Debts</td>
                <?php
                $arr163 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-1-71-200-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr163,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total Provisions For Doubtful Debts</td>
                <td style="color: red"></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr8 = [$arr163];
                $t_r3 = \App\Helpers\ACC::groupTotal($total_arr8,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r3,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>190500</td>
                <td style="color: red"></td>
                <td style="color: red">Tax Receivable</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">2-89-800-001</td>
                <td style="color: red">190510</td>
                <td>Advance Income Tax</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr164 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-89-800-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr164,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total Tax Receivable</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr9 = [$arr164];
                $t_r4 = \App\Helpers\ACC::groupTotal($total_arr9,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r4,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            {{--<tr>
                <td></td>
                <td>192200</td>
                <td style="color: red"></td>
                <td style="color: red">Other Receivable Group</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>--}}
            {{--<tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total Other Receivable Group</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>--}}
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>193000</td>
                <td style="color: red"></td>
                <td style="color: red">Prepayments & Accrued Income</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-001</td>
                <td style="color: red">193020</td>
                <td>Prepayment Expenses</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr165 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr165,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-002</td>
                <td style="color: red">193240</td>
                <td>Other Advance Payments</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr166 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr166,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-003</td>
                <td style="color: red">193050</td>
                <td>Advances on Salaries and Wages</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr167 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr167,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-004</td>
                <td style="color: red">193060</td>
                <td>Advances on Travel Expenses</td>
                <?php
                $arr168 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr168,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-005</td>
                <td style="color: red">193120</td>
                <td>Advance Payments to Staff</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr169 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr169,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2-21-500-006</td>
                <td style="color: red">193250</td>
                <td>Prepaid Rent</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr170 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-2-21-500-006',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr170,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Prepayments & Accrued Income</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr10 = [$arr165,$arr166,$arr167,$arr170];
                $t_r5 = \App\Helpers\ACC::groupTotal($total_arr10,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r5,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>Total Current Assets</td>
                <td></td>
                <td style="color: red"></td>
                <td></td>
                <?php
                $total_arr10 = [$t_b_c,$t_c_t,$t011,$t_r1,$t_r2,$t_r3,$t_r4,$t_r5];
                $t_r6 = \App\Helpers\ACC::groupTotal($total_arr10,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r6,$branch_id,1) !!}
            </tr>
            <tr>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>Total Assets</td>
                <td></td>
                <td style="color: red"></td>
                <td></td>
                <?php
                $total_ass = [$t_r6,$t_f];
                $t_ass = \App\Helpers\ACC::groupTotal($total_ass,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_ass,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>CAPITAL AND LIABILITIES</td>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td>CAPITAL AND LIABILITIES</td>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>250000</td>
                <td></td>
                <td style="color: red">Long Term Liabilities</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-33-200-001</td>
                <td style="color: red">250030</td>
                <td>Other loans due >1 year</td>

                <?php
                $arr171 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-33-200-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr171,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-33-200-002</td>
                <td style="color: red">250040</td>
                <td>Bank loans long - term</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr172 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-33-200-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr172,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-33-200-003</td>
                <td style="color: red">250080</td>
                <td>BLong Term (Amount Due to CCM)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr173 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-33-200-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr173,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Long Term Liabilities</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr11 = [$arr171,$arr172,$arr173];
                $tl_t = \App\Helpers\ACC::groupTotal($total_arr11,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($tl_t,$branch_id,-1) !!}
            </tr>
            <tr>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>200001</td>
                <td></td>
                <td style="color: red">CURRENT LIABILITIES</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>203000</td>
                <td></td>
                <td style="color: red">Service Creditors(Saving)</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-22-900-001</td>
                <td style="color: red">203851</td>
                <td>Refundable Saving (General Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr174 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-22-900-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr174,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-22-900-002</td>
                <td style="color: red">203852</td>
                <td>Voluntary Savings (General Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr175 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-22-900-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr175,$branch_id,-1); !!}
            </tr>
           {{-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td>Total</td>
                <td></td>
            </tr>--}}
            <tr>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-42-200-001</td>
                <td style="color: red">203853</td>
                <td>Interest Payable Saving (General Loan)</td>
{{--                <td class="text-center"></td>--}}
                <?php
                $arr176 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-42-200-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr176,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-42-200-002</td>
                <td style="color: red">203854</td>
                <td>Interest Payable Voluntary(General Loan)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr177 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-42-200-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr177,$branch_id,-1); !!}
            </tr>
           {{-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td>Total</td>
                <td colspan="{{count($branch_id)+1}}"></td>

            </tr>--}}
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Service Creditors(Saving)</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr12 = [$arr174,$arr175,$arr176,$arr177];
                $ts_c = \App\Helpers\ACC::groupTotal($total_arr12,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($ts_c,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>210000</td>
                <td></td>
                <td style="color: red">Other Current Liabilities</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
{{--            <tr>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td></td>--}}
{{--                <td style="color: red">210001</td>--}}
{{--                <td>Bank Loans & Overdrafts</td>--}}
{{--                <td class="text-center">-</td>--}}
{{--            </tr>--}}
            <tr>
                <td></td>
                <td></td>
                <td>3-81-100-002</td>
                <td style="color: red">250020</td>
                <td>Amount Due to CCM</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr178 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-81-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr178,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-81-100-003</td>
                <td style="color: red">210010</td>
                <td>Bank Loans due <1 year</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr179 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-81-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr179,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-81-100-004</td>
                <td style="color: red">210030</td>
                <td>Bank overdrafts</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr180 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-81-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr180,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-81-100-005</td>
                <td style="color: red">210040</td>
                <td>Bank loans & mortages ct</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr181 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-81-100-005',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr181,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Other Current Liabilities</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr13 = [$arr178,$arr179,$arr180,$arr181];
                $to_cur = \App\Helpers\ACC::groupTotal($total_arr13,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($to_cur,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>213000</td>
                <td></td>
                <td style="color: red">Tax Payables</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-63-100-001</td>
                <td style="color: red">213020</td>
                <td>Service Tax Payable</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr182 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-63-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr182,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Tax Payables</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr14 = [$arr182];
                $tt_p = \App\Helpers\ACC::groupTotal($total_arr14,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($tt_p,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>213500</td>
                <td></td>
                <td style="color: red">Other Personnel Payable</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-61-100-001</td>
                <td style="color: red">213510</td>
                <td>Salaries & Wages Payable</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr183 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-61-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr183,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-61-100-002</td>
                <td style="color: red">213570</td>
                <td>SSB Payable</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr184 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-61-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr184,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-61-100-003</td>
                <td style="color: red">213580</td>
                <td>Earn Leave  payable</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr185 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-61-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr185,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-61-100-004</td>
                <td style="color: red">213611</td>
                <td>Other Payables ( Uniform )</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr186 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-61-100-004',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr186,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Other Personnel Payable</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr15 = [$arr183,$arr184,$arr185,$arr186];
                $to_p_p = \App\Helpers\ACC::groupTotal($total_arr15,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($to_p_p,$branch_id,-11) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>215000</td>
                <td></td>
                <td style="color: red">Accruals </td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-62-100-001</td>
                <td style="color: red">215080</td>
                <td>Accrued expenses other</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr187 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-62-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr187,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3-62-100-002</td>
                <td style="color: red">215120</td>
                <td>Accrued purchases (goods in transit)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr188 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-62-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr188,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Accruals </td>
                <td></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr16 = [$arr187,$arr188];
                $t_ac = \App\Helpers\ACC::groupTotal($total_arr16,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_ac,$branch_id,1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
           {{-- <tr>
                <td></td>
                <td>216000</td>
                <td></td>
                <td style="color: red"> Deferred Income</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215180</td>
                <td style="color: red"> Deferred Income General Loan</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215181</td>
                <td style="color: red">Deferred income Special Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215182</td>
                <td style="color: red">Deferred income Agriculture Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215183</td>
                <td style="color: red">Deferred income Teacher Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215184</td>
                <td style="color: red">Deferred Income Consumer Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215185</td>
                <td style="color: red">Deferred Income SME Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215186</td>
                <td style="color: red">Deferred Income Staff Loan Interest (Internal)</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215187</td>
                <td style="color: red">Deferred Income Staff Loan HP</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215188</td>
                <td style="color: red">Deferred Income (Micro-Enterprise Loan)</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215189</td>
                <td style="color: red">Deferred Income Project Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215190</td>
                <td style="color: red">Deferred Income Insurance</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215191</td>
                <td style="color: red">Deferred Income -  Staff Loan (External)</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215192</td>
                <td style="color: red">Deferred Income -  Business Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215193</td>
                <td style="color: red">Deferred Income -  Company Loan</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">215194</td>
                <td style="color: red">Deferred Income (MKT-Aung Gyi)</td>
                <td></td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">Total Other Personnel Payable</td>
                <td style="color: red"></td>
                <td></td>
                <td class="text-center">-</td>
            </tr>--}}
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>217000</td>
                <td style="color: red"></td>
                <td style="color: red">Other Payables</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">3-89-980-001</td>
                <td style="color: red">217130</td>
                <td>Payable Audit Fees</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr189 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-89-980-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr189,$branch_id,1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">3-89-800-001</td>
                <td style="color: red">274030</td>
                <td>Sinking Funds (1% Mutual Assistant Fund)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr190 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-89-800-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr190,$branch_id,-1); !!}
            </tr>
            <tr>
                <td style="background-color: yellow">Amount Due to Director</td>
                <td></td>
                <td style="background-color: yellow">3-81-100-002</td>
                <td style="color: red">274040</td>
                <td>Other Payable(Tractor Finance)</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr191 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-3-81-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr191,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total Other Payables</td>
                <td></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr17 = [$arr189,$arr190,$arr191];
                $t_o_p = \App\Helpers\ACC::groupTotal($total_arr17,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_o_p,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Total CURRENT LIABILITIES</td>
                <td></td>
                <?php
                $total__c_lib = [$ts_c,$to_cur,$tt_p,$to_p_p,$t_ac,$t_o_p];
                $t_c_lib = \App\Helpers\ACC::groupTotal($total__c_lib,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_c_lib,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>Total  LIABILITIES</td>
                <td></td>
                <td style="color: red"></td>
                <td></td>
                <?php
                $total_lib = [$t_c_lib,$tl_t];
                $t_lib = \App\Helpers\ACC::groupTotal($total_lib,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_lib,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="color: red">Equity</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>300000</td>
                <td></td>
                <td style="color: red"> SHAREHOLDERS' EQUITY</td>
                <td></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-01-100-01</td>
                <td style="color: red"> 310000</td>
                <td style="color: red">Paid In Capital</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr192 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-01-100-01',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr192,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-01-100-02</td>
                <td style="color: red"> 310010</td>
                <td style="color: red">Shares & paid in capital</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr193 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-01-100-02',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr193,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">TOTAL  SHAREHOLDERS' EQUITY</td>
                <td style="color: red"></td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $total_arr18 = [$arr192,$arr193];
                $t_s_e = \App\Helpers\ACC::groupTotal($total_arr18,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_s_e,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>321000</td>
                <td style="color: red"></td>
                <td style="color: red"> Reserves</td>
                <td style="color: red"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-05-500-001</td>
                <td style="color: red">321200</td>
                <td style="color: red">General Reserves</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr194 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-05-500-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr194,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-05-500-002</td>
                <td style="color: red">321500</td>
                <td style="color: red">Project Reserves</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr195 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-05-500-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr195,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-05-500-003</td>
                <td style="color: red">323000</td>
                <td style="color: red">Other Reserves</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr196 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-05-500-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr196,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total  Reserves</td>
                <td style="color: red"></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr19 = [$arr194,$arr195,$arr196];
                $t_r = \App\Helpers\ACC::groupTotal($total_arr19,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>332000</td>
                <td style="color: red"></td>
                <td style="color: red">Retained Earning</td>
                <td style="color: red"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-07-100-001</td>
                <td style="color: red">332010</td>
                <td style="color: red">Retained Earnings</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr197 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-07-100-001',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr197,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-07-100-002</td>
                <td style="color: red">332020</td>
                <td style="color: red">Retained Earning Current Year</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr198 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-07-100-002',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr198,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red">4-07-100-003</td>
                <td style="color: red">340100</td>
                <td style="color: red">Suspense Account</td>
{{--                <td class="text-center">-</td>--}}
                <?php
                $arr199 = \App\Helpers\ACC::getFrdBalanceSheetAccountAmountByBranch('bs-4-07-100-003',$branch_id,$end_date);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr199,$branch_id,-1); !!}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total Retained Earning</td>
                <td style="color: red"></td>
{{--                <td class="text-center">-</td>--}}

                <?php
                $total_arr20 = [$arr197,$arr198,$arr199];
                $t_r_e = \App\Helpers\ACC::groupTotal($total_arr20,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_r_e,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red">Total Equity</td>
                <td style="color: red"></td>
                <?php
                $total_e = [$t_s_e,$t_r,$t_r_e];
                $t_e = \App\Helpers\ACC::groupTotal($total_e,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_e,$branch_id,-1) !!}
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td colspan="{{count($branch_id)+1}}"></td>
            </tr>
            <tr>
                <td></td>
                <td>TOTAL CAPITAL AND LIABILITIES</td>
                <td style="color: red"></td>
                <td style="color: red"></td>
                <td style="color: red"></td>
                <?php
                $total_e_l = [$t_e,$t_lib];
                $t_e_l = \App\Helpers\ACC::groupTotal($total_e_l,$branch_id);
                ?>
                {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_e_l,$branch_id,-1) !!}
            </tr>
            {{--<tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
            </tr>
            <tr style="border: none">
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
                <td style="padding: 15px"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="color: red"></td>
                <td style="color: red"></td>
                <td style="color: red"></td>
                <td class="text-center">True</td>
            </tr>--}}




        </tbody>
    </table>
</div>
