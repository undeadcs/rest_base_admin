<table class="table table-striped"><thead><tr>
	<th>{{ __( 'Номер' ) }}</th>
	@if ( !$attributes->has( 'hide-customer' ) )
	<th>{{ __( 'Клиент' ) }}</th>
	@endif
	<th>{{ __( 'Апартаменты' ) }}</th>
	<th>{{ __( 'С' ) }}</th>
	<th>{{ __( 'По' ) }}</th>
	<th>{{ __( 'Статус' ) }}</th>
	<th>{{ __( 'Кол-во человек' ) }}</th>
</tr></thead><tbody>
@foreach( $paginator->items( ) as $order )
<tr>
	<td><a href="{{ url( '/orders' ) }}/{{ $order->id }}">{{ $order->id }}</a></td>
	@if ( !$attributes->has( 'hide-customer' ) )
	<td><a href="{{ url( '/customers' ) }}/{{ $order->customer->id }}">{{ $order->customer->name }}</a><br/>{{ $order->customer->phone_number }}</td>
	@endif
	<td><a href="{{ url( '/apartments' ) }}/{{ $order->apartment->id }}">{{ $order->apartment->title }}</a></td>
	<td>{{ $order->from->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->to->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->status->title( ) }}</td>
	<td>{{ $order->persons_number }}</td>
</tr>
@endforeach
</tbody>
@if ( $paginator->hasPages( ) )
<tfoot><tr>
@if ( !$attributes->has( 'hide-customer' ) )
<td colspan="6">
@else
<td colspan="7">
@endif
	<x-pagination :paginator="$paginator"/>
</td></tr></tfoot>
@endif
</table>