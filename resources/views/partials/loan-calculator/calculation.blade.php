<?php
$total_principle = 0;
$total_interest = 0;
$total_ex_interest = 0;
$total_payment = 0;
?>
@if($repayment != null)
    @foreach($repayment as $r)
        <?PHP

           // $r['exact_interest'] = 0;
        ?>
        <tr>
            <td style="border: 1px solid #a8a8a8;padding: 5px;">{{$loop->index+1}}</td>
            <td style="border: 1px solid #a8a8a8;padding: 5px;">{{$r['date']}}</td>
            <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;">{{number_format($r['principal'],0)}}</td>
            <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;">{{number_format($r['interest'],0)}}</td>
            <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;">{{number_format($r['exact_interest'],0)}}</td>
            <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;">{{number_format($r['payment'],0)}}</td>
            <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;">{{number_format($r['balance'],0)}}</td>
        </tr>
        <?php
        $total_principle += $r['principal'];
        $total_interest += $r['interest'];
        $total_ex_interest += $r['exact_interest'];
        $total_payment += $r['payment'];
        ?>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"><b>Total: </b></td>
        <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"><b>{{numb_format($total_principle,0)}}</b></td>
        <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"><b>{{numb_format($total_interest,0)}}</b></td>
        <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"><b>{{numb_format($total_ex_interest,0)}}</b></td>
        <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"><b>{{numb_format($total_payment,0)}}</b></td>
        <td style="text-align: right;border: 1px solid #a8a8a8;padding: 5px;"></td>
    </tr>
@endif
