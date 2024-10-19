<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head')
</head>
<body>

<section class="bg-home d-flex align-items-center position-relative">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default">
                    <div>
                        @if(auth()->check() && Auth::user()->settings->dark_mode)
                            <img src="{{ asset('images/vacc_logo_white.png') }}" alt="" style="width: 70%;" class="mt-4 mb-4 d-block mx-auto">
                        @else
                            <img src="{{ asset('images/vacc_logo.png') }}" alt="" style="width: 70%;" class="mt-4 mb-4 d-block mx-auto">
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Introduction -->
                        <p class="text-dark">
                            <strong>{{ $client->name }}</strong> is requesting permission to access your account.
                        </p>

                        <!-- Scope List -->
                        @if (count($scopes) > 0)
                            <div class="scopes">
                                <p class="text-muted"><strong>This application will be able to (read):</strong></p>

                                <ul class="text-muted">
                                    @foreach ($scopes as $scope)
                                        <li>{{ $scope->description }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Authorize Button -->
                        <form method="post" action="{{ route('passport.authorizations.approve') }}">
                            @csrf

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <button type="submit" class="btn btn-success w-100 mt-5">Authorize</button>
                        </form>

                        <!-- Cancel Button -->
                        <form method="post" action="{{ route('passport.authorizations.deny') }}">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <button class="btn btn-danger w-100 mt-2">Cancel</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @if(auth()->check() && auth()->user()->settings->dark_mode)
        <div style="top: 0;left: 0;position: fixed;right: 0;bottom: 0;background-color: black; opacity: 0.7; z-index: -1"></div>
    @endif

    <div
        style="top: 0;left: 0;position: fixed;right: 0;bottom: 0;background-image: url( '{{ iasset('images/oauth/oauth1.png')}}');z-index: -2;filter: blur(4px); background-size: cover; background-position: center"></div>
</section>
</body>
</html>
