@extends('layouts.auth')

@section('content')
    <div class="w-full max-w-md">
        <h1 class="title text-grey-500 text-center mb-6 lg:text-left">
            {{ __('Verify Your Email Address') }}
        </h1>

        <div class="bg-white shadow px-4 py-8 mb-6 md:px-8">
            @if (session('resent'))
                <div class="alert alert-success">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </div>
    </div>
@endsection
