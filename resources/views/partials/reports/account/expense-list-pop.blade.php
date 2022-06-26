<div id="DivIdToPrintPop">
@php
    //$date=new DateTime($row->p_date);
    //$c=optional($row->currencies)->currency_symbol;
    $i=1;
@endphp

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        /*border: 1px solid black;*/
    }

    .border th, .border td {
        border: 1px solid black;
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

    <table style="width: 100%;font-family: 'Khmer OS Content';font-weight: bold;text-align: center;margin-top: 20px;line-height: 21px">
        <tr>
            <td style="font-family: 'Roboto', sans-serif ">
                Expense List
            </td>
        </tr>
    </table>
    <table style="margin-top: 20px;font-family: 'Khmer OS Content';font-size: 12px;line-height: 20px">
        <tr>
            <td width="70%">
                Date : {{\Carbon\Carbon::parse($row->date_general)->format('Y-m-d')}}
                <br>
            </td>

            <td width="30%">

                <span style="font-family: 'Roboto', sans-serif; font-weight: bold;">
                        Ref :{{$row->reference_no}}
                    </span>
            </td>
        </tr>
    </table>
        <table style="margin-top: 20px;font-family: 'Khmer OS Battambang';font-size: 11px ">
            <thead class="border">
                <tr>
                    <th style="text-align: center; width: 50px;">ល.រ<br>Nº</th>
                    <th style="">បរិយាយ<br>Description</th>
                    <th style="text-align: center">ថ្លៃទំនិញ<br>AMOUNT</th>
                </tr>
            </thead>

            <tbody class="border">
                @foreach($row->journal_details as $item)
                    @if($item->section_id == 60 || $item->section_id == 80)
                    @php
                        $acc = \App\Models\AccountChart::find($item->acc_chart_id);
                    @endphp
                        <tr>
                            <td style="text-align: center;">{{$i++}}</td>
                            <td><b>{{optional($acc)->name}}</b></td>
                            <td style="font-weight: bold;text-align: right">{{$item->dr}}</td>
                        </tr>
                    @endif
                @endforeach

            </tbody>
        </table>
        <br>
</div>
