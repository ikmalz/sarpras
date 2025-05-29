<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Kiri: Judul --}}
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Admin Sarpras
                </h2>
                <p class="text-slate-600 text-sm mt-1">
                    Sistem Manajemen Sarana & Prasarana
                </p>
            </div>

            {{-- Kanan: Profil & Notifikasi --}}
            <div class="flex items-center gap-x-4">

                {{-- Notifikasi --}}
                <div class="relative">
                    <button onclick="toggleNotifDropdown()" class="relative text-gray-700 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 
                            6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 
                            6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 
                            1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($notifikasiCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $notifikasiCount }}
                        </span>
                        @endif
                    </button>

                    {{-- Dropdown Notifikasi --}}
                    <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="p-3 font-semibold border-b text-gray-700">Notifikasi</div>
                        @forelse ($notifikasiList as $notif)
                        <div class="px-4 py-2 text-sm text-gray-600 border-b hover:bg-gray-50">
                            @if ($notif->status === 'pending')
                            <strong>{{ $notif->user->name }}</strong> <span>meminjam</span>
                            @elseif ($notif->status === 'returned')
                            <strong>{{ $notif->user->name }}</strong> <span>mengembalikan</span>
                            @endif
                            <strong>{{ $notif->item->name }}</strong><br>
                            <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi baru.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Dropdown User --}}
                <div class="relative inline-block text-left">
                    <button onclick="toggleDropdown()" class="flex items-center text-slate-700 hover:text-gray-900 font-medium focus:outline-none">
                        <span class="truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
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

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button');
            const insideDropdown = event.target.closest('#userDropdown');
            if (!button && !insideDropdown) {
                dropdown.classList.add('hidden');
            }
        });

        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notifDropdown = document.getElementById('notifDropdown');
            const button = event.target.closest('button');
            const insideDropdown = event.target.closest('#notifDropdown');
            if (!button && !insideDropdown) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>

</x-app-layout>