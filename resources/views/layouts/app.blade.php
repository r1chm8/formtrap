@extends('layouts.master')

@section('content')
    <div id="app"></div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
