<?php $u_key = rand(1,100).time().rand(1,100); ?>
<div class="row" style="padding: 15px;">
    <div class="col-md-12">
        <div class="row">
            <div class="form-group col-md-3 hidden-xs" >
                <label> </label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-barcode" style="font-size: 20px;"></i> </div>
                    <input class="form-control scanner_input" placeholder="" type="text" />
                    <div class="input-group-addon">
                        <div><i class="fa fa-search"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-9" >
                <label></label>
                @if(isset($field['prefix']) || isset($field['suffix']) || true) <div class="input-group"> @endif
                    @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
                    <input type="text" style="width: 100%"   id="product_select2_ajax_{{ $u_key }}" class="form-control" >
                    @if(isset($field['suffix']) || true) <div class="input-group-addon">
                        <a href="" data-remote="false" data-toggle="modal" data-target="#show-create-product"><span class="glyphicon glyphicon-plus"></span></a>
                    </div> @endif
                    @if(isset($field['prefix']) || isset($field['suffix']) || true) </div> @endif
            </div>
        </div>

    </div>
</div>
{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    <div class="modal fade" id="show-create-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{_t('Inventory')}}</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="315" src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <style>
            .modal-dialog-x {
                width: 95%;
                padding: 0;
            }

            .modal-content {
                width: 98%;
            }
        </style>

    @if($field['no_script']=='yes')

    {{-- allow clear --}}
    @endif

    <link href="{{asset('vendor/adminlte/plugins/jQueryUI/jquery-ui.css')}}"/>
    <style type="text/css">
        .ui-widget.ui-widget-content {
            border: 1px solid #c5c5c5;
            min-height: 300px;
        }
        .ui-widget-content {
            border: 1px solid #dddddd;
            background: #ffffff;
            color: #333333;
        }
        .ui-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: block;
            outline: 0;
        }

        .ui-menu-item{
            padding: 5px;
            cursor: pointer;
        }
        .ui-menu-item:nth-child(2n){
            background: #e9e9e9;
        }

        .ui-menu-item .ui-menu-item-wrapper.ui-state-active {
            background: #6693bc !important;
            font-weight: bold !important;
            color: #ffffff !important;
            padding: 5px;
        }
        /* IE 6 doesn't support max-height
         * we use height instead, but this forces the menu to always be this tall
         */

    </style>

    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <!-- include select2 js-->
    <script src="{{asset('vendor/adminlte/plugins/jQueryUI/jquery-ui-a.js')}}"></script>
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')

<script>

    jQuery(document).ready(function($) {
        var cache = {};

        $("#product_select2_ajax_{{ $u_key }}" ).autocomplete({
            // ss
            //source: "{{ url("api/jquery-product") }}",
            source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                    response( cache[ term ] );
                    return;
                }

                $.ajax( {
                    url: "{{ url("api/jquery-product") }}",
                    async: false,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        console.log(data);
                        //response( data );
                        cache[ term ] = data;
                        response( data );
                    }
                } );
            },
            minLength: 0,
            matchContains: true,
            //delay: 100,
            select: function( event, ui ) {
                console.log( ui.item.ct_length);
                if(ui.item.ct_length != null && $('select[name="machine_id"]').val() == ''){
                    alert('Select Machine');
                    return false;
                }
                @if(isset($field['script_run']))
                    {{ $field['script_run'] }}(ui.item.id);
                @endif
            },
            //autoFocus: true,
            close: function () {
                $(this).val('');
            }
        })
            .focus(function(){
           $(this).data("uiAutocomplete").search($(this).val());
                $(this).val('');
        })
            .click(function(){
            $(this).data("uiAutocomplete").search($(this).val());
        });
        $('.scanner_input').on('keyup',function (e) {
            // console.log(e.keyCode);
            console.log(111);
            var q = $(this).val();
            if(e.keyCode == 13 || e.keyCode == 9)
            {
                $.ajax({
                    type: 'GET',
                    url: '{{url('admin/get-scan-barcode')}}',
                    data: {
                        q: q
                    },
                    success: function (res) {

                        @if(isset($field['script_run']))
                        if(res.id >0) {
                            {{ $field['script_run'] }}(res.id,res.unit_id);
                        }
                        @endif
                        $('.scanner_input').val('');
                        @if(companyReportPart() == "company.kompot_phamar")
                        $('.scanner_input').focus();
                        @endif
                    }

                });
            }

        });

    });

    jQuery(document).keydown(function(event) {
            //console.log(event.which);
            if((event.ctrlKey || event.metaKey) && event.which == 81) {// q
                // Save Function
                $(".scanner_input" ).focus();
                event.preventDefault();
                return false;
            }
        }
    );

    $(function(){


        $('body').on('keyup','.line_qty_receive',function (e) {
            var $this = $(this);
            var $tr = $this.closest("tr");

            if(e.keyCode == 38){
                $tr.prev().find('.line_qty_receive').focus();
            }
            else if(e.keyCode == 40)
            {
                $tr.next().find(".line_qty_receive").focus();
            }
        });
    });
</script>
<script>
    $("#show-create-product").on("show.bs.modal", function(e) {
        var link = '{{url('admin/inventory/create?is_frame=1')}}';
        $(this).find('iframe').attr('src',link);
    });
</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
