<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 -mx-4 -mt-4 px-8 pt-8 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-light text-gray-800 mb-1">
                        Return Report
                    </h2>
                    <p class="text-sm text-gray-500">Manage and monitor return data</p>
                </div>
                <a href="{{ route('laporan.pengembalian.export') }}"
                    class="inline-flex items-center px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">

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
                                <input type="text" name="search" id="search" placeholder="Search users..."
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
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrower</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Information</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Return Date</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Proof</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($returns as $return)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $return->borrowing->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $return->borrowing->item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ">
                                            {{ $return->returned_quantity }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            @if($return->description)
                                            <div class=" px-3 py-2">
                                                {{ $return->description }}
                                            </div>
                                            @else
                                            <span class="text-gray-400 italic">Tidak ada keterangan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($return->is_confirmed)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ">
                                            Disetujui
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ">
                                            Menunggu
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $return->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $return->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($return->image)
                                        <div class="relative group">
                                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank" class="block">
                                                <img src="{{ asset('storage/' . $return->image) }}"
                                                    alt="Bukti Pengembalian"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-gray-200 hover:border-gray-400 transition-all duration-200 cursor-pointer mx-auto">
                                            </a>
                                        </div>
                                        @else
                                        <div class="flex flex-col items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Tidak ada data pengembalian</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            @php
                            $start = $returns->firstItem();
                            $end = $returns->lastItem();
                            $total = $returns->total();
                            @endphp
                            Menampilkan {{ $start }} sampai {{ $end }} dari total {{ $total }} data
                        </div>
                        <div>
                            {{ $returns->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
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

                function changeRowsPerPage() {
                    let rowsPerPage = document.getElementById('rows_per_page').value;
                    let url = new URL(window.location.href);
                    url.searchParams.set('rows', rowsPerPage);
                    window.location.href = url.toString();
                }
            </script>
        </x-slot>
</x-app-layout>