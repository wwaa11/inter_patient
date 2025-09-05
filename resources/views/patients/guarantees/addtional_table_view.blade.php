<!-- Additional Guarantees -->
<div>
    <h4 class="text-md mb-3 font-medium text-slate-800 dark:text-slate-200">Additional Guarantees</h4>
    @if ($patient->guaranteeAdditionals && $patient->guaranteeAdditionals->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Embassy Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Cover Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">File</th>
                        @if (auth()->user()->role === "admin")
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-600 dark:bg-slate-800">
                    @foreach ($patient->guaranteeAdditionals as $guarantee)
                        @foreach ($guarantee->details as $item)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $guarantee->additionalType->colour }};">
                                        {{ $guarantee->additionalType->name }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $guarantee->embassy_ref }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $guarantee->issueDate() }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                    <div>
                                        {{ $guarantee->coverPeriod() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">
                                    <div>{{ $item->details }}</div>
                                    @if ($guarantee->definition)
                                        <div class="mt-1 text-xs text-slate-600 dark:text-slate-400">Staff: {{ $guarantee->definition }}</div>
                                    @endif
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
                                        <button class="text-red-600 hover:text-red-900" onclick="deleteAdditionalDetail({{ $item->id }})">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-sm text-slate-500 dark:text-slate-400">No additional guarantees found.</p>
    @endif
</div>
