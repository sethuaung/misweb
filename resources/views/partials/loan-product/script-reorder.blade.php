
<?php $repayment_order = isset($entry) ? $entry->repayment_order : [];  ?>

@if(is_array($repayment_order) && count($repayment_order) > 0)
    <ul id="sortable"  >
        @foreach($repayment_order as $k => $v)
                <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <input type="hidden" name="repayment_order[{{ $k }}]" value="{{ $v }}">
                    {{ $k }}</li>
        @endforeach
    </ul>
@else
<ul id="sortable"  >
    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <input type="hidden" name="repayment_order[Interest]" value="0">
        Interest</li>
    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <input type="hidden" name="repayment_order[Penalty]" value="1">
        Penalty</li>
    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <input type="hidden" name="repayment_order[Service-Fee]" value="2">Service Fee</li>
    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <input type="hidden" name="repayment_order[Saving]" value="3">Saving</li>
    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <input type="hidden" name="repayment_order[Principle]" value="4">Principle</li>
</ul>
@endif

@push('crud_fields_styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 45px; }
        #sortable li span { position: absolute; margin-left: -1.3em; margin-top: 8px;}

    </style>
@endpush

@push('crud_fields_scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#sortable" ).sortable({
                update: function(event, ui) {
                    $( "#sortable li" ).each(function (i,item) {
                        $(this).find(':input').val(i);
                    });
                }
            });
            $( "#sortable" ).disableSelection();
        } );
    </script>
@endpush
