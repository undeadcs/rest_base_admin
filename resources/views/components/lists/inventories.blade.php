<table class="table table-striped"><thead><tr>
	<th>{{ __( 'Наименование' ) }}</th>
	<th>{{ __( 'Цена' ) }}</th>
</tr></thead><tbody>
@foreach( $paginator->items( ) as $inventory )
<tr>
	<td><a href="{{ url( '/inventories' ) }}/{{ $inventory->id }}">{{ $inventory->title }}</a></td>
	<td>{{ $inventory->currentPrice ? $inventory->currentPrice->price : '' }}</td>
</tr>
@endforeach
</tbody>
@if ( $paginator->hasPages( ) )
<tfoot><tr><td colspan="2">
	<x-pagination :paginator="$paginator"/>
</td></tr></tfoot>
@endif
</table>