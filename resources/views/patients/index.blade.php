@extends("layouts.app")

@section("title", "Patients Management")

@section("content")
    <div class="space-y-6">
        <!-- Flash Messages -->
        @if (session("success"))
            <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-check-circle h-5 w-5 text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session("success") }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="rounded-md bg-red-50 p-4 dark:bg-red-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-exclamation-circle h-5 w-5 text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session("error") }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Patients</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage all patient records and information</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @if (auth()->user()->role === "admin")
                    <a class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" href="{{ route("patients.create") }}">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add New Patient
                    </a>
                @endif
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex flex-col gap-4 sm:flex-row">
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-search h-4 w-4 text-slate-400"></i>
                    </div>
                    <input class="block w-full rounded-lg border-0 py-2 pl-10 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="searchInput" type="text" placeholder="Search patients by name, HN, or QID...">
                </div>
            </div>
            <div class="flex gap-2">
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="nationalityFilter">
                    <option value="">All Nationality</option>
                    @foreach ($nationalities as $nationality)
                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                    @endforeach
                </select>
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="typeFilter">
                    <option value="">All Type</option>
                    <option value="OPD">OPD</option>
                    <option value="IPD">IPD</option>
                </select>
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="genderFilter">
                    <option value="">All Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>

        @include("patients.table")
    </div>
@endsection

@push("scripts")
    <script>
        // Search and filter functionality
        $('#searchInput').on('input', filterPatients);
        $('#nationalityFilter').on('change', filterPatients);
        $('#typeFilter').on('change', filterPatients);
        $('#genderFilter').on('change', filterPatients);

        function filterPatients() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const nationalityFilter = $('#nationalityFilter').val();
            const typeFilter = $('#typeFilter').val();
            const genderFilter = $('#genderFilter').val();
            const rows = $('#patientsTableBody tr');

            rows.each(function() {
                const row = $(this);
                const name = row.find('td:first-child .text-sm.font-medium').text().toLowerCase() || '';
                const hn = row.find('td:nth-child(2)').text().toLowerCase() || '';
                const qid = row.find('td:first-child .text-slate-500').text().toLowerCase() || '';
                const gender = row.find('td:nth-child(4) span').text().replace(/\s/g, "") || '';
                const type = row.find('td:nth-child(6) span').text().replace(/\s/g, "") || '';
                const nationality = row.find('td:nth-child(3) span').text() || '';
                const matchesSearch = name.includes(searchTerm) || hn.includes(searchTerm) || qid.includes(searchTerm);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesNationality = !nationalityFilter || nationality === nationalityFilter;
                const matchesGender = !genderFilter || gender === genderFilter;

                if (matchesSearch && matchesType && matchesNationality && matchesGender) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    </script>
@endpush
