@extends('layouts.auth')

@section('content')
    <auth-container v-cloak>
        <h1 class="title text-grey-500 text-center mb-6 lg:text-left">
            Reset Password
        </h1>

        <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email" class="label">Email address</label>

                    <div class="control">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="input{{ $errors->has('email') ? ' error' : null }}"
                            value="{{ $email ?? old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>

                    @if($errors->has('email'))
                        <div class="error-message">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="field">
                    <label for="password" class="label">Password</label>

                    <div class="control">
                        <password
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            {{ $errors->has('password') ? 'input-class="error"' : null }}
                        ></password>
                    </div>

                    @if($errors->has('password'))
                        <div class="error-message">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="field">
                    <div class="control">
                        <button
                            type="submit"
                            class="button primary w-full"
                        >Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    <auth-container>
@endsection
