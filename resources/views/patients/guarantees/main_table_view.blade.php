<!-- Main Guarantees -->
<div class="mb-6">
    <h4 class="text-md mb-3 font-medium text-slate-800 dark:text-slate-200">Main Guarantees</h4>
    @if ($patient->guaranteeMains && $patient->guaranteeMains->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Embassy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Embassy Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Valid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Case</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Cover Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">File</th>
                        @if (auth()->user()->role === "admin")
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-600 dark:bg-slate-800">
                    @foreach ($patient->guaranteeMains as $guarantee)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $guarantee->embassyData->colour }}">
                                    {{ $guarantee->embassyData->name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $guarantee->number }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $guarantee->embassy_ref }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <span class="{{ $guarantee->status_class }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                    {{ $guarantee->status_text }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $guarantee->guaranteeCase->colour }}">
                                    {{ $guarantee->guaranteeCase->name }}
                                </span>
                                <span class="main-defination hidden text-xs font-medium text-slate-500 dark:text-slate-300">
                                    {{ $guarantee->guaranteeCase->definition }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $guarantee->issueDate() }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                <div>
                                    {{ $guarantee->coverPeriod() }}
                                    @if ($guarantee->extension && $guarantee->extensionCoverEndDate())
                                        <div class="mt-1 text-xs font-medium text-green-600">
                                            <i class="fa-solid fa-calendar-plus mr-1"></i>
                                            Extended to: {{ $guarantee->extensionCoverEndDate() }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                @if ($guarantee->file)
                                    @foreach ($guarantee->file as $file)
                                        <span class="mr-1 text-blue-600 hover:text-blue-800" onclick="viewFile('{{ $patient->hn }}', '{{ $file }}')">
                                            <i class="fa-solid fa-file"></i>
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            @if (auth()->user()->role === "admin")
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900" onclick="openExtendMainGuaranteeModal({{ $guarantee->id }}, '{{ $guarantee->embassy_ref }}')" title="Extend Guarantee">
                                            <i class="fa-solid fa-calendar-plus"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" onclick="deleteMainGuarantee({{ $guarantee->id }})" title="Delete Guarantee">
                                            <i class="fa-solid fa-trash"></i>
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
        <p class="text-sm text-slate-500 dark:text-slate-400">No main guarantees found.</p>
    @endif
</div>
