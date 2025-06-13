<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white border-b border-gray-100 px-6 py-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Dashboard
                </h1>
                <p class="text-gray-500 text-sm font-medium mt-1">
                    Sistem Manajemen Sarana & Prasarana
                </p>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Notifikasi -->
                <div class="relative">
                    <button onclick="toggleNotifDropdown()"
                        class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 
                            6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 
                            6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 
                            1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($notifikasiCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full min-w-[18px] h-[18px] flex items-center justify-center">
                            {{ $notifikasiCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse ($notifikasiList as $notif)
                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-25 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">
                                            @if ($notif->status === 'pending')
                                            <span class="font-semibold">{{ $notif->user->name }}</span> meminjam
                                            @elseif ($notif->status === 'returned')
                                            <span class="font-semibold">{{ $notif->user->name }}</span> mengembalikan
                                            @endif
                                            <span class="font-semibold text-gray-700">{{ $notif->item->name }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="px-4 py-8 text-center">
                                <div class="text-gray-300 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500">Tidak ada notifikasi baru</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Dropdown User -->
                <div class="relative">
                    <button onclick="toggleDropdown()"
                        class="flex items-center space-x-2 px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold text-gray-600">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                        </div>
                        <span class="text-sm font-medium truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <hr class="border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Informasi Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Items</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalBarang }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pinjaman Hari Ini -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Today's Borrowing</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $pinjamanHariIni }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total User -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total User</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalUser }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Belum Dikembalikan -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Not Yet Returned</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $belumDikembalikan }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pinjaman Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Latest Borrowing</h2>
                            <p class="text-sm text-gray-500 mt-1">Recent Item Borrowing List</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden">
                    @if($borrows->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrow Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($borrows as $borrow)
                                <tr class="hover:bg-gray-25 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $borrow->item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-semibold text-gray-600">
                                                    {{ substr($borrow->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-gray-900 font-medium">{{ $borrow->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($borrow->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                        @elseif($borrow->status === 'returned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Returned
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $borrow->status }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-300 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data pinjaman</h3>
                        <p class="text-gray-500">Pinjaman baru akan muncul di sini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');

            const notifDropdown = document.getElementById('notifDropdown');
            notifDropdown.classList.add('hidden');
        }

        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('hidden');

            const userDropdown = document.getElementById('userDropdown');
            userDropdown.classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            const notifDropdown = document.getElementById('notifDropdown');

            if (!event.target.closest('#userDropdown') && !event.target.closest('button[onclick="toggleDropdown()"]')) {
                userDropdown.classList.add('hidden');
            }

            if (!event.target.closest('#notifDropdown') && !event.target.closest('button[onclick="toggleNotifDropdown()"]')) {
                notifDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('userDropdown').classList.add('hidden');
                document.getElementById('notifDropdown').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>