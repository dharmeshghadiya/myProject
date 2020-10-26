@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_dealer') }}</h2>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" data-parsley-validate="" id="addEditForm" role="form">
                        @csrf
                        <input type="hidden" id="edit_value" value="{{ $user->id }}" name="edit_value">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row row-sm">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.name') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           value="{{ $user->name }}"
                                           id="name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>



                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">{{ config('languageString.security_deposit') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="security_deposit" placeholder="{{ config('languageString.security_deposit') }}"
                                           id="security_deposit" value="{{$user->companies->security_deposit}}"
                                           required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>



                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">{{ config('languageString.license_umber') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="license_number" placeholder="{{ config('languageString.license_umber') }}"
                                           value="{{ $user->companies->license_number }}"
                                           id="license_number" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="country_code">{{ config('languageString.country_code') }}<span
                                            class="error">*</span></label>
                                    <select id="country_code" name="country_code" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_country') }}</option>
                                        @foreach($countries as $country)

                                            <option value="{{ $country->code }}"
                                                    @if($country->code==$user->country_code){{'selected'}} @endif
                                            >{{ $country->code }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="mobile_no">{{ config('languageString.mobile_no') }}<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control integer"
                                           name="mobile_no" placeholder="{{ config('languageString.mobile_no') }}"
                                           value="{{ $user->mobile_no }}"
                                           id="phone_no" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12 border-bottom border-top mb-3  p-3 bg-light">
                                <div class="main-content-label mb-0">{{ config('languageString.bank_details') }} </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_name" placeholder="{{ config('languageString.bank_name') }}"
                                           id="bank_name" value="{{ $user->companies->bank_name }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_address') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_address" placeholder="{{ config('languageString.bank_address') }}"
                                           id="bank_address" value="{{ $user->companies->bank_address }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_contact_number') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_contact_number" placeholder="{{ config('languageString.bank_contact_number') }}"
                                           id="bank_contact_number" value="{{ $user->companies->bank_contact_number }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.beneficiary_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="beneficiary_name" placeholder="{{ config('languageString.beneficiary_name') }}"
                                           id="beneficiary_name" value="{{ $user->companies->beneficiary_name }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_code') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_code" placeholder="{{ config('languageString.bank_code') }}"
                                           id="bank_code" value="{{ $user->companies->bank_code }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_iban') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="iban" placeholder="{{ config('languageString.bank_iban') }}"
                                           id="iban" value="{{ $user->companies->iban }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.dealer_logo') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="dealer_logo" data-default-file="{{ asset($user->companies->dealer_logo) }}"
                                           id="dealer_logo" />
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.trade_license_doc') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="image"
                                           data-default-file="{{ asset($user->companies->trade_license_image) }}"
                                           id="image"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12 border-bottom border-top mb-3  p-3 bg-light">
                                <div class="main-content-label mb-0">{{ config('languageString.business_details') }}</div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="business_name">{{ config('languageString.business_name') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="business_name" placeholder="{{ config('languageString.business_name') }}"
                                           id="business_name" value="{{$user->companies->name}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">{{ config('languageString.business_number') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="business_number" placeholder="{{ config('languageString.business_number') }}"
                                           id="business_number" value="{{$user->companies->business_number}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::dealer.index') }}"
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


    </div>
    </div>
@endsection
@section('js')
    <script>
        const is_edit = 1;

        const country_id = '';
    </script>
    <script src="{{URL::asset('assets/js/custom/dealer/dealer.js')}}?v={{ time() }}"></script>


@endsection
