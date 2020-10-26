@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_user') }}</h2>
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
                                    <label for="name">{{ config('languageString.name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           value="{{ $user->name }}"
                                           id="name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>



                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">{{ config('languageString.country') }}<span
                                            class="error">*</span></label>
                                    <select id="country_code" name="country_code" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_country') }}</option>
                                        @foreach($countries as $country)

                                            <option value="{{ $country->code }}"
                                                    @if($country->code==$user->country_code) selected @endif
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
                                           name="phone_no" placeholder="{{ config('languageString.mobile_no') }}"
                                           value="{{ $user->mobile_no }}"
                                           id="phone_no" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>




                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::users.index') }}"
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
    </script>
    <script src="{{URL::asset('assets/js/custom/user.js')}}?v={{ time() }}"></script>



@endsection
