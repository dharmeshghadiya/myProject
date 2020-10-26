@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_model') }}</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $ryde->id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row row-sm">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="brand">{{ config('languageString.brand') }}<span class="error">*</span></label>
                                    <select id="make" name="make" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_brand') }}</option>
                                        @foreach($makes as $make)
                                            <option value="{{ $make->id }}"
                                                    @if($ryde->brand_id==$make->id) selected @endif>{{ $make->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="brand">{{ config('languageString.from_year') }}<span class="error">*</span></label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}"
                                                    @if($ryde->model_year_id==$modelYear->id) selected @endif>{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="brand">{{ config('languageString.to_year') }}<span class="error">*</span></label>
                                    <select id="to_year_id" name="to_year_id" class="form-control">
                                        <option value="">{{ config('languageString.please_select_year') }}</option>
                                        @foreach($modelYears as $modelYear)
                                            <option value="{{ $modelYear->id }}"
                                                    @if($ryde->to_year_id==$modelYear->id) selected @endif>{{ $modelYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="color">{{ config('languageString.color') }}<span class="error">*</span></label>
                                    <select id="color" name="color" class="form-control">
                                        <option value="">{{ config('languageString.please_select_color') }}</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}"
                                                    @if($ryde->color_id==$color->id) selected @endif>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="{{ $language->language_code }}_name">{{ $language->name }} {{ config('languageString.model_name') }}<span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="{{ $language->language_code }}_name"
                                               id="{{ $language->language_code }}_name"
                                               value="{{ $ryde->translateOrNew($language->language_code)->name }}"
                                               placeholder="{{ $language->name }} {{ config('languageString.model_name') }}" required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.image') }}<span class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="image"
                                           data-default-file="{{URL::asset($ryde->image)}}"
                                           id="image" />
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.body') }}<span class="error">*</span></label>
                                    <select id="body_id" name="body_id" class="form-control">
                                        <option value="">{{ config('languageString.please_select_body') }}</option>
                                        @foreach($bodies as $body)
                                            <option value="{{ $body->id }}"
                                                    @if($ryde->rydeInstance->body_id==$body->id) selected @endif>{{ $body->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.door') }}<span class="error">*</span></label>
                                    <select id="door" name="door" class="form-control" required>
                                        <option value="">{{ config('languageString.please_select_door') }}</option>
                                        @foreach($doors as $door)
                                            <option value="{{ $door->id }}"
                                                    @if($ryde->rydeInstance->door_id==$door->id) selected @endif
                                            >{{ $door->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>




                            <div class="col-4">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.seat') }}<span class="error">*</span></label>
                                    <input type="text" name="seat" id="seat" class="form-control integer"
                                           value="{{ $ryde->rydeInstance->seats }}"
                                           placeholder="{{ config('languageString.seat') }}" required>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>



                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::ryde.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/ryde.js')}}?v={{ time() }}"></script>
@endsection
