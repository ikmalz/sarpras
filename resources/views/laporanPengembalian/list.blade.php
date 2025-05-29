<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Pengembalian') }}
            </h2>
            <a href="{{ route('laporan.pengembalian.export') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-2 px-4 border">No</th>
                        <th class="py-2 px-4 border">Nama Peminjam</th>
                        <th class="py-2 px-4 border">Nama Barang</th>
                        <th class="py-2 px-4 border">Jumlah</th>
                        <th class="py-2 px-4 border">Keterangan</th>
                        <th class="py-2 px-4 border">Status</th>
                        <th class="py-2 px-4 border">Tanggal Pengembalian</th>
                        <th class="py-2 px-4 border">Image</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr class="border-b">
                        <td class="py-2 px-4 ">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 ">{{ $return->borrowing->user->name }}</td>
                        <td class="py-2 px-4 ">{{ $return->borrowing->item->name }}</td>
                        <td class="py-2 px-4 ">{{ $return->returned_quantity }}</td>
                        <td class="py-2 px-4 ">{{ $return->description ?? '-' }}</td>
                        <td class="py-2 px-4 ">
                            @if($return->is_confirmed)
                            <span class="text-green-600 font-semibold">Disetujui</span>
                            @else
                            <span class="text-yellow-600 font-semibold">Menunggu</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 ">{{ $return->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">
                            @if($return->image)
                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $return->image) }}" alt="Bukti" class="h-16 mx-auto rounded">
                            </a>
                            @else
                            <span class="text-gray-400">Tidak ada foto</span>
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
    </div>
</x-app-layout>