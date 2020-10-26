@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.main_ryde') }}</h2>
            </div>
        </div>
        <div class="pr-1 mt-2 mb-xl-0 float-right">

            <a href="{{ route('dealer::mainRyde.create') }}" class="btn btn-primary  mr-2">
                <i class="mdi mdi-plus-circle"></i> {{ config('languageString.add_new') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-2 pl-4  mb-3 mb-xl-0">

                            <select id="colorFilter" name="color" class="form-control" required>
                                <option value="">{{ config('languageString.color') }}</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}"> {{ $color->name}}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-2 pl-4 mb-3 mb-xl-0">

                            <select id="yearFilter" name="year" class="form-control" required>
                                <option value="">{{ config('languageString.year') }}</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}"> {{ $year->name}}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-2 pl-4  mb-3 mb-xl-0">

                            <select id="makeFilter" name="make" class="form-control" required>
                                <option value="">{{ config('languageString.brand') }}</option>
                                @foreach($makes as $make)
                                    <option value="{{ $make->id }}"> {{$make->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-4 pl-3  mb-3" >
                                <select id="companyAddressId" name="companyAddressId" style="width:280px" class="form-control select2"
                                        required  aria-describedby="basic-addon1">
                                    <option value="">{{ config('languageString.branch') }}</option>
                                    @foreach($companyAddresses as $companyAddress)
                                        <option
                                            value="{{ $companyAddress->id }}"> {{ $companyAddress->address.' ( '. $companyAddress->service_distance.' KM ) ' }}
                                        </option>
                                    @endforeach
                                </select>
                            <button type="button" class="btn btn-secondary"  value="filter" id="filter" aria-describedby="basic-addon2">
                                {{ config('languageString.filters') }}
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
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
                                <th>{{ config('languageString.branches') }}</th>
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
@endsection
@section('js')
    <script>
        const title = "{{ config('languageString.destroy_ryde').'?' }}";
        const ryde_destroy  = "{{ config('languageString.ryde_destroy') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";


        const feature_placeholder = "{{ config('languageString.feature_placeholder') }}";
        const option_placeholder = "{{ config('languageString.option_placeholder') }}";


        const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
        const yes_change_btn = "{{ config('languageString.yes_change_it') }}";

    </script>
    <script src="{{URL::asset('assets/js/custom/dealer/mainRyde.js')}}?v={{ time() }}"></script>
@endsection
