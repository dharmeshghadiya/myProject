@if(isset($vehicles) && count($vehicles) > 0)
    @foreach($vehicles as $vehicle)
        <div class="col-lg-3">
            <div class="card item-card">
                <div class="card-body pb-0 h-100">
                    <div class="text-center">
                        <img src="{{ asset($vehicle['image']) }}" alt="img" class="img-fluid">
                    </div>
                    <div class="card-body cardbody relative">
                        <div class="cardtitle font-weight-bold">
                            <span>{{$vehicle['make']}} - {{$vehicle['trim']}} - {{$vehicle['model']}}</span>
                        </div>
                        <div class="cardprice font-weight-bold"><span></span>{{'$'.$vehicle['price'][0]}} {{$vehicle['price'][1]}}</div>
                    </div>
                </div>
                <div class="text-center border-top pt-3 pb-3 pl-2 pr-2">
                    <a href="javascript:void(0)" class="btn btn-primary addBooking" data-name="{{$vehicle['make']}} - {{$vehicle['trim']}} - {{$vehicle['model']}}" data-id="{{ $vehicle['vehicle_id'] }}"
                       data-country-id="{{ $vehicle['country_id'] }}">Book</a>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-lg-12">
        <h3 class="text-center">{{ config('languageString.ryde_not_available') }}</h3>
    </div>
@endif
