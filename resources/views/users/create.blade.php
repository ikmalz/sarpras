<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users / Create
            </h2>
        </div>
    </x-slot>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <x-message></x-message>

                <form action="{{ route('users.store') }}" method="POST" class="bg-white px-8 pt-6 pb-8 mb-4">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="blcok text-gray-700 text-sm font-bold mb-2">
                            Nama kelas
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="blcok text-gray-700 text-sm font-bold mb-2">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="blcok text-gray-700 text-sm font-bold mb-2">
                            Password
                        </label>
                        <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="blcok text-gray-700 text-sm font-bold mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('password_confirmation') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="blcok text-gray-700 text-sm font-bold mb-2">
                            Role
                        </label>
                        <select name="role" class="form-select w-full border rounded px-3 py-2 text-gray-700">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')  <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                        <a href="{{ route('users.index') }}" class="text-gray-600 hover:underline">Back</a>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>