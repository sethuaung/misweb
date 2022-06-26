<header class="main-header">
  <!-- Logo -->
  <a href="javascript:void(0)" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">{!! config('backpack.base.logo_mini') !!}</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">
        <?php
        $branches = \App\Models\Branch::all();
        //dd(session('branch_id'));
        ?>
        <select style="color: #000;" class="change-branch-top form-control select2_field my_select ">
            @if($branches != null)
                @php
                $s_branch_id =  session('s_branch_id');
                if($s_branch_id == null){
                    $bra = $branches->first();
                    if($bra != null){
                         session(['s_branch_id'=>$bra->id]);
                    }
                }
                @endphp
                @foreach($branches as $r)
                    <option {{ session('s_branch_id')==$r->id?'selected':'' }} value="{{$r->id}}">{{$r->code}}-{{$r->title}}</option>
                @endforeach
            @endif
        </select>
    </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
      @if(!(request()->is_frame >0))
          @include('backpack::inc.menu')
      @endif
  </nav>
</header>
