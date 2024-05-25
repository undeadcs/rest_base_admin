@if ( $newEntityUrl )
<div>
	<a class="btn btn-success" href="{{ $newEntityUrl }}">{{ __( 'Добавить' ) }}</a>
</div>
@endif
<table class="table table-striped">
<thead><tr>
@foreach( $columns as $column )
<th class="col col-for-{{ $column->fieldName }}">{{ $column->title }}</th>
@endforeach
</tr></thead><tbody>
@foreach( $instances as $instance )
<tr>
	@foreach( $columns as $column )
	<td>
		@if ( $column->fieldName == $linkFieldName )
			<a href="{{ $editLink( $instance ) }}">{{ $instance->{ $column->fieldName } }}</a>
		@else
			{{ $instance->{ $column->fieldName } }}
		@endif
	</td>
	@endforeach
</tr>
@endforeach
</tbody></table>
@if ( $attributes->has( 'last-page' ) )
<nav><ul class="pagination justify-content-center">
@for( $pageNumber = 1; $pageNumber <= $attributes->get( 'last-page' ); ++$pageNumber )
<li @class( [ 'page-item', 'active' => $pageNumber == $attributes->get( 'current-page' ) ] )><a class="page-link" href="{{ $baseUrl }}/?page={{ $pageNumber }}">{{ $pageNumber }}</a></li>
@endfor
</ul></nav>
@endif