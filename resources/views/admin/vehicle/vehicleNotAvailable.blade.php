@extends('admin.layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}"
          rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.ryde_not_availability') }} </h2>
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
                    <form method="POST" data-parsley-validate="" id="vehicleNotAvailableForm" role="form">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ $vehicle_id }}">

                        <div class="row row-sm">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.start_date') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control" id="start_date" name="start_date" type="text"
                                               autocomplete="off">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.end_date') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control" id="end_date" name="end_date" type="text"
                                               autocomplete="off">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                             <div class="col-12">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.description') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">

                                        <input class="form-control" id="description" name="description" type="text"
                                               autocomplete="off">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::viewRyde',[$vehicle->company_id,$vehicle->company_address_id]) }}"
                                           class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12" id="vehicle_not_available_id">
            @include('admin.vehicle.vehicleNotAvailableShow')
        </div>

    </div>
    </div>
    </div>
@endsection
@section('js')
<script>
    const title = "{{ config('languageString.destroy_ryde_not_availability').'?' }}";
    const text = "{{ config('languageString.confirm_message') }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it') }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx') }}";
</script>
    <script src="{{URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/custom/vehicleNotAvailable.js')}}?v={{ time() }}"></script>
@endsection
