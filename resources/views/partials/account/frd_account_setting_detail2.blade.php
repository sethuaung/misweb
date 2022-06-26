<tr>
    <td>
        {!! $no !!}
    </td>
    <td>
        {!! $title !!}
    </td>

    <?php

    /*    if (!isset($code)){
            $code='pl-'.$no;
        }*/

    ?>

    <td>

        <select class="form-control js-acc_code" multiple="multiple" data-code="{{ $code }}" name="acc_code" id="acc_code" style="width: 100%">

            <?php
            $m = \App\Models\FrdAccDetail2::where('code', $code)->get();
            ?>
            @if($m != null)
                @foreach($m as $a)
                    <?php
                    $acc = \App\Models\AccountChart::find($a->chart_acc_id);
                    ?>
                    @if ($acc != null)
                        <option selected="selected" value="{{$acc->id}}">{{$acc->code}}-{{$acc->name}}</option>

                    @endif
                @endforeach
            @endif

        </select>


    </td>
</tr>