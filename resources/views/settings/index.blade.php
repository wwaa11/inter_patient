@extends("layouts.app")

@section("content")
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Settings</h1>
            <p class="text-slate-600 dark:text-slate-400">Manage system settings and configurations</p>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ session("success") }}
            </div>
        @endif

        <!-- Embassies Section -->
        <div class="mb-8 rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
            <div class="px-6 py-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Embassies</h2>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" onclick="openAddEmbassyModal()">
                        <i class="fa-solid fa-plus mr-2"></i>Add Embassy
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($embassies as $embassy)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $embassy->id }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $embassy->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $embassy->created_at->format("d M Y") }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <button class="mr-2 text-blue-600 hover:text-blue-900" onclick="editEmbassy({{ $embassy->id }}, '{{ $embassy->name }}')">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" onclick="deleteEmbassy({{ $embassy->id }})">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400" colspan="4">No embassies found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Guarantee Cases Section -->
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
            <div class="px-6 py-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Guarantee Cases</h2>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" onclick="openAddGuaranteeCaseModal()">
                        <i class="fa-solid fa-plus mr-2"></i>Add Guarantee Case
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Case</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Definition</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($guaranteeCases as $case)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $case->id }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $case->case }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $case->case_for_staff ?? "N/A" }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $case->created_at->format("d M Y") }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <button class="mr-2 text-blue-600 hover:text-blue-900" onclick="editGuaranteeCase({{ $case->id }}, '{{ $case->case }}', '{{ $case->case_for_staff }}')">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" onclick="deleteGuaranteeCase({{ $case->id }})">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400" colspan="5">No guarantee cases found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Embassy Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50" id="embassyModal">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-slate-800">
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white" id="embassyModalTitle">Add Embassy</h3>
            <form id="embassyForm" method="POST">
                @csrf
                <input id="embassyMethod" type="hidden" name="_method" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="embassyName">Embassy Name</label>
                    <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="embassyName" type="text" name="name" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="rounded-lg bg-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-400" type="button" onclick="closeEmbassyModal()">Cancel</button>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Guarantee Case Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50" id="guaranteeCaseModal">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-slate-800">
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white" id="guaranteeCaseModalTitle">Add Guarantee Case</h3>
            <form id="guaranteeCaseForm" method="POST">
                @csrf
                <input id="guaranteeCaseMethod" type="hidden" name="_method" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="guaranteeCase">Case</label>
                    <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="guaranteeCase" type="text" name="case" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="guaranteeCaseForStaff">Case for Staff (Optional)</label>
                    <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="guaranteeCaseForStaff" type="text" name="case_for_staff">
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="rounded-lg bg-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-400" type="button" onclick="closeGuaranteeCaseModal()">Cancel</button>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Embassy Modal Functions
        function openAddEmbassyModal() {
            document.getElementById('embassyModalTitle').textContent = 'Add Embassy';
            document.getElementById('embassyForm').action = '{{ route("settings.embassies.store") }}';
            document.getElementById('embassyMethod').value = '';
            document.getElementById('embassyName').value = '';
            document.getElementById('embassyModal').classList.remove('hidden');
            document.getElementById('embassyModal').classList.add('flex');
        }

        function editEmbassy(id, name) {
            document.getElementById('embassyModalTitle').textContent = 'Edit Embassy';
            document.getElementById('embassyForm').action = '{{ route("settings.embassies.update", "__ID__") }}'.replace('__ID__', id);
            document.getElementById('embassyMethod').value = 'PUT';
            document.getElementById('embassyName').value = name;
            document.getElementById('embassyModal').classList.remove('hidden');
            document.getElementById('embassyModal').classList.add('flex');
        }

        function closeEmbassyModal() {
            document.getElementById('embassyModal').classList.add('hidden');
            document.getElementById('embassyModal').classList.remove('flex');
        }

        function deleteEmbassy(id) {
            if (confirm('Are you sure you want to delete this embassy?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("settings.embassies.destroy", "__ID__") }}'.replace('__ID__', id);
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Guarantee Case Modal Functions
        function openAddGuaranteeCaseModal() {
            document.getElementById('guaranteeCaseModalTitle').textContent = 'Add Guarantee Case';
            document.getElementById('guaranteeCaseForm').action = '{{ route("settings.guarantee-cases.store") }}';
            document.getElementById('guaranteeCaseMethod').value = '';
            document.getElementById('guaranteeCase').value = '';
            document.getElementById('guaranteeCaseForStaff').value = '';
            document.getElementById('guaranteeCaseModal').classList.remove('hidden');
            document.getElementById('guaranteeCaseModal').classList.add('flex');
        }

        function editGuaranteeCase(id, caseName, caseForStaff) {
            document.getElementById('guaranteeCaseModalTitle').textContent = 'Edit Guarantee Case';
            document.getElementById('guaranteeCaseForm').action = '{{ route("settings.guarantee-cases.update", "__ID__") }}'.replace('__ID__', id);
            document.getElementById('guaranteeCaseMethod').value = 'PUT';
            document.getElementById('guaranteeCase').value = caseName;
            document.getElementById('guaranteeCaseForStaff').value = caseForStaff || '';
            document.getElementById('guaranteeCaseModal').classList.remove('hidden');
            document.getElementById('guaranteeCaseModal').classList.add('flex');
        }

        function closeGuaranteeCaseModal() {
            document.getElementById('guaranteeCaseModal').classList.add('hidden');
            document.getElementById('guaranteeCaseModal').classList.remove('flex');
        }

        function deleteGuaranteeCase(id) {
            if (confirm('Are you sure you want to delete this guarantee case?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("settings.guarantee-cases.destroy", "__ID__") }}'.replace('__ID__', id);
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modals when clicking outside
        document.getElementById('embassyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmbassyModal();
            }
        });

        document.getElementById('guaranteeCaseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGuaranteeCaseModal();
            }
        });
    </script>
@endsection
