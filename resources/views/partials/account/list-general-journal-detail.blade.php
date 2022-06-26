
<?php
    $journal_id=0;
    ?>
    @if($rows != null)
        @foreach($rows as $g_d)
            <?php

            $type = $g_d->tran_type;
            $name = '';

            if($type == 'purchase-order' || $type == 'purchase-return' || $type == 'bill' || $type == 'payment'){
                $name = optional(\App\Models\Supply::find($g_d->name))->name;
            }elseif($type == 'sale-order' || $type == 'sale-return' || $type == 'invoice' || $type == 'receipt'){
                $name = optional(\App\Models\Customer::find($g_d->name))->name;
            }

            $j_id=$g_d->journal_id;

            ?>

            <tr>
                <td style="">
                    {{$g_d->tran_type}}
                </td>
                <td>
                    @if ($j_id!=$journal_id)
                        {{\Carbon\Carbon::parse($g_d->date_general)->format('Y-m-d')}}
                    @endif
                </td>
                <td>
                    @if ($j_id!=$journal_id)
                        {{$g_d->num}}
                    @endif
                </td>
                <td>
                    @if ($j_id!=$journal_id)
                        {{$name}}
                    @endif
                </td>
                <td>{{$g_d->description}}</td>
                <td>{{$g_d->acc_chart?optional($g_d->acc_chart)->code:""}}</td>
                <td>{{$g_d->acc_chart?optional($g_d->acc_chart)->name:""}}</td>
                <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,2):''}}</td>
                <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,2):''}}</td>
                <td style="text-align: center">
                    @if ($j_id!=$journal_id)
                        {{$g_d->reference_no}}
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($g_d->tran_type == 'journal')

                        @if ($j_id!=$journal_id)
                            @if (_can('update-general-journal'))
                                <a href="{{url('admin/general-journal/'.$g_d->journal_id.'/edit')}}" class="btn btn-xs btn-warning" ><i class="fa fa-edit"></i> </a>
                            @endif
                            @if (_can('delete-general-journal'))
                                <a href="{{url('admin/general-journal/delete/'.$g_d->journal_id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i> </a>
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
        @endforeach
    @endif
