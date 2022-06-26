<div class="row" style="padding-top: 25px">
    <div class="col-md-9">
        <input type="file" name="open_detail_file" class="form-control">
    </div>
    <div class="col-md-3">
        <span><a href="{{url('api/download-loan')}}" class="btn btn-primary"><i class="fa fa-download"></i> Download Sample</a></span>
    </div>

</div>
<div class="container-fluid">
@php 
    $files = \App\Models\Loan::where('excel','!=',"")->where('excel','!=',Null)->get()->toArray();
@endphp
@foreach ($files as $file)
@php
    $file_id = $file['id'];
@endphp
    <div class="row" style="padding-top: 25px">
    <div class="col-md-9">
        <input type="text" value="{{$file['excel']}}" class="form-control" readonly>
    </div>
    <div class="col-md-3">
        <span><a href="{{url("api/download-excel/{$file_id}")}}" class="btn btn-primary"><i class="fa fa-download"></i> Download Excel</a></span>
    </div>
    </div>
@endforeach
</div>

@push('crud_fields_scripts')
    <script>

        $(function () {
            $('form').prop('enctype', 'multipart/form-data');
        });

    </script>
@endpush
