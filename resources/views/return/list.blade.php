<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pengembalian') }}
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
            for ($i = 5; $i < $totalReturning; $i +=5) {
                $options[]=$i;
                }
                if (!in_array($totalReturning, $options)) {
                $options[]=$totalReturning;
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
                <tr class="text-center">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Barang</th>
                    <th class="px-4 py-2">Peminjaman</th>
                    <th class="px-4 py-2">Jumlah dikembalikan</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Dikonfirmasi Oleh</th>
                    <th class="px-4 py-2">Aksi</th>
                    <th class="px-4 py-2">Bukti Foto</th>
                    <th class="px-4 py-2">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($returns as $index => $return)
                <tr class="border-t text-center">
                    <td class="px-4 py-2">{{ $returns->firstItem() + $index }}</td>
                    <td class="px-4 py-2">{{ $return->borrowing->item->name }}</td>
                    <td class="px-4 py-2">{{ $return->borrowing->user->name }}</td>
                    <td class="px-4 py-2">{{ $return->returned_quantity }}</td>
                    <td class="px-4 py-2">
                        @if($return->is_confirmed)
                        <span class="text-green-600 font-semibold">Disetujui</span>
                        @else
                        <span class="text-green-600 font-semibold">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        {{ $return->handled_by ? \App\Models\User::find($return->handled_by)->name : '-' }}
                    </td>
                    <td class="px-4 py-2">
                        @if(!$return->is_confirmed)
                        <form action="{{ route('returns.approve', $return->id) }}" method="POST" onsubmit="return confirm('setujui pengembalian ini?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded text-sm">Setujui</button>
                        </form>
                        @else
                        <span class="text-gray-500">Sudah disetujui
                            <i class='bx bx-check text-green-300'></i>
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($return->image)
                        <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $return->image) }}" alt="Bukti" class="h-16 mx-auto rounded">
                        </a>
                        @else
                        <span class="text-gray-400">Tidak ada foto</span>
                        @endif
                    </td>

                    <td class="px-4 py-2 text-sm">
                        {{ $return->description ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Belum ada pengembalian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="w-full flex justify-between items-center mt-4 text-sm text-gray-600">
            <div>
                @php
                $start = $returns->firstItem();
                $end = $returns->lastItem();
                $total = $returns->total();
                @endphp
                Showing {{ $start }} to {{ $end }} of {{ $total }} entries
            </div>
            <div class="flex justify-between mt-2">
                {{ $returns->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
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