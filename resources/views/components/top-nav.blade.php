<nav class="nav justify-content-center">
@foreach( $items as $item )
<a class="nav-link" href="{{ $item->url }}">{{ $item->title }}</a>
@endforeach
</nav>