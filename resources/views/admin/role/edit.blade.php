@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.edit_role') }}</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $role->id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.name') }}<span class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="name" placeholder="{{ config('languageString.name') }}"
                                           value="{{ $role->name }}"
                                           id="name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12 border-bottom border-top mt-3 mb-3 p-3 bg-light">
                                <div class="main-content-label mb-0">{{ config('languageString.abilities') }}</div>
                            </div>


                            @foreach($abilities as $ability)
                                <div class="col-3">
                                    {{ $ability->name }}
                                    <input type="hidden" name="ability[]" id="ability_{{ $ability->id }}"
                                           value="{{ $ability->id }}">
                                    <input type="hidden" name="is_ability[]" id="is_ability_{{ $ability->id }}"
                                           value="@if(in_array($ability->id,$ability_ids)) 1 @else 0 @endif">
                                    <div class="main-toggle  @if(in_array($ability->id,$ability_ids)) on @endif"
                                         style="float: right" id="{{ $ability->id }}"><span></span></div>

                                </div>
                            @endforeach

                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3 justify-content-end">
                                <div>
                                    <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}</button>
                                    <a href="{{ route('admin::roles.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/roles.js')}}?v={{ time() }}"></script>
@endsection
