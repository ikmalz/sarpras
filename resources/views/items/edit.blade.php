<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories / Edit
            </h2>
            <a href="{{ route('items.index') }}" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('items.update',$item->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="">
                            <label for="" class="text-lg font-medium">Name</label>
                            <div class="my-3 ">
                                <input value="{{ old('name', $item->name) }}" name="name" placeholder="Name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Stock</label>
                            <div class="my-3 ">
                                <input value="{{ old('stock',$item->stock) }}" name="stock" placeholder="Stock" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('stock')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Image</label>
                            @if($item->image_url)
                                <div class="my-3">
                                    <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded border">
                                    <p class="text-sm text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif

                            <div class="my-3 ">
                                <input name="image" type="file" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('image')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Category</label>
                            <select name="category_id" class="w-1/2 mt-1 border-gray-300 rounded-lg shadow-sm">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id',$item->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <button class="bg-slate-700 text-sm rounded-md px-5 py-2 text-white">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>