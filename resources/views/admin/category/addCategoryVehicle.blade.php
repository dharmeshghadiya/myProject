@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Add Category Ryde -> {{$category->name}}</h2>

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
                        <input type="hidden" id="category_id" name="category_id" value="{{ $category->id }}">

                        <input type="hidden" id="form-method" value="add">
                        <div class="row row-sm">

                             <div class="col-6">
                                <div class="form-group">
                                    <label for="company_id">Company <span class="error">*</span></label>
                                    <select id="company_id" name="company_id" class="form-control" required="">
                                        <option value="">Please Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_address_id">Branch <span class="error">*</span></label>
                                    <select id="company_address_id" name="company_address_id" class="form-control" required>
                                        <option value="">Please Select Branch</option>
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12" id="vehicle"></div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                        <a href="{{ route('admin::categoryRydeDetails',[$category->id]) }}"
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
    <script src="{{URL::asset('assets/js/custom/categoryDetail.js')}}?v={{ time() }}"></script>
@endsection
