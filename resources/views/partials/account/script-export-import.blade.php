@if (\Illuminate\Support\Facades\Session::has('message'))
    <div class="alert alert-danger">

        {{ \Illuminate\Support\Facades\Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
@endif

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
        @endif
    @endforeach
</div>



<div class="row" style="padding-top: 25px">
    <div class="col-md-9">
        <input type="file" name="open_detail_file" class="form-control">
    </div>
    <div class="col-md-3">
        <span><a href="{{url('api/download-loan')}}" class="btn btn-primary"><i class="fa fa-download"></i> Download Sample</a></span>
    </div>

</div>

@push('crud_fields_scripts')
    <script>

        $(function () {
            $('form').prop('enctype', 'multipart/form-data');
        });

    </script>
@endpush
