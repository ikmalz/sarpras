<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            @can('create users')
            <button onclick="openCreateModal()" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
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

                <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
                <div class="flex items-center gap-2">
                    <label for="rows_per_page" class="text-sm">Show</label>
                    <select id="rows_per_page" class="form-select rounded-md border-gray-300 text-sm" onchange="changeRowsPerPage()">
                        @foreach ($options as $option)
                        <option value="{{ $option }}" {{ request('rows') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm">entries</span>
                </div>

                <div class="flex items-center gap-3 w-full max-w-md">
                    <div class="relative w-full">
                        <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="search" id="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 transition" oninput="searchPermissions()" value="{{ request('search') }}">
                    </div>
                    <h1 class="w-28">sort by</h1>
                    <select id="sort_by" class="form-select rounded-md border-gray-300 text-sm" onchange="searchPermissions()">
                        <option value="">All</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Created Oldest</option>
                        <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Created Newest</option>
                    </select>
                </div>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="border-b">
                    <th class="px-6 py-3 text-left" width="60">No</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Roles</th>
                    <th class="px-6 py-3 text-left" width="180">Created</th>
                    <th class="px-6 py-3 text-center" width="180">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($users->isNotEmpty())
                @foreach ($users as $index => $user)
                <tr class="border-b">
                    <td class="px-6 py-3 text-left">
                        {{ $users->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $user->roles->pluck('name')->implode(', ') }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-3 text-center relative">
                        <button class="text-gray-600 hover:text-black focus:outline-none" onclick="toggleDropdown('user-{{ $user->id }}')">
                            ⋮
                        </button>

                        <div id="dropdown-user-{{ $user->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10">
                            <div class="py-1 text-sm text-gray-700">
                                @can('edit users')
                                <button
                                    class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600"
                                    onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->roles->first()->id ?? '' }}')">
                                    Edit
                                </button>

                                @endcan
                                @can('delete users')
                                <button onclick="deleteUser('{{ $user->id }}')" class="block px-4 py-2 hover:bg-gray-100 text-left w-full text-red-600">Delete</button>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
        <div class="w-full flex justify-between items-center text-sm text-gray-600">
            <div>
                @php
                $start = $users->firstItem();
                $end = $users->lastItem();
                $total = $users->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between">
                {{ $users->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    <!-- Modal Create User -->
    <div id="createUserModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold" onclick="closeCreateModal()">×</button>

            <h2 class="text-lg font-semibold mb-4">Create New User</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium">Role</label>
                    <select name="role" class="w-full border rounded px-3 py-2">
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Simpan</button>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-600 hover:underline">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold" onclick="closeModal('editModal')">×</button>

            <h2 class="text-xl font-semibold mb-4">Edit User</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Name</label>
                    <input type="text" name="name" id="editName" required class="w-full border rounded px-3 py-2" />
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" id="editEmail" required class="w-full border rounded px-3 py-2" />
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Role</label>
                    <select name="role" id="editRole" class="w-full border rounded px-3 py-2">
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="closeModal('editModal')" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
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