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
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $embassy->colour }}">
                                            {{ $embassy->name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $embassy->created_at->format("d M Y") }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <button class="mr-2 text-blue-600 dark:text-blue-400 dark:hover:text-blue-500" onclick="editEmbassy({{ $embassy->id }}, '{{ $embassy->name }}', '{{ $embassy->colour }}')">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </button>
                                        <button class="text-red-500 hover:text-red-900 dark:hover:text-red-600" onclick="deleteEmbassy({{ $embassy->id }})">
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
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900 dark:text-white">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background-color: {{ $case->colour }}">
                                            {{ $case->name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $case->definition ?? "N/A" }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $case->created_at->format("d M Y") }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <button class="mr-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-500" onclick="editGuaranteeCase({{ $case->id }}, '{{ $case->name }}', '{{ $case->definition }}', '{{ $case->colour }}')">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </button>
                                        <button class="text-red-500 hover:text-red-900 dark:hover:text-red-600" onclick="deleteGuaranteeCase({{ $case->id }})">
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
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-white p-4 dark:bg-slate-800" id="embassyModal">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-2xl dark:bg-slate-700">
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white" id="embassyModalTitle">Add Embassy</h3>
            <form id="embassyForm" method="POST">
                @csrf
                <input id="embassyMethod" type="hidden" name="_method" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="embassyName">Embassy Name</label>
                    <input class="@error("name") border-red-300 @else border-slate-300 @enderror mt-1 block w-full rounded-md px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-200 dark:bg-slate-500 dark:text-white" id="embassyName" type="text" name="name" value="{{ old("name") }}" required>
                    @error("name")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" id="embassyColourContainer">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="embassyColour">Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input class="h-10 w-16 rounded-md border border-slate-300 dark:border-slate-600" id="embassyColour" type="color" name="colour" value="#3B82F6">
                        <input class="flex-1 rounded-md px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-500 dark:text-white" id="embassyColourText" type="text" placeholder="#3B82F6" readonly>
                    </div>
                    @error("colour")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:bg-slate-900 dark:text-white dark:hover:bg-slate-700" type="button" onclick="closeEmbassyModal()">Cancel</button>
                    <button class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-blue-700" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Guarantee Case Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-white p-4 dark:bg-slate-800" id="guaranteeCaseModal">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-2xl dark:bg-slate-700">
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white" id="guaranteeCaseModalTitle">Add Guarantee Case</h3>
            <form id="guaranteeCaseForm" method="POST">
                @csrf
                <input id="guaranteeCaseMethod" type="hidden" name="_method" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="guaranteeCase">Case</label>
                    <input class="@error("case") border-red-300 @else border-slate-300 @enderror mt-1 block w-full rounded-md px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-200 dark:bg-slate-500 dark:text-white" id="guaranteeCase" type="text" name="case" value="{{ old("case") }}" required>
                    @error("case")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="guaranteeCaseDefinition">Definition (Optional)</label>
                    <input class="@error("definition") border-red-300 @else border-slate-300 @enderror mt-1 block w-full rounded-md px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-200 dark:bg-slate-500 dark:text-white" id="guaranteeCaseDefinition" type="text" name="definition" value="{{ old("definition") }}">
                    @error("definition")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" id="guaranteeCaseColourContainer">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="guaranteeCaseColour">Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input class="h-10 w-16 rounded-md border border-slate-300 dark:border-slate-600" id="guaranteeCaseColour" type="color" name="colour" value="#10B981">
                        <input class="flex-1 rounded-md px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-500 dark:text-white" id="guaranteeCaseColourText" type="text" placeholder="#10B981" readonly>
                    </div>
                    @error("colour")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:bg-slate-900 dark:text-white dark:hover:bg-slate-700" type="button" onclick="closeGuaranteeCaseModal()">Cancel</button>
                    <button class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-blue-700" type="submit">Save</button>
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
            document.getElementById('embassyColourContainer').classList.add('hidden');
            document.getElementById('embassyModal').classList.remove('hidden');
            document.getElementById('embassyModal').classList.add('flex');
        }

        function editEmbassy(id, name, colour) {
            document.getElementById('embassyModalTitle').textContent = 'Edit Embassy';
            document.getElementById('embassyForm').action = '{{ route("settings.embassies.update", "__ID__") }}'.replace('__ID__', id);
            document.getElementById('embassyMethod').value = 'PUT';
            document.getElementById('embassyName').value = name;
            document.getElementById('embassyColour').value = colour || '#3B82F6';
            document.getElementById('embassyColourText').value = colour || '#3B82F6';
            document.getElementById('embassyModal').classList.remove('hidden');
            document.getElementById('embassyModal').classList.add('flex');
        }

        function closeEmbassyModal() {
            document.getElementById('embassyModal').classList.add('hidden');
            document.getElementById('embassyModal').classList.remove('flex');
        }

        function deleteEmbassy(id) {
            Swal.fire({
                title: 'Delete Embassy?',
                text: 'Are you sure you want to delete this embassy? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("settings.embassies.destroy", "__ID__") }}'.replace('__ID__', id);
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Guarantee Case Modal Functions
        function openAddGuaranteeCaseModal() {
            document.getElementById('guaranteeCaseModalTitle').textContent = 'Add Guarantee Case';
            document.getElementById('guaranteeCaseForm').action = '{{ route("settings.guarantee-cases.store") }}';
            document.getElementById('guaranteeCaseMethod').value = '';
            document.getElementById('guaranteeCase').value = '';
            document.getElementById('guaranteeCaseDefinition').value = '';
            document.getElementById('guaranteeCaseColourContainer').classList.add('hidden');
            document.getElementById('guaranteeCaseModal').classList.remove('hidden');
            document.getElementById('guaranteeCaseModal').classList.add('flex');
        }

        function editGuaranteeCase(id, caseName, definition, colour) {
            document.getElementById('guaranteeCaseModalTitle').textContent = 'Edit Guarantee Case';
            document.getElementById('guaranteeCaseForm').action = '{{ route("settings.guarantee-cases.update", "__ID__") }}'.replace('__ID__', id);
            document.getElementById('guaranteeCaseMethod').value = 'PUT';
            document.getElementById('guaranteeCase').value = caseName;
            document.getElementById('guaranteeCaseDefinition').value = definition || '';
            document.getElementById('guaranteeCaseColour').value = colour || '#10B981';
            document.getElementById('guaranteeCaseColourText').value = colour || '#10B981';
            document.getElementById('guaranteeCaseModal').classList.remove('hidden');
            document.getElementById('guaranteeCaseModal').classList.add('flex');
        }

        function closeGuaranteeCaseModal() {
            document.getElementById('guaranteeCaseModal').classList.add('hidden');
            document.getElementById('guaranteeCaseModal').classList.remove('flex');
        }

        function deleteGuaranteeCase(id) {
            Swal.fire({
                title: 'Delete Guarantee Case?',
                text: 'Are you sure you want to delete this guarantee case? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("settings.guarantee-cases.destroy", "__ID__") }}'.replace('__ID__', id);
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Color picker event listeners
        document.getElementById('embassyColour').addEventListener('input', function(e) {
            document.getElementById('embassyColourText').value = e.target.value;
        });

        document.getElementById('guaranteeCaseColour').addEventListener('input', function(e) {
            document.getElementById('guaranteeCaseColourText').value = e.target.value;
        });

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
