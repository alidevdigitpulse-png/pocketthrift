<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('meta_description')"/>
    <link rel="icon" href="{{ asset('uploads/favicon.png') }}" type="image/png">
    <link rel="canonical" href="{{ url()->current() }}"/>
    @stack('schemas')
    @include('layouts.components.css')
    <style>
        .myaccount-tab-menu.nav a {
            display: block;
            padding: 20px;
            font-size: 16px;
            align-items: center;
            width: 100%;
            font-weight: bold;
            color: inherit;
            border-radius: 0;
            border: 1px solid #25282a;
            margin-bottom: 15px;
            font-family: var(--heading--font-family);
            text-transform: uppercase;
            text-decoration: none;
            transition: 0.5s;
        }

        .myaccount-tab-menu.nav a.active {
            background: #cf5103;
            color: white;
        }

        section.dashboardSection {
            padding: 25px 0px;
        }

        .myaccount-tab-menu.nav a:hover {
            background: #cf5103;
            color: white;
        }
    </style>
    @stack('css')

    {{-- Inject Active Head Tags --}}
    @php
        $activeHeadTags = \App\Models\HeadTag::where('status', 1)->get();
    @endphp
    @foreach($activeHeadTags as $tag)
        {!! $tag->code !!}
    @endforeach
</head>

<body>

    @include('layouts.components.header')
    <main>
        @yield('content')
    </main>
    @stack('breadcrumb')
    @include('layouts.components.footer')

    @include('layouts.components.modal')


    @include('layouts.components.script')
    @stack('js')





</body>

</html>
