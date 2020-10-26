@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.setting') }}</h2>

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
                            @foreach($settings as $setting)
                                @if($setting->type==1)
                                    @php /*
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="ckbox">
                                                <input type="checkbox" name="{{ $setting->meta_key }}"
                                                       @if($setting->meta_value==1) checked @endif
                                                       value="1"
                                                       id="{{ $setting->meta_key }}">
                                                <span>{{ $setting->meta_key }}</span></label>
                                        </div>
                                    </div>*/
                                    @endphp
                                @elseif($setting->type==2 || $setting->type==4)
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="{{ $setting->meta_key }}">{{ config('languageString.'.$setting->meta_key) }}</label>
                                            <div class="input-group mb-3">

                                                <input type="text" name="{{ $setting->meta_key }}"
                                                       class="form-control {{ $setting->class_value }}"
                                                       value="{{ $setting->meta_value }}"
                                                       id="{{ $setting->meta_key }}">
                                                <div class="input-group-prepend">
                                                    @if($setting->type==2)
                                                        <span class="input-group-text"
                                                              id="{{ $setting->meta_value }}">Km</span>
                                                    @else
                                                        <span class="input-group-text"
                                                              id="{{ $setting->meta_value }}">%</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($setting->type==3)
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="{{ $setting->meta_key }}">{{ config('languageString.'.$setting->meta_key) }}</label>

                                            <input type="text" name="{{ $setting->meta_key }}"
                                                   class="form-control {{ $setting->class_value }}"
                                                   value="{{ $setting->meta_value }}"
                                                   id="{{ $setting->meta_key }}">

                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-primary">{{ config('languageString.submit') }}</button>
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
    <script src="{{URL::asset('assets/js/custom/setting.js')}}?v={{ time() }}"></script>
    <script>

    </script>
@endsection
