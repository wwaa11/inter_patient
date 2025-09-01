<!-- Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 bg-white px-6 pb-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center">
            <div class="flex items-center space-x-3">
                <div class="logo-gradient flex h-8 w-8 items-center justify-center rounded-lg">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-slate-900 dark:text-white">GOP Management</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul class="flex flex-1 flex-col gap-y-7" role="list">
                <li>
                    <ul class="-mx-2 space-y-1" role="list">
                        <!-- Patients -->
                        <li>
                            <a class="{{ request()->routeIs("patients*") ? "nav-active text-emerald-700 dark:text-emerald-400" : "text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700" }} nav-item group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6" href="{{ route("patients") }}">
                                <i class="fa-solid fa-user h-6 w-6 text-lg"></i>
                                Patients
                            </a>
                        </li>

                        <!-- Dashboard -->
                        <li>
                            <a class="{{ request()->routeIs("dashboard") ? "nav-active text-emerald-700 dark:text-emerald-400" : "text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700" }} nav-item group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6" href="{{ route("dashboard") }}">
                                <i class="fa-solid fa-house h-6 w-6 text-lg"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->role === 'admin')
                        <!-- Settings -->
                        <li>
                            <a class="{{ request()->routeIs("settings*") ? "nav-active text-emerald-700 dark:text-emerald-400" : "text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700" }} nav-item group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6" href="{{ route("settings.index") }}">
                                <i class="fa-solid fa-cog h-6 w-6 text-lg"></i>
                                Settings
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
