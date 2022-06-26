@extends('backpack::layout')
@section('after_styles')
    <?php $base=asset('vendor/adminlte') ?>
@endsection
@section('content')

@endsection
@section('after_scripts')
    <script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
@endsection
