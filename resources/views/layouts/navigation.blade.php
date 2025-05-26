<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="w-64 bg-white h-screen shadow-md fixed z-10">
    <div class="p-6 flex flex-col items-center justify-center text-center">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-lg font-bold text-gray-800 space-y-2">
            <img src="{{ asset('images/logoTb.png') }}" alt="Logo" class="h-16 w-auto">
            <span>Smk Taruna Bhakti</span>
        </a>
    </div>

    
    <nav class="px-4 py-2 space-y-2">

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        @can('view permissions')
        <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
            {{ __('Permissions') }}
        </x-nav-link>
        @endcan

        @can('view roles')
        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
            {{ __('Roles') }}
        </x-nav-link>
        @endcan

        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
            {{ __('User') }}
        </x-nav-link>

        @can('view category')
        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')">
            {{ __('Categories') }}
        </x-nav-link>
        @endcan

        @can('view items')
        <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
            {{ __('Items') }}
        </x-nav-link>
        @endcan

        <x-nav-link :href="route('borrow.index')" :active="request()->routeIs('borrow.index')">
            {{ __('Borrow') }}
        </x-nav-link>

        <x-nav-link :href="route('return.index')" :active="request()->routeIs('return.index')">
            {{ __('Return') }}
        </x-nav-link>
       
        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
            {{ __('Laporan') }}
        </x-nav-link>

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
</aside>
