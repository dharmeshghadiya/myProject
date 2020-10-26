@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ $category->name }} </h2>

            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('admin::addCategoryVehicle',[$category->id]) }}" class="btn btn-primary  mr-2">
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
                                <th>{{ config('languageString.company') }}</th>
                                <th>{{ config('languageString.branches') }}</th>
                                <th>{{ config('languageString.brand') }}</th>

                                <th>{{ config('languageString.year') }}</th>
                                <th>{{ config('languageString.color') }}</th>
                                <th>{{ config('languageString.hourly_amount') }}</th>
                                <th>{{ config('languageString.daily_amount') }}</th>
                                <th>{{ config('languageString.weekly_amount') }}</th>
                                <th>{{ config('languageString.monthly_amount') }}</th>
                                <th>{{ config('languageString.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($category_vehicles as $category_vehicle)
                                <tr>
                                    <td>{{$category_vehicle->companies->name}}</td>
                                    <td>{{$category_vehicle->companyAddress->address}}</td>
                                    <td>{{$category_vehicle->vehicle->ryde->brand->name}}</td>

                                    <td>{{$category_vehicle->vehicle->ryde->modelYear->name}}</td>
                                    <td>{{$category_vehicle->vehicle->ryde->color->name}}</td>
                                    <td>{{$category_vehicle->vehicle->hourly_amount}}</td>
                                    <td>{{$category_vehicle->vehicle->daily_amount}}</td>
                                    <td>{{$category_vehicle->vehicle->weekly_amount}}</td>
                                    <td>{{$category_vehicle->vehicle->monthly_amount}}</td>
                                    <td><button data-id="{{$category_vehicle->id}}" class="delete-single btn btn-sm btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="{{config('languageString.delete')}}"><i class="bx bx-trash font-size-16 align-middle"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
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
    const category_id = "{{$category->id}}";
    const title = "{{ config('languageString.destroy_category_ryde').'?' }}";
    const text = "{{ config('languageString.confirm_message') }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it')}}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx') }}";
</script>
    <script src="{{URL::asset('assets/js/custom/categoryDetail.js')}}"></script>
@endsection
