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
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .skiptranslate {
            display: flex;
        }
    </style>
    @stack("styles")

</head>

<body class="h-full bg-slate-50 dark:bg-slate-900">
    <div class="min-h-full">
        <!-- Mobile menu -->
        @include("layouts.partials.mobile-menu")
        <!-- Sidebar -->
        @include("layouts.partials.sidebar")
        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 dark:border-slate-700 dark:bg-slate-800">
                <!-- Mobile menu button -->
                <button class="-m-2.5 p-2.5 text-slate-700 lg:hidden dark:text-slate-300" type="button" onclick="toggleMobileMenu()">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-slate-200 lg:hidden dark:bg-slate-700" aria-hidden="true"></div>

                {{-- Translate --}}
                <div class="relative flex flex-1" id="google_translate_element"></div>

                {{-- Profile --}}
                <div class="flex gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200 dark:lg:bg-slate-700" aria-hidden="true"></div>

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button class="-m-1.5 flex items-center p-1.5" type="button" @click="open = !open">
                                <span class="sr-only">Open user menu</span>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-slate-900 dark:text-white" aria-hidden="true">{{ auth()->user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown menu -->
                            <div class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-slate-900/5 focus:outline-none dark:bg-slate-800" x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                                <form method="POST" action="{{ route("logout") }}">
                                    @csrf
                                    <button class="block w-full px-3 py-1 text-left text-sm leading-6 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" type="submit">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-6">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    @yield("content")
                </div>
            </main>
        </div>
    </div>

    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,th',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL,
            }, 'google_translate_element');
        }

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
