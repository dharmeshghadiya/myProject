@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ $company_address->address }} {{ config('languageString.rydes') }}</h2>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mt-3 mb-3 mb-xl-0">

                <a href="{{ url('dealer/ryde/create/'.$id) }}" class="btn btn-primary  mr-2">
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
                <input type="hidden" name="branch_id" value="{{$id}}" id="branch_id">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.brand') }}</th>
                                <th>{{ config('languageString.model') }}</th>
                                <th>{{ config('languageString.year') }}</th>
                                <th>{{ config('languageString.color') }}</th>
                                <th>{{ config('languageString.status') }}</th>
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
    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="globalModalDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ config('languageString.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="select2modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{config('languageString.sold_vehicle_modal')}} </h6>

                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>

                <form method="POST" data-parsley-validate="" id="priceModelForm" role="form">
                    @csrf

                    <div class="modal-body">
                        <div class="col-sm-4">
                            <label>{{config('languageString.price')}}</label>
                        </div>
                        <input type="hidden" name="value_id" id="value_id">

                        <div class="row">
                            <div class="col-12" >
                                <input type="text" id="price" data-parsley-type="number" name="price" class="form-control" required="">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn ripple btn-success">{{ config('languageString.submit') }}</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">{{ config('languageString.close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_ryde').'?' }}";
        const text = "{{ config('languageString.confirm_message') }}";
        const price = "{{ config('languageString.price') }}";
        const enter_price = "{{ config('languageString.enter_price') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";


        const feature_placeholder = "{{ config('languageString.feature_placeholder') }}";
        const option_placeholder = "{{ config('languageString.option_placeholder') }}";


        const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
        const yes_change_btn = "{{ config('languageString.yes_change_it') }}";

    </script>
    <script src="{{URL::asset('assets/js/custom/dealer/vehicle.js')}}?v={{ time() }}"></script>
@endsection
