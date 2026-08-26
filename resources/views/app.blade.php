<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @php
            $versionFile = public_path('build-version.json');
            $buildVersion = '9.0.000';

            if (file_exists($versionFile)) {
                $versionData = json_decode(file_get_contents($versionFile), true);
                $buildVersion = $versionData['version'] ?? '9.0.000';
            }
        @endphp
        <meta name="build-version" content="{{ $buildVersion }}">
        <script>
            var build_version = '{{ $buildVersion }}';
        </script>
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
