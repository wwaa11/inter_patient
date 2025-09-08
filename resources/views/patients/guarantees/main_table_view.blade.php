<!-- Main Guarantees -->
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <!-- Header Section -->
    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 px-3 py-4 dark:border-slate-700 dark:from-slate-800 dark:to-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                    <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Main Guarantees</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Primary guarantee information and documentation</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if (auth()->user()->role === "admin")
                    <a class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700" href="{{ route("patients.guarantees.main.create", $patient->hn) }}">
                        <i class="fa-solid fa-plus mr-1"></i>Main Guarantee
                    </a>
                @endif
                @if ($patient->guaranteeMains && $patient->guaranteeMains->count() > 0)
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $patient->guaranteeMains->count() }} {{ Str::plural("guarantee", $patient->guaranteeMains->count()) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if ($patient->guaranteeMains && $patient->guaranteeMains->count() > 0)
        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Embassy</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Embassy Ref/Number</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Issue Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Cover Period</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Case</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Valid</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Files</th>
                        @if (auth()->user()->role === "admin")
                            <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                    @foreach ($patient->guaranteeMains as $guarantee)
                        <tr class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-3 py-4">
                                <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-semibold text-white shadow-sm" style="background-color: {{ $guarantee->embassyData->colour }}">
                                    <i class="fas fa-building mr-2"></i>
                                    {{ $guarantee->embassyData->name }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <i class="fas fa-file-contract mr-2 text-slate-500"></i>
                                    {{ $guarantee->embassy_ref }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag mr-2 text-slate-500"></i>
                                    {{ $guarantee->number }}
                                </div>
                            </td>

                            <td class="px-3 py-4 text-sm text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus mr-2 text-slate-500"></i>
                                    {{ $guarantee->issueDate() }}
                                </div>
                            </td>
                            <td class="px-3 py-4 text-sm text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-slate-500"></i>
                                    <span>{{ $guarantee->coverPeriod() }}</span>
                                </div>
                                @if ($guarantee->extension && $guarantee->extensionCoverEndDate())
                                    <div class="mt-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="fas fa-calendar-plus mr-1"></i>
                                        Extended to: {{ $guarantee->extensionCoverEndDate() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                <div>
                                    <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-semibold text-white shadow-sm" style="background-color: {{ $guarantee->guaranteeCase->colour }}">
                                        <i class="fas fa-briefcase mr-2"></i>
                                        {{ $guarantee->guaranteeCase->name }}
                                    </span>
                                    @if ($guarantee->guaranteeCase->definition)
                                        <div class="mt-1 text-xs text-slate-600 dark:text-slate-400">
                                            <i class="fa-solid fa-language mr-1"></i>
                                            {{ $guarantee->guaranteeCase->definition }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <span class="{{ $guarantee->status_class }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                                    @if (str_contains($guarantee->status_class, "green"))
                                        <i class="fas fa-check-circle mr-1"></i>
                                    @elseif (str_contains($guarantee->status_class, "red"))
                                        <i class="fas fa-times-circle mr-1"></i>
                                    @else
                                        <i class="fas fa-clock mr-1"></i>
                                    @endif
                                    {{ $guarantee->status_text }}
                                </span>
                            </td>
                            <td class="w-0 px-3 py-4">
                                @if ($guarantee->file && count($guarantee->file) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($guarantee->file as $file)
                                            <button class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 transition-colors duration-200 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50" onclick="viewFile('{{ $patient->hn }}', '{{ $file }}')">
                                                <i class="fas fa-file-alt mr-1.5"></i>
                                                {{ substr(basename($file), 26) }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm italic text-slate-400">No files</span>
                                @endif
                            </td>
                            @if (auth()->user()->role === "admin")
                                <td class="px-3 py-4">
                                    <div class="flex space-x-2">
                                        <button class="inline-flex items-center rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 transition-colors duration-200 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-900" onclick="openExtendMainGuaranteeModal({{ $guarantee->id }}, '{{ $guarantee->embassy_ref }}')" title="Extend Guarantee">
                                            <i class="fas fa-calendar-plus mr-1"></i>
                                            Extend
                                        </button>
                                        <a href="{{ route('patients.guarantees.main.edit', [$patient->hn, $guarantee->id]) }}" class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 transition-colors duration-200 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900" title="Edit Guarantee">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <button class="inline-flex items-center rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 transition-colors duration-200 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900" onclick="deleteMainGuarantee({{ $guarantee->id }})" title="Delete Guarantee">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="px-3 py-12 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                <i class="fas fa-shield-alt text-xl text-slate-400 dark:text-slate-500"></i>
            </div>
            <h3 class="mb-2 text-sm font-medium text-slate-900 dark:text-slate-100">No Main Guarantees</h3>
            <p class="mb-4 text-sm text-slate-500 dark:text-slate-400">This patient doesn't have any main guarantees on record.</p>
            @if (auth()->user()->role === "admin")
                <button class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors duration-200 hover:bg-blue-700 hover:shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Add Guarantee
                </button>
            @endif
        </div>
    @endif
</div>
