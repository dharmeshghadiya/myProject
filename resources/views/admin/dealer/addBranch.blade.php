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
                <h2 class="content-title mb-0 my-auto">Add Branch</h2>
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
                        <input type="hidden" id="user_id" value="{{ $id }}" name="user_id">
                        <input type="hidden" id="company_id" value="{{ $company_id }}" name="company_id">
                        <input type="hidden" id="form-method" value="add">
                        <div class="row">

                            <?php /*
                            <div class="col-12 border-bottom border-top mb-3 p-3 bg-light">
                                <div class="main-content-label mb-0">Login Details
                                    <label class="ckbox" style="float: right">
                                        <input type="checkbox" name="same_as_contact_details"
                                               value="1" checked
                                               id="same_as_contact_details" >
                                        <span>Same as Contact Details</span></label>
                                </div>
                            </div>*/ ?>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="branch_name">{{ config('languageString.branch_name') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           name="branch_name" placeholder="Branch Name"
                                           id="branch_name"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_account">{{ config('languageString.bank_account') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           name="bank_account" placeholder="{{ config('languageString.bank_account') }}"
                                           id="bank_account"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="trading_license">{{ config('languageString.trading_license') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           name="trading_license" placeholder="{{ config('languageString.trading_license') }}"
                                           id="trading_license"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="allowed_millage">{{ config('languageString.allowed_millage') }}<span
                                            class="error"></span></label><br>
                                    <input type="text" class="form-control"
                                           name="allowed_millage" placeholder="{{ config('languageString.allowed_millage') }}"
                                           id="allowed_millage"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="branch_logo">{{ config('languageString.branch_logo') }}<span
                                            class="error"></span></label><br>
                                    <input type="file" class="form-control dropify"
                                           name="branch_logo"
                                           id="branch_logo"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="branch_contact_name">Branch Contact Name<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control same_contact"
                                           name="branch_contact_name" placeholder="Branch Contact Name"
                                           id="branch_contact_name" value="{{ $user->name }}"/>
                                </div>
                            </div>
                            <?php /*
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="login_user_mobile_no">Branch Contact No<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control integer same_contact"
                                           name="login_user_mobile_no" placeholder="Contact No"
                                           id="login_user_mobile_no" value="{{ $user->mobile_no }}"/>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="login_user_email">Branch Email<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control same_contact"
                                           name="login_user_email" placeholder="Login User Email"
                                           id="login_user_email" value="{{ $user->email }}" />
                                </div>
                            </div>

                            <div class="col-12 d-none same_contact">
                                <div class="form-group">
                                    <label for="password">Password<span class="error">*</span></label><br>
                                    <input type="password" class="form-control"
                                           name="password" placeholder="Password"
                                           id="password"/>
                                </div>
                            </div>*/ ?>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">Branch Contact No<span class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="phone_no" placeholder="Branch Contact No"
                                           id="phone_no" required value="{{ $user->mobile_no }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="service_distance">Service Distance</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="service_distance"
                                               class="form-control integer"
                                               value="{{ $service_distance }}"
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
                                           id="address" required autocomplete="off"/>
                                    <input type="hidden" class="form-control" name="latitude" id="latitude"
                                           placeholder="Latitude" required/>

                                    <input type="hidden" class="form-control" name="longitude"
                                           id="longitude"
                                           placeholder="Longitude" required/>
                                    <input type="hidden" class="form-control" name="country_code"
                                           id="country_code"
                                           placeholder="Country Code" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div id="map-canvas" style="height:300px;"></div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-group">
                                    <label for="country">Country <span class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="country" id="country"
                                               class="form-control" required>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
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
                                                            value="2"
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
                                        <th>Shift 1 - Start Time</th>
                                        <th>Shift 1 - End Time</th>
                                        <th>Shift 2</th>
                                        <th>Shift 2 - Start Time</th>
                                        <th>Shift 2 - End Time</th>
                                    </tr>
                                    </thead>
                                    @foreach($weeks as $key=>$week)
                                        <tr>
                                            <th>{{ $week }}
                                                <div class="main-toggle on shift_one" style="float: right"
                                                     id="{{ $key }}">
                                                    <span></span></div>
                                            </th>
                                            <td>
                                                <input type="hidden" name="day_no[]" id="day_no_{{ $key }}"
                                                       value="{{ $key+1 }}">
                                                <input type="hidden" name="day_value[]" id="day_value_{{ $key }}"
                                                       value="1">
                                                <input type="text" name="weekStartArray[]" value="10:00"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="start_{{ $key }}" placeholder="Start Time">
                                            </td>
                                            <td>
                                                <input type="text" name="weekEndArray[]" value="13:00"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="end_{{ $key }}" placeholder="End Time">
                                            </td>
                                            <th>
                                                <div class="main-toggle on shift_two" style="float: right"
                                                     id="{{ $key }}">
                                                    <span></span></div>
                                            </th>
                                            <td>
                                                <input type="hidden" name="shift_two_day_no[]"
                                                       id="shift_two_day_no_{{ $key }}"
                                                       value="{{ $key+1 }}">
                                                <input type="hidden" name="shift_two_day_value[]"
                                                       id="shift_two_day_value_{{ $key }}"
                                                       value="1">
                                                <input type="text" name="shiftTwoWeekStartArray[]" value="14:00"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="shift_two_start_{{ $key }}" placeholder="Start Time">
                                            </td>
                                            <td>
                                                <input type="text" name="shiftTwoWeekEndArray[]" value="21:00"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="shift_two_end_{{ $key }}" placeholder="End Time">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <a href="{{ route('admin::dealer.show',[$id]) }}"
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
@endsection
