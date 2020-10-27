@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_ryde') }}
                    - {{$company_details->companies->name}}
                    - {{ $company_details->address }}</h2>
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
                        <input type="hidden" id="form-method" value="add">
                        <div class="row row-sm">

                            <input type="hidden" id="company_address_id" name="company_address_id"
                                   value="{{ $company_details->id }}">

                            <input type="hidden" id="company_id" name="company_id"
                                   value="{{ $company_details->company_id }}">

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="daily_amount">{{ config('languageString.hourly_amount') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" name="hourly_amount" id="hourly_amount"
                                               class="form-control float"
                                               value="0"
                                               placeholder="{{ config('languageString.hourly_amount') }}"
                                               aria-label="hourly_amount"
                                               aria-describedby="basic-addon1">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="daily_amount">{{ config('languageString.daily_amount') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" name="daily_amount" id="daily_amount"
                                               class="form-control float"
                                               placeholder="{{ config('languageString.daily_amount') }}"
                                               aria-label="daily_amount"
                                               aria-describedby="basic-addon1">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.weekly_amount') }}<span
                                            class="error">*({{ config('languageString.per_day') }})</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2">$</span>
                                        </div>
                                        <input type="text" name="weekly_amount" id="weekly_amount"
                                               class="form-control float"
                                               placeholder="{{ config('languageString.weekly_amount') }}"
                                               aria-label="weekly_amount"
                                               aria-describedby="basic-addon1">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.monthly_amount') }}<span
                                            class="error">*({{ config('languageString.per_day') }})</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">$</span>
                                        </div>
                                        <input type="text" name="monthly_amount" id="monthly_amount"
                                               class="form-control float"
                                               placeholder="{{ config('languageString.monthly_amount') }}"
                                               aria-label="monthly_amount"
                                               aria-describedby="basic-addon3">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.brand') }}<span class="error">*</span></label>
                                    <select id="make" name="make" class="form-control">
                                        <option value="">{{ config('languageString.please_select_brand') }}</option>
                                        @foreach($makes as $make)
                                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.from_year') }}<span
                                            class="error">*</span></label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}">{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.to_year') }}<span
                                            class="error">*</span></label>
                                    <select id="to_year_id" name="to_year_id" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}">{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="color">{{ config('languageString.color') }}<span class="error">*</span></label>
                                    <select id="color" name="color" class="form-control">
                                        <option value="">{{ config('languageString.please_select_color') }}</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.model') }}<span class="error">*</span></label>
                                    <select id="model" name="model" class="form-control">
                                        <option value="">{{ config('languageString.please_select_model') }}</option>
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.buy_price') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="buy_price" id="buy_price" class="form-control integer"
                                           placeholder="{{ config('languageString.buy_price') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.allowed_millage') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="allowed_millage" id="allowed_millage"
                                           class="form-control integer"
                                           placeholder="{{ config('languageString.allowed_millage') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.cost_per_extra_km') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="cost_per_extra_km" id="cost_per_extra_km"
                                           class="form-control integer"
                                           placeholder="{{ config('languageString.cost_per_extra_km') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.engine') }}<span class="error">*</span></label>
                                    <select id="engine" name="engine" class="form-control">
                                        <option value="">{{ config('languageString.please_select_engine') }}</option>
                                        @foreach($engines as $engine)
                                            <option value="{{ $engine->id }}">{{ $engine->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.gearbox') }}<span
                                            class="error">*</span></label>
                                    <select id="gearbox" name="gearbox" class="form-control">
                                        <option value="">{{ config('languageString.please_select_gearbox') }}</option>
                                        @foreach($gearboxes as $gearbox)
                                            <option value="{{ $gearbox->id }}">{{ $gearbox->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.fuel') }}<span
                                            class="error">*</span></label>
                                    <select id="fuel" name="fuel" class="form-control">
                                        <option value="">{{ config('languageString.please_select_fuel') }}</option>
                                        @foreach($fuels as $fuel)
                                            <option value="{{ $fuel->id }}">{{ $fuel->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.van_number') }} <span class="error">*</span></label>
                                    <div class="input-group mb-3">

                                        <input type="text" name="van_number" id="van_number"
                                               class="form-control"
                                               placeholder="{{ config('languageString.van_number') }}"
                                               aria-label="van_number"
                                        >
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.plate_number') }} <span
                                            class="error">*</span> </label>
                                    <div class="input-group mb-3">

                                        <input type="text" name="plate_number" id="plate_number"
                                               class="form-control"
                                               placeholder="{{ config('languageString.plate_number') }}"
                                               aria-label="plate_number"
                                        >
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.trim') }}</label>
                                    <div class="input-group mb-3">

                                        <input type="text" name="trim" id="trim"
                                               class="form-control"
                                               placeholder="{{ config('languageString.trim') }}"
                                               aria-label="trim"
                                        >
                                    </div>

                                </div>
                            </div>

                            <div class="col-12" id="ryde"></div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="daily_amount">{{ config('languageString.security_deposit') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" name="security_deposit" id="security_deposit"
                                               class="form-control float"
                                               placeholder="{{ config('languageString.security_deposit') }}"
                                               aria-label="security_deposit"
                                               aria-describedby="basic-addon1"
                                               value="{{ $security_deposit }}">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="daily_amount">{{ config('languageString.insurances') }} <span
                                            class="error">*</span></label>
                                    <select id="insurance" name="insurance" class="form-control">
                                        <option value="">{{ config('languageString.please_select_insurance') }}</option>
                                        @foreach($insurances as $insurance)
                                            <option value="{{ $insurance->id }}">{{ $insurance->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.options') }}<span class="error">*</span></label>
                                    <select id="option" name="option[]" class="form-control "  multiple>
                                        @foreach($options as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.features') }} <span
                                            class="error">*</span></label>
                                    <select id="featured" name="featured[]" class="form-control"  multiple>
                                        @foreach($features as $feature)
                                            <option value="{{ $feature->id }}">{{ $feature->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            @if(count($global_extras)>0)
                                <div class="col-12 border-bottom border-top mt-3 mb-3 p-3 bg-light">
                                    <div
                                        class="main-content-label mb-0">{{ config('languageString.extra_selection') }}</div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>{{ config('languageString.extra') }}</th>
                                            <th>{{ config('languageString.price_type') }}</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($global_extras as $keys=>$global_extra)
                                            <tr>
                                                <td>
                                                    {{ $global_extra->name }}
                                                    <div class="main-toggle on extra_switch" style="float: right"
                                                         id="{{ $keys }}">
                                                        <span></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="extra_ids[]"
                                                           value="{{ $global_extra->id }}">
                                                    <select id="extra_id_{{ $keys }}"
                                                            name="extra[]"
                                                            class="form-control form-control-sm @if($branch_extras[$global_extra->id]==2) bg-light @endif"
                                                            required
                                                            @if($branch_extras[$global_extra->id]==2) readonly @endif>
                                                        <option value="1"
                                                                @if($branch_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.daily') }}</option>
                                                        <option
                                                            value="0"
                                                            @if($branch_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2"
                                                            @if($branch_extras[$global_extra->id]==2) selected @endif >{{ config('languageString.not_require') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="price[]"
                                                           class="form-control form-control-sm integer" value="0"
                                                           id="extra_price_{{ $keys }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif


                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::viewRyde',[$company_details->company_id,$company_details->id]) }}"
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
    <script>
        const feature_placeholder = "{{ config('languageString.no_cancel_plx') }}";
        const option_placeholder = "{{ config('languageString.option_placeholder') }}";
    </script>
    <script src="{{URL::asset('assets/js/custom/vehicle.js')}}?v={{ time() }}"></script>
@endsection
