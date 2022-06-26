<?php
$_e = isset($entry)?$entry:null;
$create_client = request()->create_client??'';
$client_pending_id = request()->client_pending_id??0;
$m= \App\Models\ClientPending::find($client_pending_id);
$nrc_number = optional($m)->nrc_number??'';
?>
@push('crud_fields_scripts')
    <script>
        $(function (e) {

            var company = $('#company_part').val();
            var nrc_number = '{{$nrc_number}}';
            $('body').on('change', '[name="primary_phone_number"]', function () {
                //alert('Phone number must be 11 digits.');

                var phone = $(this).val();
                phone = phone.replace(/[^0-9]/g, '');

                if (phone.length != 11) {
                    // alert('Phone number must be 11 digits.');
                    return phone;
                }

            });
            $('body').on('change', '[name="branch_id"]', function () {
                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-branch-code')}}',
                    data: {
                        branch_id: branch_id,
                    },
                    success: function (res) {
                        var code = res.code;

                        if(company != "company.moeyan"){
                            $('[name="client_number"]').val(code);
                        }
                    }

                });


            });


            $('body').on('change', '[name="marital_status"]', function () {
                var x = $('[name="marital_status"]').find(":selected").text();
                //alert(x);

                var t = false;
                if (x == 'Single') {
                    t = true;
                }

                //$('[name="father_name"]').prop("readonly", t);
                $('[name="husband_name"]').prop("readonly", t);
                $('[name="occupation_of_husband"]').prop("readonly", t);
                $('[name="no_children_in_family"]').prop("readonly", t);
                //$('[name="no_of_working_people"]').prop("readonly", t);
                //$('[name="no_of_dependent"]').prop("readonly", t);
                //$('[name="no_of_person_in_family"]').prop("readonly", t);

            });
            $('body').on('change','[name="id_format"]',function () {
                var id_format = $(this).val();

                if(id_format == "Auto"){
                    $('[name="client_number"]').attr('readonly', true);
                    $('[name="branch_id"]').trigger('change');
                }else{
                    $('[name="client_number"]').removeAttr('readonly', true);
                    $('[name="client_number"]').val('');
                }
            });

            $('[name="marital_status"]').trigger('change');
            @if($_e == null)
            $('[name="id_format"]').trigger('change');
            @endif

            @if(companyReportPart()=='company.bolika')
            $('#nrc_type').val('Old Format').trigger('change');
            @endif

            @if($create_client == 'request_to_client')
            $('#nrc_type').val('Old Format').trigger('change');
            $('[name="nrc_number_new"]').val(nrc_number);
            @endif

        });
    </script>
@endpush
