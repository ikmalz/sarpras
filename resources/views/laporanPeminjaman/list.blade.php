<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Peminjaman') }}
            </h2>
            <a href="{{ route('laporan.peminjaman.export') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <table class="w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-center">Nama Peminjam</th>
                        <th class="px-4 py-2 text-center">Barang</th>
                        <th class="px-4 py-2 text-center">Jumlah</th>
                        <th class="px-4 py-2 text-center">Status</th>
                        <th class="px-4 py-2 text-center">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-center">Jatuh Tempo</th>
                        <th class="px-4 py-2 text-center">Disetujui Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowings as $index => $borrow)
                    <tr class="border-b">
                        <td class="px-4 py-2 text-center">{{ $index + $borrowings->firstItem() }}</td>
                        <td class="px-4 py-2 text-center">{{ $borrow->user->name }}</td>
                        <td class="px-4 py-2 text-center">{{ $borrow->item->name }}</td>
                        <td class="px-4 py-2 text-center">{{ $borrow->quantity }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded-md text-white
                                    {{ $borrow->status == 'approved' ? 'bg-green-500' : 
                                       ($borrow->status == 'rejected' ? 'bg-red-500' :
                                       ($borrow->status == 'returned' ? 'bg-yellow-500' :
                                       ($borrow->status == 'completed' ? 'bg-gray-500' : 'bg-gray-400'))) }}">
                                {{ ucfirst($borrow->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">{{ $borrow->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-center">{{ $borrow->due ? $borrow->due->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $borrow->approver ? $borrow->approver->name : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>