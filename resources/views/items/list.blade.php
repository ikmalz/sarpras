<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Items') }}
                </h2>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Items
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            @php
            $options = [];
            for ($i = 5; $i < $totalItem; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalItem, $options)) {
                $options[]=$totalItem;
                }
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col lg:flex-row gap-4 justify-between">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" name="search" id="search" placeholder="Search Items..."
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
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="60">No</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" width="120">Created</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider" width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if($items->isNotEmpty())
                        @foreach ($items as $index => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="px-6 py-5 text-sm text-gray-500">
                                {{ $items->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $item->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                        class="w-12 h-12 object-cover rounded-xl border border-gray-200">
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm text-gray-600">{{ $item->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</span>
                            </td>
                            <td class="px-10 py-8 text-center absolute">
                                <button class="text-gray-600 hover:text-black focus:outline-none" onclick="toggleDropdown('{{ $item->id }}')">
                                    <i class="bx bx-dots-horizontal-rounded text-gray-600"></i>
                                </button>
                                <div id="dropdown-{{ $item->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10">
                                    <ul class="py-1 text-sm text-gray-700">
                                        @can('edit items')
                                        <button onclick='openEditModal(@json($item))' class="flex items-center w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        @endcan

                                        @can('delete items')
                                        <button onclick="deleteItem('{{ $item->id }}')" class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                        @endcan
                                    </ul>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first item.</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($category))
            <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-100">
                <p class="text-sm text-gray-600">Showing items for category: <span class="font-medium text-gray-900">{{ $category->name }}</span></p>
            </div>
            @endif

            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    @php
                    $start = $items->firstItem();
                    $end = $items->lastItem();
                    $total = $items->total();
                    @endphp
                    Showing {{ $start }} to {{ $end }} of {{ $total }} entries
                </div>
                <div>
                    {{ $items->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
    </div>

    <div id="createModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative">
            <button onclick="closeCreateModal()" class="absolute top-2 right-3 text-gray-600 hover:text-black">✕</button>
            <h2 class="text-xl font-semibold mb-4">Create Item</h2>

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200" placeholder="Enter item name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input type="number" name="stock" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200" placeholder="Enter stock quantity">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" name="image" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200">
                            <option value="">Select Category</option>
                            @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                            @else
                            <option value="">No categories available</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="closeCreateModal()" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-xl transition-all duration-200">
                        Create Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative">
            <button onclick="closeEditModal()" class="absolute top-2 right-3 text-gray-600 hover:text-black">✕</button>
            <h2 class="text-xl font-semibold mb-4">Create Item</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                        <input type="text" name="name" id="edit-name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200" placeholder="Enter item name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input type="number" name="stock" id="edit-stock" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200" placeholder="Enter stock quantity">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" name="image" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gray-400 focus:ring-0 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" id="edit-category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-xl transition-all duration-200">
                        Update Item
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

            function openEditModal(item) {
                document.getElementById('edit-id').value = item.id;
                document.getElementById('edit-name').value = item.name;
                document.getElementById('edit-stock').value = item.stock;
                document.getElementById('edit-category').value = item.category_id;

                document.getElementById('editForm').action = `/items/${item.id}`;
                document.getElementById('editModal').classList.remove('hidden');
            }


            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
            }

            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
            }

            function deleteItem(id) {
                if (confirm('Apakah kamu yakin ingin menghapus item ini?')) {
                    fetch(`/items/destroy`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                location.reload();
                            } else {
                                alert('Gagal menghapus item.');
                            }
                        });
                }
            }

            function changeRowsPerPage() {
                let rowsPerPage = document.getElementById('rows_per_page').value;
                let url = new URL(window.location.href);
                url.searchParams.set('rows', rowsPerPage);
                window.location.href = url.toString();
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
        </script>
    </x-slot>
</x-app-layout>