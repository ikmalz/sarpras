<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Users') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your users and their roles</p>
            </div>
            @can('create users')
            <button onclick="openCreateModal()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 shadow-sm">
                <i class="bx bx-plus text-lg mr-1"></i>
                Add User
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            @php
            $options = [];
            for ($i = 5; $i < $totalUsers; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalUsers, $options)) {
                $options[]=$totalUsers;
                }
                @endphp

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between">
                        <!-- Search -->
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="search" id="search" placeholder="Search users..."
                                    class="w-full pl-10 pr-4 py-3 text-sm rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200"
                                    oninput="searchPermissions()" value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="flex gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by</label>
                                <select id="sort_by" class="form-select rounded-lg border-gray-300 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" onchange="searchPermissions()">
                                    <option value="">Default</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="rows_per_page" class="text-sm font-medium text-gray-700">Show</label>
                                <select id="rows_per_page" class="form-select rounded-lg border-gray-300 text-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" onchange="changeRowsPerPage()">
                                    @foreach ($options as $option)
                                    <option value="{{ $option }}" {{ request('rows') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900" width="80">Id</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Role</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Created</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900" width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if($users->isNotEmpty())
                                @foreach ($users as $index => $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td class="px-2 py-4">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium text-gray-800 mr-1">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                        @endforeach
                                        @else
                                        <span class="text-sm text-gray-400">No role assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center absolute">
                                        <div class="relative inline-block text-left">
                                            <button class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-150" onclick="toggleDropdown('user-{{ $user->id }}')">
                                                <i class="bx bx-dots-horizontal-rounded text-gray-600"></i>
                                            </button>

                                            <div id="dropdown-user-{{ $user->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-200">
                                                <div class="py-2">
                                                    @can('edit users')
                                                    <button
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150"
                                                        onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->roles->first()->id ?? '' }}')">
                                                        <i class="bx bx-edit text-gray-500 mr-2"></i>
                                                        Edit User
                                                    </button>
                                                    @endcan
                                                    @can('delete users')
                                                    <button onclick="deleteUser('{{ $user->id }}')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                                        <i class="bx bx-trash text-red-500 mr-2"></i>
                                                        Delete User
                                                    </button>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bx bx-user text-gray-300 text-5xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first user.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($users->isNotEmpty())
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            @php
                            $start = $users->firstItem();
                            $end = $users->lastItem();
                            $total = $users->total();
                            @endphp
                            Showing {{ $start }} to {{ $end }} of {{ $total }} entries
                        </div>
                        <div>
                            {{ $users->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    @endif
                </div>
        </div>
    </div>

    <!-- Modal Create User -->
    <div id="createUserModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md shadow-xl">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Create New User</h2>
                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-150" onclick="closeCreateModal()">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" required>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md shadow-xl">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Edit User</h2>
                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-150" onclick="closeModal('editModal')">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>
            </div>

            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="editName" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="editEmail" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" id="editRole" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function searchPermissions() {
                const search = document.getElementById('search').value;
                const sort = document.getElementById('sort_by').value;
                const rows = document.getElementById('rows_per_page').value;

                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('sort', sort);
                url.searchParams.set('rows', rows);
                window.location.href = url.toString();
            }

            function openCreateModal() {
                document.getElementById('createUserModal').classList.remove('hidden');
            }

            function closeCreateModal() {
                document.getElementById('createUserModal').classList.add('hidden');
            }

            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
            }

            function openEditModal(id, name, email, roleId) {
                openModal('editModal');
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editRole').value = roleId;

                const form = document.getElementById('editForm');
                form.action = `/users/${id}`;
            }



            // function deleteRole(id) {
            //     if (confirm("Are you sure you want to delete?")) {
            //         $.ajax({
            //             url: '{{ route("roles.destroy") }}',
            //             type: 'delete',
            //             data: {
            //                 id: id
            //             },
            //             dataType: 'json',
            //             headers: {
            //                 'x-csrf-token': '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 window.location.href = '{{ route("roles.index") }}';
            //             }
            //         })
            //     }
            // }

            function deleteUser(id) {
                if (confirm("Are you sure you want to delete this user?")) {
                    fetch(`/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(err => Promise.reject(err));
                            }
                            return res.json();
                        })
                        .then(data => {
                            alert(data.message);
                            location.reload();
                        })
                        .catch(err => {
                            alert(err.message || 'Failed to delete user.');
                        });
                }
            }


            function toggleDropdown(id) {
                document.querySelectorAll('[id^="dropdown-user-"]').forEach(el => {
                    if (el.id === `dropdown-${id}`) {
                        el.classList.toggle('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('[onclick^="toggleDropdown"]') && !e.target.closest('[id^="dropdown-user-"]')) {
                    document.querySelectorAll('[id^="dropdown-user-"]').forEach(el => el.classList.add('hidden'));
                }
            })

            function changeRowsPerPage() {
                let rowsPerPage = document.getElementById('rows_per_page').value;
                let url = new URL(window.location.href);
                url.searchParams.set('rows', rowsPerPage);
                window.location.href = url.toString();
            }
        </script>
    </x-slot>
</x-app-layout>