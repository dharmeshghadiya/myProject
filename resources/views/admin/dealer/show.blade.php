@extends('admin.layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/fancybox/jquery.fancybox.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ $details->name }} {{ config('languageString.detail') }}</h4>

            </div>
        </div>

        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('admin::addBranch',[$details->id,$details->companies->id]) }}"
                   class="btn btn-primary  mr-2">
                    <i class="mdi mdi-plus-circle"></i> {{ config('languageString.add_new_ranch') }}
                </a>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-6 py-2">
            <div class="card h-100">
                <div class="card-body">
                    <p class="card-title mb-3">{{ config('languageString.business_bank_license_details') }}</p>
                    <div class="row border-top border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0"> {{ config('languageString.company_name') }} </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->name }}</p>
                        </div>
                    </div>

                    <div class="row  border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.bank_iban') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->iban }}</p>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.license_umber') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->license_number }}</p>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.trade_license_image') }}</p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ asset($details->companies->trade_license_image) }}"
                               class="btn btn-secondary btn-sm fancybox">{{ config('languageString.view_image') }} </a>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.dealer_logo') }}</p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ asset($details->companies->dealer_logo) }}"
                               class="btn btn-secondary btn-sm fancybox">{{ config('languageString.view_image') }} </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-6 py-2">
            <div class="card card h-100">
                <div class="card-body">
                    <p class="card-title mb-3">{{ config('languageString.contact_details') }}</p>
                    <div class="row border-top border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.name') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->name }}</p>
                        </div>
                    </div>
                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.email') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->email }}</p>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.mobile_no') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{ $details->companies->mobile_no }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15 ">{{ config('languageString.current_year_total_booking') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 ">{{ $total_current_year_booking }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15 ">{{ config('languageString.current_month_total_booking') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 ">{{ $total_current_month_booking }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15 ">{{ config('languageString.current_year_total_amount_earning') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 ">
                                    ${{ $total_current_year_amount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15 ">{{ config('languageString.current_month_total_amount_earning') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1">
                                    ${{ $total_current_month_amount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15">{{ config('languageString.total_balance') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1">${{ $due_balance }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15">{{ config('languageString.total_ryde') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1">{{ $total_ride }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15">{{ config('languageString.total_roday_booked_ryde') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1">{{ $total_today_ride_booked }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><p
                            class="mb-3 tx-15">{{ config('languageString.total_maintenance') }}</p>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1">{{ $maintenance_ride }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="border-bottom mb-3">
                        <p class="card-title">{{ config('languageString.branches') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.phone_no') }}</th>
                                <th>{{ config('languageString.address') }} </th>
                                <th>{{ config('languageString.service_distance') }} </th>
                                <th>{{ config('languageString.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $key=>$branch)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $branch->phone_no }}</td>
                                    <td>{{ $branch->address }}</td>
                                    <td>{{ $branch->service_distance }} KM</td>
                                    <td>
                                        <div class="btn-icon-list">
                                            <a href="{{ route('admin::editBranch',[$details->id,$branch->id]) }}"
                                               class="btn btn-info btn-icon"
                                               data-effect="effect-fall"
                                               data-id="{{ $branch->id }}"
                                               data-toggle="tooltip" data-placement="top"
                                               title="{{ config('languageString.edit') }}">
                                                <i class="bx bx-pencil font-size-16 align-middle"></i>
                                            </a>

                                            <button
                                                class="branch-details btn btn-secondary btn-icon"
                                                data-effect="effect-fall"
                                                data-id="{{ $branch->id }}"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ config('languageString.view_details') }}">
                                                <i class="bx bx-bullseye font-size-16 align-middle"></i>
                                            </button>

                                            <a href="{{ route('admin::viewRyde',[$details->companies->id,$branch->id]) }}"
                                               class="btn btn-indigo btn-icon"
                                               data-effect="effect-fall"
                                               data-id="{{ $branch->id }}"
                                               data-toggle="tooltip" data-placement="top"
                                               title="{{ config('languageString.rydes') }}">
                                                <i class="bx bx-car font-size-16 align-middle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>

    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="globalModalDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ config('languageString.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/plugins/fancybox/jquery.fancybox.js')}}"></script>
    <script src="{{URL::asset('assets/js/custom/dealerDetails.js')}}?v={{ time() }}"></script>
@endsection
