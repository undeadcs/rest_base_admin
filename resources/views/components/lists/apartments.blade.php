<table class="table table-striped"><thead><tr>
	<th>{{ __( 'Наименование' ) }}</th>
	<th>{{ __( 'Номер' ) }}</th>
	<th>{{ __( 'Вместимость' ) }}</th>
	<th>{{ __( 'Цена' ) }}</th>
</tr></thead><tbody>
@foreach( $paginator->items( ) as $apartment )
<tr>
	<td><a href="{{ url( '/apartments' ) }}/{{ $apartment->id }}">{{ $apartment->title }}</a></td>
	<td>{{ $apartment->number }}</td>
	<td>{{ $apartment->capacity }}</td>
	<td>{{ $apartment->currentPrice ? $apartment->currentPrice->price : '' }}</td>
</tr>
@endforeach
</tbody>
@if ( $paginator->hasPages( ) )
<tfoot><tr><td colspan="4">
	<x-pagination :paginator="$paginator"/>
</td></tr></tfoot>
@endif
</table>