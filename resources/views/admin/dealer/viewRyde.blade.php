@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ $company_details->name }} {{ config('languageString.rydes') }}</h2>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">

                <a href="{{ url('admin/ryde/create/'.$company_details->id.'/'.$branch_id) }}"
                   class="btn btn-info  mr-2">
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
                                <th>{{ config('languageString.brand') }}</th>
                                <th>{{ config('languageString.model') }}</th>
                                <th>{{ config('languageString.year') }}</th>
                                <th>{{ config('languageString.color') }}</th>
                                <th>{{ config('languageString.status') }}</th>
                                <th>{{ config('languageString.actions') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->id }}</td>
                                    <td>{{ $vehicle->ryde->brand->name }}</td>
                                    <td>{{ $vehicle->ryde->name }}</td>
                                    <td>{{ $vehicle->ryde->modelYear->name }}</td>
                                    <td>{{ $vehicle->ryde->color->name }}</td>
                                    <td>
                                        @if($vehicle->status=='Active')
                                            <span class="badge badge-success">{{ $vehicle->status }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $vehicle->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-icon-list">
                                            <a href="{{ url('admin/ryde/edit/'.$vehicle->company_address_id.'/'.$vehicle->id) }}"
                                               class="btn btn-info btn-icon"
                                               data-toggle="tooltip" data-placement="top"
                                               title="{{config('languageString.edit')}}"><i
                                                        class="bx bx-pencil font-size-16 align-middle"></i></a>

                                            <button data-id="{{$vehicle->id}}"
                                                    class="delete-single btn btn-danger btn-icon"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{config('languageString.delete')}}"><i
                                                        class="bx bx-trash font-size-16 align-middle"></i></button>

                                            <button class="vehicle-details btn btn-info btn-icon"
                                                    data-id="{{ $vehicle->id }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ config('languageString.view_details') }}">
                                                <i class="bx bx-bullseye font-size-16 align-middle"></i>
                                            </button>
                                            @if($vehicle->status == 'Active')
                                                <button data-id="{{$vehicle->id}}" data-status="{{'InActive'}}"
                                                        class="status-change btn btn-success btn-icon"
                                                        data-effect="effect-fall" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{config('languageString.in_active')}}"><i
                                                            class="bx bx-refresh font-size-16 align-middle"></i>
                                                </button>
                                            @else
                                                <button data-id="{{$vehicle->id}}" data-status="{{'Active'}}"
                                                        class="status-change btn btn-success btn-icon"
                                                        data-effect="effect-fall" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{config('languageString.active')}}"><i
                                                            class="bx bx-refresh font-size-16 align-middle"></i>
                                                </button>
                                            @endif

                                            @if($vehicle->status == 'Active')
                                                <a href="{{route('admin::vehicleNotAvailable', [$vehicle->id])}}"
                                                   class="btn btn-info btn-icon"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="{{ config('languageString.ryde_not_availability')}}"><i
                                                            class="bx bx-error-circle"></i></a>
                                            @endif
                                        </div>

                                    </td>
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
        const company_id = "{{$company_details->id}}";
        const branch_id = "{{$branch_id}}";
        const title = "{{ config('languageString.destroy_ryde').'?' }}";
        const vehicle_destroy = "{{ config('languageString.vehicle_destroy') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";

        const change_status_msg = "{{ config('languageString.confirm_change_status_message')}}";
        const yes_change_btn = "{{ config('languageString.yes_change_it') }}";

    </script>
    <script src="{{URL::asset('assets/js/custom/vehicle.js')}}?v={{ time() }}"></script>
@endsection
