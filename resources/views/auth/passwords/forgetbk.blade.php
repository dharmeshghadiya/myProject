@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background: #81E614;color:#fff;">{{ __('Forget Password') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('forgotPassword') }}">
                            @csrf

                           
                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    
                                </div>
                            </div>

                            

                           

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Forget Password') }}
                                    </button>
                                </div>
                            </div>

                            @if (Session::has('error_message'))
                                <div class="alert alert-danger mt-3 p-2">{{ Session::get('error_message') }}</div>
                            @endif

                            @if (Session::has('success_message'))
                                <div class="alert alert-success mt-3 p-2">{{ Session::get('success_message') }}</div>
                            @endif

                            

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.location = "com.rydezilla://foget-password";
    </script>
@endsection