<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Roles Management') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage system roles and permissions</p>
            </div>
            @can('create roles')
            <button onclick="openCreateRoleModal()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Role
            </button>
            @endcan
        </div>
    </x-slot>


    <div class="py-8">
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

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between">
                        <!-- Search -->
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="search" id="search" placeholder="Search roles..."
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

                <!-- Roles Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900" width="80">ID</th>
                                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-900">Role Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Permissions</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Created</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900" width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if($roles->isNotEmpty())
                                @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                            {{ $role->id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900 capitalize">{{ $role->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-justify">
                                        {{ $role->permissions->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($role->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block text-left">
                                            <button class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-150" onclick="toggleMenu('role-{{ $role->id }}')">
                                                <i class="bx bx-dots-horizontal-rounded text-gray-600"></i>
                                            </button>

                                            <div id="menu-role-{{ $role->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-200">
                                                <div class="py-2">
                                                    @can('edit roles')
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                                        <i class="bx bx-edit text-gray-500 mr-2"></i>
                                                        Edit Role
                                                    </a>
                                                    @endcan
                                                    @can('delete roles')
                                                    <button onclick="deleteRole('{{ $role->id }}')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                                        <i class="bx bx-trash text-red-500 mr-2"></i>
                                                        Delete Role
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
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data role</h3>
                                            <p class="text-gray-500">Belum ada role yang dibuat dalam sistem</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($roles->isNotEmpty())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                @php
                                $start = $roles->firstItem();
                                $end = $roles->lastItem();
                                $total = $roles->total();
                                @endphp
                                Menampilkan <span class="font-medium text-gray-900">{{ $start }}</span> sampai <span class="font-medium text-gray-900">{{ $end }}</span> dari <span class="font-medium text-gray-900">{{ $total }}</span> entri
                            </div>
                            <div>
                                {{ $roles->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
        </div>
    </div>

    <div id="createRoleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Tambah Role Baru</h2>
                        <p class="text-sm text-gray-600 mt-1">Buat role baru dengan permission yang sesuai</p>
                    </div>
                    <button onclick="closeCreateRoleModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <form id="createRoleForm" class="p-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role</label>
                        <input type="text" name="name" id="roleName"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors duration-200"
                            placeholder="Masukkan nama role">
                        <p class="text-red-500 text-sm mt-1 hidden" id="nameError"></p>
                    </div>

                    <div>
                        <!-- Permissions Grid -->
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
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeCreateRoleModal()"
                        class="px-6 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg transition-colors duration-200 font-medium">
                        Simpan Role
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