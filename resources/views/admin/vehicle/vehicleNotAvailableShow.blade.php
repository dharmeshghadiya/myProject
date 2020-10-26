<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom mb-3">
                    <h6 class="card-title">{{ config('languageString.ryde_not_availability') }}
                        - {{ $vehicle->companyAddress->address }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table mg-b-0 text-md-nowrap" id="data-table">
                        <thead>
                        <tr>
                            <th>{{ config('languageString.brand') }}</th>
                            <th>{{ config('languageString.year') }}</th>
                            <th>{{ config('languageString.color') }}</th>
                            <th>{{ config('languageString.start_date') }}</th>
                            <th>{{ config('languageString.end_date') }}</th>
                            <th>{{ config('languageString.description') }}</th>
                            <th>{{ config('languageString.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($vehicleNotAvailability as $vehicleNotAvailable)
                            <tr>
                                <td>{{$vehicleNotAvailable->vehicle->ryde->brand->name}}</td>
                                <td>{{$vehicleNotAvailable->vehicle->ryde->modelYear->name}}</td>
                                <td>{{$vehicleNotAvailable->vehicle->ryde->color->name}}</td>
                                <td>{{$vehicleNotAvailable->start_date}}</td>
                                <td>{{$vehicleNotAvailable->end_date}}</td>
                                <td>{{$vehicleNotAvailable->description}}</td>
                                <td>
                                    <div class="btn-icon-list">
                                        <button data-id="{{$vehicleNotAvailable->id}}"
                                                data-vehicle_id="{{$vehicle->id}}"
                                                class="delete-single btn btn-icon btn-danger"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ config('languageString.delete') }}"><i
                                                class="bx bx-trash font-size-16 align-middle"></i></button>
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
