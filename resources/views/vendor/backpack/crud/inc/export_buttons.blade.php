@if ($crud->exportButtons())
  {{--<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" type="text/javascript"></script>--}}
  {{--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.bootstrap.min.js" type="text/javascript"></script>--}}
  {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>--}}
  {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>--}}
  {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>--}}
  {{--<script src="//cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" type="text/javascript"></script>--}}
  {{--<script src="//cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" type="text/javascript"></script>--}}
  {{--<script src="//cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js" type="text/javascript"></script>--}}

  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/dataTables.buttons.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/buttons.bootstrap.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/jszip.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/pdfmake.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/vfs_fonts.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/vfs_fonts.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/buttons.html5.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/buttons.print.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/adminlte') }}/plugins/datatables/js/buttons.colVis.min.js" type="text/javascript"></script>


  <script>
    crud.dataTableConfiguration.buttons = [
        {
            extend: 'collection',
            text: '<i class="fa fa-download"></i> {{ trans('backpack::crud.export.export') }}',
            buttons: [
                {
                    name: 'copyHtml5',
                    extend: 'copyHtml5',
                    exportOptions: {
                       columns: [':visible:not(.not-export-col):not(.hidden):not([data-visible-in-export=false])'],
                    },
                    action: function(e, dt, button, config) {
                        crud.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                        crud.responsiveToggle(dt);
                    }
                },
                {
                    name: 'excelHtml5',
                    extend: 'excelHtml5',
                    exportOptions: {
                       columns: [':visible:not(.not-export-col):not(.hidden):not([data-visible-in-export=false])'],
                    },
                    action: function(e, dt, button, config) {
                        crud.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                        crud.responsiveToggle(dt);
                    }
                },
                {
                    name: 'csvHtml5',
                    extend: 'csvHtml5',
                    exportOptions: {
                       columns: [':visible:not(.not-export-col):not(.hidden):not([data-visible-in-export=false])'],
                    },
                    action: function(e, dt, button, config) {
                        crud.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                        crud.responsiveToggle(dt);
                    }
                },
                {
                    name: 'pdfHtml5',
                    extend: 'pdfHtml5',
                    exportOptions: {
                       columns: [':visible:not(.not-export-col):not(.hidden):not([data-visible-in-export=false])'],
                    },
                    orientation: 'landscape',
                    action: function(e, dt, button, config) {
                        crud.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                        crud.responsiveToggle(dt);
                    }
                },
                {
                    name: 'print',
                    extend: 'print',
                    exportOptions: {
                       columns: [':visible:not(.not-export-col):not(.hidden):not([data-visible-in-export=false])'],
                    },
                    action: function(e, dt, button, config) {
                        crud.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                        crud.responsiveToggle(dt);
                    }
                }
            ]
        },
        {
            extend: 'colvis',
            text: '<i class="fa fa-eye-slash"></i> {{ trans('backpack::crud.export.column_visibility') }}',
            columns: ':not(.not-export-col):not([data-visible-in-export=false])'
        }
    ];

    // move the datatable buttons in the top-right corner and make them smaller
    function moveExportButtonsToTopRight() {
      crud.table.buttons().each(function(button) {
        if (button.node.className.indexOf('buttons-columnVisibility') == -1 && button.node.nodeName=='BUTTON')
        {
          button.node.className = button.node.className + " btn-sm";
        }
      })
      $(".dt-buttons").appendTo($('#datatable_button_stack' ));
      $('.dt-buttons').css('display', 'inline-block');
    }

    crud.addFunctionToDataTablesDrawEventQueue('moveExportButtonsToTopRight');
  </script>
@endif