@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_admin') }}</h2>

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
                        <input type="hidden" id="edit_value" value="{{ $user->id }}" name="edit_value">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           id="name" required value="{{ $user->name }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.email') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="email" placeholder="{{ config('languageString.email') }}"
                                           id="email" required value="{{ $user->email }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.password') }}
                                        <div class="checkbox">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup"
                                                       class="custom-control-input" id="is_password_update" value="1">
                                                <label for="is_password_update"
                                                       class="custom-control-label mt-1">{{ config('languageString.is_password_update') }}
                                                </label>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="password" class="form-control"
                                           name="password" placeholder="{{ config('languageString.password') }}"
                                           id="password" readonly/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3 justify-content-end">
                                <div>
                                    <button type="submit"
                                            class="btn btn-success">{{ config('languageString.submit') }}</button>
                                    <a href="{{ route('admin::admins.index') }}"
                                       class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
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
    <script src="{{URL::asset('assets/js/custom/admins.js')}}?v={{ time() }}"></script>
@endsection
