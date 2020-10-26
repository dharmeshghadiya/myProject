!-- main-sidebar -->
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
            <img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-icon dark-theme" alt="logo">
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
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::admin') }}">
                    <i class="fa fa-tachometer-alt side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.dashboard') }}</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-users-cog side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.dealer') }}</span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                           href="{{ route('admin::dealer.index') }}">{{ config('languageString.dealer') }}</a></li>
                    <li><a class="slide-item"
                           href="{{ route('admin::dealer.create') }}">{{ config('languageString.add_dealer') }}</a></li>
                </ul>
            </li>

            <?php /*
            <li class="side-item side-item-category">Vehicle</li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M10.9 19.91c.36.05.72.09 1.1.09 2.18 0 4.16-.88 5.61-2.3L14.89 13l-3.99 6.91zm-1.04-.21l2.71-4.7H4.59c.93 2.28 2.87 4.03 5.27 4.7zM8.54 12L5.7 7.09C4.64 8.45 4 10.15 4 12c0 .69.1 1.36.26 2h5.43l-1.15-2zm9.76 4.91C19.36 15.55 20 13.85 20 12c0-.69-.1-1.36-.26-2h-5.43l3.99 6.91zM13.73 9h5.68c-.93-2.28-2.88-4.04-5.28-4.7L11.42 9h2.31zm-3.46 0l2.83-4.92C12.74 4.03 12.37 4 12 4c-2.18 0-4.16.88-5.6 2.3L9.12 11l1.15-2z"
                            opacity=".3"></path>
                        <path
                            d="M12 22c5.52 0 10-4.48 10-10 0-4.75-3.31-8.72-7.75-9.74l-.08-.04-.01.02C13.46 2.09 12.74 2 12 2 6.48 2 2 6.48 2 12s4.48 10 10 10zm0-2c-.38 0-.74-.04-1.1-.09L14.89 13l2.72 4.7C16.16 19.12 14.18 20 12 20zm8-8c0 1.85-.64 3.55-1.7 4.91l-4-6.91h5.43c.17.64.27 1.31.27 2zm-.59-3h-7.99l2.71-4.7c2.4.66 4.35 2.42 5.28 4.7zM12 4c.37 0 .74.03 1.1.08L10.27 9l-1.15 2L6.4 6.3C7.84 4.88 9.82 4 12 4zm-8 8c0-1.85.64-3.55 1.7-4.91L8.54 12l1.15 2H4.26C4.1 13.36 4 12.69 4 12zm6.27 3h2.3l-2.71 4.7c-2.4-.67-4.35-2.42-5.28-4.7h5.69z"></path>
                    </svg>
                    <span class="side-menu__label">Vehicle</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('admin::vehicle.index') }}">Vehicles</a></li>
                    <li><a class="slide-item" href="{{ route('admin::vehicle.create') }}">Add Vehicle</a></li>
                </ul>
            </li>*/ ?>

            <?php /*
            <li class="side-item side-item-category">Manage Payments</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::admin') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none"/>
                        <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/>
                        <path
                            d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/>
                    </svg>
                    <span class="side-menu__label">Payments</span>
                </a>
            </li>*/ ?>


            @php
                $array=['body','brand','model','door','color','modelYear','engine','gearbox','fuel','insurance','option','extra','feature','modelYear'];
            @endphp

            <li class="slide  @if(in_array(request()->segment(2),$array)) is-expanded @endif">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-car side-menu__icon"></i>
                    <span class="side-menu__label">Ryde</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a class="slide-item {{ (request()->is('admin/ryde*')) ? 'active' : '' }}"
                           href="{{ route('admin::ryde.index') }}">{{ config('languageString.model') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/body*')) ? 'active' : '' }}"
                           href="{{ route('admin::body.index') }}">{{ config('languageString.bodies') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/brand*')) ? 'active' : '' }}"
                           href="{{ route('admin::brand.index') }}">{{ config('languageString.brands') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/color*')) ? 'active' : '' }}"
                           href="{{ route('admin::color.index') }}">{{ config('languageString.colors') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/modelYear*')) ? 'active' : '' }}"
                           href="{{ route('admin::modelYear.index') }}">{{ config('languageString.years') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/door*')) ? 'active' : '' }}"
                           href="{{ route('admin::door.index') }}">{{ config('languageString.doors') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/engine*')) ? 'active' : '' }}"
                           href="{{ route('admin::engine.index') }}">{{ config('languageString.engines') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/gearbox*')) ? 'active' : '' }}"
                           href="{{ route('admin::gearbox.index') }}">{{ config('languageString.gearboxes') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/fuel*')) ? 'active' : '' }}"
                           href="{{ route('admin::fuel.index') }}">{{ config('languageString.fuels') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/insurance*')) ? 'active' : '' }}"
                           href="{{ route('admin::insurance.index') }}">{{ config('languageString.insurances') }}</a>
                    </li>
                    <li>
                        <a class="slide-item {{ (request()->is('admin/option*')) ? 'active' : '' }}"
                           href="{{ route('admin::option.index') }}">{{ config('languageString.options') }}</a>
                    </li>

                    <li>
                        <a class="slide-item {{ (request()->is('admin/feature*')) ? 'active' : '' }}"
                           href="{{ route('admin::feature.index') }}">{{ config('languageString.features') }}</a>
                    </li>
                </ul>
            </li>

            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-money-bill-alt side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.manage_payments') }}</span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                           href="{{ route('admin::commission.index') }}">{{ config('languageString.ryde_zilla_commissions') }}</a>
                    </li>
                </ul>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::booking.index') }}">
                    <i class="fa fa-vr-cardboard side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.booking') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::category.index') }}">
                    <i class="fa fa-clipboard-check side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.featured_category') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::announcement.index') }}">
                    <i class="fa fa-sticky-note side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.announcement') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::users.index') }}">
                    <i class="fa fa-users side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.users') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::admins.index') }}">
                    <i class="fa fa-chalkboard-teacher side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.admins') }}</span>
                </a>
            </li>

            @php /*
            <li class="side-item side-item-category">{{ config('languageString.manage_admins') }}</li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-users-cog side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.abilities') }}</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                           href="{{ route('admin::abilities.index') }}">{{ config('languageString.abilities') }}</a>
                    </li>
                    <li><a class="slide-item"
                           href="{{ route('admin::abilities.create') }}">{{ config('languageString.add_ability') }}</a>
                    </li>
                </ul>
            </li>

            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                    <i class="fa fa-user-shield side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.roles') }}</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('admin::roles.index') }}">{{ config('languageString.roles') }}</a></li>
                    <li><a class="slide-item" href="{{ route('admin::roles.create') }}">{{ config('languageString.add_role') }}</a></li>
                </ul>
            </li>*/
            @endphp

            <li class="side-item side-item-category">{{ config('languageString.notification') }}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::reportProblem') }}">
                    <i class="fa fa-pencil-ruler side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.report_problem') }}</span>
                    <span class="badge badge-danger side-badge d-none" id="report_problem_count"></span>
                </a>
            </li>
             <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::becomeADealer.index') }}">
                    <i class="fa fa-user-circle side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.become_a_dealer') }}</span>
                    <span class="badge badge-danger side-badge d-none" id="become_dealer_count"></span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::reportModel.index') }}">
                    <i class="fa fa-adjust side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.report_model') }}</span>
                    <span class="badge badge-danger side-badge d-none" id="report_model_count"></span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::contactUs') }}">
                    <i class="fa fa-address-card side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.contact_us') }}</span>
                    <span class="badge badge-danger side-badge d-none" id="contact_request_count">New</span>
                </a>
            </li>

            <li class="side-item side-item-category">{{ config('languageString.global_settings') }}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::emailString.index') }}">
                    <i class="fa fa-envelope side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.email_string') }}</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::languageString.index') }}">
                    <i class="fa fa-language side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.language_string') }}</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::setting') }}">
                    <i class="fa fa-wrench side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.setting') }}</span>
                </a>
            </li>


            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::globalExtra.index') }}">
                    <i class="fa fa-sliders-h side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.global_extra') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::country.index') }}">
                    <i class="fa fa-globe-europe side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.country') }}</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin::page.index') }}">
                    <i class="fa fa-file-word side-menu__icon"></i>
                    <span class="side-menu__label">{{ config('languageString.page') }}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
