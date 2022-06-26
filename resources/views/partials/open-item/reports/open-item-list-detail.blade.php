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
        $report_name = '<span style="font-size: 22px;"><b>Open Item List Detail</b></span><br>';

        $from_date = $from_date;
        $to_date = $to_date;
        ?>
        @include('report_layout.header',['report_name'=>$report_name,'from_date'=> $from_date,'to_date'=>$to_date])

        <table style="width: 100%">
            <tbody class="border">
                @foreach($rows as $row)
                    @php
                        $date = new DateTime($row->received_date);
                    @endphp
                    <tr>
                        <th colspan="5"><b>Open-item/{{$row->reference_no}}
                            <span style="font-size:20px"> &raquo; </span>
                            {{optional($row->warehouses)->name}}<span > &raquo; </span>
                            {{$date->format('d/M/Y H:i A')}}</b></th>
                    </tr>
                    <tr>
                        <th><div></div></th>
                        <th>Location</th>
                        <th>Quantity</th>
                        <th>Lot Number</th>
                        <th>Expire Date</th>
                    </tr>
                    @foreach ($row->open_item_detail as $item)
                        <tr>
                            <td><div></div></td>
                            <td colspan="10"><b>{{optional($item->product)->product_name}}({{optional($item->spec)->name}})
                                <span> &raquo; </span>
                                {{optional($item->warehouse)->name}}<span> &raquo; </span>
                                Qty/{{$item->line_qty_receive}}</b></td>
                        </tr>
                        @foreach ($item->open_item_location_detail as $location_detail)
                            <tr>
                                <td></td>
                                <td>{{optional($location_detail->location)->name}}</td>
                                <td>{{$location_detail->qty}}</td>
                                <td>{{$location_detail->lot}}</td>
                                <td>{{$location_detail->factory_expire_date}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

@else
    <h1 align="center">No data Found</h1>
@endif
