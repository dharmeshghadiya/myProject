@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.dealer') }}</h2>
            </div>
        </div>

        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">

            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-body p-2">
            <div class="input-group">
                <select id="country_id" name="country_id" class="form-control select2">
                    <option value="">{{ config('languageString.please_select_country') }}</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach

                </select> <span
                    class="input-group-append"> <button class="btn btn-secondary" id="dealer-search" type="button">{{ config('languageString.search') }}</button> </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.name') }}</th>
                                <th>{{ config('languageString.email') }}</th>
                                <th>{{ config('languageString.mobile_no') }}</th>
                                <th>{{ config('languageString.total_ryde') }}</th>
                                <th>{{ config('languageString.status') }}</th>

                                <th>{{ config('languageString.actions') }}</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_dealer').'?' }}";
        const dealer_destroy = "{{ config('languageString.dealer_destroy').'?' }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it').'?' }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx').'?' }}";
        const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
        const yes_change_btn = "{{ config('languageString.yes_change_it') }}";

    </script>
    <script src="{{URL::asset('assets/js/custom/dealer.js')}}?v={{ time() }}"></script>
@endsection
