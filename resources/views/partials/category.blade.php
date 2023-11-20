<li>
    @if(count($category->children) == 0)
    <div class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
        <input id="default-radio-{{ $category->id }}" type="radio" value="{{ $category->id }}" @change="setText($event.target)" {{ $category->id == $selectedId ? 'checked' : '' }} name="category_id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
        <label for="default-radio-{{ $category->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $category->name }}</label>
    </div>
    @else
    <button id="doubleDropdownButton" data-dropdown-toggle="doubleDropdown{{ $category->id }}" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $category->name }}
        <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
    </button>
    <div id="doubleDropdown{{ $category->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="doubleDropdownButton">
        @foreach($category->children as $category)
            @include('partials.category', $category)
        @endforeach
        </ul>
    </div>
    @endif
</li>