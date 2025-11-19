<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('uploads/favicon.png') }}" type="image/png">

    <!--<link rel="icon" href="{{ asset($favicon) }}">-->
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
            background: #ff5900;
            color: white;
        }

        section.dashboardSection {
            padding: 25px 0px;
        }

        .myaccount-tab-menu.nav a:hover {
            background: #ff5900;
            color: white;
        }
    </style>
    @stack('css')
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
