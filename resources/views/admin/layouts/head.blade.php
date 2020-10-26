<!-- Title -->
<title> Rydezilla </title>
<!-- Favicon -->
<link rel="icon" href="{{URL::asset('assets/img/brand/favicon.png')}}" type="image/x-icon"/>
<!-- Icons css -->

<link href="{{URL::asset('assets/css/icons.css')}}" rel="stylesheet">

<!--  Custom Scroll bar-->
<link href="{{URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet"/>
<!--  Right-sidemenu css -->
<link href="{{URL::asset('assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">
<!-- Sidemenu css -->

<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/sumoselect/sumoselect.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
@yield('css')
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/bootstrap-switch-master/dist/css/bootstrap4/bootstrap-switch.css') }}"
      rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/intl-tel-input/css/intlTelInput.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/css/style-datepicker.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}" rel="stylesheet">
<!-- style css -->
<style>
    select[readonly] option, select[readonly] optgroup {
        display: none;
        pointer-events: none;
    }
</style>

@guest
    @if(substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2)=='en')
        <link rel="stylesheet" href="{{URL::asset('assets/css/sidemenu.css')}}">
         @if (!request()->is('/'))
         <link href="{{URL::asset('assets/css/style.css')}}" rel="stylesheet">
         @endif
         <link href="{{URL::asset('assets/css/style-dark.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/css/skin-modes.css')}}" rel="stylesheet"/>

    @else
        <link href="{{URL::asset('assets/css-rtl/style.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/css-rtl/style-dark.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/css-rtl/skin-modes.css')}}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{URL::asset('assets/css-rtl/sidemenu.css')}}">
    @endif
@elseif(Session::get('locale')=='en')
     @if (!request()->is('/'))
    <link href="{{URL::asset('assets/css/style.css')}}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{URL::asset('assets/css/sidemenu.css')}}">
    <link href="{{URL::asset('assets/css/style-dark.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/css/skin-modes.css')}}" rel="stylesheet"/>

@else

    <link href="{{URL::asset('assets/css-rtl/style.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/css-rtl/style-dark.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/css-rtl/skin-modes.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{URL::asset('assets/css-rtl/sidemenu.css')}}">
@endif



<script type="text/javascript">
    @guest
    var APP_URL = {!! json_encode(url('/admin')) !!};
    var is_admin_open = 1;
    @elseif(Auth::user()->user_type=='admin')
    var APP_URL = {!! json_encode(url('/admin')) !!};
    var is_admin_open = 1;
    @else
    var APP_URL = {!! json_encode(url('/dealer')) !!};
    var is_admin_open = 0;
    @endif
</script>
