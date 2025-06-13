<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="w-64 bg-white h-screen shadow-md fixed z-10">
    <div class="p-6 flex flex-col items-center justify-center text-center">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-lg font-bold text-gray-800 space-y-2">
            <img src="{{ asset('images/logoTb.png') }}" alt="Logo" class="h-16 w-auto">
            <span>Smk Taruna Bhakti</span>
        </a>
    </div>


    <div class="px-4 py-2 space-y-2 overflow-y-auto scrollbar-hide h-[calc(100vh-10rem)]">
        <nav class="space-y-2">

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>

            @auth
            @if(auth()->user()->hasRole('admin'))
            @php
            $userPermissionActive = request()->routeIs('permissions.index') || request()->routeIs('roles.index') || request()->routeIs('users.index');
            @endphp
            <div x-data="{ open: {{ $userPermissionActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-4 py-2 text-md font-medium text-gray-600 hover:bg-gray-100 focus:outline-none">
                    User and Permission
                    <svg class="w-4 h-4 ml-2 transform transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-md">
                    <a href="{{ route('permissions.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('permissions.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Permissions
                    </a>
                    <a href="{{ route('roles.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('roles.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Roles
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('users.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        User
                    </a>
                </div>
            </div>
            @endif
            @endauth


            @auth
            @if(auth()->user()->hasRole('admin'))
            @php
            $masterDataActive = request()->routeIs('categories.index') || request()->routeIs('items.index');
            @endphp
            <div x-data="{ open: {{ $masterDataActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-4 py-2 text-md font-medium text-gray-600 hover:bg-gray-100 focus:outline-none">
                    Master Data
                    <svg class="w-4 h-4 ml-2 transform transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-md">
                    <a href="{{ route('categories.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('categories.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Categories
                    </a>
                    <a href="{{ route('items.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('items.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Items
                    </a>
                </div>
            </div>
            @endif
            @endauth


            @auth
            @if(auth()->user()->hasRole('admin'))
            @php
            $transactionActive = request()->routeIs('borrow.index') || request()->routeIs('return.index');
            @endphp
            <div x-data="{ open: {{ $transactionActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-4 py-2 text-md font-medium text-gray-600 hover:bg-gray-100 focus:outline-none">
                    Transaction
                    <svg class="w-4 h-4 ml-2 transform transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-md">
                    <a href="{{ route('borrow.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('borrow.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Borrow
                    </a>
                    <a href="{{ route('return.index') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('return.index') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Return
                    </a>
                </div>
            </div>
            @endif
            @endauth


            @auth
            @if(auth()->user()->hasRole('admin'))
            @php
            $laporanActive = request()->routeIs('laporan.barang') || request()->routeIs('laporan.peminjaman') || request()->routeIs('laporan.pengembalian');
            @endphp
            <div x-data="{ open: {{ $laporanActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-4 py-2 text-md font-medium text-gray-600 hover:bg-gray-100 focus:outline-none">
                    Report
                    <svg class="w-4 h-4 ml-2 transform transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-md">
                    <a href="{{ route('laporan.barang') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('laporan.barang') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Stock Report
                    </a>
                    <a href="{{ route('laporan.peminjaman') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('laporan.peminjaman') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Borrow Report
                    </a>
                    <a href="{{ route('laporan.pengembalian') }}"
                        class="block px-3 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('laporan.pengembalian') ? 'bg-gray-200 font-semibold text-gray-500' : 'text-gray-500' }}">
                        Return Report
                    </a>
                </div>
            </div>
            @endif
            @endauth

            <!-- Profile & Logout -->
            <hr class="my-3">
            <x-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-nav-link>
            </form>
        </nav>
    </div>
</aside>