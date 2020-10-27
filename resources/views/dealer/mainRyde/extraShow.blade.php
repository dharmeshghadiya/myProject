@if(count($global_extras)>0)
                                <div class="col-12 border-bottom border-top mt-3 mb-3 p-3 bg-light">
                                    <div
                                        class="main-content-label mb-0">{{ config('languageString.extra_selection') }}</div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>{{ config('languageString.extra') }}</th>
                                            <th>{{ config('languageString.price_type') }}</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($global_extras as $keys=>$global_extra)
                                            <tr>
                                                <td>
                                                    {{ $global_extra->name }}
                                                    <div class="main-toggle on extra_switch" style="float: right"
                                                         id="{{ $keys }}">
                                                        <span></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="extra_ids[]"
                                                           value="{{ $global_extra->id }}">
                                                    <select id="extra_id_{{ $keys }}"
                                                            name="extra[]" class="form-control form-control-sm @if($branch_extras[$global_extra->id]==2) bg-light @endif"
                                                            required @if($branch_extras[$global_extra->id]==2) readonly @endif>
                                                        <option value="1" @if($branch_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.daily') }}</option>
                                                        <option
                                                            value="0" @if($branch_extras[$global_extra->id]==1) selected @endif>{{ config('languageString.one_time') }}</option>
                                                        <option
                                                            value="2"
                                                            @if($branch_extras[$global_extra->id]==2) selected @endif >{{ config('languageString.not_require') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="price[]"
                                                           class="form-control form-control-sm integer" value="0"
                                                           id="extra_price_{{ $keys }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

@section('js')

    <script>

    </script>
@endsection
