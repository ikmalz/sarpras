<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 -mx-4 -mt-4 px-8 pt-8 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-light text-gray-800 mb-1">
                        Laporan Peminjaman
                    </h2>
                    <p class="text-sm text-gray-500">Kelola dan pantau data peminjaman barang</p>
                </div>
                <a href="{{ route('laporan.peminjaman.export') }}"
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
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrow Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved By</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($borrowings as $index => $borrow)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $index + $borrowings->firstItem() }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-2 ">{{ $borrow->user->name }}</td>
                                    <td class="px-6 py-2 ">{{ $borrow->item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $borrow->quantity }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                        $statusConfig = [
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                        'returned' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Dikembalikan'],
                                        'completed' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Selesai'],
                                        'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Menunggu']
                                        ];
                                        $config = $statusConfig[$borrow->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $borrow->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $borrow->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($borrow->due)
                                        <div class="text-sm text-gray-900">{{ $borrow->due->format('d M Y') }}</div>
                                        @else
                                        <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($borrow->approver)
                                        <div class="flex items-center">
                                            <div class="ml-2">
                                                <div class="text-sm text-gray-900">{{ $borrow->approver->name }}</div>
                                            </div>
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-400">-</span>
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
                            $start = $borrowings->firstItem();
                            $end = $borrowings->lastItem();
                            $total = $borrowings->total();
                            @endphp
                            Menampilkan {{ $start }} sampai {{ $end }} dari total {{ $total }} data
                        </div>
                        <div>
                            {{ $borrowings->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
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