@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_ryde') }}</h2>
            </div>
        </div>
        <div class="pr-1 mb-3 mb-xl-0 float-right" id="reportmodal">
            <a href="#" class="btn btn-info  mr-2" onclick="ReportModel()" data-target="#select2modal" data-toggle="modal" >Report About Model</a>
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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $vehicle->id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row row-sm">

                            <input type="hidden" id="company_id" name="company_id" value="{{ $vehicle->company_id }}">
                            <input type="hidden" id="vehicle_id" name="vehicle_id"
                                   value="{{ $vehicle->id }}">

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
                                                    value="{{ $companyAddress->id }}" @if($vehicle->company_address_id==$companyAddress->id) selected @endif>{{ $companyAddress->address.' ( '. $companyAddress->service_distance.' KM ) ' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.hourly_amount') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" name="hourly_amount" id="hourly_amount"
                                               class="form-control float"
                                               value="{{ $vehicle->hourly_amount }}" required
                                               placeholder="{{ config('languageString.hourly_amount') }}" aria-label="hourly_amount"
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
                                               placeholder="{{ config('languageString.daily_amount') }}" value="{{ $vehicle->daily_amount }}" required
                                               aria-label="daily_amount" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.weekly_amount') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2">$</span>
                                        </div>
                                        <input type="text" name="weekly_amount" id="weekly_amount"
                                               class="form-control float" value="{{ $vehicle->weekly_amount }}" required
                                               placeholder="{{ config('languageString.weekly_amount') }}" aria-label="weekly_amount"
                                               aria-describedby="basic-addon1">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.monthly_amount') }}<span
                                            class="error">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">$</span>
                                        </div>
                                        <input type="text" name="monthly_amount" id="monthly_amount"
                                               class="form-control float" value="{{ $vehicle->monthly_amount }}"
                                               placeholder="{{ config('languageString.monthly_amount') }}" aria-label="monthly_amount" required
                                               aria-describedby="basic-addon3">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.brand') }}<span class="error">*</span></label>
                                    <select id="make" name="make" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_brand') }}</option>
                                        @foreach($makes as $make)
                                            <option value="{{ $make->id }}"
                                                    @if($vehicle->ryde->brand_id==$make->id) selected @endif>{{ $make->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-2">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.from_year') }}<span class="error">*</span></label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}"
                                                    @if($vehicle->ryde->model_year_id==$modelYear->id) selected @endif>{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.to_year') }}<span class="error">*</span></label>
                                    <select id="to_year_id" name="to_year_id" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}"
                                                    @if($vehicle->ryde->to_year_id==$modelYear->id) selected @endif>{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.color') }}<span class="error">*</span></label>
                                    <select id="color" name="color" class="form-control">
                                        <option value="">{{ config('languageString.please_select_color') }}</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}"
                                                    @if($vehicle->ryde->color_id==$color->id) selected @endif>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="model">{{ config('languageString.model') }}<span class="error">*</span></label>
                                    <select id="model" name="model" class="form-control" required>
                                        @foreach($brand_models as $brand_model)
                                            <option value="{{ $brand_model->id }}"
                                                    @if($vehicle->ryde_id==$brand_model->id) selected @endif
                                            >{{ $brand_model->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.buy_price') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="buy_price" id="buy_price" class="form-control integer"
                                           value="{{ $vehicle->buy_price }}"  placeholder="{{ config('languageString.buy_price') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.allowed_millage') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="allowed_millage" id="allowed_millage"
                                           class="form-control integer"
                                           value="{{ $vehicle->allowed_millage }}"     placeholder="{{ config('languageString.allowed_millage') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.cost_per_extra_km') }}<span
                                            class="error"></span></label>
                                    <input type="text" name="cost_per_extra_km" id="cost_per_extra_km"
                                           class="form-control integer"
                                           value="{{ $vehicle->cost_per_extra_km }}"     placeholder="{{ config('languageString.cost_per_extra_km') }}">
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.engine') }}<span class="error">*</span></label>
                                    <select id="engine" name="engine" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_engine') }}</option>
                                        @foreach($engines as $engine)
                                            <option value="{{ $engine->id }}"
                                                    @if($vehicle->engine_id==$engine->id) selected @endif>{{ $engine->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.fuel') }}<span class="error">*</span></label>
                                    <select id="fuel" name="fuel" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_fuel') }}</option>
                                        @foreach($fuels as $fuel)
                                            <option value="{{ $fuel->id }}"
                                                    @if($vehicle->fuel_id==$fuel->id) selected @endif
                                            >{{ $fuel->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.gearbox') }}<span class="error">*</span></label>
                                    <select id="gearbox" name="gearbox" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_gearbox') }}</option>
                                        @foreach($gearboxes as $gearbox)
                                            <option value="{{ $gearbox->id }}"
                                                    @if($vehicle->gearbox_id==$gearbox->id) selected @endif
                                            >{{ $gearbox->name }}</option>
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
                                                value="{{ $vehicle->van_number }}">
                                    </div>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="car_name">{{ config('languageString.plate_number') }} <span class="error">*</span> </label>
                                    <div class="input-group mb-3">

                                        <input type="text" name="plate_number" id="plate_number"
                                               class="form-control"
                                               placeholder="{{ config('languageString.plate_number') }}"
                                               aria-label="plate_number"
                                               value="{{ $vehicle->plate_number }}">
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
                                               value="{{ $vehicle->trim }}">
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
                                               placeholder="Security Deposit" aria-label="security_deposit"
                                               aria-describedby="basic-addon1"
                                               value="{{ $vehicle->security_deposit }}">
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
                                            <option value="{{ $insurance->id }}"
                                                    @if($vehicle->insurance_id==$insurance->id) selected @endif>{{ $insurance->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                           <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.options') }}<span class="error">*</span></label>
                                    <?php
                                    $vehicle_option_array = [];
                                    foreach ($vehicle->vehicleOptions as $vehicleOption) {
                                        // $rydeFeatures[]=$rydeFeatures->feature_id;
                                        $vehicle_option_array[] = $vehicleOption->option_id;
                                    }

                                    ?>
                                    <select id="option" name="option[]" class="form-control" multiple>
                                        @foreach($options as $option)

                                            <option value="{{ $option->id }}"
                                                    @if(in_array($option->id,$vehicle_option_array)) selected @endif
                                            >{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.features') }}<span class="error">*</span></label>
                                    <?php
                                    $vehicleFeature_array = [];
                                    foreach($vehicle->vehicleFeature as $vehicleFeature){
                                        $vehicleFeature_array[] = $vehicleFeature->feature_id;
                                    }
                                    ?>

                                    <select id="featured" name="featured[]" class="form-control" multiple>
                                        @foreach($features as $feature)
                                            <option value="{{ $feature->id }}"
                                                    @if(in_array($feature->id,$vehicleFeature_array)) selected @endif>{{ $feature->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                          <div class="col-12" id="extra">
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
                                                    <div
                                                        class="main-toggle @if($vehicle_extra[$global_extra->id]!=2) on @endif extra_switch"
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
                                                            class="form-control form-control-sm @if($vehicle_extra[$global_extra->id]==2) bg-light @endif"
                                                            required
                                                            @if($vehicle_extra[$global_extra->id]==2) readonly @endif>
                                                        <option value="1"
                                                                @if($vehicle_extra[$global_extra->id]==1) selected @endif>{{ config('languageString.daily') }}</option>
                                                        <option
                                                            value="0"
                                                            @if($vehicle_extra[$global_extra->id]==1) selected @endif>{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2"
                                                            @if($vehicle_extra[$global_extra->id]==2) selected @endif >{{ config('languageString.not_require') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="price[]"
                                                           class="form-control form-control-sm integer @if($vehicle_extra[$global_extra->id]==2) bg-light @endif"
                                                           value="{{ $vehicle_extra_price[$global_extra->id] }}"
                                                           id="extra_price_{{ $keys }}"
                                                           @if($vehicle_extra[$global_extra->id]==2) readonly @endif>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            </div>



                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('dealer::mainRyde.index') }}"
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
    <!-- Report Modal -->
        <div class="modal" id="select2modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Report About Model </h6>

                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <form method="POST" data-parsley-validate="" id="reportModelForm" role="form">
                        @csrf

                    <div class="modal-body">
                       <div class="col-sm-4">
                        <label>Message</label>
                    </div>
                    <input type="hidden" name="make_id" id="make_id">
                    <input type="hidden" name="model_year_id" id="model_year_id">
                    <input type="hidden" name="toYearId" id="toYearId">
                    <input type="hidden" name="color_id" id="color_id">
                    <input type="hidden" name="branch_id" id="branch_id">
                      <div class="row">
                        <div class="col-12" >
                         <textarea id="message" name="message" class="form-control" required=""></textarea>
                        </div>

                     </div>
                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn ripple btn-success">Submit</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('js')
    <script>
        const feature_placeholder = "{{ config('languageString.feature_placeholder') }}";
        const option_placeholder = "{{ config('languageString.option_placeholder') }}";
    </script>

    <script src="{{URL::asset('assets/js/custom/dealer/mainRyde.js')}}?v={{ time() }}"></script>
    <script>
        setTimeout(function () {
            $("#model").trigger('change');
        }, 3000);

    </script>
@endsection
