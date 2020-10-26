@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.report_model') }}</h2>
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
                                <th>{{ config('languageString.company') }}</th>
                                <th>{{ config('languageString.brand') }}</th>
                                <th>{{ config('languageString.from_year') }}</th>
                                <th>{{ config('languageString.to_year') }}</th>
                                <th>{{ config('languageString.color') }}</th>
                                <th>{{ config('languageString.message') }}</th>
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
    const title = "{{ config('languageString.destroy_report_model').'?' }}";
    const text = "{{ config('languageString.confirm_message').'?' }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it').'?' }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx').'?' }}";
    const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
    const yes_change_btn= "{{ config('languageString.yes_change_it') }}";

</script>
    <script src="{{URL::asset('assets/js/custom/reportModel.js')}}?v={{ time() }}"></script>
@endsection
