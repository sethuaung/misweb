<?php
$_e = isset($entry)?$entry:null;
$journal_details = optional($_e)->journal_details;
$currency = optional($_e)->currency;
$symbol = 'K';// isset($currency->currency_symbol)?$currency->currency_symbol:'';

?>
@push('crud_fields_styles')
    <style>
        .red{
            color:red;
            font-weight: bold;
        }
    </style>
@endpush
<div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr style="background-color:#3c8dbc;color:white;">
                <th>{{_t('chart Account')}}</th>
                <th class="text-center" style="display: none;">{{_t('debit')}}</th>
                <th class="text-center">{{_t('amount')}}</th>
                <th class="text-center"><i class="fa fa-trash-o"></i></th>
            </tr>
        </thead>
        <tbody class="get_journal">
            @if($journal_details != null)
                @if(count($journal_details) > 0)
                    @foreach($journal_details as $journal_detail)

                        @include('partials.profit.add-detail',['rd' => $journal_detail])
                    @endforeach
                @endif
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right">{{_t('total')}}</th>
                <th class="text-right" style="display: none;"><span class="total_dr">0</span><span class="currency2"> </span></th>
                <th class="text-right"><span class="total_cr">0</span><span class="currency2"> </span></th>
                <th class="text-center"><i class="fa fa-trash-o"></i></th>
            </tr>
        </tfoot>
    </table>
</div>

@push('crud_fields_scripts')

    <script>
        //var if_equal = false;
        $(function () {
            $('[name="acc_chart_id"]').html('');
           $('[name="acc_chart_id"]').on('change', function () {
              var acc_chart_id = $(this).val();
              //alert('hello world');
              if (acc_chart_id > 0) {
                  $.ajax({
                      url:'{{url('admin/profit-add-detail')}}',
                      data:{
                         acc_chart_id:acc_chart_id
                      },
                      success:function (res) {
                          $('.get_journal').append(res);
                      }
                  });
              }
           });

           $(document).on('keyup', '.j_dr', function () {
               var rid = $(this).data('id');
               var tr  = $('#id-'+rid);
               tr.find('.j_cr').val('');
               get_total();
           });
           $(document).on('keyup', '.j_cr', function () {
               var rid = $(this).data('id');
               var tr  = $('#id-'+rid);
               tr.find('.j_dr').val('');
               get_total();
           });

           /*$('form').on('submit', function () {
               return if_equal;
           });*/

           $(document).on('click', '.remove-detail', function () {
               var rid = $(this).data('id');
               var tr  = $('#id-'+rid);
               tr.remove();
               get_total();
           });

           get_total();
        });

        function get_total() {
            var total_dr = 0;
            var total_cr = 0;
            $('.j_dr').each(function () {
                var dr = $(this).val() - 0;
                if(dr > 0){
                    total_dr += dr;
                }
            });
            $('.j_cr').each(function () {
                var cr = $(this).val() - 0;
                if(cr > 0){
                    total_cr += cr;
                }
            });
            $('.total_dr').text(total_dr);
            $('.total_cr').text(total_cr);

            /*if(total_dr != total_cr){
                if_equal = false;
                $('[type="submit"]').prop("disabled", true);
                $('.total_dr').parent().addClass('red');
                $('.total_cr').parent().addClass('red');
            } else {
                if_equal = true;
                $('[type="submit"]').prop("disabled", false);
                $('.total_dr').parent().removeClass('red');
                $('.total_cr').parent().removeClass('red');
            }*/
        }
    </script>
@endpush
