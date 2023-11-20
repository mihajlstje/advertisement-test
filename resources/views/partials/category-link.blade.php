<li class="py-1">
    <a href="/?category_id={{ $category->id }}" class="hover:underline">{{ $category->name }}</a>
    @if(count($category->children) > 0)
    <ul class="pl-2 py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="doubleDropdownButton">
        @foreach($category->children as $category)
            @include('partials.category-link', ['category' => $category])
        @endforeach
    </ul>
    @endif
</li>