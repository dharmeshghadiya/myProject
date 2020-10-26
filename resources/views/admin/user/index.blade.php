@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.users') }}</h2>
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
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.name') }}</th>
                                <th>{{ config('languageString.email') }}</th>
                                <th>{{ config('languageString.mobile_no') }}</th>
                                <th>{{ config('languageString.status') }}</th>
                                <th>{{ config('languageString.actions') }}</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_dealer').'?' }}";
        const text = "{{ config('languageString.confirm_message').'?' }}";
        const confirmButtonText= "{{ config('languageString.yes_delete_it').'?' }}";
        const cancelButtonText= "{{ config('languageString.no_cancel_plx').'?' }}";
        const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
        const yes_change_btn= "{{ config('languageString.yes_change_it') }}";
    </script>
    <script src="{{URL::asset('assets/js/custom/user.js')}}?v={{ time() }}"></script>
@endsection
