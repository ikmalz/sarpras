<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories / Edit
            </h2>
            <a href="{{ route('categories.index') }}" class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('categories.update',$category->id) }}" method="post">
                        @csrf
                        <div class="">
                            <label for="" class="text-lg font-medium">Slug</label>
                            <div class="my-3 ">
                                <input value="{{ old('slug', $category->slug) }}" name="slug" placeholder="Slug" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('slug')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <label for="" class="text-lg font-medium">Name</label>
                            <div class="my-3 ">
                                <input value="{{ old('name',$category->name) }}" name="name" placeholder="Name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="bg-slate-700 text-sm rounded-md px-5 py-2 text-white">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>