@if ( $newEntityUrl )
<div>
<a class="btn btn-success" href="{{ $newEntityUrl }}">{{ __( 'Добавить' ) }}</a>
</div>
@endif
<table class="table"><thead>
@foreach( $columns as $column )
<th class="col col-for-{{ $column->fieldName }}">{{ $column->title }}</th>
@endforeach
</thead><tbody>
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
</tbody><tfoot>
</tfoot></table>