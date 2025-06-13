<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('List Borrowing') }}
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


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrows Name</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Due</th>
                                <th class="px-6 py-5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
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
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                <td class="px-6 py-5 text-center">
                                    <span class="text-sm text-gray-500 font-medium">
                                        {{ $borrowings->firstItem() + $index }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $borrow->user->name }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-sm text-gray-600">{{ $borrow->item->name }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-fulltext-sm font-medium text-gray-500">
                                        {{ $borrow->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center capitalize font-thin">
                                    <span class="{{ $statusClass }} font-semibold ">
                                        {{ $borrow->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-sm text-gray-600">
                                        {{ $borrow->due ? $borrow->due->format('d M Y') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if ($borrow->status === 'pending')
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('borrowings.approve', $borrow->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('borrowings.reject', $borrow->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Diproses
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
                                        <p class="text-gray-500">Belum ada peminjaman yang tercatat dalam sistem.</p>
                                    </div>
                                </td>
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
                        Showing {{ $start }} to {{ $end }} of {{ $total }} entries
                    </div>
                    <div>
                        {{ $borrowings->appends(['rows' => request('rows')])->links('vendor.pagination.custom') }}
                    </div>
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