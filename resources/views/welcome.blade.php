{{--<table>
    <tr>
        @foreach($users->first()->toArray() as $f=>$v)
            <th>{{$f}}</th>
        @endforeach
    </tr>
    @foreach($users as $user)
        <tr style="background-color: red;">
            @foreach($user->toArray() as $f=>$v)
                <th>{{  $v }}</th>
            @endforeach
        </tr>
    @endforeach
</table>--}}
