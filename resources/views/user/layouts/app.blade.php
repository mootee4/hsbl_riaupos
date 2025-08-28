<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>@yield('title', 'HSBL Riau Pos')</title>

    {{-- Favicon --}}
<link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

  {{-- Tailwind via CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>

  @stack('styles')
</head>
<body class="bg-gray-200">

  @include('user.partials.header')

  <main class="pt-32">
    @yield('content')
  </main>

  @include('user.partials.footer')

  @stack('scripts')
</body>
</html>
