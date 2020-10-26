@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.extras') }}</h2>

            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <form id="addEditForm">
                            <input type="hidden" name="company_address_id" id="company_address_id"
                                   value="{{ $company_address_id }}">
                            @if(count($global_extras)>0)
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
                                                    <div
                                                        class="main-toggle @if($branch_extras[$global_extra->id]!=2) on @endif extra_switch"
                                                        style="float: right"
                                                        id="{{ $keys }}">
                                                        <span></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="extra_ids[]"
                                                           value="{{ $global_extra->id }}">
                                                    <select id="extra_id_{{ $keys }}"
                                                            name="extra[]"
                                                            class="form-control form-control-sm @if($branch_extras[$global_extra->id]==2) bg-light @endif"
                                                            required
                                                            @if($branch_extras[$global_extra->id]==2) readonly @endif>
                                                        <option value="1"
                                                                @if($branch_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.daily') }}</option>
                                                        <option value="0"
                                                                @if($branch_extras[$global_extra->id]==0) selected @endif>{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2"
                                                            @if($branch_extras[$global_extra->id]==2) selected @endif >{{ config('languageString.not_require') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-0 mt-3 justify-content-end">
                                        <div>
                                            <button type="submit"
                                                    class="btn btn-primary">{{ config('languageString.submit') }}</button>
                                            <a href="{{ route('dealer::branch.index') }}"
                                               class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/dealer/branchExtra.js')}}?v={{ time() }}"></script>
@endsection
