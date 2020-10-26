@extends('admin.layouts.master')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_new_booking') }}</h2>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" data-parsley-validate="" id="bookingForm" role="form">
                        @csrf
                        <input type="hidden" id="form-method" value="add">

                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.booking_date') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control" id="booking_date" name="booking_date" type="text"
                                               autocomplete="off" required>
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_address_id">{{ config('languageString.branches') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">

                                        <select id="company_address_id" name="company_address_id" class="form-control"
                                                required>
                                            <option
                                                value="">{{ config('languageString.please_select_branch') }}</option>
                                            @foreach($companyAddresses as $companyAddress)
                                                <option
                                                    value="{{ $companyAddress->id }}">{{ $companyAddress->address.' ( '. $companyAddress->service_distance.' KM ) ' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('dealer::booking.index') }}"
                                           class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <div class="row" id="ryde-display"></div>
    </div>
    </div>

    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="booking-modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row  mb-3">
                        <div class="col-md-12 mb-2">
                            <div class="form-form-group">
                                <label for="email">{{ config('languageString.name') }}</label>
                                <input type="text" name="name" id="name" class="form-control"/>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <div class="form-form-group">
                                <label for="email">{{ config('languageString.email') }}</label>
                                <input type="text" name="email" id="email" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-form-group">
                                <label for="country_code">{{ config('languageString.country_code') }}</label>
                                <select id="country_code" name="country_code" class="form-control">
                                    @foreach($countries as $country)
                                        <option @if($companyAddress->country_id==$country->id) selected
                                                @endif value="{{ $country->code }}">+{{ $country->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8 mb-2">
                            <div class="form-form-group">
                                <label for="mobile_no">{{ config('languageString.mobile_no') }}</label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control"/>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <div class="form-form-group">
                                <label for="notes">{{ config('languageString.notes') }}</label>
                                <textarea type="text" name="notes" id="notes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="booking-modal-body"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="bookNow">Book</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_ryde_not_availability').'?' }}";
        const text = "{{ config('languageString.confirm_message') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script src="{{URL::asset('assets/js/custom/dealer/booking.js')}}?v={{ time() }}"></script>
@endsection
