<div>
    @foreach($category->ancestors as $ancestor)
    <a href="/?category_id={{ $ancestor->id }}" class="hover:underline">{{ $ancestor->name }}</a> >
    @endforeach
    <a href="/?category_id={{ $category->id }}" class="hover:underline">{{ $category->name }}</a>
</div>