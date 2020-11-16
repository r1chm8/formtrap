<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        
        {{-- Favicon --}}
        @include('partials.favicon')

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Meta --}}
        <title>@yield('meta.title') | {{ config('app.name') }}</title>

        @hasSection('meta.description')
            <meta name="description" content="@yield('meta.description')" />
        @endif

        {{-- Open Graph --}}
        <meta property="og:title" content="{{ config('app.name') }}" />
        <meta property="og:url" content="{{ url()->full() }}" />
        <meta property="og:image" content="{{ url('/images/open-graph.jpg') }}" />

        {{-- Fonts --}}
        <link rel="dns-prefetch" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600" rel="stylesheet">

        {{-- Styles --}}
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        {{-- Global site tag (gtag.js) - Google Analytics --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-140944699-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-140944699-1');
        </script>
    </head>

    <body>
        @yield('content')

        @stack('scripts')
    </body>
</html>
