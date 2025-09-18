<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="3600" />

    <title>@yield("title", "Management Dashboard") - {{ config("app.name", "Laravel") }}</title>
    <link rel="icon" type="image/png" href="{{ asset("images/Logo.ico") }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset("font-awesome/css/all.min.css") }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Styles / Scripts -->
    @vite(["resources/css/app.css", "resources/js/app.js"])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack("styles")
</head>

<body class="h-full bg-slate-50 dark:bg-slate-900">
    <div class="min-h-full">
        <!-- Sidebar -->
        @include("layouts.partials.sidebar")

        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Header -->
            @include("layouts.partials.header")

            <!-- Page content -->
            <main class="py-6">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    @yield("content")
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile menu -->
    @include("layouts.partials.mobile-menu")

    <script>
        function toggleMobileMenu() {
            const mobileMenu = $('#mobile-menu');
            if (mobileMenu.hasClass('hidden')) {
                mobileMenu.removeClass('hidden');
            } else {
                mobileMenu.addClass('hidden');
            }
        }
    </script>
    @stack("scripts")
</body>

</html>
