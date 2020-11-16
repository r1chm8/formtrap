@extends('layouts.auth')

@section('content')
    <auth-container v-cloak>
        <h1 class="title text-grey-500 text-center mb-6 lg:text-left">
            Register
        </h1>

        <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="name" class="label">Your name</label>

                    <div class="control">
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="input{{ $errors->has('name') ? ' error' : null }}"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        >
                    </div>

                    @if($errors->has('name'))
                        <div class="error-message">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="field">
                    <label for="email" class="label">Email address</label>

                    <div class="control">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="input{{ $errors->has('email') ? ' error' : null }}"
                            value="{{ old('email') }}"
                            required
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
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="input"
                            required
                            autocomplete="new-password"
                            {{ $errors->has('password') ? 'input-class="error"' : null }}
                        >
                    </div>

                    @if($errors->has('password'))
                        <div class="error-message">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="field">
                    <label for="password_confirmation" class="label">Confirm Password</label>

                    <div class="control">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="input"
                            required
                            autocomplete="new-password"
                        >
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button
                            type="submit"
                            class="button primary w-full"
                        >Register</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-8 text-center">
            Already have an account? <a href="{{ route('login') }}">login</a>
        </div>
    </auth-container>
@endsection
