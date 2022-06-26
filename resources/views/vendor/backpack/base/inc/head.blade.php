<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@if (config('backpack.base.meta_robots_content'))
<meta name="robots" content="{{ config('backpack.base.meta_robots_content', 'noindex, nofollow') }}">
@endif

{{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>
  {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
</title>

@yield('before_styles')
@stack('before_styles')

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">
{{--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">--}}
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/ionicons/css/ionicons.min.css">

<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
<link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

{{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/fonts/sans-pro.css">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/fonts/moul.css">

<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
<link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />



@push('after_styles')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css')}}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
{{--<link href="https://fonts.googleapis.com/css?family=Content|Moul|Titillium+Web" rel="stylesheet">--}}


<!-- BackPack Base CSS -->
<link rel="stylesheet" href="{{ asset('vendor/backpack/base/backpack.base.css') }}?v=3">
@if (config('backpack.base.overlays') && count(config('backpack.base.overlays')))
    @foreach (config('backpack.base.overlays') as $overlay)
    <link rel="stylesheet" href="{{ asset($overlay) }}">
    @endforeach
@endif


@yield('after_styles')
@stack('after_styles')

<style>
    .skin-blue .main-header .navbar{
        background: #03a9f4;
        color: white;
    }
    .skin-blue .main-header .logo{
        background: #03a9f4;
        border-color: #03a9f4 !important;
    }

    .btn-primary{
        background: #00BCD4;
    }
    .btn-primary:hover{
        border-color: #008697 !important;
        background: #008fa1;
    }
    .btn-primary:focus, .btn-primary.focus, .btn-primary:hover {
        background-color: #00BCD4;
    }
    .btn:hover, .btn:focus, .btn.focus{
        -webkit-box-shadow: 0 0 0 100px rgba(0, 0, 0, 0.05) inset;
        box-shadow: 0 0 0 100px rgba(0, 0, 0, 0.05) inset;
    }
    .btn-sm:not(.btn-rounded), .btn-group-sm > .btn:not(.btn-rounded), .btn-xs:not(.btn-rounded), .btn-group-xs > .btn:not(.btn-rounded){
        border-radius: 3px;
    }
</style>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
