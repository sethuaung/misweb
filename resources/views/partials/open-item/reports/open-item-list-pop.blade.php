<div id="DivIdToPrint">
@if(isset($row))

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
    $from_date = '';
    $to_date = '';
    ?>
    @include('report_layout.header',['report_name'=>$report_name,'from_date'=> $from_date,'to_date'=>$to_date])

    @php
    $date = new DateTime($row->received_date);
    @endphp

    <div><b>Open-item</b> : {{$row->reference_no}}</div>
    <div><b>Wharehouse</b> : {{optional($row->warehouses)->name}}</div>
    <div><b>Open-item Date</b> : {{$date->format('d/M/Y H:i A')}}</div>
    <div>
        <table style="width: 100%">
            <thead class="border">
                <tr>
                    <th></th>
                    <th>Location</th>
                    <th>Quantity</th>
                    <th>Lot Number</th>
                    <th>Expire Date</th>
                </tr>
            </thead>
                @foreach ($row->open_item_detail as $item)
             <tbody class="border">
                    <tr>

                        <td colspan="5"><b>{{optional($item->product)->product_name}}({{optional($item->spec)->name}})
                            <span> &raquo; </span>
                            {{optional($item->warehouse)->name}}</b></td>
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
             </tbody>
        </table>


@else
    <h1 align="center">No data Found</h1>
@endif
</div>
