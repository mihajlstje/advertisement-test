<div x-data="category()">

    <button id="multiLevelDropdownButton" data-dropdown-toggle="multi-dropdown" class="w-full flex p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 block mt-1" type="button">
        <div x-text="buttonText"></div>
        <svg class="w-2.5 h-2.5 ml-auto inline-block mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div id="multi-dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="multiLevelDropdownButton">

        @foreach ($categories as $category)
            @include('partials.category', ['category' => $category, 'selectedId' => $selectedId])
        @endforeach
        </ul>
    </div>

</div>
