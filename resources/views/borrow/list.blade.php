<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Peminjaman') }}
            </h2>
            <!-- @can('create users')
            <a href="{{ route('roles.create') }}" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Create</a>
            @endcan -->
        </div>
    </x-slot>

    <div id="loadingSpinner" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="w-16 h-16 border-4 border-blue-400 border-dashed rounded-full animate-spin"></div>
    </div>



    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            @php
            $options = [];
            for ($i = 5; $i < $totalBorrowing; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalBorrowing, $options)) {
                $options[]=$totalBorrowing;
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
            <thead class="bg-gray-200">
                <tr class="border-b">
                    <th class="px-6 py-3 text-center">NO</th>
                    <th class="px-6 py-3 text-center">Nama Peminjaman</th>
                    <th class="px-4 py-2 text-center">Barang</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Jatuh Tempo</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($borrowings as $index => $borrow)
                @php
                $colorClassMap = [
                'pending' => 'text-yellow-600',
                'approved' => 'text-green-600',
                'rejected' => 'text-red-600',
                'returning' => 'text-blue-600',
                'done' => 'text-gray-600',
                ];
                $statusClass = $colorClassMap[$borrow->status] ?? 'text-gray-600';
                @endphp
                <tr class="border-b">
                    <td class="px-6 py-3 text-center font-thin">{{ $borrowings->firstItem() + $index }}</td>
                    <td class="px-6 py-3 text-center font-thin">{{ $borrow->user->name }}</td>
                    <td class="px-6 py-3 text-center font-thin">{{ $borrow->item->name }}</td>
                    <td class="px-6 py-3 text-center font-thin">{{ $borrow->quantity }}</td>
                    <td class="px-6 py-3 text-center capitalize font-thin">
                        <span class="{{ $statusClass }} font-semibold ">
                            {{ $borrow->status }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">{{ $borrow->due ? $borrow->due->format('d M Y') : '-' }}</td>
                    <td class="px-6 py-3 text-center">
                        @if ($borrow->status === 'pending')
                        <form action="{{ route('borrowings.approve', $borrow->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button class="bg-gray-600 hover:bg-gray-700 text-white text-sm py-1 px-3 rounded">
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('borrowings.reject', $borrow->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                                Tolak
                            </button>
                        </form>
                        @else
                        <span class="text-gray-500 text-sm">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="w-full flex justify-between items-center text-sm text-gray-600">
            <div>
                @php
                $start = $borrowings->firstItem();
                $end = $borrowings->lastItem();
                $total = $borrowings->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between">
                {{ $borrowings->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            const spinner = document.getElementById('loadingSpinner');
            spinner.style.display = 'none';
        })

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