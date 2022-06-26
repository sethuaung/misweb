<?php
$total_principle = 0;
$total_interest = 0;
$total_payment = 0;
?>

@if($repayment != null)
    @foreach($repayment as $r)

        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$r['date']}}</td>
            <td style="text-align: right;">{{number_format($r['principal'],0)}}</td>
            <td style="text-align: right;">{{number_format($r['interest'],0)}}</td>
            <td style="text-align: right;">{{number_format($r['payment'],0)}}</td>
            <td style="text-align: right;">{{number_format($r['balance'],0)}}</td>
        </tr>

        <?php
        $total_principle += $r['principal'];
        $total_interest += $r['interest'];
        $total_payment += $r['payment'];
        ?>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;"><b>Total: </b></td>
        <td style="text-align: right;"><b>{{numb_format($total_principle,0)}}</b></td>
        <td style="text-align: right;"><b>{{numb_format($total_interest,0)}}</b></td>
        <td style="text-align: right;"><b>{{numb_format($total_payment,0)}}</b></td>
        <td style="text-align: right;"></td>
    </tr>
@endif
