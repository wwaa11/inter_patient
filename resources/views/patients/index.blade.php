@extends("layouts.app")

@section("title", "Patients Management")

@section("content")
    <div class="mb-6 space-y-6">
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

        <form class="w-full" action="{{ route("patients.index") }}" method="GET">
            <!-- Search and Filter Section -->
            <div class="flex flex-col gap-4 sm:flex-row">
                <a class="flex items-center rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" href="{{ route("patients.index") }}">
                    <i class="fa-solid fa-refresh h-4 w-4 text-slate-400"></i>
                </a>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-search h-4 w-4 text-slate-400"></i>
                </div>
                <input class="block w-full rounded-lg border-0 py-2 pl-10 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="searchInput" value="{{ $request->search }}" name="search" type="text" placeholder="Search patients by name, HN, or QID...">
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="nationalityFilter" name="nationality">
                    <option value="">All Nationality</option>
                    @foreach ($nationalities as $nationality)
                        <option {{ $request->nationality == $nationality ? "selected" : "" }} value="{{ $nationality }}">{{ $nationality }}</option>
                    @endforeach
                </select>
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="typeFilter" name="type">
                    <option {{ $request->type == "All Type" ? "selected" : "" }} value="">All Type</option>
                    <option {{ $request->type == "OPD" ? "selected" : "" }} value="OPD">OPD</option>
                    <option {{ $request->type == "IPD" ? "selected" : "" }} value="IPD">IPD</option>
                </select>
                <select class="rounded-lg border-0 px-3 py-2 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" id="genderFilter" name="gender">
                    <option {{ $request->gender == "All Gender" ? "selected" : "" }} value="">All Gender</option>
                    <option {{ $request->gender == "Male" ? "selected" : "" }} value="Male">Male</option>
                    <option {{ $request->gender == "Female" ? "selected" : "" }} value="Female">Female</option>
                </select>

                <button class="flex cursor-pointer items-center rounded-lg border-0 px-3 py-2 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm dark:bg-slate-700 dark:text-white dark:ring-slate-600" type="submit">
                    <i class="fa-solid fa-search h-4 w-4 text-slate-400"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">HN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Nationality</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Birthday</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Location</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800" id="patientsTableBody">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-emerald-500 to-teal-500">
                                            <span class="text-sm font-medium text-white">{{ substr($patient->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $patient->name }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ $patient->qid ?? "No QID" }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-sm text-slate-900 dark:text-white">{{ $patient->hn }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-sm text-slate-900 dark:text-white">
                                <span class="">{{ $patient->nationality }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="{{ $patient->gender === "Male" ? "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" : ($patient->gender === "Female" ? "bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200" : "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200") }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                    {{ $patient->gender }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                {{ $patient->birthday ? \Carbon\Carbon::parse($patient->birthday)->format("d M Y") : "N/A" }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="{{ $patient->type === "OPD" ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200" : ($patient->type === "IPD" ? "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200" : "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200") }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                    {{ $patient->type ?? "Unknown" }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $patient->location ?? "N/A" }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end">
                                    <a class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-3 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" href="{{ route("patients.view", $patient->hn) }}" title="View Patient">
                                        <i class="fa-solid fa-eye mr-2"></i>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-12 text-center" colspan="7">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-users mb-4 text-4xl text-slate-400"></i>
                                    <h3 class="mb-2 text-lg font-medium text-slate-900 dark:text-white">No patients found</h3>
                                    <p class="mb-4 text-slate-500 dark:text-slate-400">Get started by adding your first patient.</p>
                                    @if (auth()->user()->role === "admin")
                                        <a class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-teal-700" href="{{ route("patients.create") }}">
                                            <i class="fa-solid fa-plus mr-2"></i>
                                            Add Patient
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($patients->hasPages())
            <div class="border-t border-slate-200 px-6 py-3 dark:border-slate-700">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    </div>
@endsection

@push("scripts")
@endpush
