<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('Permissions') }}
                </h1>
                <p class="text-gray-500 text-sm font-medium mt-1">
                    Manage system access permissions
                </p>
            </div>
            @can('create permissions')
            <button onclick="openCreateModal()"
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create Permission
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            @php
            $options = [];
            for ($i = 5; $i < $totalPermissions; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalPermissions, $options)) {
                $options[]=$totalPermissions;
                }
                @endphp
                <!-- Search and Filter Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between">
                        <!-- Search -->
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="search" id="search" placeholder="Search Permission..."
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
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="60">no</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="180">Created</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="180">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if($permissions->isNotEmpty())
                                @foreach ($permissions as $index => $permission)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $permissions->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $permission->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($permission->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-10 py-2 relative">
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleMenu('{{ $permission->id }}')" class="inline-flex justify-center w-full text-sm font-medium text-gray-700 hover:text-gray-900 rounded-md px-2 py-2 focus:ring-gray-200 focus:outline-none focus:ring-2">
                                                ...
                                            </button>

                                            <div id="menu-{{ $permission->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-200">
                                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                    @can('edit permissions')
                                                    <a href="javascript:void(0);" onclick="openEditModal('{{ $permission->id }}')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150"
                                                        role="menu-item">
                                                        <i class="bx bx-edit text-gray-500 mr-2"></i>
                                                        Edit
                                                    </a>
                                                    @endcan
                                                    @can('delete category')
                                                    <a href="javascript:void(0);" onclick="deletePermission('{{ $permission->id }}')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
                                                        role="menu-item">
                                                        <i class="bx bx-trash text-red-500 mr-2"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-gray-300 mb-4">
                                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No permissions found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first permission.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($permissions->isNotEmpty())
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            @php
                            $start = $permissions->firstItem();
                            $end = $permissions->lastItem();
                            $total = $permissions->total();
                            @endphp
                            Showing {{ $start }} to {{ $end }} of {{ $total }} entries
                        </div>
                        <div>
                            {{ $permissions->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    @endif
                </div>
        </div>

        <!-- modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Edit Permission</h2>
                        <p class="text-sm text-gray-500 mt-1">Update permission details</p>
                    </div>
                    <button onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" id="editForm" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                        <input type="text"
                            name="name"
                            id="editName"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-colors">
                        <p class="text-red-500 text-sm mt-2 hidden" id="editError"></p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            onclick="submitEditForm()"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition-colors">
                            Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Create Permission</h2>
                        <p class="text-sm text-gray-500 mt-1">Add a new permission to the system</p>
                    </div>
                    <button onclick="closeCreateModal()"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" id="createForm" class="p-6">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                        <input type="text"
                            name="name"
                            id="createName"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-colors"
                            placeholder="Enter permission name">
                        <p class="text-red-500 text-sm mt-2 hidden" id="createError"></p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button"
                            onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            onclick="submitCreateForm()"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition-colors">
                            Create Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Delete Permission</h3>
                            <p class="text-sm text-gray-500 mt-1">This action cannot be undone</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">Are you sure you want to delete this permission? This will permanently remove it from the system.</p>
                    <div class="flex justify-end gap-3">
                        <button onclick="closeDeleteModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button onclick="confirmDelete()"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                            Delete Permission
                        </button>
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

                // for delete
                let deleteId = null;

                function deletePermission(id) {
                    deleteId = id;
                    const modal = document.getElementById('deleteModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeDeleteModal() {
                    deleteId = null;
                    const modal = document.getElementById('deleteModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function confirmDelete() {
                    if (!deleteId) return;

                    $.ajax({
                        url: '{{ route("permissions.destroy") }}',
                        type: 'delete',
                        data: {
                            id: deleteId
                        },
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("permissions.index") }}';
                        },
                        error: function() {
                            alert("Failed to delete permission.")
                        }
                    });

                    closeDeleteModal();
                }

                function toggleMenu(id) {
                    document.querySelectorAll("[id^='menu-']").forEach(function(menu) {
                        menu.classList.add('hidden');
                    });

                    var menu = document.getElementById("menu-" + id);
                    if (menu) {
                        menu.classList.toggle("hidden");
                    }
                }

                window.addEventListener('click', function(e) {
                    if (!e.target.matches('button') && !e.target.closest('[id^="menu-"]')) {
                        document.querySelectorAll("[id^='menu-']").forEach(function(menu) {
                            menu.classList.add('hidden');
                        })
                    }
                });

                function changeRowsPerPage() {
                    let rowsPerPage = document.getElementById('rows_per_page').value;
                    let url = new URL(window.location.href);
                    url.searchParams.set('rows', rowsPerPage);
                    window.location.href = url.toString();
                }

                function openEditModal(id) {
                    const modal = document.getElementById('editModal')
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    document.getElementById('editError').classList.add('hidden');
                    document.getElementById('editError').textContent = '';

                    fetch(`/permissions/${id}/edit`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('editName').value = data.name;
                            document.getElementById('editForm').action = `/permissions/${id}`;
                        })
                        .catch(err => {
                            document.getElementById('editError').textContent = 'Gagal memuat data.'
                            document.getElementById('editError').classList.remove('hidden');
                        })
                }

                function closeEditModal() {
                    const modal = document.getElementById('editModal')
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function submitEditForm() {
                    const form = document.getElementById('editForm');
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                document.getElementById('editError').textContent = data.message || 'Update failed.';
                                document.getElementById('editError').classList.remove('hidden');
                            }
                        })
                        .catch(err => {
                            document.getElementById('editError').classList.remove('hidden');
                        });
                }

                function openCreateModal() {
                    const modal = document.getElementById('createModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    document.getElementById('createName').value = '';
                    document.getElementById('createError').classList.add('hidden');
                    document.getElementById('createError').textContent = '';
                }

                function closeCreateModal() {
                    const modal = document.getElementById('createModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function submitCreateForm() {
                    const name = document.getElementById('createName').value;
                    const errorEl = document.getElementById('createError');

                    fetch(`{{ route('permissions.store') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.id) {
                                location.reload();
                            } else {
                                errorEl.textContent = data.message || 'Creation failed.';
                                errorEl.classList.remove('hidden');
                            }
                        })
                        .catch(() => {
                            errorEl.classList.remove('hidden');
                        })
                }
            </script>
        </x-slot>
</x-app-layout>