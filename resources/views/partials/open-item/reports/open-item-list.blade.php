@if(count($rows) > 0)

    <link href="https://fonts.googleapis.com/css?family=Content|Moul|Titillium+Web" rel="stylesheet">


    <style>
        table {
            border-collapse: collapse;
        }

        .border th, .border td {
            border: 1px solid rgba(188, 188, 188, 0.96);
            padding: 5px;
        }

        .right {
            text-align: right;
        }

        h3 {
            font-family: 'Moul', Titillium Web;
            font-size: 18px;
        }

        h4 {
            font-family: 'Hanuman', Titillium Web;
            font-size: 15px;
        }

        tr td {
            font-family: 'Hanuman', Titillium Web;
            font-size: 12px;
        }
        tr th {
            font-family: 'Hanuman', Titillium Web;
            font-size: 12px;
        }


    </style>




        <?php
        $report_name = '<span style="font-size: 22px;"><b>Open Item List</b></span><br>';

        $from_date = $from_date;
        $to_date = $to_date;
        $use_date = $use_date
        ?>
        @include('report_layout.header',['report_name'=>$report_name,'from_date'=> $from_date,'to_date'=>$to_date,'use_date'=>$use_date])


        <table style="width: 100%">
            <thead class="border">
                <tr>
                    <th>No</th>
                    <th>Reference No</th>
                    <th>Warehouse</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody class="border">
                @foreach($rows as $row)
                    @php
                        $date = new DateTime($row->received_date);
                    @endphp
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$row->reference_no}}</td>
                        <td>{{optional($row->warehouses)->name}}</td>
                        <td>{{$date->format('d/M/Y H:i A')}}</td>
                        <td>{{optional($row->open_item_detail)->sum('line_qty_receive')}}</td>
                        <td><a href="{{url("admin/report/open-item-list-pop/{$row->id}")}}" data-remote="false" data-toggle="modal"
                            data-target="#show-purchase-detail" class="btn btn-default btn-xs"><span><i class="fa fa-eye"></i></span></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>


    <!-- Default bootstrap modal example -->
    <div class="modal fade" id="show-purchase-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Open-item</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="printDivPop()" class="btn btn-default glyphicon glyphicon-print print"></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#show-purchase-detail").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);
                $(this).find(".modal-body").load(link.attr("href"));
            });
            // $('.print').on('click',function () {
            //     window.print();
            // });

            function printDivPop()
            {

                var DivIdToPrintPop=document.getElementById('DivIdToPrint');

                var newWin=window.open('','Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">'+DivIdToPrintPop.innerHTML+'</body></html>');

                newWin.document.close();

                setTimeout(function(){newWin.close();},10);

            }

        </script>
@else
    <h1 align="center">No data Found</h1>
@endif
