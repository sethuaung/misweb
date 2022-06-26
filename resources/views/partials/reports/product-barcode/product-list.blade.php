<style>
    .my-label{
        /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
        width: 2.025in; /* plus .6 inches from padding */
        height: .875in; /* plus .125 inches from padding */
        /*padding: .125in .3in 0;*/
        margin-right: .125in; /* the gutter */

        float: left;

        text-align: center;
        overflow: hidden;

        outline: 1px dotted; /* outline doesn't occupy space like border does */
        color: black !important;
        font-size: 11px;
        padding-top: 10px;
    }
    .page-break  {
        clear: left;
        display:block;
        page-break-after:always;
    }
</style>

<div id="DivIdToPrint" style="clear: both;width: 8.5in;margin: 0in .1875in;text-align: center;">

@if($rows != null)

        @foreach($rows as $row)
            @foreach(range(1,33) as $n)
                <div class="my-label">{!! \Milon\Barcode\DNS1D::getBarcodeSVG($row->id, $barcode_type) !!}
                    <br>{{ $row->id }}
                    <br>{{ $row->product_name }}
                </div>
            @endforeach
            <div class="page-break"></div>
        @endforeach

@else
<h1>No data</h1>
@endif
</div>
