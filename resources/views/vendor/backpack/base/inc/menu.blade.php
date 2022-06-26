
@php
    $user=auth()->user();
    $unreadNotifications = [];

    if ($user != null){
     $unreadNotifications = $user->unreadNotifications;
    }
@endphp
<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->

        @if (backpack_auth()->check())
            <!-- Topbar. Contains the left part -->
            @include('backpack::inc.topbar_left_content')
        @endif

    <!-- ========== End of top menu left items ========== -->
    </ul>
</div>


<div class="navbar-custom-menu pull-right">
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);
    ?>

    <ul class="nav navbar-nav">
        <li><img src="{{asset($logo)}}" height="51"></li>
        <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">{{count($unreadNotifications)}}</span>
            </a>
            <ul class="dropdown-menu" style="width:400px;">
                <li class="header">You have {{ count($unreadNotifications) }} notifications</li>
                <li>
                    <!-- inner menu: contains the actual data -->

                    <ul class="menu infinite-scroll" style="overflow-y: scroll; max-height:350px;">
                        {{-- <div class="infinite-scroll"> --}}

                        {{-- @if(count($unreadNotifications) >0 )
                            @foreach ($user->unreadNotifications as $notification)
                                <li>
                                    @break($notification->type=="App\Notifications\LatePaymentNotification")
                                    @if($notification->type=="App\Notifications\LatePaymentNotification")
                                        @php
                                        $loan_id = $notification->data['details']['disbursement_id'];
                                        $loan=  \App\Models\Loan::find($loan_id);
                                        @endphp
                                        <a href="{{url("/read_notification/{$notification->id}")}}" >
                                            <i class="fa fa-money "></i> Customer <b>{{ optional(optional($loan)->client_name)->name }}</b>  Payment Late on Loan {{optional($loan)->disbursement_number}}
                                        </a>
                                    @endif
                                </li>
                            @endforeach

                        @endif --}}
                        <?php
                        $notifications = \App\Models\Notification::where('notifiable_id', 1)->paginate(10);
                        // dd($notifications);
                        foreach ($notifications as $key => $notification) {
                            if($notification->type=="App\Notifications\LatePaymentNotification"){

                                $notification_data = json_decode($notification->data)->details;
                                $loan_id = $notification_data->disbursement_id;
                                $loan=  \App\Models\Loan::find($loan_id);
                                // var_dump($loan);
                        ?>
                        <li style="padding: 5px 10px; border-bottom: 1px solid #f4f4f4;">
                            <a href="{{url("/read_notification/{$notification_data->id}")}}">
                                <i class="fa fa-money "></i> Customer <b>{{ optional(optional($loan)->client_name)->name }}</b>  Payment Late on Loan {{optional($loan)->disbursement_number}} , {{ $key }}
                            </a>
                        </li>
                        <?php
                            }
                            // echo "<li><a href=''>".$notification->type."</a></li>";
                        }
                        ?>

                        {{ $notifications->links() }}
                        {{-- </div> --}}
                    </ul>
                </li>
                <li class="footer"><a href="{{url("list_notification_all")}}">View all</a></li>
            </ul>
        </li>
        <!-- Notifications: style can be found in dropdown.less -->

    </ul>














    <ul class="nav navbar-nav">
        <!-- ========================================================= -->
        <!-- ========= Top menu right items (ordered right) ========== -->
        <!-- ========================================================= -->

        @if (config('backpack.base.setup_auth_routes'))
            @if (backpack_auth()->guest())
                <li>
                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/login') }}">{{ trans('backpack::base.login') }}</a>
                </li>
                @if (config('backpack.base.registration_open'))
                    <li><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
                @endif
            @else
                <!-- Topbar. Contains the right part -->
                @include('backpack::inc.topbar_right_content')
                <li><a href="{{ route('backpack.auth.logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('backpack::base.logout') }}</a></li>
            @endif
        @endif
        <!-- ========== End of top menu right items ========== -->
    </ul>


</div>
