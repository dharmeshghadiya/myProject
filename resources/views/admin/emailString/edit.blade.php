@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_email_string') }}</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $email_string->id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row row-sm">


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="template_name">{{ config('languageString.template_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="template_name"
                                           id="template_name"
                                           value="{{ $email_string->template_name }}"
                                           placeholder="Screen Name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="template_name">{{ config('languageString.key') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name_key"
                                           id="name_key"
                                           readonly
                                           value="{{ $email_string->name_key }}"
                                           placeholder="Key" required />
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="{{ $language->language_code }}_name">{{ $language->name }} {{ config('languageString.name') }}<span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="{{ $language->language_code }}_name"
                                               id="{{ $language->language_code }}_name"
                                               value="{{ $email_string->translateOrNew($language->language_code)->name }}"
                                               placeholder="{{ $language->name }} {{ config('languageString.name') }}" required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::emailString.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/emailString.js')}}?v={{ time() }}"></script>
@endsection
