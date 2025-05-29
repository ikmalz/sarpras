<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Stok Barang') }}
            </h2>
            <a href="{{ route('report.stock.pdf') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Barang</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dipinjam Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->category->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($item->stock == 0)
                                    <span class="text-red-600 font-semibold">Dipinjam / Kosong</span>
                                    @else
                                    <span class="text-green-600 font-semibold">Tersedia</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($item->borrows->isNotEmpty())
                                    <ul class="list-disc list-inside">
                                        @foreach($item->borrows as $borrow)
                                        <li>{{ $borrow->user->name }}</li>
                                        @endforeach
                                    </ul>
                                    <div class="text-xs text-gray-500 mt-1">{{ $item->borrows->count() }} orang</div>
                                    @else
                                    <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center px-6 py-4 text-gray-500">Tidak ada data barang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-700 font-bold" colspan="3">Total</th>
                                <th class="px-6 py-3 text-left text-gray-700 font-bold">{{ $totalStock }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>