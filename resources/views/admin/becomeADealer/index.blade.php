@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ config('languageString.become_a_dealer') }}</h4>
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
                                <th>{{ config('languageString.name') }}</th>
                                <th>{{ config('languageString.business_name') }}</th>
                                <th>{{ config('languageString.bussines_number') }}</th>
                                <th>{{ config('languageString.email') }}</th>
                                
                                <th>{{ config('languageString.mobile_no') }}</th>
                                
                                
                                <th>{{ config('languageString.reason') }}</th>
                                <th>{{ config('languageString.status') }}</th>

                                <th>{{ config('languageString.actions') }}</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
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
@endsection
@section('js')
<script>
    const title = "{{ config('languageString.destroy_become_a_dealer').'?' }}";
    const text = "{{ config('languageString.confirm_message').'?' }}";
    const confirmButtonText= "{{ config('languageString.yes_delete_it').'?' }}";
    const cancelButtonText= "{{ config('languageString.no_cancel_plx').'?' }}";

    const accept_status_msg = "{{ 'Are you sure you want Accept?' }}";
    const reject_status_msg = "{{ 'Are you sure you want Reject?' }}";
    const acceptButtonText = "{{ config('languageString.yes_accept_it').'?' }}";
    const rejectButtonText = "{{ config('languageString.yes_reject_it').'?' }}";
    const yes_change_btn= "{{ config('languageString.yes_change_it') }}";

     const accept_status = "{{ config('languageString.accept_status') }}";

</script>
    <script src="{{URL::asset('assets/js/custom/becomeADealer.js')}}?v={{ time() }}"></script>
@endsection
