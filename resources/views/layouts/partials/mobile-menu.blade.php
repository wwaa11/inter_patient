<!-- Mobile menu -->
<div class="hidden lg:hidden" id="mobile-menu">
    <div class="fixed inset-0 z-50"></div>
    <div class="fixed inset-y-0 left-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-slate-900/10 dark:bg-slate-800 dark:sm:ring-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex h-8 items-center justify-center rounded-lg">
                    <img class="h-8" src="{{ asset("images/logo.ico") }}" alt="logo">
                </div>
                <span class="text-lg font-bold text-slate-900 dark:text-white">GOP Management</span>
            </div>
            <button class="-m-2.5 rounded-md p-2.5 text-slate-700 dark:text-slate-300" type="button" onclick="toggleMobileMenu()">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-slate-500/10 dark:divide-slate-700">
                <div class="space-y-2 py-6">
                    <a class="{{ request()->routeIs("patients*") ? "bg-emerald-50 dark:bg-emerald-900/20" : "" }} -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="{{ route("patients") }}">
                        <div class="flex items-center space-x-3">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span>Patients</span>
                        </div>
                    </a>
                    {{-- <a class="{{ request()->routeIs("dashboard") ? "bg-emerald-50 dark:bg-emerald-900/20" : "" }} -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="{{ route("dashboard") }}">
                        <div class="flex items-center space-x-3">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                            </svg>
                            <span>Dashboard</span>
                        </div>
                    </a> --}}
                    <a class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="#">
                        <div class="flex items-center space-x-3">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span>Users</span>
                        </div>
                    </a>
                    <a class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="#">
                        <div class="flex items-center space-x-3">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Analytics</span>
                        </div>
                    </a>
                    <a class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="#">
                        <div class="flex items-center space-x-3">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Reports</span>
                        </div>
                    </a>
                    @if (auth()->user()->role === "admin")
                        <a class="{{ request()->routeIs("settings*") ? "bg-emerald-50 dark:bg-emerald-900/20" : "" }} -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" href="{{ route("settings.index") }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Settings</span>
                            </div>
                        </a>
                    @endif
                </div>
                <div class="py-6">
                    <div class="flex items-center space-x-3 px-3">
                        <div>
                            <div class="text-base font-semibold leading-6 text-slate-900 dark:text-white">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium leading-5 text-slate-500 dark:text-slate-400">{{ auth()->user()->position }}</div>
                        </div>
                    </div>
                    <div class="mt-6 space-y-1">
                        <form method="POST" action="{{ route("logout") }}">
                            @csrf
                            <button class="block w-full rounded-lg px-3 py-2 text-left text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-slate-700" type="submit">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
