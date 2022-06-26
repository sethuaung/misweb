<!-- html5 date input -->
<?php
$v = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] :
    (isset($field['default']) ? $field['default'] : date('1990-m-d') ));
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ( $field['value'] instanceof \Carbon\Carbon || $field['value'] instanceof \Jenssegers\Date\Date )) {
    $field['value'] = $field['value']->toDateString();
}

if($v != null){
    $d = \App\Helpers\IDate::getDay($v)-0;
    $m = \App\Helpers\IDate::getMonth($v)-0;
    $y = \App\Helpers\IDate::getYear($v)-0;
}
?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label><br>
    @include('crud::inc.field_translatable_icon')
    {{--
    <input
        type="date"
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')
        >
    --}}


    <style>
        .year-padding{
            padding: 6px 5px;
        }
    </style>
    <div class="form-group">
    <table width="100%">
        <tr>
            <td width="30%"><select class="form-control year-padding radius-all my_select"  id="year"></select></td>
            <td width="45%"><select class="form-control radius-all my_select" id="month"></select></td>
            <td width="25%"><select class="form-control radius-all my_select" id="day"></select></td>
        </tr>
    </table>
    </div>
    <input
            type="hidden"
            name="{{ $field['name'] }}"
            value="{{ $v }}"
            @include('crud::inc.field_attributes')
    >


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>Age</label>
    <input  readonly placeholder="age" class="age form-control" type="text" value="">

</div>





@push('crud_fields_styles')

@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    <script>

        $(document).ready(function() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            var qntYears = 4;
            var selectYear = $("#year");
            var selectMonth = $("#month");
            var selectDay = $("#day");
            var currentYear = new Date().getFullYear();

            for (var y = 1950; y < {{ date('Y') }}; y++){
                let date = new Date(currentYear);
                var yearElem = document.createElement("option");
                yearElem.value = currentYear
                yearElem.textContent = currentYear;
                selectYear.append(yearElem);
                currentYear--;
            }

            for (var m = 0; m < 12; m++){
                let monthNum = new Date(2018, m).getMonth()
                let month = monthNames[monthNum];
                var monthElem = document.createElement("option");
                monthElem.value = monthNum;
                monthElem.textContent = month;
                selectMonth.append(monthElem);
            }

            var d = new Date();
            var month = d.getMonth();
            var year = d.getFullYear();
            var day = d.getDate();

            selectYear.val(year);
            selectYear.on("change", AdjustDays);
            selectMonth.val(month);
            selectMonth.on("change", AdjustDays);

            AdjustDays();
            selectDay.val(day)

            function AdjustDays(){
                var year = selectYear.val();
                var month = parseInt(selectMonth.val()) + 1;
                selectDay.empty();

                //get the last day, so the number of days in that month
                var days = new Date(year, month, 0).getDate();

                //lets create the days of that month
                for (var d = 1; d <= days; d++){
                    var dayElem = document.createElement("option");
                    dayElem.value = d;
                    dayElem.textContent = d;
                    selectDay.append(dayElem);
                }
            }


            $('body').on('change','#year,#month,#day',function () {

                var day = $('#day').val()-0;
                var month = $('#month').val()-0 + 1;
                var year = $('#year').val()-0;


                $('[name="{{ $field['name'] }}"]').val(year + '-' + month + '-' + day);

                var born = new Date(year, month , day );
                var now = new Date({{ date('Y') }}, {{ date('m')-0 }} , {{ date('d')-0 }} );
                var age =Math.floor((now.getTime() - born.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
                $('.age').val(age);

            });

            $('#day').val({{ $d }});
            $('#month').val({{ $m-1 }});
            $('#year').val({{ $y }});

            $('#year').trigger('change');

            $('#day').val({{ $d }});
            $('#month').val({{ $m-1 }});
            $('#year').val({{ $y }});

            $('#day').trigger('change');

        });

    </script>
@endpush
