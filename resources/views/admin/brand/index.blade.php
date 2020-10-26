@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.brand') }}</h2>

            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('admin::brand.create') }}" class="btn btn-primary  mr-2">
                    <i class="mdi mdi-plus-circle"></i> {{ config('languageString.add_new') }}
                </a>
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
                                <th>{{ config('languageString.brand_logo') }}</th>
                                <th>{{ config('languageString.actions') }}</th>
                            </tr>
                            </thead>
                        </table>
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
<script>
    const title = "{{ config('languageString.destroy_brand').'?' }}";
    const brand_destroy_warning = "{{ config('languageString.brand_destroy_warning') }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it') }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx') }}";
</script>
    <script src="{{URL::asset('assets/js/custom/brand.js')}}?v={{ time() }}"></script>
@endsection
