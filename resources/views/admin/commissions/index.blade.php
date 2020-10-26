@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.commissions') }}</h2>
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
                                <th>{{ config('languageString.table_company_name') }}</th>
                                <th>{{ config('languageString.due_balance') }} </th>
                                <th>{{ config('languageString.transferred_balance') }}</th>
                                <th>{{ config('languageString.last_transfer_date') }}</th>
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

    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="globalModalTitle"></span> {{ config('languageString.transfer_amount')}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="company_id" name="company_id" value="">
                    <input type="text" id="amount" name="amount" class="form-control integer" placeholder="{{ config('languageString.amount')}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary transfer_amount"
                            data-dismiss="modal">{{ config('languageString.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        const text = "{{ config('languageString.confirm_change_status_message')}}";
        const confirmButtonText = "{{ config('languageString.yes_change_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";
    </script>
    <script src="{{URL::asset('assets/js/custom/commissions.js')}}?v={{ time() }}"></script>
@endsection
