@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_admin') }}</h2>

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
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           id="name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.email') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="email" placeholder="{{ config('languageString.email') }}"
                                           id="email" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.password') }}<span
                                            class="error">*</span></label>
                                    <input type="password" class="form-control"
                                           name="password" placeholder="{{ config('languageString.password') }}"
                                           id="password" required/>
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
