@extends('layouts.auth')

@section('content')
    <div class="w-full max-w-md">
        <h1 class="title text-grey-500 text-center mb-6 lg:text-left">
            Reset Password
        </h1>

        <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="field">
                    <label for="email" class="label">Email address</label>

                    <div class="control">
                        <input
                            id="email"
                            type="email"
                            class="input{{ $errors->has('email') ? ' error' : null }}"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    @if($errors->has('email'))
                        <div class="error-message">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="field">
                    <div class="control">
                        <button
                            type="submit"
                            class="button primary w-full"
                        >Send Password Reset Link</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
