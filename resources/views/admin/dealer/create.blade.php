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
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_dealer') }}</h2>
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
                            <div class="col-12 border-bottom mb-3  p-3 bg-light">
                                <div
                                    class="main-content-label mb-0">{{ config('languageString.contact_details') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           id="name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="mobile_no">{{ config('languageString.mobile_no') }}<span
                                            class="error">*</span></label><br>
                                    <input type="text" class="form-control integer"
                                           name="mobile_no" placeholder="{{ config('languageString.mobile_no') }}"
                                           id="mobile_no" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">{{ config('languageString.email') }}<span class="error">*</span></label>
                                    <input type="email" class="form-control"
                                           name="email" placeholder="{{ config('languageString.email') }}"
                                           id="email" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password">{{ config('languageString.password') }}<span
                                            class="error">*</span></label><br>
                                    <input type="password" class="form-control"
                                           name="password" placeholder="{{ config('languageString.password') }}"
                                           id="password" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">{{ config('languageString.security_deposit') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="security_deposit"
                                           placeholder="{{ config('languageString.security_deposit') }}"
                                           id="security_deposit" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">{{ config('languageString.license_umber') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="license_number"
                                           placeholder="{{ config('languageString.license_umber') }}"
                                           id="license_number" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.license_expiry_date') }}<span
                                            class="error">*</span></label>
                                    <input class="form-control fc-datepicker" name="license_expiry_date"
                                           id="license_expiry_date" placeholder="MM/DD/YYYY" type="text" required>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">Membership Status<span
                                            class="error">*</span></label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="Active">{{ config('languageString.active') }}</option>
                                        <option value="InActive">{{ config('languageString.InActive') }}</option>

                                    </select>
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
                                           id="bank_name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_address') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_address" placeholder="{{ config('languageString.bank_address') }}"
                                           id="bank_address" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_contact_number') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_contact_number"
                                           placeholder="{{ config('languageString.bank_contact_number') }}"
                                           id="bank_contact_number" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.beneficiary_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="beneficiary_name"
                                           placeholder="{{ config('languageString.beneficiary_name') }}"
                                           id="beneficiary_name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_code') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="bank_code" placeholder="{{ config('languageString.bank_code') }}"
                                           id="bank_code" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.bank_iban') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="iban" placeholder="{{ config('languageString.bank_iban') }}"
                                           id="iban" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.dealer_logo') }}<span
                                            class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="dealer_logo"
                                           id="dealer_logo" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.trade_license_doc') }}<span
                                            class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="image"
                                           id="image" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12 border-bottom border-top mb-3  p-3 bg-light">
                                <div
                                    class="main-content-label mb-0">{{ config('languageString.business_details') }} </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="business_name">{{ config('languageString.business_name') }} <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="business_name"
                                           placeholder="{{ config('languageString.business_name') }}"
                                           id="business_name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="license_number">{{ config('languageString.business_number') }} <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control integer"
                                           name="business_number"
                                           placeholder="{{ config('languageString.business_number') }}"
                                           id="business_number" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            @if(count($global_extras)>0)
                                <div class="col-12 border-bottom border-top mb-3  p-3 bg-light">
                                    <div
                                        class="main-content-label mb-0">{{ config('languageString.extra_selection') }}</div>
                                </div>

                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>{{ config('languageString.extra') }}</th>
                                            <th>{{ config('languageString.price_type') }}</th>
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
                                                            name="extra[]" class="form-control form-control-sm"
                                                            required>
                                                        <option value="1">{{ config('languageString.daily') }}</option>
                                                        <option value="0">{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2">{{ config('languageString.not_require') }}</option>
                                                    </select>
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
    <!-- /row -->

    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script>
        const is_edit = 0;
        const country_id = '';
    </script>
    <script src="{{URL::asset('assets/js/custom/dealer.js')}}?v={{ time() }}"></script>

@endsection
