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
                    <th width="250">សំគាល់<br/>Remarks</th>
                    <th>ចំនួន<br>QTY</th>
                    <th>ខ្នាត<br>UOM</th>
                    <th colspan="2" width="100">តម្លៃមុន/ក្រោយបញ្ចុះថ្លៃ<br>PRICE BEFORE/AFTER DISC</th>
                    <th>ថ្លៃទំនិញ<br>AMOUNT</th>
                </tr>
            </thead>

            <tbody class="border">
                @foreach($row->sale_details as $item)
                    @php
                        $warehouse = optional($item->warehouse)->name !=null ? optional($item->warehouse)->name : optional($row->warehouse)->name;
                        //$sale_loc = optional($item)->sale_location_lot;
                        $items=\App\Models\Unit::find($item->line_unit_id);
                    @endphp
                        <tr>
                            <td>{{$i++}}</td>
                            <td><b>{{optional($item->product)->product_name}}</b></td>
                            <td>{{optional($items)->description}}</td>
                            <td>{{$item->line_qty}}</td>
                            <td>{{optional($items)->name}}</td>
                            <td style="font-weight: bold">{{$item->unit_cost}}{{$c}}</td>
                            <td style="font-weight: bold">{{$item->net_unit_cost}}{{$c}}</td>
                            <td style="font-weight: bold">{{$item->line_amount}}{{$c}}</td>
                        </tr>

                @endforeach
                        <tr>
                           <td colspan="5" rowspan="3">សំគាល់/REMARKS:</td>
                           <td colspan="2" style="font-weight: bold">សរុប/SUB TOTAL($)</td>
                           <td style="font-weight: bold;text-align: right">{{$row->subtotal}}{{$c}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight: bold">បញ្ចុះថ្លៃ/DISCOUNT</td>
                            <td style="font-weight: bold;text-align: right">{{$row->discount_amount}}{{$c}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight: bold">សរុបរួមអាករ/AMOUNT DUE ($)(VAT INCLUDED)</td>
                            <td style="font-weight: bold;text-align: right">{{$row->grand_total}}{{$c}}</td>
                        </tr>
            </tbody>
        </table>
        @include('report_layout.footer')
</div>
