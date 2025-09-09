@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">User Management</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Manage system users and their roles</p>
                    </div>
                    <button class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-700" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add User
                    </button>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session("success"))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-900/20">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle mr-3 text-emerald-600 dark:text-emerald-400"></i>
                        <span class="text-emerald-800 dark:text-emerald-200">{{ session("success") }}</span>
                    </div>
                </div>
            @endif

            @if (session("error"))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-circle mr-3 text-red-600 dark:text-red-400"></i>
                        <span class="text-red-800 dark:text-red-200">{{ session("error") }}</span>
                    </div>
                </div>
            @endif

            <!-- Users Table -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">User ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Division</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->userid }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->position }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->department }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->division }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="{{ $user->role === "admin" ? "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200" : "bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200" }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                            <i class="fa-solid {{ $user->role === "admin" ? "fa-crown" : "fa-user" }} mr-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $user->created_at->format("M d, Y") }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" onclick="openRoleModal('{{ $user->id }}', '{{ $user->userid }}', '{{ $user->role }}')">
                                                <i class="fa-solid fa-user-cog mr-1"></i> Edit Role
                                            </button>
                                            @if ($user->id !== auth()->id())
                                                <form class="inline" action="{{ route("users.destroy", $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    <button class="font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" type="submit">
                                                        <i class="fa-solid fa-trash mr-1"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-12 text-center" colspan="4">
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <i class="fa-solid fa-users mb-4 text-4xl"></i>
                                            <p class="text-lg font-medium">No users found</p>
                                            <p class="mt-1">Get started by creating your first user.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="border-t border-slate-200 bg-slate-50 px-6 py-3 dark:border-slate-700 dark:bg-slate-900">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="fixed inset-0 z-50 hidden bg-black bg-opacity-50" id="createModal">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-md rounded-lg bg-white shadow-xl dark:bg-slate-800">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add New User</h3>
                </div>
                <form id="createForm" action="{{ route("users.store") }}" method="POST">
                    @csrf
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300" for="user_id">User ID</label>
                            <input class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="createUserId" name="user_id" type="text" required>
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300" for="role">Role</label>
                            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="createRole" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                        <button class="px-4 py-2 font-medium text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" type="button" onclick="closeCreateModal()">
                            Cancel
                        </button>
                        <button class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-700" type="submit">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Role Edit Modal -->
    <div class="fixed inset-0 z-50 hidden bg-black bg-opacity-50" id="roleModal">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-md rounded-lg bg-white shadow-xl dark:bg-slate-800">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Edit User Role</h3>
                </div>
                <form id="roleForm" method="POST">
                    @csrf
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">User ID</label>
                            <input class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-900 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="modalUserId" type="text" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300" for="role">Role</label>
                            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="modalRole" name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                        <button class="px-4 py-2 font-medium text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" type="button" onclick="closeRoleModal()">
                            Cancel
                        </button>
                        <button class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-700" type="submit">
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createUserId').value = '';
            document.getElementById('createRole').value = 'user';
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openRoleModal(userId, userIdDisplay, currentRole) {
            document.getElementById('modalUserId').value = userIdDisplay;
            document.getElementById('modalRole').value = currentRole;
            document.getElementById('roleForm').action = '{{ route("users.updateRole", "__ID__") }}'.replace('__ID__', userId);
            document.getElementById('roleModal').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });

        document.getElementById('roleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRoleModal();
            }
        });
    </script>
@endsection
