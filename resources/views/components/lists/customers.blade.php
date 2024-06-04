<table class="table table-striped"><thead><tr>
	<th>{{ __( 'Имя' ) }}</th>
	<th>{{ __( 'Телефон' ) }}</th>
	<th>{{ __( 'Номер машины' ) }}</th>
</tr></thead><tbody>
@foreach( $paginator->items( ) as $customer )
<tr>
	<td><a href="{{ url( '/customers' ) }}/{{ $customer->id }}">{{ $customer->name }}</a></td>
	<td>{{ $customer->phone_number }}</td>
	<td>{{ $customer->car_number }}</td>
</tr>
@endforeach
</tbody>
@if ( $paginator->hasPages( ) )
<tfoot><tr><td colspan="3">
	<x-pagination :paginator="$paginator"/>
</td></tr></tfoot>
@endif
</table>