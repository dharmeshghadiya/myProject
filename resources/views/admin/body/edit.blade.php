@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_body') }}</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $body->id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row row-sm">
                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="{{ $language->language_code }}_name">{{ $language->name }} {{ config('languageString.name') }}<span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="{{ $language->language_code }}_name"
                                               id="{{ $language->language_code }}_name"
                                               value="{{ $body->translateOrNew($language->language_code)->name }}"
                                               placeholder="{{ $language->name }} {{ config('languageString.name') }}" required />
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="front_image">{{ config('languageString.front_image') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="front_image"
                                           id="front_image" data-default-file="{{URL::asset($body->front_image)}}" />
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="back_image">{{ config('languageString.back_image') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="back_image"
                                           id="back_image" data-default-file="{{URL::asset($body->back_image)}}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="right_image">{{ config('languageString.right_image') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="right_image"
                                           id="right_image" data-default-file="{{URL::asset($body->right_image)}}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="left_image">{{ config('languageString.left_image') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="left_image"
                                           id="left_image" data-default-file="{{URL::asset($body->left_image)}}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-primary">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::body.index') }}" class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
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
    <script src="{{URL::asset('assets/js/custom/body.js')}}?v={{ time() }}"></script>
@endsection
