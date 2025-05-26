<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permissions') }}
            </h2>
            @can('create permissions')
            <button onclick="openCreateModal()" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
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
                    <th class="px-6 py-3 text-left" width="60">no</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left" width="180">Created</th>
                    <th class="px-6 py-3 text-center" width="180">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($permissions->isNotEmpty())
                @foreach ($permissions as $index => $permission)
                <tr class="border-b">
                    <td class="px-6 py-3 text-left">
                        {{ $permissions->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $permission->name }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-3 text-center relative">
                        <div class="relative inline-block text-left">
                            <button onclick="toggleMenu('{{ $permission->id }}')" class="inline-flex justify-center w-full text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                ⋮
                            </button>

                            <div id="menu-{{ $permission->id }}" class="origin-top-right absolute mt-2 w-28 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @can('edit permissions')
                                    <a href="javascript:void(0);" onclick="openEditModal('{{ $permission->id }}')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menu-item">Edit</a>
                                    @endcan
                                    @can('delete category')
                                    <a href="javascript:void(0);" onclick="deletePermission('{{ $permission->id }}')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menu-item">Delete</a>
                                </div>
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
                $start = $permissions->firstItem();
                $end = $permissions->lastItem();
                $total = $permissions->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>

            <div class="flex justify-between">
                {{ $permissions->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>

    <!-- modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 transition ease-out duration-200">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Edit Permission</h2>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="editName" class="border border-gray-300 rounded w-full px-3 py-2">
                    <p class="text-red-500 text-sm mt-1 hidden" id="editError"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="submitEditForm()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 transition ease-out duration-200">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Create Permission</h2>
                <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form method="POST" id="createForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="createName" class="border border-gray-300 rounded w-full px-3 py-2">
                    <p class="text-red-500 text-sm mt-1 hidden" id="createError"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="submitCreateForm()" class="bg-slate-700 text-white px-4 py-2 rounded hover:bg-slate-800">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-bold mb-4">Delete Confirmation</h2>
            <p class="mb-4 text-sm text-gray-700">Apakah kamu menghapus permission ini?</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button onclick="confirmDelete()" class="px-4 py-2 text-sm  text-white bg-gray-500 rounded hover:bg-gray-600">Delete</button>
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
                const formData = new formData(form);

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
                        document.getElementById('editError').textContent = 'Terjadi kesalahan.';
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
                    .then(res => res.json)
                    .then(data => {
                        if (data.success || data.id) {
                            location.reload();
                        } else {
                            errorEl.textContent = data.message || 'Creation failed.';
                            errorEl.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        errorEl.textContent = 'Terjadi kesalahan.';
                        errorEl.classList.remove('hidden');
                    })
            }
        </script>
    </x-slot>
</x-app-layout>