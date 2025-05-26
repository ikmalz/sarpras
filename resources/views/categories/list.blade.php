<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>
            <button onclick="toggleCreateModal()" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            @php
            $options = [];
            for ($i = 5; $i < $totalCategories; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalCategories, $options)) {
                $options[]=$totalCategories;
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
                    <th class="px-6 py-3 text-left">Slug</th>
                    <th class="px-6 py-3 text-left" width="180">Created</th>
                    <th class="px-6 py-3 text-center" width="280">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($categories->isNotEmpty())
                @foreach ($categories as $index => $category)
                <tr class="border-b">
                    <td class="px-6 py-3 text-left">
                        {{ $categories->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $category->name }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $category->slug }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ \Carbon\Carbon::parse($category->created_at)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-3 text-center relative">
                        <button class="text-gray-600 hover:text-black focus:outline-none" onclick="toggleDropdown('{{ $category->id }}')">
                            ⋮
                        </button>

                        <div id="dropdown-{{ $category->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10">
                            <ul class="py-1 text-sm text-gray-700">
                                @can('edit category')
                                <li>
                                    <a href="javascript:void(0);" onclick="openEditModal('{{ $category->id }}', '{{ $category->slug }}', '{{ $category->name }}')" class="block px-4 py-2 hover:bg-gray-100 text-left">Edit</a>
                                </li>
                                @endcan

                                @can('delete category')
                                <li>
                                    <a href="javascript:void(0);" onclick="deleteCategory('{{ $category->id }}')" class="block px-4 py-2 hover:bg-gray-100 text-red-600 text-left">Delete</a>
                                </li>
                                @endcan

                                <li>
                                    <a href="{{ route('items.byCategory', $category->id) }}" class="block px-4 py-2 hover:bg-gray-100 text-emerald-700 text-left">View Items</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">Category Belum Tersedia</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="w-full flex justify-between items-center text-sm text-gray-600">
            <div>
                @php
                $start = $categories->firstItem();
                $end = $categories->lastItem();
                $total = $categories->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between">
                {{ $categories->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>

    <div id="createCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-[500px]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Create Category</h2>
                <button onclick="toggleCreateModal()" class="text-gray-500 hover:text-black text-xl">&times;</button>
            </div>

            <form id="createCategoryForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" name="slug" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required>
                    <p class="text-red-600 text-sm mt-1" id="slugError"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required>
                    <p class="text-red-600 text-sm mt-1" id="nameError"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="submitCreateForm()" class="bg-slate-700 px-4 py-2 text-white rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div id="editCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-[500px]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">EditCategory</h2>
                <button onclick="toggleEditModal()" class="text-gray-500 hover:text-black text-xl">&times;</button>
            </div>

            <form id="editCategoryForm">
                @csrf
                <input type="hidden" name="id" id="edit-id">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" name="slug" id="edit-slug" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required>
                    <p class="text-red-600 text-sm mt-1" id="editSlugError"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" id="edit-name" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required>
                    <p class="text-red-600 text-sm mt-1" id="editNameError"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="submitEditForm()" class="bg-slate-700 px-4 py-2 text-white rounded-md">Update</button>
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

            function toggleEditModal() {
                const modal = document.getElementById('editCategoryModal');
                modal.classList.toggle('hidden');
            }

            function openEditModal(id, slug, name) {
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-slug').value = slug;
                document.getElementById('edit-name').value = name;
                document.getElementById('editSlugError').innerText = '';
                document.getElementById('editNameError').innerText = '';
                toggleEditModal();
            }

            function submitEditForm() {
                const formData = {
                    id: document.getElementById('edit-id').value,
                    slug: document.getElementById('edit-slug').value,
                    name: document.getElementById('edit-name').value,
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT', // spoofing method PUT
                };

                $.ajax({
                    url: '/categories/' + formData.id,
                    type: 'POST', // Ganti PUT ke POST
                    data: formData,
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            document.getElementById('editSlugError').innerText = errors.slug ? errors.slug[0] : '';
                            document.getElementById('editNameError').innerText = errors.name ? errors.name[0] : '';
                        }
                    }
                });
            }


            function toggleCreateModal() {
                const modal = document.getElementById('createCategoryModal');
                modal.classList.toggle('hidden');
            }

            function submitCreateForm() {
                const form = document.getElementById('createCategoryForm')
                const formData = new FormData(form);

                document.getElementById('slugError').innerText = '';
                document.getElementById('nameError').innerText = '';

                $.ajax({
                    url: '{{ route("categories.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const error = xhr.responseJSON.errors;
                            if (errors.slug) {
                                document.getElementById('slugError').innerText = errors.slug[0];
                            }
                            if (errors.name) {
                                document.getElementById('nameError').innerText = errors.name[0];
                            }
                        }
                    }
                });
            }

            function deleteCategory(id) {
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: '{{ route("categories.destroy") }}',
                        type: 'delete',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("categories.index") }}';
                        }
                    })
                }
            }

            function toggleDropdown(id) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                    if (el.id === `dropdown-${id}`) {
                        el.classList.toggle('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('[onclick^="toggleDropdown"]') && !e.target.closest('[id^="dropdown-"]')) {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
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