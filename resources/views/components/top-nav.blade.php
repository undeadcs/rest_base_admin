<ul class="nav nav-tabs mb-3">
@foreach( $items as $item )
<li class="nav-item"><a @class([ 'nav-link', 'active' => isset( $item->current ) ]) href="{{ $item->url }}">{{ $item->title }}</a></li>
@endforeach
</ul>