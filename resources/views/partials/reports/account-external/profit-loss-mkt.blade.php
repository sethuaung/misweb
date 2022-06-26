<?php
$start_date = $start_date ?? null;
$end_date = $end_date ?? null;
$branch_id = $branch_id ?? [];

?>
<div id="DivIdToPrint">

    <table class="table-data" id="table-data">
        <thead>
        <tr>
            <th class="text-center">

            </th>
            <th class="text-center">

            </th>

            <th class="text-center">
                FRD Code
            </th>

            <th class="text-center">
                Mgt;
            </th>

            <th class="text-center">
                Account Name
            </th>
            @if($branch_id != null)
                @foreach($branch_id as $key)
                    <?php
                    $branch = \App\Models\Branch::find($key);
                    ?>
                    @if($branch != null)
                        <th>{{$branch->title}}</th>

                    @endif
                @endforeach
            @endif
            <th class="text-center">
                Total
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="border">
            <td class="text-right border">400000</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">Turnover</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">436000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Operating Revenue</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-001</td>
            <td class="border" style="text-align: center">437801</td>
            <td class="border" style="text-align: left;color: red">Credit Income(General Loan)</td>
            <?php
            $arr = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr,$branch_id,-1); !!}

        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-002</td>
            <td class="border" style="text-align: center">437802</td>
            <td class="border" style="text-align: left;color: red">Credit Income (Special Loan)</td>
            <?php
            $arr_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_1,$branch_id,-1); !!}

        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;color: red"></td>
            <td class="border" style="text-align: center">5-21-100-003</td>
            <td class="border" style="text-align: center">437803</td>
            <td class="border" style="text-align: left;color: red">Credit Income(Agri Loan)</td>

            <?php
            $arr_2 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-003', $branch_id, $start_date, $end_date);
            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_2,$branch_id,-1); !!}

        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-004</td>
            <td class="border" style="text-align: center">437804</td>
            <td class="border" style="text-align: left;color: red">Credit Income(Teacher Loan)</td>
            <?php
            $arr_3 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_3,$branch_id,-1); !!}
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-007</td>
            <td class="border" style="text-align: center">437807</td>
            <td class="border" style="text-align: left;color: red">Credit Income Staff Loan (Internal )</td>
            <?php
            $arr_7 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-007', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_7,$branch_id,-1); !!}
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-008</td>
            <td class="border" style="text-align: center">437809</td>
            <td class="border" style="text-align: left;color: red">Credit Income(Micro-Enterprise Loan)</td>
            <?php
            $arr_8 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-008', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_8,$branch_id,-1); !!}

        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-21-100-012</td>
            <td class="border" style="text-align: center">437812</td>
            <td class="border" style="text-align: left;color: red">Credit Income Staff Loan (External)</td>
            <?php
            $arr_12 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-21-100-012', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_12,$branch_id,-1); !!}
        </tr>


        <tr>
            <td style="padding:10px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center;">5-23-100-001</td>
            <td class="border" style="text-align: center">437815</td>
            <td style="color: red">Over Due Income (General Loan)</td>
            <?php
            $arr_13 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-001', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_13,$branch_id,-1); !!}

        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center;">5-23-100-002</td>
            <td class="border" style="text-align: center">437816</td>
            <td style="color: red">Over Due Income (Special Loan)</td>
            <?php
            $arr_14 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-002', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_14,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center;">5-23-100-003</td>
            <td class="border" style="text-align: center">437817</td>
            <td style="color: red">Over Due Income (Agri Loan)</td>
            <?php
            $arr_15 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_15,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center;">5-23-100-004</td>
            <td class="border" style="text-align: center">437818</td>
            <td style="color: red">Over Due Income (Teacher Loan)</td>
            <?php
            $arr_16 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_16,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center;">5-23-100-005</td>
            <td class="border" style="text-align: center">437819</td>
            <td style="color: red">Over Due Income (Staff Loan Internal)</td>
            <?php
            $arr_17 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_17,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center ">5-23-100-006</td>
            <td class="border" style="text-align: center">437820</td>
            <td style="color: red">Over Due Income (Staff Loan External)</td>
            <?php
            $arr_18 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-23-100-006', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_18,$branch_id,-1); !!}
        </tr>
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">460000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Other Operating Income</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-100-01</td>
            <td class="border" style="text-align: center">460140</td>
            <td style="color: red">Member fees</td>

            <?php
            $arr_19 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-100-01', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_19,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-100-02</td>
            <td class="border" style="text-align: center">490070</td>
            <td style="color: red">Savings Book Revenue</td>

            <?php
            $arr_20 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-100-02', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_20,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-100-03</td>
            <td class="border" style="text-align: center">460030</td>
            <td style="color: red">Late Fee Collected</td>
            <?php
            $arr_21 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-100-03', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_21,$branch_id,-1); !!}
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total Income</td>
            <?php
            $total_in = [$arr,$arr_1,$arr_2,$arr_3,$arr_7,$arr_8,$arr_12, $arr_13,$arr_14,$arr_15,$arr_16,$arr_17,$arr_18,$arr_19,$arr_20,$arr_21];
            $t_in = \App\Helpers\ACC::groupTotal($total_in,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_in,$branch_id,-1) !!}
        </tr>
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">700000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Other Income</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-200-001</td>
            <td class="border" style="text-align: center">710100</td>
            <td style="color: red">Other income</td>

            <?php
            $arr_22 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-200-001', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_22,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-200-002</td>
            <td class="border" style="text-align: center">710120</td>
            <td style="color: red">Interest on Bank</td>
            <?php
            $arr_23 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-200-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_23,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-200-003</td>
            <td class="border" style="text-align: center">710140</td>
            <td style="color: red">Penalty Charges</td>
            <?php
            $arr_24 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-200-003', $branch_id, $start_date, $end_date);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_24,$branch_id,-1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">5-73-200-004</td>
            <td class="border" style="text-align: center">710150</td>
            <td style="color: red">Baddebt Recover</td>

            <?php
            $arr_25 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-5-73-200-004', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_25,$branch_id,-1); !!}

        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total Other Income</td>
            <?php
            $total_o_i = [$arr_22,$arr_23,$arr_24,$arr_25];
            $t_o_i = \App\Helpers\ACC::groupTotal($total_o_i,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_o_i,$branch_id,-1) !!}
        </tr>
        <tr class="border">
            <td class="text-right border">600002</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">Expenses</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">646001</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Interest Result</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">6-12-200-001</td>
            <td class="border" style="text-align: center">646040</td>
            <td style="color:red;">Interest expenses ( Compulsory )</td>
            <?php
                $arr_26 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-12-200-001', $branch_id, $start_date, $end_date);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_26,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">6-12-200-002</td>
            <td class="border" style="text-align: center">646050</td>
            <td style="color:red;">Interest expenses ( Voluntory )</td>
            <?php
            $arr_27 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-12-200-002', $branch_id, $start_date, $end_date);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_27,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align: center">6-12-200-003</td>
            <td class="border" style="text-align: center">610710</td>
            <td style="color:red;">Loan Interest to Employee Saving</td>
            <?php
            $arr_28 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-12-200-003', $branch_id, $start_date, $end_date);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_28,$branch_id,1); !!}
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold"></td>
            <?php
            $total_o_i = [$arr_26,$arr_27,$arr_28];
            $t_o_i = \App\Helpers\ACC::groupTotal($total_o_i,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_o_i,$branch_id,-1) !!}
        </tr>
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: left;text-decoration: underline">Provision For Loan Loss</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">635000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Provision for doubtful debt</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">635010</td>
            <td class="border" style="text-align:center">67-71-100-001</td>
            <td class="border" style="text-align: center;">635010</td>
            <td class="border" style="color: red">Provisions For Doubtful Debts</td>
            <?php
            $arr_29 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-67-71-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_29,$branch_id,1); !!}
        </tr>

        <tr class="border">
            <td class="text-right border">610000</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">PERSONNEL</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">610005</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Salary & Wages</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-001</td>
            <td class="border" style="text-align: center;">610010</td>
            <td style="color: red">Salary & Wages - Permanent Staff</td>
            <?php
            $arr_30 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_30,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-002</td>
            <td class="border" style="text-align: center;">610011</td>
            <td style="color: red">Late Fine Deduction</td>
            <?php
            $arr_31 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_31,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-003</td>
            <td class="border" style="text-align: center;">610012</td>
            <td style="color: red">Without Pay Leave Deduction</td>
            <?php
            $arr_32 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_32,$branch_id,1); !!}
        </tr>

        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-004</td>
            <td class="border" style="text-align: center;">610013</td>
            <td style="color: red">Absent Deduction</td>
            <?php
            $arr_33 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_33,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-005</td>
            <td class="border" style="text-align: center;">610019</td>
            <td style="color: red">Other Deductioin</td>
            <?php
            $arr_34 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_34,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-006</td>
            <td class="border" style="text-align: center;">610020</td>
            <td style="color: red">Salary & Wages -Temporary staff</td>
            <?php
            $arr_35 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-006', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_35,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-007</td>
            <td class="border" style="text-align: center;">610030</td>
            <td style="color: red">Salary & Wages -Contract Wages</td>
            <?php
            $arr_36 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-007', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_36,$branch_id,1); !!}
        </tr>

        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-008</td>
            <td class="border" style="text-align: center;">610040</td>
            <td style="color: red">Earn Leave Payments</td>
            <?php
            $arr_37 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-008', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_37,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-009</td>
            <td class="border" style="text-align: center;">610050</td>
            <td style="color: red">Casual Wages</td>
            <?php
            $arr_38 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-009', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_38,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-010</td>
            <td class="border" style="text-align: center;">610100</td>
            <td style="color: red">Directors' Salaries</td>>
            <?php
            $arr_39 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-010', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_39,$branch_id,1); !!}
        </tr>

        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-011</td>
            <td class="border" style="text-align: center;">610120</td>
            <td style="color: red">Directors' Remuneration</td>
            <?php
            $arr_40 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-011', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_40,$branch_id,1); !!}

        </tr>

        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-012</td>
            <td class="border" style="text-align: center;">635010</td>
            <td style="color: red">Overtime</td>
            <?php
            $arr_41 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-012', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_41,$branch_id,1); !!}
        </tr>
        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-100-013</td>
            <td class="border" style="text-align: center;">610130</td>
            <td style="color: red">Bonus</td>
            <?php
            $arr_42 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-013', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_42,$branch_id,1); !!}
        </tr>

        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-71-100-014</td>
            <td class="border" style="text-align: center;">610135</td>
            <td style="color: red">Attendance Bonus</td>
            <?php
            $arr_43 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-100-014', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_43,$branch_id,1); !!}
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold"></td>
            <?php
            $total_salary = [$arr_30,$arr_31,$arr_33,$arr_34,$arr_35,$arr_36,$arr_37,$arr_38,$arr_39,$arr_40,$arr_41,$arr_42,$arr_43];
            $t_salary = \App\Helpers\ACC::groupTotal($total_salary,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_salary,$branch_id,-1) !!}
        </tr>
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">610200</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">HR Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-001</td>
            <td class="border" style="text-align: center;">610320</td>
            <td style="color: red">Meal Allowance</td>
            <?php
            $arr_44 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_44,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-002</td>
            <td class="border" style="text-align: center;">610345</td>
            <td style="color: red">Stipend for Staffs' Children Sholarship</td>
            <?php
            $arr_45 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_45,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-003</td>
            <td class="border" style="text-align: center;">610350</td>
            <td style="color: red">SSB - Employer Contribution</td>
            <?php
            $arr_46 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_46,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-004</td>
            <td class="border" style="text-align: center;">610355</td>
            <td style="color: red">SSB - Employee Contribution</td>
            <?php
            $arr_47 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_44,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-005</td>
            <td class="border" style="text-align: center;">610360</td>
            <td style="color: red">Recruiting</td>
            <?php
            $arr_45_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_45_1,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-006</td>
            <td class="border" style="text-align: center;">610450</td>
            <td style="color: red">Staff welfare</td>
            <?php
            $arr_46_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-006', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_46_1,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-007</td>
            <td class="border" style="text-align: center;">610500</td>
            <td style="color: red">Staff incentive award</td>
            <?php
            $arr_47_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-007', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_47_1,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-008</td>
            <td class="border" style="text-align: center;">610520</td>
            <td style="color: red">Compensation</td>
            <?php
            $arr_48 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-008', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_48,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-009</td>
            <td class="border" style="text-align: center;">610530</td>
            <td style="color: red">Income Tax - Employee Contribution</td>
            <?php
            $arr_49 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-009', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_49,$branch_id,1); !!}

        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-010</td>
            <td class="border" style="text-align: center;">610535</td>
            <td style="color: red">Income Tax - Employer Contribution</td>
            <?php
            $arr_50 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-010', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_50,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-011</td>
            <td class="border" style="text-align: center;">610650</td>
            <td style="color: red">Employee Insurance Contributions</td>
            <?php
            $arr_51 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-011', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_51,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-900-012</td>
            <td class="border" style="text-align: center;">610660</td>
            <td style="color: red">Employer Insurance Contributions</td>
            <?php
            $arr_52 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-012', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_52,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-71-100-013</td>
            <td class="border" style="text-align: center;">610990</td>
            <td style="color: red">Other personnel expenses</td>
            <?php
            $arr_53 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-900-013', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_53,$branch_id,1); !!}
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold"></td>
            <?php
            $total_o_ex = [$arr_44,$arr_45,$arr_46,$arr_47,$arr_46_1,$arr_47_1,$arr_48,$arr_49,$arr_50,$arr_51,$arr_52,$arr_53];
            $t_o_ex = \App\Helpers\ACC::groupTotal($total_o_ex,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_o_ex,$branch_id,-1) !!}
        </tr>

        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">611000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Training Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-300-001</td>
            <td class="border" style="text-align: center;">610410</td>
            <td style="color: red">Training Fees</td>
            <?php
            $arr_54 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-300-001', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_54,$branch_id,1); !!}
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-300-002</td>
            <td class="border" style="text-align: center;">610420</td>
            <td style="color: red">Meal Allowance & Other</td>
            <?php
            $arr_55 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-300-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_55,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-300-003</td>
            <td class="border" style="text-align: center;">610425</td>
            <td style="color: red">Transportation Charges (Training fare)</td>
            <?php
            $arr_56 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-300-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_56,$branch_id,1); !!}
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold"></td>
            <?php
            $total_t_ex = [$arr_54,$arr_55,$arr_56];
            $t_t_ex = \App\Helpers\ACC::groupTotal($total_t_ex,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_t_ex,$branch_id,-1) !!}
        </tr>
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">615000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Administration Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-001</td>
            <td class="border" style="text-align: center;">600202</td>
            <td style="color: red;">Client Incentive Expenses</td>
            <?php
            $arr_57 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_57,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-002</td>
            <td class="border" style="text-align: center;">610400</td>
            <td style="color: red;">Staff Uniform</td>
            <?php
            $arr_58 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_58,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-003</td>
            <td class="border" style="text-align: center;">610430</td>
            <td style="color: red;">Entertainment & Incentive Expenses</td>
            <?php
            $arr_59 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_59,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-004</td>
            <td class="border" style="text-align: center;">610435</td>
            <td style="color: red;">Meeting Expenses</td>
            <?php
            $arr_60 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_60,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-400-001</td>
            <td class="border" style="text-align: center;">610510</td>
            <td style="color: red;">Medical Benefit</td>
            <?php
            $arr_61 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-400-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_61,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-005</td>
            <td class="border" style="text-align: center;">615015</td>
            <td style="color: red;">Staff Amenities</td>
            <?php
            $arr_62 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_62,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-200-001</td>
            <td class="border" style="text-align: center;">615016</td>
            <td style="color: red;">Miscellaneous Expenses</td>
            <?php
            $arr_63 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-200-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_63,$branch_id,1)!!};
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-006</td>
            <td class="border" style="text-align: center;">615017</td>
            <td style="color: red;">Meal & Entertainment</td>
            <?php
            $arr_64 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-006', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_64,$branch_id,1); !!}

        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-300-001</td>
            <td class="border" style="text-align: center;">610410</td>
            <td style="color: red;">Warranty Expenses</td>
            <?php
            $arr_65 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-31-300-001', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_65,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-007</td>
            <td class="border" style="text-align: center;">616530</td>
            <td style="color: red;">Courier & Postage</td>
            <?php
            $arr_66 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-007', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_66,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-400-001</td>
            <td class="border" style="text-align: center;">616540</td>
            <td style="color: red;">Licences & Stamp Duty</td>
            <?php
            $arr_67 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-400-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_67,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-008</td>
            <td class="border" style="text-align: center;">616510</td>
            <td style="color: red;">Research & Development</td>
            <?php
            $arr_68 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-008', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_68,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-009</td>
            <td class="border" style="text-align: center;">616560</td>
            <td style="color: red;">Transportation</td>
            <?php
            $arr_69 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-009', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_69,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-57-100-001</td>
            <td class="border" style="text-align: center;">616570</td>
            <td style="color: red;">Staionary & Utilities Expenses</td>
            <?php
            $arr_70 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-57-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_70,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-010</td>
            <td class="border" style="text-align: center;">617000</td>
            <td style="color: red;">Housekeeping & Office Supplies</td>
            <?php
            $arr_71 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-010', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_71,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-58-900-001</td>
            <td class="border" style="text-align: center;">617520</td>
            <td style="color: red;">Vehicle Insurance</td>
            <?php
            $arr_72 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-58-900-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_72,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-58-900-002</td>
            <td class="border" style="text-align: center;">617530</td>
            <td style="color: red;">General Insurance</td>
            <?php
            $arr_73 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-58-900-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_73,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-011</td>
            <td class="border" style="text-align: center;">625000</td>
            <td style="color: red;">Equipment Hire</td>
            <?php
            $arr_74 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-011', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_74,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-012</td>
            <td class="border" style="text-align: center;">625020</td>
            <td style="color: red;">Subscriptions</td>
            <?php
            $arr_75 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-012', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_75,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-63-100-001</td>
            <td class="border" style="text-align: center;">625030</td>
            <td style="color: red;">Donations</td>
            <?php
            $arr_76 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-63-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_76,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-013</td>
            <td class="border" style="text-align: center;">625040</td>
            <td style="color: red;">Fax & Telephone Charges</td>
            <?php
            $arr_77 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-013', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_77,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-300-014</td>
            <td class="border" style="text-align: center;">610410</td>
            <td style="color: red;">Other administrative expenses</td>
            <?php
            $arr_78 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-014', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_78,$branch_id,1); !!}

        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-31-300-015</td>
            <td class="border" style="text-align: center;">630020</td>
            <td style="color: red;">Bank Charges Paid</td>
            <?php
            $arr_79 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-300-015', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_79,$branch_id,1); !!}
        </tr>



        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">621000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Maintenance & Repairs</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-100-001</td>
            <td class="border" style="text-align: center;">621010</td>
            <td style="color: red;">Building Maintenance</td>
            <?php
            $arr_80 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_80,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-100-002</td>
            <td class="border" style="text-align: center;">621020</td>
            <td style="color: red;">Machinery & Equipment Maintenance</td>
            <?php
            $arr_81 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-100-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_81,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-100-003</td>
            <td class="border" style="text-align: center;">621030</td>
            <td style="color: red;">Repairs & Renewals</td>
            <?php
            $arr_82 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-100-003', $branch_id, $start_date, $end_date);

            ?>

            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_82,$branch_id,1); !!}

        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-100-004</td>
            <td class="border" style="text-align: center;">621040</td>
            <td style="color: red">Repairs & Maintenance (Office Equipment)</td>
            <?php
            $arr_83 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-100-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_83,$branch_id,1); !!}
        </tr>



        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">621100</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Utilities & Supplies</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-500-001</td>
            <td class="border" style="text-align: center;">621110</td>
            <td style="color: red;">Utilities</td>
            <?php
            $arr_84 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-500-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_84,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-500-002</td>
            <td class="border" style="text-align: center;">621120</td>
            <td style="color: red;">Electricity</td>
            <?php
            $arr_85 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-500-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_85,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-500-003</td>
            <td class="border" style="text-align: center;">621130</td>
            <td style="color: red;">Water</td>
            <?php
            $arr_86 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-500-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_86,$branch_id,1); !!}
        </tr>



        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">621100</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Professional Fee</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-001</td>
            <td class="border" style="text-align: center;">615120</td>
            <td style="color: red;">Audit services</td>
            <?php
            $arr_87 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-300-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_87,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-002</td>
            <td class="border" style="text-align: center;">615130</td>
            <td style="color: red;">Consulting services</td>
            <?php
            $arr_88 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-300-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_88,$branch_id,1); !!}

        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-500-001</td>
            <td class="border" style="text-align: center;">615140</td>
            <td style="color: red;">Legal services</td>
            <?php
            $arr_89 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-500-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_89,$branch_id,1); !!}
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-003</td>
            <td class="border" style="text-align: center;">615150</td>
            <td style="color: red;">Accountancy Fees</td>
            <?php
            $arr_90 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-300-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_90,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-004</td>
            <td class="border" style="text-align: center;">615160</td>
            <td style="color: red;">External Services</td>
            <?php
            $arr_090 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-300-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_090,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-005</td>
            <td class="border" style="text-align: center;">615170</td>
            <td style="color: red;">Other Professional Fees</td>
            <?php
            $arr_91 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-300-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_91,$branch_id,1); !!}
        </tr>
      {{--  <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">615200</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">IT Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-001</td>
            <td class="border" style="text-align: center;">610325</td>
            <td style="color: red;">Communication Allowance</td>
            <?php
            $arr_92 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_92,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-002</td>
            <td class="border" style="text-align: center;">615180</td>
            <td style="color: red;">SAP Service Fees</td>
            <?php
            $arr_93 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_93,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-003</td>
            <td class="border" style="text-align: center;">615300</td>
            <td style="color: red;">Fiber Internet Line Expenses</td>
            <?php
            $arr_94 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_94,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-004</td>
            <td class="border" style="text-align: center;">615310</td>
            <td style="color: red;">Computer Accessories</td>
            <?php
            $arr_95 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_95,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-005</td>
            <td class="border" style="text-align: center;">615315</td>
            <td style="color: red;">Hardware Expenses</td>
            <?php
            $arr_96 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-005', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_96,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-300-006</td>
            <td class="border" style="text-align: center;">615318</td>
            <td style="color: red;">Other IT Expenses</td>
            <?php
            $arr_97 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-006', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_97,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-300-007</td>
            <td class="border" style="text-align: center;">615320</td>
            <td style="color: red;">Corporate Media</td>
            <?php
            $arr_98 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-300-007', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_98,$branch_id,1); !!}
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>


        <tr class="border">
            <td class="text-right border">615400</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">Travel & Entertainment</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">615401</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline"> Travelling (Local )</td>
            <td class="border"></td>
            <td class="border"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-100-02</td>
            <td class="border" style="text-align: center;">615410</td>
            <td style="color: red">Fares</td>
            <td class="border"></td>
            <?php
            $arr_99 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-100-02', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_99,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-100-03</td>
            <td class="border" style="text-align: center;">615430</td>
            <td style="color: red">Accommodation - domestic</td>
            <td class="border"></td>
            <?php
            $arr_100 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-100-03', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_100,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-100-04</td>
            <td class="border" style="text-align: center;">615490</td>
            <td style="color: red">Travel Costs - Other</td>
            <td class="border"></td>
            <?php
            $arr_101 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-100-04', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_101,$branch_id,1); !!}
        </tr>


        {{--<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">616000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline"> Travelling ( Oversea )</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-150-001</td>
            <td class="border" style="text-align: center;">616010</td>
            <td style="color: red">Fares</td>
            <?php
            $arr_102 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-150-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_102,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-150-002</td>
            <td class="border" style="text-align: center;">616030</td>
            <td style="color: red">Accommodation - international</td>
            <?php
            $arr_103 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-150-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_103,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-55-150-003</td>
            <td class="border" style="text-align: center;">616090</td>
            <td style="color: red">Travel Costs - Other</td>
            <?php
            $arr_104 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-55-150-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_104,$branch_id,1); !!}
        </tr>


        {{--<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-center border">615400</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">Operation Expeneses</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-54-100-001</td>
            <td class="border" style="text-align: center;">600010</td>
            <td style="color: red">Advertising & Promotion, own</td>
            <?php
            $arr_105 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-54-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_105,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-57-200-001</td>
            <td class="border" style="text-align: center;">630040</td>
            <td style="color: red">Printing and Forms Expenses</td>>
            <?php
            $arr_106 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-57-200-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_106,$branch_id,1); !!}f
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-51-500-001</td>
            <td class="border" style="text-align: center;">616090</td>
            <td style="color: red">Bad Debt Expense</td>
            <?php
            $arr_106_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-51-500-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_106_1,$branch_id,1); !!}
        </tr>

       {{-- <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">622000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline"> Upkeep of Vehicles</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-100-001</td>
            <td class="border" style="text-align: center;">622010</td>
            <td style="color: red">Motor Vehicle Up keep for Office</td>
            <?php
            $arr_107 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_107,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-100-002</td>
            <td class="border" style="text-align: center;">622020</td>
            <td style="color: red">Motor Vehicle Up keep for Staff</td>
            <?php
            $arr_108 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-100-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_108,$branch_id,1); !!}

        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-100-003</td>
            <td class="border" style="text-align: center;">622030</td>
            <td style="color: red">Auto Vehicle Up keep for Office</td>
            <?php
            $arr_109 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-100-003', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_109,$branch_id,1); !!}
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">623000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Fuel</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-200-001</td>
            <td class="border" style="text-align: center;">623010</td>
            <td style="color: red">Motor Vehicle (Petrol) For Office</td>
            <?php
            $arr_110 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-200-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_110,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-200-002</td>
            <td class="border" style="text-align: center;">623020</td>
            <td style="color: red">Motor Vehicle (Petrol) for Staff</td>
            <?php
            $arr_111 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-200-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_111,$branch_id,1); !!}
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-68-200-0033</td>
            <td class="border" style="text-align: center;">623030</td>
            <td style="color: red">Auto Vehicle (Petrol) For Office</td>
            <?php
            $arr_112 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-68-200-0033', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_112,$branch_id,1); !!}


        </tr>
{{--

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>
--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">629000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Rental Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-200-001</td>
            <td class="border" style="text-align: center;">615023</td>
            <td style="color: red">Rental Charges of Office</td>
            <?php
            $arr_113 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-200-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_113,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-200-002</td>
            <td class="border" style="text-align: center;">615021</td>
            <td style="color: red">Rental Charges of Vehicle</td>
            <?php
            $arr_114 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-200-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_114,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-200-003</td>
            <td class="border" style="text-align: center;">615022</td>
            <td style="color: red">Rental Charges of Cycle</td>
            <?php
            $arr_113_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-200-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_113_1,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-53-200-004</td>
            <td class="border" style="text-align: center;">615025</td>
            <td style="color: red">Rental Charges of computer</td>
            <?php
            $arr_114_1 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-53-200-004', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_114_1,$branch_id,1); !!}
        </tr>

{{--
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr class="border">
            <td class="text-center border">641000</td>
            <td class="text-left" style="text-align: left;text-decoration: underline">DEPRECIATION & AMORTIZATION</td>
            <td class="border"></td>
            <td class="border"></td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>
        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">641001</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Depreciation</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-400-002</td>
            <td class="border" style="text-align: center;">641040</td>
            <td style="color: red">Depreciation of Tools & Equipments</td>
            <?php
            $arr_115 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-400-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_115,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-350-001</td>
            <td class="border" style="text-align: center;">641050</td>
            <td style="color: red">Depreciation of Furniture, Fixture & Fitting</td>
            <?php
            $arr_116 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-350-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_116,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-500-001</td>
            <td class="border" style="text-align: center;">641060</td>
            <td style="color: red">Depreciation of Computer & IT</td>
            <?php
            $arr_117 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-500-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_117,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-600-001</td>
            <td class="border" style="text-align: center;">641070</td>
            <td style="color: red">Depreciation of Motor Vehicles</td>
            <?php
            $arr_118 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-600-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_118,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-600-002</td>
            <td class="border" style="text-align: center;">641075</td>
            <td style="color: red">Depreciation of Motor Cycles</td>
            <?php
            $arr_119 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-600-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_119,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-42-400-001</td>
            <td class="border" style="text-align: center;">641200</td>
            <td style="color: red">Depreciation of Electronic & Electrical</td>
            <?php
            $arr_120 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-42-400-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_120,$branch_id,1); !!}
        </tr>

        {{--<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">642000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Amortization</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-43-100-01</td>
            <td class="border" style="text-align: center;">642001</td>
            <td style="color: red">Amortization of Capitalised formation costs</td>
            <?php
            $arr_121 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-43-100-01', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_121,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-43-100-02</td>
            <td class="border" style="text-align: center;">642030</td>
            <td style="color: red">Amortization of cap software</td>
            <?php
            $arr_122 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-43-100-02', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_122,$branch_id,1); !!}
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-43-100-03</td>
            <td class="border" style="text-align: center;">642060</td>
            <td style="color: red">Amortization of other intangible assets</td>
            <?php
            $arr_123 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-43-100-03', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_123,$branch_id,1); !!}
        </tr>

        {{--<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td style="padding: 15px"></td>
            <td>Total Operating Cost</td>
            <td></td>
            <td></td>
            <td></td>
            <?php
            $total_o_c = [$arr,$arr_1,$arr_2,$arr_3,$arr_7,$arr_8,$arr_12, $arr_13,$arr_14,$arr_15,$arr_16,$arr_17,$arr_18,$arr_19,$arr_20,$arr_21,
                $arr_22,$arr_23,$arr_24,$arr_25,$arr_26,$arr_27,$arr_28,$arr_29,$arr_30,$arr_31,$arr_32,$arr_33,$arr_34,$arr_35,$arr_36,$arr_37,$arr_38,$arr_39,
                $arr_40,$arr_41,$arr_42,$arr_43,$arr_44,$arr_45,$arr_46,$arr_47,$arr_45_1,$arr_46_1,$arr_47_1, $arr_48,$arr_49,$arr_50,$arr_51,$arr_52,$arr_53,$arr_54,
                $arr_55,$arr_56,$arr_57,$arr_58,$arr_59,$arr_60,$arr_61,$arr_62,$arr_63,$arr_64,$arr_65,$arr_66,
                $arr_67,$arr_68,$arr_69,$arr_70,$arr_71,$arr_72,$arr_73,$arr_74,$arr_75,$arr_76,$arr_77,$arr_78,$arr_79,$arr_80,$arr_81,$arr_82,
                $arr_83,$arr_84,$arr_85,$arr_86,$arr_87,$arr_88,$arr_89,$arr_90,$arr_090,$arr_91,$arr_92,$arr_93,$arr_94,$arr_95,$arr_96,$arr_97,
                $arr_98,$arr_99,$arr_100,$arr_101,$arr_102,$arr_103,$arr_104,$arr_105,$arr_106,$arr_106_1,$arr_107,$arr_108,$arr_109,$arr_110,
                $arr_111,$arr_112,$arr_113,$arr_114,$arr_113_1,$arr_114_1,$arr_115,$arr_116,$arr_117,$arr_118,$arr_119,$arr_120,$arr_121,$arr_122,$arr_123,];
            $t_o_c = \App\Helpers\ACC::groupTotal($total_o_c,$branch_id);
            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($t_o_c,$branch_id,-1) !!}
        </tr>


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td style="padding: 15px"></td>
            <td>Earning Before Interest and Tax</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">630000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Financial Expenses</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-92-100-01</td>
            <td class="border" style="text-align: center;">646030</td>
            <td style="color: red">Interest expenses</td>
            <?php
            $arr_124 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-92-100-01', $branch_id, $start_date, $end_date);

            ?>{!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_124,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-92-100-02</td>
            <td class="border" style="text-align: center;">630010</td>
            <td style="color: red">Bank Interest Paid</td>
            <?php
            $arr_125 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-92-100-02', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_125,$branch_id,1); !!}
        </tr>

{{--
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}


        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr class="border">
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;">805000</td>
            <td class="border"></td>
            <td class="border" style="text-align: left;text-decoration: underline">Income Taxes</td>
            <td class="border"></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>

            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-69-100-001</td>
            <td class="border" style="text-align: center;">805010</td>
            <td style="color: red">Current income taxes</td>
            <?php
            $arr_126 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-69-100-001', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_126,$branch_id,1); !!}
        </tr>
        <tr>
            <td class="text-right border"></td>
            <td class="text-left" style="text-align: center;"></td>
            <td class="border" style="text-align:center">6-69-100-002</td>
            <td class="border" style="text-align: center;">805020</td>
            <td style="color: red">Deferred taxes</td>
            <?php
            $arr_127 = \App\Helpers\ACC::getFrdProfitAccountAmountByBranch('pl-6-69-100-002', $branch_id, $start_date, $end_date);

            ?>
            {!! \App\Helpers\ACC::getFrdProfitAccountAmountByBranchTd($arr_127,$branch_id,1); !!}


        </tr>
{{--
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color: black;font-weight:bold">Total</td>
            <td class="border"></td>
        </tr>--}}
        <tr>
            <td style="padding: 15px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>


        <tr>
            <td style="padding: 15px"></td>
            <td>Earning After Interest and Tax</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($branch_id)+1}}"></td>
        </tr>

        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
