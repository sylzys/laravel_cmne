@php 
$url = explode(backpack_url()."/", \URL::current());
// dd(backpack_url());
$display = in_array($url[1], ['inventory', 'bundle', 'serviceroom', 'category', 'subcategory']);
@endphp
@if($display)  
<div class="nav-category">
    @php
        $cats = \App\Models\Category::all()
            ->sortBy('id');
    @endphp
    @foreach ($cats as $cat)
        <a href="{{ backpack_url('category/' . $cat->id . '/show') }}">{{ $cat->description }}</a>
    @endforeach
</div>
@endif
