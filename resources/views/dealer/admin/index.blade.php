@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.dashboard')}}</h2>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    <div class="row row-sm">
        <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">TODAY ORDERS</h6></div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class=""><h4 class="tx-20 font-weight-bold mb-1 text-white">$5,74.12</h4>
                                <p class="mb-0 tx-12 text-white op-7">Compared to last week</p></div>
                            <span class="float-right my-auto ml-auto"> <i class="fas fa-arrow-circle-up text-white"></i> <span
                                        class="text-white op-7"> +427</span> </span></div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1"><canvas width="283" height="30"
                                                              style="display: inline-block; width: 283px; height: 30px; vertical-align: top;"></canvas></span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">TODAY EARNINGS</h6></div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class=""><h4 class="tx-20 font-weight-bold mb-1 text-white">$1,230.17</h4>
                                <p class="mb-0 tx-12 text-white op-7">Compared to last week</p></div>
                            <span class="float-right my-auto ml-auto"> <i
                                        class="fas fa-arrow-circle-down text-white"></i> <span class="text-white op-7"> -23.09%</span> </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1"><canvas width="283" height="30"
                                                               style="display: inline-block; width: 283px; height: 30px; vertical-align: top;"></canvas></span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">TOTAL EARNINGS</h6></div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class=""><h4 class="tx-20 font-weight-bold mb-1 text-white">$7,125.70</h4>
                                <p class="mb-0 tx-12 text-white op-7">Compared to last week</p></div>
                            <span class="float-right my-auto ml-auto"> <i class="fas fa-arrow-circle-up text-white"></i> <span
                                        class="text-white op-7"> 52.09%</span> </span></div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1"><canvas width="283" height="30"
                                                               style="display: inline-block; width: 283px; height: 30px; vertical-align: top;"></canvas></span>
            </div>
        </div>
       
    </div>
    <!-- row -->
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
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection
