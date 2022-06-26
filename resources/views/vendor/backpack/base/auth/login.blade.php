@extends('backpack::layout_guest')

@section('content')
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);
    ?>
    <div class="row m-t-25">
        <div class="col-md-4 col-md-offset-4">
            <style>
                .center {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 50%;
                    margin-top: 20px;
                    max-height: 100px;
                }
            </style>

{{--            <h3 class="text-center m-b-20">{{ trans('backpack::base.login') }}</h3>--}}
            <div class="box login-box">


                <div class=" login-logo  p-10 ">
                        @if (file_exists($logo))
                            <img class="img-responsive  hsm-img center" src="{{asset($logo)}}" width="100%">
                        @else
                            <img width="100%" src="{{asset('logo/cloudnet_logo.png')}}" class="img-responsive  hsm-img center" alt="Logo Image">
                        @endif
{{--                    <span><h1>{{$company}}</h1></span>--}}
                </div>

                <div class="box-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has($username) ? ' has-error' : '' }}">
                            <label class="control-label">{{ config('backpack.base.authentication_column_name') }}</label>
                             <input type="text" class="form-control radius-all" name="{{ $username }}" value="{{ old($username) }}" placeholder="Email">

                                @if ($errors->has($username))
                                    <span class="help-block">
                                        <strong>{{ $errors->first($username) }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">{{ trans('backpack::base.password') }}</label>
                             <input type="password" class="form-control radius-all" name="password" placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button  type="submit" class="btn btn-block btn-primary" style="font-size: 15px;border-radius: 8px">
                                    {{ trans('backpack::base.login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
         {{--   @if (backpack_users_have_email())
                <div class="text-center m-t-10"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
            @endif
            @if (config('backpack.base.registration_open'))
                <div class="text-center m-t-10"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
            @endif--}}
        </div>
    </div>
@endsection


@section('after_styles')
    <style>
        .content-wrapper{
            background-color: #f9f9f9;
        }

        .box{
            background-color: #ffffff;

            /*border-radius: 7px;*/
            /*border-radius: 2px;*/
            /*box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.3);*/
            padding: 10px;
            margin: 0 auto 10px;
            border-top: 1px solid #ffffff;
            font-family: "Poppins" !important;

        }
        .main-footer{
            background-color: #ffffff;
        }

        .form-control, .select2-container--bootstrap .select2-selection, .input-group-addon{
            /*border-color: #dfdfdf !important;*/
            /*color: #313c58;*/
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
            font-family: "Poppins" !important;

        }

        .login-box, .register-box{
            width: 400px;
        }

     /*   .form-control:not(select):focus{
            border-color: #d5d5d5 !important;
            !* box-shadow:inset 0 0 3px 3px #f2f2f2;
             background: #fff;*!

            !*-webkit-border-radius:5px;*!
            !*-moz-border-radius:5px;*!
            !*border-radius:5px;*!
            -webkit-box-shadow:0 0 5px #e9e9e9 inset;
            -moz-box-shadow:0 0 5px #e9e9e9 inset;
            box-shadow:0 0 5px #e9e9e9 inset;

        }*/

    </style>
@stop
