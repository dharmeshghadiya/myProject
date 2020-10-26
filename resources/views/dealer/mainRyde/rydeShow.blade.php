<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    @if(isset($id))
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.image') }}</th>
                                <th>{{ config('languageString.body') }}</th>

                                <th>{{ config('languageString.door') }}</th>


                                <th>{{ config('languageString.seat') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="hidden" name="ryde_id" id="ryde_id" value="{{$id}}">{{ $id }}</td>
                                <td><img src="{{asset($model_image)}}" style="width:100px"></td>
                                <td>{{ $body }}</td>
                                <td>{{ $door }}</td>
                                <td>{{ $seats }}</td>
                            </tr>
                            </tbody>
                        </table>


                    @else
                        <h3 class="text-center">{{ config('languageString.ryde_does_not_found') }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--/div-->
</div>
