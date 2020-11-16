@extends('layouts.auth')

@section('content')
    <auth-container v-cloak>
        <h1 class="title text-grey-500 text-center mb-6 lg:text-left">
            Login
        </h1>

        <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email" class="label">Email</label>

                    <div class="control">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="input{{ $errors->has('email') ? ' error' : null }}"
                            value="{{ old('email') }}"
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
                    
                    <password
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    ></password>
                </div>

                <div class="field">
                    <div class="-m-2 flex items-center">
                        <div class="p-2 flex-shrink-0 md:w-1/2">
                            <div class="control">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    class="checkbox"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                
                                <label for="remember">Remeber me</label>
                            </div>
                        </div>

                        <div class="p-2 flex-grow text-right md:w-1/2">
                            @if (Route::has('password.request'))
                                <div class="field">
                                    <a href="{{ route('password.request') }}" class="text-grey-500 hover:text-grey-400">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button
                            type="submit"
                            class="button primary w-full"
                        >Login</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-8 text-center">
            Not a member <a href="{{ route('register') }}">register</a>
        </div>
    </auth-container>
@endsection
