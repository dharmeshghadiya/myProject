@extends('admin.layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{URL::asset('assets/plugins/clockpicker/dist/bootstrap-clockpicker.min.css')}}">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Edit Branch</h2>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Dealers</span>
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
                    <form method="POST" data-parsley-validate="" id="addEditForm" role="form">
                        @csrf
                        <input type="hidden" id="edit_value" value="{{ $branch_details->id }}" name="edit_value">
                        <input type="hidden" id="user_id" value="{{ $branch_details->companies->user_id }}"
                               name="user_id">
                        <input type="hidden" id="dealer_id" value="{{ $dealer_id }}" name="dealer_id">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="branch_name">{{ config('languageString.branch_name') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           value="{{$branch_details->branch_name}}" name="branch_name"
                                           placeholder="Branch Name"
                                           id="branch_name"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_account">{{ config('languageString.bank_account') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           value="{{$branch_details->bank_account}}" name="bank_account"
                                           placeholder="Branch Bank Account"
                                           id="bank_account"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="trading_license">{{ config('languageString.trading_license') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           value="{{$branch_details->trading_license}}" name="trading_license"
                                           placeholder="Branch Trading License"
                                           id="trading_license"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="allowed_millage">{{ config('languageString.allowed_millage') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           value="{{$branch_details->allowed_millage}}" name="allowed_millage"
                                           placeholder="Branch Allowed Millage"
                                           id="allowed_millage"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="branch_logo">{{ config('languageString.branch_logo') }}<span
                                            class="error"></span></label><br>
                                    <input type="file" class="form-control dropify"
                                           name="branch_logo"
                                           data-default-file="{{ asset($branch_details->branch_logo) }}"
                                           id="branch_logo"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="branch_name">Branch Contact Name<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           value="{{$branch_details->branch_contact_name}}" name="branch_contact_name"
                                           placeholder="Branch Contact Name"
                                           id="branch_contact_name"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="login_user_name">Login User Name<span class="error">*</span></label><br>
                                    <input type="text" class="form-control"
                                           name="login_user_name" placeholder="Login User Name"
                                           id="login_user_name" value="{{ $branch_details->users->name }}"/>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="login_user_mobile_no">Login User Contact No<span class="error">*</span></label><br>
                                    <input type="text" class="form-control integer"
                                           name="login_user_mobile_no" placeholder="Contact No"
                                           id="login_user_mobile_no" value="{{ $branch_details->users->mobile_no }}"/>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="login_user_email">Login User Email<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control"
                                           name="login_user_email" placeholder="Login User Email"
                                           id="login_user_email" value="{{ $branch_details->users->email }}" readonly/>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">Business Number<span class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="phone_no" placeholder="Business Number"
                                           value="{{ $branch_details->phone_no }}"
                                           id="phone_no" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="service_distance">Service Distance</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="service_distance"
                                               class="form-control integer"
                                               value="{{ $branch_details->service_distance }}"
                                               id="service_distance">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="service_distance">Km</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">Address<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="address" placeholder="Address"
                                           value="{{ $branch_details->address }}"
                                           id="address" required autocomplete="off"/>
                                    <input type="hidden" class="form-control" name="latitude" id="latitude"
                                           value="{{ $branch_details->latitude }}"
                                           placeholder="Latitude" required/>

                                    <input type="hidden" class="form-control" name="longitude"
                                           id="longitude"
                                           value="{{ $branch_details->longitude }}"
                                           placeholder="Longitude" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div id="map-canvas" style="height:300px;"></div>
                            </div>

                            @if(count($global_extras)>0)
                                <div class="col-12 border-bottom border-top mt-3 mb-3 p-3 bg-light">
                                    <div
                                        class="main-content-label mb-0">{{ config('languageString.extra_selection') }} </div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>Extra</th>
                                            <th>Price Type</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($global_extras as $keys=>$global_extra)
                                            <tr>
                                                <td>
                                                    {{ $global_extra->name }}
                                                    <div
                                                        class="main-toggle @if($dealer_extras[$global_extra->id]!=2) on @endif extra_switch"
                                                        style="float: right"
                                                        id="{{ $keys }}">
                                                        <span></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="extra_ids[]"
                                                           value="{{ $global_extra->id }}">
                                                    <select id="extra_id_{{ $keys }}"
                                                            name="extra[]"
                                                            class="form-control form-control-sm @if($dealer_extras[$global_extra->id]==2) bg-light @endif"
                                                            required
                                                            @if($dealer_extras[$global_extra->id]==2) readonly @endif>
                                                        <option value="1"
                                                                @if($dealer_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.daily') }}</option>
                                                        <option value="0"
                                                                @if($dealer_extras[$global_extra->id]==0) selected @endif>{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2" disabled
                                                            @if($dealer_extras[$global_extra->id]==2) selected @endif>{{ config('languageString.not_require') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <div class="col-12 border-bottom border-top mt-3 mb-3 p-3 bg-light">
                                <div class="main-content-label mb-0">Working Hour</div>
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="thead-dark text-center">
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                    </tr>
                                    </thead>
                                    @foreach($weeks as $key=>$week)
                                        @php($day_value=0)
                                        @php($start_time='10:00')
                                        @php($end_time='20:00')
                                        @if(isset($branch_details->companyTime[$key]->day_no))
                                            @php($day_value=1)
                                            @php($start_time=$branch_details->companyTime[$key]->start_time)
                                            @php($end_time=$branch_details->companyTime[$key]->end_time)
                                        @endif
                                        <tr>
                                            <th>{{ $week }}
                                                <div class="main-toggle @if($day_value==1) on @endif"
                                                     style="float: right"
                                                     id="{{ $key }}">
                                                    <span></span></div>
                                            </th>
                                            <td>
                                                <input type="hidden" name="day_no[]" id="day_no_{{ $key }}"
                                                       value="{{ $key+1 }}">
                                                <input type="hidden" name="day_value[]" id="day_value_{{ $key }}"
                                                       value="{{ $day_value }}">
                                                <input type="text" name="weekStartArray[]" value="{{ $start_time }}"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="start_{{ $key }}" placeholder="Start Time"
                                                       @if($day_value==0) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="text" name="weekEndArray[]" value="{{ $end_time }}"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="end_{{ $key }}" placeholder="End Time"
                                                       @if($day_value==0) disabled @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <a href="{{ route('admin::dealer.show',[$dealer_id]) }}"
                                           class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/plugins/clockpicker/dist/bootstrap-clockpicker.min.js')}}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjbtflnGL0mEj7aHh9VOHPAa_0cqbJabY&libraries=places&callback=initMap"
        async defer></script>
    <script src="{{URL::asset('assets/js/custom/branch.js')}}?v={{ time() }}"></script>
    <script>
        $(function () {
            addMarker('{{ $branch_details->latitude }}', '{{ $branch_details->longitude }}', '{{ $branch_details->address }}')
        });
    </script>
@endsection
