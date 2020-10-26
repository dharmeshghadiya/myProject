<div class="row">
    <div class="col-xl-12">
        <div class="card">

            <div class="card-body">
                <h4> {{ config('languageString.rydes') }} </h4>
                <div class="table-responsive">
                    @if(isset($vehicles) && count($vehicles) > 0)
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <tr>
                                <th><input type="checkbox" name=""  id="allcheckbox"></th>
                                <th>{{ config('languageString.brand') }}</th>
                               
                                <th>{{ config('languageString.year') }}</th>
                                <th>{{ config('languageString.color') }}</th>
                                <th>{{ config('languageString.hourly_amount') }}</th>
                                <th>{{ config('languageString.daily_amount') }}</th>
                                <th>{{ config('languageString.weekly_amount') }}</th>
                                <th>{{ config('languageString.monthly_amount') }}</th>
                            </tr>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicle)
                              
                                <tr>
                                    <td><input type="checkbox" name="vehicle_id[]" value="{{$vehicle->id}}]" @if(in_array($vehicle->id, $vehicle_ids)) {{'disabled'}} @else class="source" required @endif></td>
                                    <td>{{$vehicle->ryde->brand->name}}</td>
                                    <td>{{$vehicle->ryde->modelYear->name}}</td>
                                    <td>{{$vehicle->ryde->color->name}}</td>
                                    <td>{{$vehicle->hourly_amount}}</td>
                                    <td>{{$vehicle->daily_amount}}</td>
                                    <td>{{$vehicle->weekly_amount}}</td>
                                    <td>{{$vehicle->monthly_amount}}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>

                       
                    @else
                        <h3 class="text-center">{{ config('languageString.ryde_not_available') }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--/div-->
</div>
