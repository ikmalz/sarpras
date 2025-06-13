<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 -mx-4 -mt-4 px-8 pt-8 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-light text-gray-800 mb-1">
                        Stock Report
                    </h2>
                    <p class="text-sm text-gray-500">Monitor inventory availability and status</p>
                </div>
                <!-- <a href="{{ route('report.stock.excel') }}"
                    class="inline-flex items-center px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md ml-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Excel
                </a> -->
                <a href="{{ route('report.stock.pdf') }}"
                    class="inline-flex items-center px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
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

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Barang</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">category</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrowed By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @if($items->isNotEmpty())
                            @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-5 text-sm text-gray-500">
                                    {{ $items->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->category)
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium text-gray-800">
                                        {{ $item->category->name }}
                                    </div>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold">
                                        {{ $item->stock }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($item->stock == 0)
                                    <span class="text-red-600 font-semibold">Dipinjam / Kosong</span>
                                    @else
                                    <span class="text-green-600 font-semibold">Tersedia</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->borrows->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 text-sm text-gray-900">
                                        @foreach($item->borrows->take(2) as $borrow)
                                        <span class="px-2 py-1 rounded">{{ $borrow->user->name }}</span>
                                        @endforeach

                                        @if($item->borrows->count() > 2)
                                        <button onclick="showPopup('{{ $item->id }}')" class="text-gray-600 text-sm">
                                            +{{ $item->borrows->count() - 2 }} lainnya
                                        </button>
                                        @endif
                                    </div>

                                    <div id="popup-{{ $item->id }}" class="fixed inset-0 z-50 bg-black/30 hidden justify-center items-center">
                                        <div class="bg-white rounded-2xl shadow-xl w-[22rem] max-w-full p-6 space-y-4">
                                            <div class="flex justify-between items-center border-b pb-3">
                                                <h2 class="text-gray-800 font-semibold text-base">Daftar Peminjam</h2>
                                                <button onclick="closePopup('{{ $item->id }}')" class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <ul class="space-y-2 max-h-60 overflow-y-auto text-sm text-gray-700">
                                                @foreach($item->borrows as $borrow)
                                                <li class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                                    <span>{{ $borrow->user->name }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="text-end pt-2 border-t">
                                                <button onclick="closePopup('{{ $item->id }}')" class="text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center px-6 py-12">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">Tidak ada data barang</h3>
                                        <p class="text-sm text-gray-500">Belum ada barang yang terdaftar dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                            @else
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <th class="px-10 py-3 text-left text-gray-700 font-bold" colspan="3">Total</th>
                                <th class="px-6 py-3 text-center text-gray-700 font-bold ">{{ $totalStock }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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

    <script>
        function showPopup(id) {
            document.getElementById(`popup-${id}`).classList.remove('hidden');
            document.getElementById(`popup-${id}`).classList.add('flex');
        }

        function closePopup(id) {
            document.getElementById(`popup-${id}`).classList.add('hidden');
            document.getElementById(`popup-${id}`).classList.remove('flex');
        }

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

        function changeRowsPerPage() {
            let rowsPerPage = document.getElementById('rows_per_page').value;
            let url = new URL(window.location.href);
            url.searchParams.set('rows', rowsPerPage);
            window.location.href = url.toString();
        }
    </script>

</x-app-layout>