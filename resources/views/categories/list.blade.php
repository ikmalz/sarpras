<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Categories') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your category collection</p>
            </div>
            <button onclick="toggleCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </button>
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col lg:flex-row gap-4 justify-between">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" name="search" id="search" placeholder="Search Categories..."
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

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="60">
                                No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="180">
                                Created
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider" width="120">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if($categories->isNotEmpty())
                        @foreach ($categories as $index => $category)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium text-gray-800">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($category->created_at)->format('d M, Y') }}
                            </td>
                            <td class="px-12 py-4 whitespace-nowrap text-center absolute">
                                <button class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-full transition-colors"
                                    onclick="toggleDropdown('{{ $category->id }}')">
                                    ...
                                </button>

                                <div id="dropdown-{{ $category->id }}"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10">
                                    <div class="py-1">
                                        @can('edit category')
                                        <a href="javascript:void(0);"
                                            onclick="openEditModal('{{ $category->id }}', '{{ $category->slug }}', '{{ $category->name }}')"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        @endcan

                                        <a href="{{ route('items.byCategory', $category->slug) }}"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Items
                                        </a>

                                        @can('delete category')
                                        <div class="border-t border-gray-100"></div>
                                        <a href="javascript:void(0);"
                                            onclick="deleteCategory('{{ $category->id }}')"
                                            class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">No categories available</p>
                                    <p class="text-gray-400 text-xs mt-1">Create your first category to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if($categories->isNotEmpty())
            <div class="bg-white px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        @php
                        $start = $categories->firstItem();
                        $end = $categories->lastItem();
                        $total = $categories->total();
                        @endphp
                        Showing <span class="font-medium">{{ $start }}</span> to <span class="font-medium">{{ $end }}</span> of <span class="font-medium">{{ $total }}</span> entries
                    </div>
                    <div class="flex items-center">
                        {{ $categories->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>

    <div id="createCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Create Category</h2>
                <button onclick="toggleCreateModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <form id="createCategoryForm" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text"
                            name="slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500 transition-colors"
                            placeholder="category-slug"
                            required>
                        <p class="text-red-600 text-sm mt-1 hidden" id="slugError"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text"
                            name="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500 transition-colors"
                            placeholder="Category Name"
                            required>
                        <p class="text-red-600 text-sm mt-1 hidden" id="nameError"></p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button"
                        onclick="toggleCreateModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button"
                        onclick="submitCreateForm()"
                        class="px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Edit Category</h2>
                <button onclick="toggleEditModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <form id="editCategoryForm" class="p-6">
                @csrf
                <input type="hidden" name="id" id="edit-id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text"
                            name="slug"
                            id="edit-slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500 transition-colors"
                            required>
                        <p class="text-red-600 text-sm mt-1 hidden" id="editSlugError"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text"
                            name="name"
                            id="edit-name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500 transition-colors"
                            required>
                        <p class="text-red-600 text-sm mt-1 hidden" id="editNameError"></p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button"
                        onclick="toggleEditModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button"
                        onclick="submitEditForm()"
                        class="px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Update Category
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