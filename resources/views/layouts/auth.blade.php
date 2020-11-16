@extends('layouts.master')

@section('content')
    <div id="app" class="flex flex-col min-h-full bg-grey-200 lg:flex-row-reverse">
        <div class="flex justify-center items-center bg-blue-500-to-right-purple-500 px-4 pt-5 pb-4 lg:w-1/2 xl:w-3/5">
            <a href="{{ url('/') }}">
                <img
                    src="/images/logo.svg"
                    alt="Formtrap"
                    class="block w-32 lg:w-48"
                >
            </a>
        </div>

        <div class="flex flex-grow justify-center w-full px-4 py-8 lg:items-center lg:px-8 lg:w-1/2 xl:w-2/5">
            @yield('content')
        </div>
    </div>
@overwrite

@push('scripts')
    <script src="{{ mix('js/auth.js') }}"></script>
@endpush
