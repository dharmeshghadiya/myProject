@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.report_problem') }} </h2>
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
                                <th>{{ config('languageString.message') }}</th>
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

    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">{{ config('languageString.report_problem_details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="globalModalDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ config('languageString.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_report_problem').'?' }}";
        const text = "{{ config('languageString.confirm_message') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it')}}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";
    </script>
    <script src="{{URL::asset('assets/js/custom/reportProblem.js')}}?v={{ time() }}"></script>
@endsection
