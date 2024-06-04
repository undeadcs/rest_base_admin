@if ( $paginator->hasPages( ) )
<nav><ul class="pagination justify-content-center">
@if ( $prevFramePage = $PrevFrame( ) )
<li class="page-item"><a class="page-link" href="{{ $paginator->path( ) }}/?page={{ $prevFramePage }}">&laquo;</a></li>
@else
<li class="page-item disabled"><span class="page-link">&laquo;</span></li>
@endif
@for( $pageNumber = $StartingPageNumber( ); $pageNumber <= $EndingPageNumber( ); ++$pageNumber )
<li @class( [ 'page-item', 'active' => $pageNumber == $paginator->currentPage() ] )><a class="page-link" href="{{ $paginator->path( ) }}/?page={{ $pageNumber }}">{{ $pageNumber }}</a></li>
@endfor
@if ( $nextFramePage = $NextFrame( ) )
<li class="page-item"><a class="page-link" href="{{ $paginator->path( ) }}/?page={{ $nextFramePage }}">&raquo;</a></li>
@else
<li class="page-item disabled"><span class="page-link">&raquo;</span></li>
@endif
</ul></nav>
@endif