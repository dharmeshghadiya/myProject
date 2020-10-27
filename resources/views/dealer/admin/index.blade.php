@extends('admin.layouts.master')
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.dashboard')}}</h2>
            </div>
        </div>
        <div class="main-dashboard-header-right">
            <div>
                <label class="tx-13">Customer Ratings</label>
                <div class="main-star">
                    <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i> <span>(14,873)</span>
                </div>
            </div>
            <div>
                <label class="tx-13">Rydezilla Sales</label>
                <h5>563,275</h5>
            </div>
            <div>
                <label class="tx-13">Walk In Sales</label>
                <h5>783,675</h5>
            </div>
        </div>
    </div>

    <!-- breadcrumb -->
@endsection
@section('content')

       <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">TOTAL BOOKINGS</h6></div>
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
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">TOTAL REVENUE</h6></div>
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
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class=""><h6 class="mb-3 tx-12 text-white">UPCOMING BALANCE</h6></div>
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

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden sales-card bg-warning-gradient">
                            <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                                <div class="">
                                    <h6 class="mb-3 tx-12 text-white">TOTAL RYDES</h6>
                                </div>
                                <div class="pb-0 mt-0">
                                    <div class="d-flex">
                                        <div class="">
                                            <h4 class="tx-20 font-weight-bold mb-1 text-white">$4,820.50</h4>
                                            <p class="mb-0 tx-12 text-white op-7">Compared to last week</p>
                                        </div>
                                        <span class="float-right my-auto ml-auto">
                                            <i class="fas fa-arrow-circle-down text-white"></i>
                                            <span class="text-white op-7"> -152.3</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span id="compositeline4" class="pt-1"><canvas width="283" height="30"
                                                               style="display: inline-block; width: 283px; height: 30px; vertical-align: top;"></canvas></span>
                        </div>
                </div>

    </div>


    <div class="row row-sm">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mb-0">Earning Per Month</h4>
                                </div>
                                <!-- <p class="tx-12 text-muted mb-0">Order Status and Tracking. Track your order from ship date to arrival. To begin, enter your order number.</p> -->
                            </div>
                            <div class="card-body">
                                <div class="total-revenue">
                                    <div>
                                      <h4>120,750</h4>
                                      <label><span class="bg-primary"></span>success</label>
                                    </div>
                                    <div>
                                      <h4>56,108</h4>
                                      <label><span class="bg-danger"></span>Pending</label>
                                    </div>
                                    <div>
                                      <h4>32,895</h4>
                                      <label><span class="bg-warning"></span>Failed</label>
                                    </div>
                                  </div>
                                <div id="bar" class="sales-bar mt-4"></div>
                            </div>
                        </div>
                    </div>

    </div>
    <div class="row row-sm row-deck">

                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card card-table-two">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mb-1">Upcoming Pickups (5)</h4>
                            </div>
                           <!--  <span class="tx-12 tx-muted mb-3 ">This is your most recent earnings for today's date.</span> -->
                            <div class="table-responsive country-table">
                                <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-lg-15p">User Name</th>
                                            <th class="wd-lg-20p">Ryde Model</th>
                                            <th class="wd-lg-25p">Location</th>
                                            <th class="wd-lg-25p">Plate Number</th>
                                            <th class="wd-lg-40p">Pickup Date</th>
                                            <th class="wd-lg-25p">Pickup Time</th>
                                            <th class="wd-lg-25p">View All Pickups</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>DFGTN TYOER</td>
                                            <td>CDFG-67-GH</td>
                                            <td>DFG US</td>
                                            <td>DFGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>ABC</td>
                                            <td>DABC-2019</td>
                                            <td>LHR pK</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>ADCE</td>
                                            <td>DF-2020</td>
                                            <td>LHR pK</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>DFGRT</td>
                                            <td>FGT-6FG</td>
                                            <td>Dehli India</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>BNGTYER RTG</td>
                                            <td>FGNT65-678</td>
                                            <td>UK GRHT</td>
                                            <td>DFSE4</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>DFGTN TYOER</td>
                                            <td>CDFG-67-GH</td>
                                            <td>DFG US</td>
                                            <td>DFGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
    </div>
    <div class="row row-sm row-deck">

                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card card-table-two">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mb-1">Upcoming Returns (5)</h4>
                            </div>
                           <!--  <span class="tx-12 tx-muted mb-3 ">This is your most recent earnings for today's date.</span> -->
                            <div class="table-responsive country-table">
                                <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-lg-15p">User Name</th>
                                            <th class="wd-lg-20p">Ryde Model</th>
                                            <th class="wd-lg-25p">Location</th>
                                            <th class="wd-lg-25p">Plate Number</th>
                                            <th class="wd-lg-40p">Pickup Date</th>
                                            <th class="wd-lg-25p">Pickup Time</th>
                                            <th class="wd-lg-25p">View All Pickups</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>ABC</td>
                                            <td>DABC-2019</td>
                                            <td>LHR pK</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>ADCE</td>
                                            <td>DF-2020</td>
                                            <td>LHR pK</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>DFGRT</td>
                                            <td>FGT-6FG</td>
                                            <td>Dehli India</td>
                                            <td>FGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>BNGTYER RTG</td>
                                            <td>FGNT65-678</td>
                                            <td>UK GRHT</td>
                                            <td>DFSE4</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                        <tr>
                                            <td>DFGTN TYOER</td>
                                            <td>CDFG-67-GH</td>
                                            <td>DFG US</td>
                                            <td>DFGT56</td>
                                            <td>05 Dec 2019</td>
                                            <td>4:30PM</td>
                                            <td class="tx-right tx-medium tx-danger">67</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
<script src="{{URL::asset('assets/js/index.js')}}"></script>
@endsection
