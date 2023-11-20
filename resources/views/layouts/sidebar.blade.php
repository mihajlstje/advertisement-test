<div>

    <ul>

    @foreach ($categories as $category)
        @include('partials.category-link', ['category' => $category])
    @endforeach

    </ul>
    

</div>