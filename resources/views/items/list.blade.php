<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Items') }}
            </h2>
            <button onclick="openCreateModal()" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</button>
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
                    <th class="px-6 py-3 text-left">Stock</th>
                    <th class="px-6 py-3 text-left">Image</th>
                    <th class="px-6 py-3 text-left">Category Name</th>
                    <th class="px-6 py-3 text-left" width="180">Created</th>
                    <th class="px-6 py-3 text-center" width="180">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($items->isNotEmpty())
                @foreach ($items as $index => $item)
                <tr class="border-b">
                    <td class="px-6 py-3 text-left">
                        {{ $items->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $item->stock }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ $item->category->name ?? '-' }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-3 text-center relative">
                        <button class="text-gray-600 hover:text-black focus:outline-none" onclick="toggleDropdown('{{ $item->id }}')">
                            ⋮
                        </button>
                        <div id="dropdown-{{ $item->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10">
                            <ul class="py-1 text-sm text-gray-700">
                                @can('edit items')
                                <li>
                                    <a href="javascript:void(0);" onclick='openEditModal(@json($item))' class="block px-4 py-2 hover:bg-gray-100 text-black text-left">Edit</a>
                                </li>
                                @endcan

                                @can('delete items')
                                <li>
                                    <a href="javascript:void(0);" onclick="deleteItem('{{ $item->id }}')" class="block px-4 py-2 hover:bg-gray-100 text-red-600 text-left">Delete</a>
                                </li>
                                @endcan
                            </ul>
                        </div>

                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">Item Belum Tersedia</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if(isset($category))
        <p class="text-sm text-gray-500 mt-1">Showing items for category: <strong>{{ $category->name }}</strong></p>
        @endif
        <div class="w-full flex justify-between items-center text-sm text-gray-600">
            <div>
                @php
                $start = $items->firstItem();
                $end = $items->lastItem();
                $total = $items->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between">
                {{ $items->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>

    <div id="createModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative">
            <button onclick="closeCreateModal()" class="absolute top-2 right-3 text-gray-600 hover:text-black">✕</button>
            <h2 class="text-xl font-semibold mb-4">Create Item</h2>

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Item name">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" name="stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Stock">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select Category --</option>
                        @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                        @else
                        <option value="">No categories found</option>
                        @endif
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-slate-300 text-white px-4 py-2 rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative">
            <button onclick="closeEditModal()" class="absolute top-2 right-3 text-gray-600 hover:text-black">✕</button>
            <h2 class="text-xl font-semibold mb-4">Create Item</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="edit-name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Item name">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" name="stock" id="edit-stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Stock">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="edit-category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-slate-300 text-white px-4 py-2 rounded-md">Submit</button>
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