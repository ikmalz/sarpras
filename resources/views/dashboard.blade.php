<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin Sarpras
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Informasi Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Total Barang --}}
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Barang</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalBarang }}</p>
                        </div>
                        <div class="bg-gray-400 text-white p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h4l3 10h8l3-8h-8l-3-10H3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pinjaman Hari Ini --}}
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pinjaman Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $pinjamanHariIni }}</p>
                        </div>
                        <div class="bg-gray-400 text-white p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M5 11h14M5 19h14M9 15h6" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total User --}}
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total User</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalUser }}</p>
                        </div>
                        <div class="bg-gray-400 text-white p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 015 16V8a4 4 0 118 0v8a4 4 0 01-.121 1.804M12 20h.01M15.121 17.804A4 4 0 0115 16V8a4 4 0 118 0v8a4 4 0 01-.121 1.804M20 20h.01" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Belum Dikembalikan --}}
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Belum Dikembalikan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $belumDikembalikan }}</p>
                        </div>
                        <div class="bg-gray-400 text-red-600 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Pinjaman Terbaru --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Pinjaman Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-2">Nama Barang</th>
                                <th class="px-4 py-2">Peminjam</th>
                                <th class="px-4 py-2">Tanggal Pinjam</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($borrows as $borrow)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-2">{{ $borrow->item->name }}</td>
                                <td class="px-4 py-2">{{ $borrow->user->name }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $borrow->status }}
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center px-4 py-4 text-gray-500">Belum ada data pinjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>