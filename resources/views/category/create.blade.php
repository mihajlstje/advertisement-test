<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Category') }}
        </h2>
    </x-slot>

    <div class="py-12">

    <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <form method="POST" action="{{ route('category.store') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" placeholder="{{ __('Enter category name') }}" autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="parent_id" :value="__('Parent Category')" />
                <select id="select" name="parent_id" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected disabled>Select Please</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach

                </select>
                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">

                <x-primary-button class="ms-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </div>
        </form>
    
    </div>
        
    </div>
</x-app-layout>
