<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('List Returning') }}
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">confirmed</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Proof</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Information</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($returns as $index => $return)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $returns->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $return->borrowing->item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $return->borrowing->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $return->returned_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($return->is_confirmed)
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                        @else
                                        <span class="text-green-600 font-semibold">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-12 py-4 text-sm text-gray-600">
                                        {{ $return->handled_by ? \App\Models\User::find($return->handled_by)->name : '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if(!$return->is_confirmed)
                                        <form action="{{ route('returns.approve', $return->id) }}" method="POST" onsubmit="return confirm('setujui pengembalian ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-700 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-sm">Setujui</button>
                                        </form>
                                        @else
                                        <span class="text-gray-500 text-sm">Sudah disetujui</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($return->image)
                                        <a href="{{ asset('storage/' . $return->image) }}" target="_blank" class="group relative">
                                            <img src="{{ asset('storage/' . $return->image) }}" alt="Bukti" class="h-12 w-12 rounded-lg object-cover border border-gray-200 group-hover:opacity-75 transition-opacity">
                                            <div class="absolute inset-0 rounded-lg bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </div>
                                        </a>
                                        @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $return->description ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-gray-500 font-medium">Belum ada data pengembalian</p>
                                            <p class="text-gray-400 text-sm mt-1">Data pengembalian akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($returns->hasPages())
                    <div class="bg-white px-6 py-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                @php
                                $start = $returns->firstItem();
                                $end = $returns->lastItem();
                                $total = $returns->total();
                                @endphp
                                Menampilkan <span class="font-medium">{{ $start }}</span> sampai <span class="font-medium">{{ $end }}</span> dari <span class="font-medium">{{ $total }}</span> entri
                            </div>
                            <div>
                                {{ $returns->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                    @endif
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