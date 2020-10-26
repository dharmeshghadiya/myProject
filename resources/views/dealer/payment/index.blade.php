@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.payment') }}</h2>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                @if($total_balance>0)
                    <h4 class="content-title mb-0 my-auto">{{ config('languageString.total_balance') }} : <span id="total_balance">{{ '$'.$total_balance }}</span></h4>
                @endif
            </div>
        </div>
        @php /*
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                @if($total_balance>0)
                    <a href="#" class="btn btn-info mr-2 withdraw">
                        <i class="fa fa-money-check-alt"></i> {{ config('languageString.withdraw') }}
                    </a>
                @endif
            </div>
        </div>
        */
        @endphp
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                @php /*
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">{{ config('languageString.total_balance') }} : <span id="total_balance">{{ '$ '.$total_balance }}</span></h4>
                    </div>
                </div>
                */
                @endphp
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{ config('languageString.id') }}</th>
                                <th>{{ config('languageString.amount') }}</th>
                                <th>{{ config('languageString.type') }}</th>
                                <th>{{ config('languageString.status') }}</th>
                                <th>{{ config('languageString.date') }}</th>
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

    <div class="modal fade" id="withDrawModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">With Draw</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
    <script src="{{URL::asset('assets/js/custom/dealer/payment.js')}}?v={{ time() }}"></script>
@endsection
