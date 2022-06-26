@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <style>
        .pg-d url{
            display: block !important;
        }
    </style>

@endsection

@section('content')
        @if(empty($_REQUEST['group_id']))
        @php
        $_REQUEST['group_id'] = 0;
        @endphp
     @endif
     @if(empty($_REQUEST['center_id']))
     @php
        $_REQUEST['center_id'] = 0;
     @endphp
  @endif
    <div class="container-fluid">
        <form method="GET">
            <div class="form-group col-md-4">
                <select class="form-control select2_field-center" data-source="{{url("api/get-center-leader-name-id")}}" multiple name="center_id"  >

                   @if($_REQUEST['center_id'] != 0)
                        <?php

                            $c = \App\Models\CenterLeader::find($_REQUEST['center_id']);
                        ?>
                    <option {{isset($_REQUEST['center_id'])?$c->id==$_REQUEST['center_id']?"selected='selected'":'':''}} value="{{optional($c)->id}}">{{optional($c)->title}}</option>
                    @endif
                </select>
            </div>
            <div class="form-group col-md-4">
                <select class="form-control select2_field-group" data-source="{{url("api/get-group-loan2")}}" multiple name="group_id" >
                    @if($_REQUEST['group_id'] != 0)
                        <?php

                        $c = \App\Models\GroupLoan::find($_REQUEST['group_id']);
                        ?>
                        <option {{isset($_REQUEST['group_id'])?$c->id==$_REQUEST['group_id']?"selected='selected'":'':''}} value="{{optional($c)->id}}">{{optional($c)->group_code}}</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{url('admin/group-loan-deposit')}}" class="btn btn-danger">Clear</a>
            </div>

        </form>
    </div>


    <form method="post"  action="{{url('admin/group-loan-approve')}}" >
        {!! csrf_field() !!}
        <div class="shadow" style="background: #fff; padding: 10px">
            <div class="row ">
                <div class="col-sm-12 ">
                    <div class="b-group">
                        <table class="table table-bordered " style="width: 100%;background-color: white" id="table-data">
                            <thead>
                            <tr>
                                <th></th>
                                <th>CenterID-Name</th>
                                <th>Group ID</th>
                                <th>Group Name</th>
                                <th>Total Loans</th>
                                <th><input type="checkbox" id="check_all_group"></th>
                            </tr>
                            </thead>
                            <tbody class="group-list">
                            <?php
                            $total = 0;
                            ?>
                            @if($g_pending != null)
                                @foreach($g_pending as $row)

                                    <?php
                                    $rand_id = rand(1,1000).time().rand(1,1000);
                                    $group = \App\Models\GroupLoan::find($row->group_loan_id);

                                    $center = null;
                                    if($group != null){
                                        $center = \App\Models\CenterLeader::find($group->center_id);
                                    }
                                    $total += $row->amount;
                                    ?>
                                    <tr id="p-{{$rand_id}}">
                                        <td>{{$loop->index +1}}</td>
                                        <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                                        <td>{{optional($group)->group_code}}</td>
                                        <td>{{optional($group)->group_name}}</td>
                                        <td>{{$row->amount}}</td>
                                        <td class="noExl" >
                                            <a href="{{url("admin/list-member-pending?group_loan_id={$row->group_loan_id}&rand_id={$rand_id}")}}"
                                               data-remote="false" data-toggle="modal" data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                            <input type="checkbox" class="form-check-input c-checked-group"
                                                   data-payment="{{$row->amount}}" name="approve_check[{{$rand_id}}]"
                                                   value="{{$row->group_loan_id}}"/>
                                            <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$row->group_loan_id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
                                <td colspan="2">{{$total}}</td>
                            </tr>
                            </tbody>

                        </table>
                        <div class="my-p-p-p">
                            {!! $g_pending->links() !!}
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Note</label>
                        <input class="form-control" name="approve_note">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Date</label>
                        <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                        <span></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Cash Payment</label>
                        <input type="number" class="form-control cash-topay" name="" value="" >
                    </div>
                </div>
                <div class="col-sm-4">
                    <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Approve" />
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="show-detail-modal-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel2"></h4>
                </div>
                <div class="modal-body">


                    @if(isset($row))
                        <iframe id="iframe{{ optional($row)->id }}" style="width: 100%;height:500px"></iframe>
                    @endif
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')

    {{--<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>--}}
    <script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("#show-detail-modal-group").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            //alert(link.attr("href"));
            // $(this).find(".modal-body").load(link.attr("href"));


            $('#iframe').prop('src',link.attr("href"));

        });
        $(function () {
            $('.approve_date').datepicker(
                { format: 'yyyy-mm-dd'}
            );

            /*$("#show-detail-modal-group").on("show.bs.modal", function(e) {
                alert('ok');
                var link = $(e.relatedTarget);
                $(this).find(".modal-body").load(link.attr("href"));
            });*/
            //alert('ok');
        });

        function sum_payment() {
        var total = 0;

        $('.c-checked-group').each(function () {

            if ($(this).is(':checked')) {
                var payment = $(this).data('payment');
                total += payment;
            }
        });
        $('.cash-topay').val(total);

    }
    </script>

    <script>

function check_b(){
            $('#check_all_group').on('change', function (event) {

                if (this.checked) { // check select status
                    $('.c-checked-group').each(function () {
                        this.checked = true;  //select all
                        sum_payment();

                    });
                } else {
                    $('.c-checked-group').each(function () {
                        this.checked = false; //deselect all

                        sum_payment();
                    });
                }
            });

            $('.c-checked-group').on('change', function () {
                sum_payment();
            });
        }

        $(function () {
             check_b();
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

        });
        $(function () {
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
        });
    </script>
    <script>
        $(".select2_field-group").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select group",
                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "group_code";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });
        $(".select2_field-center").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select center",

                    ajax: {
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "title";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                ;

            }
        });



        function exportTableToExcel(tableID, filename = ' '){
            // $('#table-data tr').find('th:last-child, td:last-child').remove();
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            // console.log(tableSelect);
            // tableSelect.find('tbody').remove();

            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename?filename+'.xls':'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType,
                    exclude: ".noExl"
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }

    </script>

    <script>
        $(function () {
            $('.my-p-p-p .pagination').show();
        });
    </script>


@endsection




