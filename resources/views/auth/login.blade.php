<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config("app.name", "Laravel") }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset("font-awesome/css/all.min.css") }}">

    <!-- Styles / Scripts -->
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="h-full bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Background Pattern -->
    <div class="bg-[url('data:image/svg+xml,%3Csvg width= absolute inset-0"60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23059669" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="2" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

    <div class="relative flex min-h-full flex-col justify-center px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <div class="mx-auto w-full max-w-sm sm:max-w-md">
            <!-- Logo -->
            <div class="flex justify-center">
                <div class="logo-large flex h-32 w-32 items-center justify-center rounded-3xl sm:h-40 sm:w-40">
                    <img class="h-20 w-20 object-contain sm:h-28 sm:w-28" src="{{ asset("images/logo.png") }}" alt="Logo">
                </div>
            </div>
            <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-slate-900 sm:mt-8 sm:text-3xl dark:text-white">
                GOP Management
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Sign in to access your dashboard
            </p>
        </div>

        <div class="mx-auto mt-6 w-full max-w-sm sm:mt-8 sm:max-w-md">
            <div class="login-form px-4 py-8 shadow-2xl ring-1 ring-slate-200/50 sm:rounded-2xl sm:px-8 sm:py-10 lg:px-10">
                <form class="space-y-5 sm:space-y-6" id="loginForm">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold leading-6 text-slate-900 dark:text-white" for="userid">
                            <i class="fa-solid fa-id-card mr-2 text-emerald-600"></i>
                            Employee ID
                        </label>
                        <div class="mt-2">
                            <input class="login-input block w-full rounded-lg border-0 px-4 py-3 text-base text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:rounded-xl sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600 dark:placeholder:text-slate-400" id="userid" name="userid" type="text" required placeholder="Enter your employee ID" autocomplete="username">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold leading-6 text-slate-900 dark:text-white" for="password">
                            <i class="fa-solid fa-lock mr-2 text-emerald-600"></i>
                            Password
                        </label>
                        <div class="mt-2">
                            <input class="login-input block w-full rounded-lg border-0 px-4 py-3 text-base text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:rounded-xl sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600 dark:placeholder:text-slate-400" id="password" name="password" type="password" required placeholder="Enter your password" autocomplete="current-password">
                        </div>
                    </div>

                    <div>
                        <button class="login-button flex w-full transform justify-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-4 text-base font-semibold leading-6 text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-emerald-700 hover:to-teal-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 active:scale-[0.98] disabled:opacity-50 sm:rounded-xl sm:py-3 sm:text-sm" id="loginBtn" type="submit">
                            <span class="flex items-center" id="loginBtnText">
                                <i class="fa-solid fa-sign-in-alt mr-2"></i>
                                Sign in
                            </span>
                            <svg class="-ml-1 mr-3 hidden h-5 w-5 animate-spin text-white" id="loginSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Error/Success Messages -->
                    <div class="hidden" id="messageContainer">
                        <div class="hidden rounded-lg border border-red-200 bg-red-50 p-3 sm:rounded-xl sm:p-4 dark:border-red-800 dark:bg-red-900/20" id="errorMessage">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-exclamation-circle h-5 w-5 text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                        Login Failed
                                    </h3>
                                    <div class="mt-1 text-sm text-red-700 sm:mt-2 dark:text-red-300">
                                        <p id="errorText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden rounded-lg border border-emerald-200 bg-emerald-50 p-3 sm:rounded-xl sm:p-4 dark:border-emerald-800 dark:bg-emerald-900/20" id="successMessage">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-check-circle h-5 w-5 text-emerald-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                                        Login Successful
                                    </h3>
                                    <div class="mt-1 text-sm text-emerald-700 sm:mt-2 dark:text-emerald-300">
                                        <p id="successText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#loginForm').on('submit', async function(e) {
            e.preventDefault();

            const loginBtn = $('#loginBtn');
            const loginBtnText = $('#loginBtnText');
            const loginSpinner = $('#loginSpinner');
            const messageContainer = $('#messageContainer');
            const errorMessage = $('#errorMessage');
            const successMessage = $('#successMessage');
            const errorText = $('#errorText');
            const successText = $('#successText');

            // Reset messages
            messageContainer.addClass('hidden');
            errorMessage.addClass('hidden');
            successMessage.addClass('hidden');

            // Show loading state
            loginBtn.prop('disabled', true);
            loginBtnText.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Signing in...');
            loginSpinner.removeClass('hidden');

            try {
                const formData = new FormData(this);
                const response = await axios.post('{{ route("login.request") }}', formData);

                const data = response.data;

                if (data.status === 'success') {
                    successText.text(data.message);
                    successMessage.removeClass('hidden');
                    messageContainer.removeClass('hidden');

                    // Redirect to dashboard after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("patients") }}';
                    }, 500);
                } else {
                    errorText.text(data.message || 'Login failed. Please try again.');
                    errorMessage.removeClass('hidden');
                    messageContainer.removeClass('hidden');
                }
            } catch (error) {
                errorText.text('An error occurred. Please try again.');
                errorMessage.removeClass('hidden');
                messageContainer.removeClass('hidden');
            } finally {
                // Reset button state
                loginBtn.prop('disabled', false);
                loginBtnText.html('<i class="fa-solid fa-sign-in-alt mr-2"></i>Sign in');
                loginSpinner.addClass('hidden');
            }
        });
    </script>
</body>

</html>
