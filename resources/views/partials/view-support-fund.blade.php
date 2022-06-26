<?php
$_e = isset($entry)?$entry:null;
$fund_detail=optional($_e)->support_fund_detail;
$fund_type = optional($_e)->support_fund_type;
$client_id = optional($_e)->client_id;
$is_edit = true;
?>
<div id="supporting-fund">
@if($fund_detail != null)
    @include('partials.loan_by_fund_type',['f_detail'=>$fund_detail,'type'=>$fund_type,'l_client'=>$client_id,'is_edit'=>true,'_e'=>$_e])
@endif
</div>


@section('after_styles')
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@push('crud_fields_scripts')
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(function () {

            $('body').on('change', '.client_id', function () {

                var c_id = $('[name="client_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/get-client-g/')}}'+ '/' + c_id,
                    data: {
                        g_id: c_id,
                    },
                    success: function (res) {

                        $('[name="client_nrc_number"]').val(res.nrc_number);
                        $('[name="client_name"]').val(res.name_other);

                        @if($_e == null)
                            $('[name="support_fund_type"]').val('').change();
                        @endif
                    }

                });


            });

            $('body').on('change','.dead_writeoff_status',function () {
                var str_write_off = $(this).val();
                var total_loan_outstanding =parseFloat($('[name="total_loan_outstanding"]').val());

                var arr_write_off = str_write_off.split("-");
                var write_off_stutus = arr_write_off[0];
                var write_off_amount = parseFloat(arr_write_off[1]);
                if(write_off_stutus == "Yes"){
                    total_amount_outstanding = (total_loan_outstanding + write_off_amount);
                }else {
                    total_amount_outstanding = (total_loan_outstanding - write_off_amount);
                }
                $('[name="total_loan_outstanding"]').val(total_amount_outstanding);


            });

            $('body').on('change','[name="support_fund_type"]',function () {
                var fund_type = $(this).val();
                var client_id = $('[name="client_id"]').val();
                $.ajax({
                    type: 'GET',
                    url: '{{url('/admin/change-fund-type')}}',
                    data: {
                        fund_type: fund_type,
                        client_id: client_id
                    },
                    success: function (res) {
                        $('#supporting-fund').html(res);
                    }

                });

            });
        });
        @if(optional($_e) != null)
        $(function () {
            $('[name="client_id"]').trigger('change');
            $('.js-cash-out').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/account-cash")}}',
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page, // pagination
                        };
                    },
                    processResults: function (data, params) {
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        params.page = params.page || 1;
                        var result = {
                            results: $.map(data.data, function (item) {

                                return {
                                    text: item["code"]+'-'+item["name"],
                                    id: item["id"]
                                }
                            }),
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        };
                        return result;
                    }
                }
            });

            $('.js-cash-out').val("{{optional($_e)->cash_acc_id}}");
            $('.js-cash-out').trigger('change');

        });
        @endif
    </script>
@endpush
