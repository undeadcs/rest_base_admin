<table class="table table-striped"><thead><tr>
	<th>{{ __( 'Номер' ) }}</th>
	<th>{{ __( 'Клиент' ) }}</th>
	<th>{{ __( 'Апартаменты' ) }}</th>
	<th>{{ __( 'С' ) }}</th>
	<th>{{ __( 'По' ) }}</th>
	<th>{{ __( 'Статус' ) }}</th>
	<th>{{ __( 'Кол-во человек' ) }}</th>
</tr></thead><tbody>
@foreach( $paginator->items( ) as $order )
<tr>
	<td><a href="{{ url( '/orders' ) }}/{{ $order->id }}">{{ $order->id }}</a></td>
	<td><a href="{{ url( '/customers' ) }}/{{ $order->customer->id }}">{{ $order->customer->name }}</a><br/>{{ $order->customer->phone_number }}</td>
	<td><a href="{{ url( '/apartments' ) }}/{{ $order->apartment->id }}">{{ $order->apartment->title }}</a></td>
	<td>{{ $order->from->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->to->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->status->title( ) }}</td>
	<td>{{ $order->persons_number }}</td>
</tr>
@endforeach
</tbody><tfoot><tr><td colspan="7">
	<x-pagination :paginator="$paginator"/>
</td></tr></tfoot></table>