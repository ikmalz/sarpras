<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            @can('create users')
            <a href="{{ route('users.create') }}" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</a>
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
                                <a href="{{ route('users.edit', $user->id) }}" class="block px-4 py-2 hover:bg-gray-100 text-left">Edit</a>
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