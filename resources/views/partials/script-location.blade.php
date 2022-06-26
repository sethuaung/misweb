<?php
    $_e = isset($entry)?$entry:null;
    $locations = optional($_e)->locations;
    //$rand_id = rand(1,1000).time().rand(1,1000);
?>

@if(isset($id))

<div class="location-detail">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ _t('Location') }}</th>
                <th>{{ _t('Aisle') }}</th>
                <th>{{ _t('Bay') }}</th>
                <th>{{ _t('Bin') }}</th>
                <th>{{ _t('Level') }}</th>
                <th>{{ _t('Direction') }}</th>
                <th>{{ _t('Color') }}</th>
                <th>{{ _t('For Sale') }}</th>
                <th><a class="btn btn-xs btn-primary add-detail"> + </a></th>
            </tr>
        </thead>
        <tbody class="show-rows-detail">
        @if($locations != null)
            @if(count($locations)>0)
                @foreach($locations as $location)
                   @include('partials.location.location_detail',['r' => $location])
                @endforeach
            @endif
        @endif
        </tbody>
    </table>

</div>

@push('crud_fields_styles')
    <style>

    </style>
@endpush

@push('crud_fields_scripts')

    <script>
        var warehouse_id = '{{optional($_e)->id}}';

        $(function () {


            $('body').on('click','.add-detail',function () {
                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/add-location')}}',
                    data: {
                        warehouse_id : warehouse_id
                    },
                    success: function (res) {
                        $('.show-rows-detail').append(res);
                    }

                });
            });

            $('body').on('click','.del-detail',function () {
                var location_id = $(this).data('location_id');
                var rid = $(this).data('rid');
                var tr = $('#id-' + rid);
                $.ajax({
                    type: 'POST',
                    url: '{{url('/api/del-location')}}',
                    data: {
                        location_id : location_id
                    },
                    success: function (res) {
                        if(res.error == 0) {
                            tr.remove();
                        }
                    }

                });
            });

            $('body').on('keyup','.l-aisle,.l-bay,.l-bin,.l-level',function () {
                var rid = $(this).data('rid');
                var tr = $('#id-' + rid);
                delay(function(){
                    genLocation(tr);
                }, 1000 );
            });

            $('body').on('change','.l-direction,.l-color,.l-for-sale',function () {
                var rid = $(this).data('rid');
                var tr = $('#id-' + rid);
                genLocation(tr);
            });

        });

         function removeLastChar(value, char){
            var lastChar = value.slice(-1);
            if(lastChar == char) {
                value = value.slice(0, -1);
            }
            return value;
        }

        function genLocation(tr) {
            var location_id = tr.data('location_id');
            var aisle = tr.find('.l-aisle').val();
            var bay = tr.find('.l-bay').val();
            var bin = tr.find('.l-bin').val();
            var level = tr.find('.l-level').val();
            var direction = tr.find('.l-direction').val();

            var color = tr.find('.l-color').val();
            var for_sale = tr.find('.l-for-sale').val();

            var location =  aisle + '-' + bay + '-' + bin + '-' + level + '-' + direction;
            location = removeLastChar((location.replace(/\-\-/g,'-')).replace(/\-\-/g,'-'),'-');

            tr.find('.l-location').val(location);

            $.ajax({
                type: 'POST',
                url: '{{url('/api/save-location')}}',
                data: {
                    location_id : location_id,
                    aisle : aisle,
                    bay : bay,
                    bin : bin,
                    level : level,
                    direction : direction,
                    color : color,
                    for_sale : for_sale,
                    location_name: location
                },
                success: function (res) {

                }

            });

        }


    </script>
@endpush

@endif