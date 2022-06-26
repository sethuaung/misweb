<div id="DivIdToPrintPop">
@php
    $date=new DateTime($row->p_date);
    $c=optional($row->currencies)->currency_symbol;
    $cus = \App\Models\Customer::find($row->customer_id);
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
        @include('report_layout.header')
        <table style="margin-top: 20px; ">
            <thead class="border">
                <tr>
                    <th>ល.រ<br>Nº</th>
                    <th>បរិយាយមុខទំនិញ<br>PRODUCT NAME</th>
                    <th>បរិមាណបញ្ជាទិញ<br>QTY ORDERED</th>
                    <th>ខ្នាត<br>UOM</th>
                    <th>បរិមាណកំពុងបញ្ជូន<br>QTY DELIVER</th>
                    <th>បរិមាណបានបញ្ជូន<br>QTY DELIVERED</th>
                    <th>ឃ្លាំ<br>WAREHOUSE</th>
                    <th>បរិមាណនៅសល់<br>QTY REMAIN</th>
                </tr>
            </thead>

            <tbody class="border">
                @foreach($row->sale_details as $item)
                    @php
                        $warehouse = optional($item->warehouse)->name !=null ? optional($item->warehouse)->name : optional($row->warehouse)->name;
                        //$sale_loc = optional($item)->sale_location_lot;
                        $qty_total = \App\Models\SaleDetail::find($item->parent_id)->line_qty;
                        //$items=\App\Models\Unit::find($item->line_unit_id);
                    @endphp
                        <tr>
                            <td>{{$i++}}</td>
                            <td><b>{{optional($item->product)->product_name}}</b></td>
                            <td>{{$qty_total??0}}</td>
                            <td>{{optional($item->unit)->title}}</td>
                            <td>{{$item->line_qty}}</td>
                            <td>{{$item->line_qty_delivery-$item->line_qty}}</td>
                            <td>{{optional($item->warehouse)->name}}</td>
                            <td>{{$item->line_qty_remain}}</td>
                        </tr>

                @endforeach
                        <tr>
                           <td colspan="៧">ទំនេញទិញហើយមិនអាចដូរវិញទេ។</td>

                        </tr>

            </tbody>
        </table>
        @include('report_layout.footer')
</div>
