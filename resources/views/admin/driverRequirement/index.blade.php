@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ $country->name }} Driver Requirement</h2>

            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('admin::driverRequirement.create',[$country->id]) }}" class="btn btn-primary  mr-2">
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
                                <th>#</th>
                                <th>{{ config('languageString.requirement') }}</th>
                                <th>{{ config('languageString.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($driver_requirements as $key=>$driver_requirement)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $driver_requirement->name }}</td>
                                    <td><a href="{{ route('admin::driverRequirement.edit', [$driver_requirement->id,$country->id]) }}"
                                           class="btn btn-sm btn-outline-info waves-effect waves-light"
                                           data-toggle="tooltip" data-placement="top" title="{{ config('languageString.edit') }}"><i
                                                class="bx bx-pencil font-size-16 align-middle"></i></a>

                                        <button data-id="{{ $driver_requirement->id }}" data-country-id="{{ $country->id }}"
                                                class="delete-single btn btn-sm btn-outline-danger waves-effect waves-light"
                                                data-toggle="tooltip" data-placement="top" title="{{ config('languageString.delete') }}"><i
                                                class="bx bx-trash font-size-16 align-middle"></i>
                                        </button>
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
@endsection
@section('js')
<script>
    const title = "{{ config('languageString.destroy_driver_requirement').'?' }}";
    const text = "{{ config('languageString.confirm_message') }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it') }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx') }}";
</script>
    <script src="{{URL::asset('assets/js/custom/driverRequirement.js')}}"></script>
@endsection
