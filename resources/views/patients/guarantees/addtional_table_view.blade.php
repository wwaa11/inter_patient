<!-- Additional Guarantees -->
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <!-- Header Section -->
    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 dark:border-slate-700 dark:from-slate-800 dark:to-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="rounded-lg bg-emerald-100 p-2 dark:bg-emerald-900/30">
                    <i class="fas fa-shield-alt text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Additional Guarantees</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Manage additional guarantee details and documentation</p>
                </div>
            </div>
            @if ($patient->guaranteeAdditionals && $patient->guaranteeAdditionals->count() > 0)
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                        {{ $patient->guaranteeAdditionals->count() }} {{ Str::plural("guarantee", $patient->guaranteeAdditionals->count()) }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    @if ($patient->guaranteeAdditionals && $patient->guaranteeAdditionals->count() > 0)
        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Embassy Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Cover Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Files</th>
                        @if (auth()->user()->role === "admin")
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                    @foreach ($patient->guaranteeAdditionals as $guarantee)
                        <!-- Guarantee Header Row -->
                        <tr class="bg-slate-50 dark:bg-slate-700">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-semibold text-white shadow-sm" style="background-color: {{ $guarantee->additionalType->colour }};">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ $guarantee->additionalType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <i class="fas fa-building mr-2 text-slate-500"></i>
                                    {{ $guarantee->embassy_ref }}/{{ $guarantee->mb }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus mr-2 text-slate-500"></i>
                                    {{ $guarantee->issueDate() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">
                                @if (strtolower($guarantee->coverPeriod()) !== "n/a")
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2 text-slate-500"></i>
                                            <span>{{ $guarantee->coverPeriod() }}</span>
                                        </div>
                                        @if ($guarantee->isInCoverPeriod())
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Expired
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="italic text-slate-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($guarantee->file && count($guarantee->file) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($guarantee->file as $file)
                                            <button class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 transition-colors duration-200 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50" onclick="viewFile('{{ $patient->hn }}', '{{ $file }}')">
                                                <i class="fas fa-file-alt mr-1.5"></i>
                                                {{ basename($file) }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm italic text-slate-400">No files</span>
                                @endif
                            </td>
                            @if (auth()->user()->role === "admin")
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-400">{{ $guarantee->details->count() }} {{ Str::plural("detail", $guarantee->details->count()) }}</span>
                                        <button class="inline-flex items-center rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 transition-colors duration-200 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-900/50" onclick="addDetailToGuarantee({{ $guarantee->id }})" title="Add Detail">
                                            <i class="fas fa-plus mr-1"></i>
                                            Add Detail
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>

                        <!-- Detail Rows -->
                        @foreach ($guarantee->details as $item)
                            <tr class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 pl-12">
                                    @if ($item->case !== null)
                                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold text-white" style="background-color: {{ $item->guaranteeCase->colour }};">
                                            <i class="fas fa-briefcase mr-1"></i>
                                            {{ $item->guaranteeCase->name }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">No case</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->details)
                                        <div class="mb-3 max-w-xs">
                                            <p class="truncate text-sm text-slate-700 dark:text-slate-300" title="{{ $item->details }}">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ $item->details }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($item->definition)
                                        <div class="text-sm text-slate-600 dark:text-slate-400">
                                            <i class="fa-solid fa-language"></i>
                                            {{ $item->definition }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-3">
                                        @if ($item->amount)
                                            <div class="flex items-center">
                                                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ $item->amount }}</span>
                                            </div>
                                        @endif
                                        @if ($item->price)
                                            <div class="flex items-center">
                                                <i class="fas fa-dollar-sign mr-1 text-blue-600"></i>
                                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ $item->price }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->start_date || $item->end_date || ($item->specific_date && is_array($item->specific_date) && count($item->specific_date) > 0))
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {!! $item->specificDate() !!}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">

                                </td>
                                @if (auth()->user()->role === "admin")
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 transition-colors duration-200 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50" onclick="editAdditionalDetail({{ $item->id }})">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </button>
                                            <button class="inline-flex items-center rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 transition-colors duration-200 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50" onclick="deleteAdditionalDetail({{ $item->id }})">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                <i class="fas fa-shield-alt text-xl text-slate-400 dark:text-slate-500"></i>
            </div>
            <h3 class="mb-2 text-sm font-medium text-slate-900 dark:text-slate-100">No Additional Guarantees</h3>
            <p class="mb-4 text-sm text-slate-500 dark:text-slate-400">This patient doesn't have any additional guarantees on record.</p>
            @if (auth()->user()->role === "admin")
                <button class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-700 hover:shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Add Guarantee
                </button>
            @endif
        </div>
    @endif
</div>

@push("scripts")
    <script>
        function editAdditionalDetail(id) {
            window.location.href = '{{ route("patients.guarantees.additional.edit", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', id);
        }

        function deleteAdditionalDetail(id) {
            if (confirm('Are you sure you want to delete this additional guarantee detail?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("patients.guarantees.additional.destroy", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', id);

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method override for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }

        function addDetailToGuarantee(guaranteeId) {
            // Redirect to the add detail page for the specific guarantee
            const url = '{{ route("patients.guarantees.additional.detail.create", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', guaranteeId)
            window.location.href = url;
        }
    </script>
@endpush
