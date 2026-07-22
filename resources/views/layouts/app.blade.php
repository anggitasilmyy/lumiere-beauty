<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Lumiere Beauty Clinic - Home page klinik kecantikan dengan hero slider, promo interaktif, treatment, membership, dan contact." />

    <title>@yield('title', 'Lumiere Beauty Clinic')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Bootstrap dari Vite --}}
    @vite(['resources/css/app.css'])

    {{-- CSS custom Lumiere tetap dipakai setelah Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <script>
        window.LUMIERE_BASE_URL = "{{ asset('') }}";
    </script>
</head>
<body>
    <div class="page-shell">
        @include('partials.navbar')
        @include('partials.alert')
        @yield('content')
        @include('partials.footer')
    </div>

    {{-- JavaScript Bootstrap dari Vite --}}
    @vite(['resources/js/app.js'])

    {{-- JavaScript custom Lumiere --}}
    <script src="{{ asset('assets/js/script.js') }}" defer></script>
</body>
</html>