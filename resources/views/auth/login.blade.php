@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('twitch_login') }}">
                        @csrf
                        <div class="form-group row mb-0 offset-5">
                            <button type="submit" class="btn btn-primary">
                                Login with Twitch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
