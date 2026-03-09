@extends('layouts.app')



@section('title', 'Dashboard')



@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pre-authorizations Dashboard Link -->
                <a href="{{ route('dashboard.preauth') }}"
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div
                        class="absolute right-0 top-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-emerald-500/5 transition-all group-hover:bg-emerald-500/10">
                    </div>
                    <div class="flex flex-col h-full">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Pre-Authorization</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6 font-light">Detailed analysis of service types,
                            coverage decisions, provider distribution, and staff performance.</p>
                        <div class="mt-auto flex items-center font-medium text-emerald-600 dark:text-emerald-400">
                            View Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Admissions Dashboard Link -->
                <a href="{{ route('dashboard.admissions') }}"
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div
                        class="absolute right-0 top-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-blue-500/5 transition-all group-hover:bg-blue-500/10">
                    </div>
                    <div class="flex flex-col h-full">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Admissions</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6 font-light">Comprehensive overview of admitting
                            status, department allocation, GOP status, and user workload.</p>
                        <div class="mt-auto flex items-center font-medium text-blue-600 dark:text-blue-400">
                            View Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
