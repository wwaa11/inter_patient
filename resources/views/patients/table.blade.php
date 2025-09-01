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
                                    <a class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-3 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" href="{{ route("patients.show", $patient->hn) }}" title="View Patient">
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
                                    <a class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-teal-700" href="{{ route("patients.create") }}">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Add Patient
                                    </a>
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
