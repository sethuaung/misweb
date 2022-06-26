
@if($g_journals != null )
    <?php
        $total_dr = 0;
        $total_cr = 0;
        $journal_id=0;
    //    dd($g_journals);
    ?>
{{--    @foreach($data as $g)--}}
    @foreach($g_journals as $g)
        <?php
            $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->first();
            $t_dr = 0;
            $t_cr = 0;
        ?>
        @if($g_d != null)
            <?php
            $ref = '';
            $type = $g_d->tran_type;
            $cus = optional(\App\Models\Customer::find($g_d->name));
            if($type == 'loan-deposit'){
                $deposit = \App\Models\LoanDeposit::find($g_d->tran_id);
                $ref = optional($deposit)->referent_no;
            }
            if($type == 'loan-disbursement'){
                $deposit = \App\Models\PaidDisbursement::find($g_d->tran_id);
                $ref = optional($deposit)->reference;
            }
            if($type == 'payment'){
                $deposit = \App\Models\LoanPayment::find($g_d->tran_id);
                $ref = optional($deposit)->payment_number;
            }
            $branch = \App\Models\Branch::where('id',$g_d->branch_id)->first();
            $branch_name = optional($branch)->title;
            $j_id=$g_d->journal_id;
            ?>
            <tr>
                <td style="white-space: nowrap;text-transform: capitalize">
                    @if ($j_id != $journal_id)
                        {{$g_d->tran_type}}
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    @if ($j_id != $journal_id)
                        {{$g->date_general->format('Y-m-d')}}
                    @endif
                </td>
                <td style="text-align: center;white-space: nowrap;">
                    @if ($j_id != $journal_id)
                        {{$g->reference_no}}
                    @endif
                </td>
                <td style="white-space: nowrap;">

                    {{$g->tran_reference}}</td>
                <td style="white-space: nowrap;">
                    {{optional($cus)->client_number}}
                </td>
                <td>
                    {{optional($cus)->name}}
                </td>
                @if (CompanyReportPart() == "company.moeyan")
                    <td style="white-space: nowrap;">{{$branch_name}}</td>
                @else
                    <td style="white-space: nowrap;">{{$g_d->external_acc_chart_code}}</td>    
                @endif
                <td style="white-space: nowrap;">{{optional($g_d->acc_chart)->code}}</td>
                <td>{{optional($g_d->acc_chart)->name}}</td>
                <td>{!! $g_d->description !!}</td>
                <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,2):''}}</td>
                <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,2):''}}</td>

                <td style="text-align: center; white-space: nowrap;">
                    @if ($j_id != $journal_id)
                        {{--@if($g_d->tran_type == 'profit')--}}
                            @if(companyReportPart() == 'company.moeyan')
                                @if($g->attach_document == [] && $g->attach_document == null)
                                    <a href="{{url('admin/profit-print/'.$g->journal_id)}}" target=_blank class="btn btn-xs btn-info" ><i class="fa fa-print"></i> </a>
                                @else
                                    <a href="{{url('admin/cashIn_file/'.$g->id)}}" class="btn btn-xs btn-info" ><i class="fa fa-cloud-download"></i> </a>
                                    <a href="{{url('admin/profit-print/'.$g->journal_id)}}" target=_blank class="btn btn-xs btn-info" ><i class="fa fa-print"></i> </a>
                                @endif
                            @endif
                            @if(backpack_user()->can('update-other-income'))
                            <a href="{{url('admin/journal-profit/'.$g->journal_id.'/edit')}}" class="btn btn-xs btn-warning" ><i class="fa fa-edit"></i> </a>
                            @endif
                            @if(backpack_user()->can('delete-other-income'))
                            <a href="{{url('admin/journal-profit-delete/'.$g->journal_id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i> </a>
                            @endif
                        {{--@endif--}}
                    @endif
                </td>
            </tr>
            @if ($j_id != $journal_id)
                <?php
                $journal_id=$j_id;
                ?>
            @endif
            <?php
                $total_dr += $g_d->dr;
                $total_cr += $g_d->cr;
            ?>
        @endif
    @endforeach
    <tr >
        <td colspan="10"><b>Total</b></td>
        <td style="text-align: right"><b>{{$total_dr>0?numb_format($total_dr,2):''}}</b></td>
        <td style="text-align: right"><b>{{$total_cr>0?numb_format($total_cr,2):''}}</b></td>
        <td></td>
        <td></td>
    </tr>

@endif
