<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
            @can('create roles')
            <a onclick="openCreateRoleModal()" class=" cursor-pointer bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>


            @php
            $options = [];
            for ($i = 5; $i < $totalRows; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalRows, $options)) {
                $options[]=$totalRows;
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
                    <th class="px-6 py-3 text-left" width="60">#</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Permission</th>
                    <th class="px-6 py-3 text-left" width="180">Created</th>
                    <th class="px-6 py-3 text-center" width="180">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($roles->isNotEmpty())
                @foreach ($roles as $role)
                <tr class="border-b">
                    <td class="px-6 py-3 text-left">
                        {{ $role->id }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $role->name }}
                    </td>
                    <td class="px-6 py-3 text-justify">
                        {{ $role->permissions->pluck('name')->implode(', ') }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ \Carbon\Carbon::parse($role->created_at)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-3 text-center relative">
                        <div class="relative inline-block text-left">
                            <button onclick="toggleMenu('role-{{ $role->id }}')" class="inline-flex justify-center w-full text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                ⋮
                            </button>
                            <div id="menu-role-{{ $role->id }}" class="origin-top-right absolute mt-2 w-28 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @can('edit roles')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit</a>
                                    @endcan

                                    @can('delete roles')
                                    <a href="javascript:void(0);" onclick="deleteRole('{{ $role->id }}')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">Delete</a>
                                    @endcan
                                </div>
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
                $start = $roles->firstItem();
                $end = $roles->lastItem();
                $total = $roles->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between">
                {{ $roles->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    <div id="createRoleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg w-full max-w-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Create Role</h2>
            <form id="createRoleForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="roleName" class="border-gray-300 shadow-sm w-full rounded-lg mt-1">
                    <p class="text-red-500 text-sm mt-1 hidden" id="nameError"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Permissions</label>
                    <input type="text" id="permissionSearch" placeholder="Search Permissions..." class="w-full mb-3 p-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300">

                    <div id="permissionList" class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto pr-1">
                        @foreach ($permissionsAll as $permission)
                        <label for="perm-{{ $permission->id }}" class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkBox" name="permission[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}" class="rounded-md text-gray-700">
                            <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-2">
                    <button type="button" onclick="closeCreateRoleModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-md">Submit</button>
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

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('permissionSearch');
                const permissionList = document.getElementById('permissionList');
                const permissionItems = document.querySelectorAll('label');

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    permissionItems.forEach(function(item) {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden')
                        }
                    })
                })
            })

            function openCreateRoleModal() {
                document.getElementById('createRoleModal').classList.remove('hidden');
            }

            function closeCreateRoleModal() {
                document.getElementById('createRoleModal').classList.add('hidden');
                document.getElementById('createRoleForm').reset();
                document.getElementById('nameError').classList.add('hidden');
            }

            document.getElementById('createRoleForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const data = new FormData(form);

                fetch('{{  route("roles.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: data
                }).then(response => response.json()).then(result => {
                    if (result.status === 'success') {
                        window.location.href = '{{ route("roles.index") }}';
                    } else {
                        if (result.errors && result.errors.name) {
                            document.getElementById('nameError').textContent = result.errors.name[0];
                            document.getElementById('nameError').classList.remove('hidden');
                        }
                    }
                }).catch(err => console.error(err));
            })

            function deleteRole(id) {
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: '{{ route("roles.destroy") }}',
                        type: 'delete',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("roles.index") }}';
                        }
                    })
                }
            }

            function toggleMenu(id) {
                document.querySelectorAll("[id^='menu-role-']").forEach(el => {
                    if (el.id !== 'menu-' + id) el.classList.add('hidden');
                });

                const menu = document.getElementById("menu-" + id);
                if (menu) {
                    menu.classList.toggle("hidden");
                }
            }

            window.addEventListener('click', function(event) {
                const isClickInside = event.target.closest('[id^="menu-role-"]') || event.target.closest('button[onclick^="toggleMenu"]');
                if (!isClickInside) {
                    document.querySelectorAll("[id^='menu-role']").forEach(el.classList.add('hidden'));
                }
            });


            function changeRowsPerPage() {
                let rowsPerPage = document.getElementById('rows_per_page').value;
                let url = new URL(window.location.href);
                url.searchParams.set('rows', rowsPerPage);
                window.location.href = url.toString();
            }
        </script>
    </x-slot>
</x-app-layout>