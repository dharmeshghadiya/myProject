<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('admin::admin') }}">
            <img src="{{URL::asset('assets/img/brand/logo-dark.png')}}" class="main-logo" alt="logo">
        </a>
        <a class="desktop-logo logo-dark active" href="{{ route('admin::admin') }}">
            <img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="main-logo dark-theme" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('admin::admin') }}">
            <img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-icon" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('admin::admin') }}">
            <img src="{{URL::asset('assets/img/brand/favicon-white.png')}}" class="logo-icon dark-theme" alt="logo">
        </a>
    </div>

    <div class="main-sidemenu">
{{--        <div class="app-sidebar__user clearfix">--}}
{{--            <div class="dropdown user-pro-body">--}}
{{--                <div class="">--}}
{{--                    <img alt="user-img" class="avatar avatar-xl brround"--}}
{{--                         src="{{ URL::asset(Auth::user()->image) }}"><span--}}
{{--                        class="avatar-status profile-status bg-green"></span>--}}
{{--                </div>--}}
{{--                <div class="user-info">--}}
{{--                    <h4 class="font-weight-semibold mt-3 mb-0">{{  Auth::user()->name }}</h4>--}}
{{--                    @php /*<span class="mb-0 text-muted">Premium Member</span>*/ @endphp--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <ul class="side-menu mt-3">
            <li class="side-item side-item-category">Main</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::admin') }}">
                    <i class="fa fa-tachometer-alt side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.dashboard') }}</span>
                </a>
            </li>

            @if(Auth::user()->parent_id==0)
                <li class="side-item side-item-category">{{ config('languageString.branches') }}</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                        <i class="fa fa-code-branch side-menu__icon"></i>
                        <span class="side-menu__label">{{ config('languageString.branches') }}</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('dealer::branch.index') }}">{{ config('languageString.branches') }}</a></li>
                        <li><a class="slide-item" href="{{ route('dealer::branch.create') }}">{{ config('languageString.add_branch') }}</a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->parent_id==0)
                <li class="side-item side-item-category">{{ config('languageString.main_ryde') }}</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                        <i class="fa fa-code-branch side-menu__icon"></i>
                        <span class="side-menu__label">{{ config('languageString.main_ryde') }}</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('dealer::mainRyde.index') }}">{{ config('languageString.rydes') }}</a></li>
                        <li><a class="slide-item" href="{{ route('dealer::mainRyde.create') }}">{{ config('languageString.add_ryde') }}</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->parent_id==0)
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('dealer::payment.index') }}">
                        <i class="fa fa-money-check-alt side-menu__icon"></i>
                        <span class="side-menu__label">{{ config('languageString.manage_payments') }}</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->parent_id==0)
            <li class="slide">
                <a class="side-menu__item" href="{{ route('dealer::booking.index') }}">
                    <i class="fa fa-flag side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.booking') }}</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->parent_id==0)
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('dealer::extra.index') }}">
                        <i class="fa fa-money-check-alt side-menu__icon"></i>
                        <span class="side-menu__label">{{ config('languageString.extras') }}</span>
                    </a>
                </li>
            @endif


        </ul>
    </div>
</aside>
<!-- main-sidebar -->
