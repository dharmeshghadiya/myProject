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
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_branch') }}</h2>

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
                                    <label for="license_number">{{ config('languageString.branch_contact_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="branch_contact_name"
                                           placeholder="{{ config('languageString.branch_contact_name') }}"
                                           id="branch_contact_name" value="{{$branch_details->branch_contact_name}}"
                                           required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">{{ config('languageString.branch_contact_number') }}
                                        <span class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="phone_no"
                                           placeholder="{{ config('languageString.branch_contact_number') }}"
                                           id="phone_no" value="{{$branch_details->phone_no}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label
                                        for="service_distance">{{ config('languageString.service_distance') }}</label>
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
                                    <label for="image">{{ config('languageString.address') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="address" placeholder="{{ config('languageString.address') }}"
                                           value="{{ $branch_details->address }}"
                                           id="address" required autocomplete="off"/>
                                    <input type="hidden" class="form-control" name="latitude" id="latitude"
                                           value="{{ $branch_details->latitude }}"
                                           placeholder="Latitude" required/>

                                    <input type="hidden" class="form-control" name="longitude"
                                           id="longitude"
                                           value="{{ $branch_details->longitude }}"
                                           placeholder="Longitude" required/>

                                    <input type="hidden" class="form-control" name="country_code"
                                           id="country_code"
                                           placeholder="Country Code"
                                           value="{{ $branch_details->country->country_code }}" required/>


                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div id="map-canvas" style="height:300px;"></div>
                            </div>

                            <div>&nbsp;</div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="country">{{ config('languageString.country_column') }} <span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="country" id="country"
                                               class="form-control"
                                               value="{{ $branch_details->country->translateOrNew(Auth::user()->locale)->name }}"
                                               required>
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
                                <div class="main-content-label mb-0">{{ config('languageString.working_hour') }}</div>
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="thead-dark text-center">
                                    <tr>
                                        <th>{{ config('languageString.day') }}</th>
                                        <th>{{ config('languageString.shift_1_start_time') }} </th>
                                        <th>{{ config('languageString.shift_1_end_time') }} </th>
                                        <th>{{ config('languageString.shift_2') }} </th>
                                        <th>{{ config('languageString.shift_2_start_time') }} </th>
                                        <th>{{ config('languageString.shift_2_end_time') }} </th>
                                    </tr>
                                    </thead>
                                    @foreach($weeks as $key=>$week)
                                        @php($day_value=0)
                                        @php($start_time='10:00')
                                        @php($end_time='20:00')
                                        @php($shift_two_start='10:00')
                                        @php($shift_two_end='20:00')
                                        @if(isset($branch_details->companyTime[$key]->day_no))
                                            @php($day_value=1)
                                            @php($start_time=$branch_details->companyTime[$key]->sift1_start_time)
                                            @php($shift_two_start=$branch_details->companyTime[$key]->sift1_end_time)
                                            @php($end_time=$branch_details->companyTime[$key]->sift2_start_time)
                                            @php($shift_two_end=$branch_details->companyTime[$key]->sift2_end_time)
                                        @endif

                                        <tr>
                                            <th>{{ $week }}
                                                <div class="main-toggle @if($day_value==1) on @endif shift_one"
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

                                            <th>
                                                <div class="main-toggle @if($day_value==1) on @endif shift_two"
                                                     style="float: right"
                                                     id="{{ $key }}">
                                                    <span></span></div>
                                            </th>

                                            <td>
                                                <input type="hidden" name="shift_two_day_no[]"
                                                       id="shift_two_day_no_{{ $key }}"
                                                       value="{{ $key+1 }}">
                                                <input type="hidden" name="shift_two_day_value[]"
                                                       id="shift_two_day_value_{{ $key }}"
                                                       value="{{ $day_value }}">
                                                <input type="text" name="shiftTwoweekStartArray[]"
                                                       value="{{ $shift_two_start }}"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="shift_two_start_{{ $key }}" placeholder="Start Time"
                                                       @if($day_value==0) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="text" name="shiftTwoWeekEndArray[]"
                                                       value="{{ $shift_two_end }}"
                                                       class="form-control form-control-sm clockpicker"
                                                       id="shift_two_end_{{ $key }}" placeholder="End Time"
                                                       @if($day_value==0) disabled @endif>
                                            </td>


                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('dealer::branch.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/dealer/branches.js')}}?v={{ time() }}"></script>
    <script>
        $(function () {
            addMarker('{{ $branch_details->latitude }}', '{{ $branch_details->longitude }}', '{{ $branch_details->address }}')
        });
    </script>
@endsection
