<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Home - Administrator')</title>
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    {{-- Global CSS --}}
    <link href="{{ asset('css/layoutWeb.css') }}" rel="stylesheet" />

    {{-- Stack Styles --}}
    @stack('styles')
    <link href="{{ asset('css/layoutWeb.css') }}" rel="stylesheet" />
</head>

<body>
    @include('partials.header') <!-- header -->
    <div class="wrapper">
        @include('partials.sidebar') <!-- sidebar -->

        <main class="content">
            @yield('content') <!-- Isi halaman -->
        </main>
    </div>

    @include('partials.footer') <!-- footer -->

    @stack('scripts')

    @include('partials.sweetalert')



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>