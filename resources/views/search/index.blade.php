<x-app-layout>

    <div class="py-12">

        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex flex-col lg:flex-row mt-4">

                    <div class="w-full p-0 lg:w-4/12 mb-4 lg:mb-0">
                        
                        <div class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                            <h5 class="mb-4 text-2xl font-bold tracking-tight dark:text-white text-gray-700">{{ __('Categories') }}</h5>

                            @include('layouts.sidebar')

                        </div>

                    </div>

                    <div class="w-full lg:w-8/12 pl-0 lg:pl-4">

                        @if(isset($selectedCategory))

                        <div class="mb-5">

                            <x-category-breadcrumbs :category="$selectedCategory" />

                        </div>

                        @endif

                        <form action="/">

                            @if(isset(request()->category_id))
                            <input type="hidden" name="category_id" value="{{ request()->category_id }}">
                            @endif

                            <div class="flex flex-col lg:flex-row mb-4">

                                <div class="w-full lg:w-1/3 mb-4 lg:mb-0 pr-0 lg:pr-2">

                                    <x-input-label for="city_id" :value="__('Location')" />
                                    <select id="city_id" name="city_id" class="block bg-white w-full mt-1 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected disabled>Select Please</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ request()->input('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="w-full lg:w-1/3 pl-0 lg:px-2">
                                    <x-input-label for="minPrice" :value="__('Min. Price')" />
                                    <div class="flex mt-1">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            {{ $defaultCurrency }}
                                        </span>
                                        <input type="number" name="minPrice" value="{{ request()->input('minPrice') }}" placeholder="{{ __('Enter min. price') }}" id="minPrice" class="rounded-none bg-white rounded-e-lg sm:text-xs bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 px-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/3 pl-0 lg:pl-2">
                                    <x-input-label for="maxPrice" :value="__('Max. Price')" />
                                    <div class="flex mt-1">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            {{ $defaultCurrency }}
                                        </span>
                                        <input type="number" name="maxPrice" value="{{ request()->input('maxPrice') }}" placeholder="{{ __('Enter max. price') }}" id="maxPrice" class="rounded-none bg-white rounded-e-lg sm:text-xs bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 px-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                </div>

                            </div>

                            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="default-search" name="keyword" value="{{ request()->input('keyword') }}" class="block bg-white w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Search for products...') }}">
                                <button type="submit" class="text-white absolute end-2.5 absolute-center-vertical bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                            </div>

                        </form>


                        <div class="mt-5">

                            @if(count($data) > 0)

                            <div class="separator"></div>

                            <div class="flex justify-between mb-5">

                                <div x-data="table">

                                    <x-input-label for="sort" :value="__('Limit')" />

                                    <select id="sort" name="sort" @change="limiterChanged($event.target.value)" class="block bg-white mt-1 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        
                                        <option selected disabled>Select Please</option>

                                        <option value="2" {{ request()->input('limit') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="10" {{ request()->input('limit') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="30" {{ request()->input('limit') == 30 ? 'selected' : '' }}>30</option>
                                        <option value="50" {{ request()->input('limit') == 50 ? 'selected' : '' }}>50</option>

                                    </select>

                                </div>
                            
                                <div x-data="table">

                                    <x-input-label for="sort" :value="__('Sort')" />
                                    
                                    <select id="sort" name="sort" @change="searchSortChanged($event.target.value)" class="block bg-white mt-1 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        
                                        <option selected disabled>Select Please</option>
        
                                        <option value="price-asc" {{ request()->input('sortBy') == 'price' && request()->input('sortType') == 'asc' ? 'selected' : '' }}>Cheap</option>
                                        <option value="price-desc" {{ request()->input('sortBy') == 'price' && request()->input('sortType') == 'desc' ? 'selected' : '' }}>Expensive</option>
                                        <option value="created_at-asc" {{ request()->input('sortBy') == 'created_at' && request()->input('sortType') == 'asc' ? 'selected' : '' }}>Old</option>
                                        <option value="created_at-desc" {{ request()->input('sortBy') == 'created_at' && request()->input('sortType') == 'desc' ? 'selected' : '' }}>New</option>

                                    </select>

                                </div>
                                

                            </div>

                            @foreach($data as $row)
                        
                            <div class="p-4 bg-white shadow-lg rounded mb-4">

                                <div class="flex flex-col lg:flex-row">

                                    <div class="w-3/12">
                                        <img src="{{ asset($row->image) }}" class="w-full h-44 object-fit-cover overflow-clip-margin-unset" alt="{{ $row->title }}">
                                    </div>

                                    <div class="w-5/12 pl-3">
                                        <a href="{{ route('advert.show', ['advert' => $row->id]) }}" class="text-lg font-bold text-blue-900 hover:underline">{{ $row->title }}</a>
                                        <p class="text-gray-500">{{ $row->short_desc }}</p>
                                    </div>
                                    <div class="w-2/12 pl-3">
                                        <div class="text-lg font-bold text-red-800">
                                            {{ $defaultCurrency.number_format($row->price, 2) }}
                                        </div>
                                    </div>
                                    <div class="w-2/12 pl-3">

                                        <div class="text-gray-500 lg:text-end mb-2 text-sm">
                                            {{ (new Carbon\Carbon($row->created_at))->diffForHumans() }}
                                        </div>

                                        <div class="lg:text-end text-blue-900">{{ $row->city }}</div>
                                        
                                    </div>

                                </div>

                            </div>

                            @endforeach

                            @else

                            <div class="p-4 bg-white shadow-lg rounded mb-4">No results found for given criteria...</div>

                            @endif

                            <div class="my-3">

                                {{ $data->withQueryString()->links() }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
</x-app-layout>