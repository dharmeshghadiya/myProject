@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.booking') }}</h2>
            </div>
        </div>

        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mt-2 mb-xl-0">
                <a href="{{ route('dealer::booking.create') }}"
                   class="btn btn-primary  mr-2">
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
                                <th>{{ config('languageString.company') }}</th>
                                <th>{{ config('languageString.company') }} {{ config('languageString.address') }}</th>
                                <th>{{ config('languageString.user_name') }}</th>
                                <th>{{ config('languageString.mobile_no') }}</th>
                                <th>{{ config('languageString.start') }} & {{ config('languageString.end') }} {{ config('languageString.date') }}</th>
                                <th>{{ config('languageString.total_day_rent') }}</th>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ config('languageString.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
<script>
    const title = "{{ config('languageString.destroy_ryde').'?' }}";
    const text = "{{ config('languageString.confirm_message') }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it') }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx') }}";
</script>
    <script src="{{URL::asset('assets/js/custom/dealer/booking.js')}}?v={{ time() }}"></script>
@endsection
