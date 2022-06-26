
@if($g_journals != null )
    <?php
    //dd($data); 
    $total_dr = 0;
    $total_cr = 0;
    $journal_id=0;
//      dd($g_journals);
    
    ?>
    
    @foreach($g_journals as $g)
    
        <?php
            $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->first();
            $t_dr = 0;
            $t_cr = 0;
            //dd($g)
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
                if($type == 'expense'){
                    $branch = \App\Models\Branch::where('id',$g_d->branch_id)->first();
                    $branch_name = optional($branch)->title;
                }
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

                    {{$g->tran_reference}}
                </td>
                @if(companyReportPart() == 'company.mkt')
                <td style="white-space: nowrap;">
                    {{$branch_name}}
                </td>
                <td style="white-space: nowrap;">
                    {{Auth()->user()->user_code}}
                </td>
                @endif
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
                        @if($g_d->tran_type == 'expense')

                                <?php
                                    
                                    $att_doc=$g->attach_document;

                                    if ($att_doc!=null){
                                        if(isset($att_doc[0])){
                                            $att_document=$att_doc[0];
                                        }
                                        
                                    }else{
                                        $att_document=$g->attach_document;
                                    }

                                ?>
                                @if(companyReportPart() == 'company.moeyan')
                                    @if($g->attach_document == [] && $g->attach_document == null)
                                        <a href="{{url('/admin/print-expense?is_pop=1&expense_id='.$g->journal_id.'')}}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
                                    @else
                                        <a href="{{url('admin/cashOut_file/'.$g->id)}}" class="btn btn-xs btn-info" ><i class="fa fa-cloud-download"></i> </a>
                                        <a href="{{url('/admin/print-expense?is_pop=1&expense_id='.$g->journal_id.'')}}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
                                    @endif
                                @endif
                                    {{--@foreach(json_decode($g->attach_document) as $doc)--}}
                                {{--<a href="{{asset($doc)}}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>--}}
                             {{--@endforeach--}}

                            @if(_can('update-journal-expense'))
                                <a href="{{url('admin/expense/'.$g->journal_id.'/edit')}}" class="btn btn-xs btn-warning" ><i class="fa fa-edit"></i> </a>
                            @endif
                            @if(_can('delete-journal-expense'))
                                <a href="{{url('admin/expense-delete/'.$g->journal_id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i> </a>
                            @endif
                        @endif
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
        @if(companyReportPart() == 'company.mkt')
            <td colspan="12"><b>Total</b></td>
        @else
            <td colspan="10"><b>Total</b></td>
        @endif
        <td style="text-align: right"><b>{{$total_dr>0?numb_format($total_dr,2):''}}</b></td>
        <td style="text-align: right"><b>{{$total_cr>0?numb_format($total_cr,2):''}}</b></td>
        <td></td>
        <td></td>
    </tr>

@endif
