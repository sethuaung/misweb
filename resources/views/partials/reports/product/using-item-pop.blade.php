<div id="DivIdToPrintPop">
    @php
        $date=new DateTime($row->p_date);
        $c=optional($row->currencies)->currency_symbol;
        $sup = \App\Models\Supply::find($row->supplier_id);
        $i=1;
    @endphp

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .border th, .border td {
            border: 1px solid rgba(188, 188, 188, 0.96);
            padding: 5px;
        }

        .right {
            text-align: right;
        }
        @media print {
            body.modal-open {
                visibility: hidden;
                width: 100%;
            }
            body.modal-open .modal .modal-body {
                visibility: visible; /* make visible modal body and header */
                width: 100%;
            }
            table{
                width: 100%;
            }
        }
    </style>
    <h1 style="float: left">VH</h1>
    <h2 style="padding: 0px; font-weight: bold;text-align: center">Using Item</h2></h1>
    <hr style="border: 1px solid #000;width: 100%">

    <table>
        <tr>
            <td style="padding-right: 15px;">
                <div style="border: 1px solid #000; border-radius: 5px; padding: 10px">
                    <p>ល.ខ វិកាយប័ត្រ / Inv No:{{$row->reference_no}}</p>
                    <p>កាលបរិច្ឆេទ / Date: {{\Carbon\Carbon::parse($row->using_date)->format('d/m/Y')}}</p>
                    <p>WareHouse:{{optional($row->warehouses)->name}}</p>
                </div>
            </td>
            <td>
                <div style="text-align: center;">
                </div>
                <div style="border: 1px solid #000; border-radius: 5px; padding: 10px">
                    <p>Class:{{optional($row->acc_classes)->name}}</p>
                    <p>Job:{{optional($row->job)->name}}</p>

                </div>
            </td>
        </tr>
    </table>

    {{--@include('purchase_report_layout.header')--}}
    <table style="margin-top: 20px; ">
        <thead class="border">
        <tr>
            <th>ល.រ<br>Nº</th>
            <th>បរិយាយមុខទំនិញ<br>PRODUCT NAME</th>
            <th>ចំនួន<br>QTY</th>
            <th>ខ្នាត<br>UOM</th>
        </tr>
        </thead>

        <tbody class="border">
        <?php
           $total = 0;
        ?>

        @foreach($row->using_item_detail as $item)
            @php
                $warehouse = optional($item->warehouse)->name !=null ? optional($item->warehouse)->name : optional($row->warehouse)->name;
                //$sale_loc = optional($item)->sale_location_lot;
                $items=\App\Models\Unit::find($item->line_unit_id);
            @endphp
            <tr>
                <td>{{$i++}}</td>
                <td><b>{{optional($item->product)->product_name}}</b></td>
                <td>{{$item->line_qty}}</td>
                <td>{{optional($items)->title}}</td>

                <?php
                $total +=$item->line_amount;
                ?>
            </tr>


        @endforeach
        {{--<tr>--}}
            {{--<td colspan="3">សំគាល់/REMARKS:{{$row->note}}</td>--}}
            {{--<td colspan="2" style="font-weight: bold">សរុប/SUB TOTAL($)</td>--}}
            {{--<td style="font-weight: bold;text-align: right">{{$total}}{{$c}}</td>--}}
        {{--</tr>--}}

        </tbody>
    </table>
</div>

