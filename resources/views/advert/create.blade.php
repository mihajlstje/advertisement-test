<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Advert') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" enctype="multipart/form-data" action="{{ route('advert.store') }}">
                @csrf

                <div> 
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" placeholder="{{ __('Enter advert title') }}" autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="desc" :value="__('Description')" />
                    <textarea name="desc" placeholder="{{ __('Enter product description') }}" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 block mt-1 w-full">{{ old('desc') }}</textarea>
                    <x-input-error :messages="$errors->get('desc')" class="mt-2" />
                </div>

                <div class="flex flex-col lg:flex-row mt-4">
                    <div class="w-full lg:w-4/12 lg:pe-2 mb-4 lg:mb-0">

                        <x-input-label for="category_id" :value="__('Category')" />
                        <x-category-selector id="category_id" :categories="$categories" :selectedId="old('category_id')"></x-category-selector>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />

                    </div>
                    <div class="w-full lg:w-4/12 lg:px-2 mb-4 lg:mb-0">
                        
                        <x-input-label for="price" :value="__('Price')" />
                        <div class="flex mt-1">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            {{ $defaultCurrency }}
                        </span>
                        <input type="number" name="price" value="{{ old('price') }}" placeholder="{{ __('Enter product price') }}" id="price" class="rounded-none rounded-e-lg sm:text-xs bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 px-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />

                    </div>
                    <div class="w-full lg:w-4/12 lg:ps-2">

                        <x-input-label for="condition" :value="__('Is product new?')" />

                        <div class="flex py-2.5">

                            <div class="flex items-center me-4">
                                <input id="inline-radio" type="radio" value="{{ App\Enums\Conditions::NEW->value }}" {{ old('condition') == App\Enums\Conditions::NEW->value ? 'checked' : '' }} name="condition" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="inline-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">New</label>
                            </div>
                            <div class="flex items-center me-4">
                                <input id="inline-2-radio" type="radio" value="{{ App\Enums\Conditions::USED->value }}" {{ old('condition') == App\Enums\Conditions::USED->value ? 'checked' : '' }} name="condition" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="inline-2-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Used</label>
                            </div>

                        </div>

                        <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                    
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row mt-4">

                    <div class="w-full lg:w-6/12 lg:pe-2 mb-4 lg:mb-0">
                    
                        <x-input-label for="city_id" :value="__('Location')" />
                        <select id="city_id" name="city_id" class="block w-full mt-1 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected disabled>Select Please</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach

                        </select>
                        <x-input-error :messages="$errors->get('city_id')" class="mt-2" />

                    </div>

                    <div class="w-full lg:w-6/12 lg:ps-2">
                    
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="{{ __('Enter phone number') }}" autofocus />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />

                    </div>

                </div>


                <div class="mt-4" x-data="image()">
                    <x-input-label for="image" :value="__('Image')" />
                    <input type="file" x-ref="image" name="image" @change="imageChanged($event.target, $refs.imagePreview)" accept="image/png, image/gif, image/jpeg, image/jpg" class="hidden">
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />              
                    <div class="inline-block mt-4 cursor-pointer border-dashed border-2 border-sky-500" @click="$refs.image.click()">
                        <img src="{{ asset('/assets/images/add-image-placeholder.jpeg') }}" class="basis-1/5 h-48 object-fit-cover" x-ref="imagePreview">
                    </div>
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
