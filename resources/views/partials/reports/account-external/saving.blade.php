<div id="DivIdToPrint">
    @include('partials.reports.header',
    ['report_name'=>'Saving Report','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <tbody>

        </tbody>
    </table>

    <div class="panel-body" style="padding: 0px !important;">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6 col-p-6">&nbsp;</div>
                    <div class="col-md-4 col-p-4">
                        <p class="text-right">
                            <span style="font-family:&quot;Times New Roman&quot;,serif;">Date :</span>
                        </p>
                    </div>
                    <div class="col-md-2 col-p-2" style="border-bottom: 1px solid #333;">{{date("d/m/Y")}}</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 col-p-6">&nbsp;</div>
                    <div class="col-md-4 col-p-4">
                        <p class="text-right">
                            <span style="font-family:&quot;Times New Roman&quot;,serif;">Reporting Period:</span>
                        </p>
                    </div>
                    <div class="col-md-2 col-p-2" style="border-bottom: 1px solid #333;">mm</div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:1%;">
                        No.
                    </th>
                    <th class="text-center" rowspan="2" style="border: solid windowtext 1.0pt;width:25%;">
                        Region
                    </th>
                    <th class="text-center" colspan="2" style="border: solid windowtext 1.0pt;width:11%;">
                        Compulsory Saving
                    </th>
                    <th class="text-center" colspan="2" style="border: solid windowtext 1.0pt;width:10%;">
                        Voluntary  Saving
                    </th>
                    <th class="text-center" colspan="2" style="border: solid windowtext 1.0pt;width:12%;">
                        Total
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Number of Clients
                    </th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Amount
                    </th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Number of Clients
                    </th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Amount
                    </th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Number of Clients
                    </th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;width:11%;">
                        Amount
                    </th>
                </tr>
                </thead>
                <tbody>
                @php
                    $branches = App\Models\Branch::All();
                    $total_client = $total_client??0;
                    $total_amount = $total_amount??0;
                @endphp
                @foreach ($branches as $branch)
                @php
                    $clients = App\Models\Client::where('branch_id',$branch->id)->count();
                    $compulsory =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
                                                                            ->where(getLoanTable().'.status_note_date_activated','>=',$start_date)
                                                                            ->where(getLoanTable().'.status_note_date_activated','<=',$end_date)
                                                                            ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
                                                                            ->where(getLoanCompulsoryTable().'.branch_id','=',$branch->id)
                                                                            ->sum(getLoanCompulsoryTable().'.principles');
                    $withdraw =\App\Models\CompulsorySavingTransaction::join('loans', 'loans.id', '=', 'compulsory_saving_transaction.loan_id')
                                                                      ->where('compulsory_saving_transaction.tran_date','>=',$start_date)
                                                                      ->where('compulsory_saving_transaction.tran_date','<=',$end_date)
                                                                      ->where('compulsory_saving_transaction.train_type','=','withdraw')
                                                                      ->where('loans.branch_id','=', $branch->id)
                                                                      ->sum('compulsory_saving_transaction.amount');     

                    // $withdraw =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
                    //                                                         ->where('tran_date','<=',$end_date)
                    //                                                         ->where('train_type','=','withdraw')
                    //                                                         ->sum('amount');
                    $remain =  $compulsory - $withdraw;
                    $total_client += $clients;
                    $total_amount += $remain;
                @endphp
                <tr >
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">{{$branch->id}}</td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        {{$branch->title}}
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        {{$clients}}
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        {{$remain}}
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        0
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        0
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">{{$clients}}</td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">{{$remain}}</td>
                </tr>
                @endforeach

                {{-- <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        2
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        3
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        4
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        5
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        6
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        7
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        8
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        9
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: solid windowtext 1.0pt;width: 1%;">
                        10
                    </td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                    <td class="text-center" style="border: solid windowtext 1.0pt;"></td>
                </tr> --}}
                </tbody>
                <tfoot>
                <tr>
                    <th style="border: solid windowtext 1.0pt;"></th>
                    <th class="text-center" style="border: solid windowtext 1.0pt;">Total</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">{{$total_client}}</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">{{$total_amount}}</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">-</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">-</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">{{$total_client}}</th>
                    <th class="text-right" style="border: solid windowtext 1.0pt;">{{$total_amount}}</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6 col-p-6">&nbsp;</div>
                    <div class="col-md-4 col-p-4">
                        <p class="text-right">
                            <span style="font-family:&quot;Times New Roman&quot;,serif;">Prepared by (Name/Signature) :</span>
                        </p>
                    </div>
                    <div class="col-md-2 col-p-2" style="border-bottom: 1px solid #333;">&nbsp;</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 col-p-6">&nbsp;</div>
                    <div class="col-md-4 col-p-4">
                        <p class="text-right">
                            <span style="font-family:&quot;Times New Roman&quot;,serif;">Checked by (Name/Signature):</span>
                        </p>
                    </div>
                    <div class="col-md-2 col-p-2" style="border-bottom: 1px solid #333;"> &nbsp;</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 col-p-6">&nbsp;</div>
                    <div class="col-md-4 col-p-4">
                        <p class="text-right">
                            <span style="font-family:&quot;Times New Roman&quot;,serif;">Approved by (Name/Signature):</span>
                        </p>
                    </div>
                    <div class="col-md-2 col-p-2" style="border-bottom: 1px solid #333;"> &nbsp;</div>
                </div>
            </div>
        </div>
    </div>


</div>
