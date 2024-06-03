<form class="mb-3" method="post" action="{{ url( $customer->id ? '/customers/'.$customer->id : '/customers' ) }}" autocomplete="off">
@csrf
@if ( $customer->id )
@method( 'PUT' )
<input type="hidden" name="id" value="{{ $customer->id }}"/>
@endif
<div class="mb-3">
	<label for="name">{{ __( 'Имя' ) }}</label>
	<input id="name" class="form-control" type="text" name="name" value="{{ $customer->name }}" autofocus="autofocus"/>
</div>
<div class="mb-3">
	<label for="phone_number">{{ __( 'Телефон' ) }}</label>
	<input id="phone_number" class="form-control" type="text" name="phone_number" value="{{ $customer->phone_number }}"/>
</div>
<div class="mb-3">
	<label for="car_number">{{ __( 'Номер машины' ) }}</label>
	<input id="car_number" class="form-control" type="text" name="car_number" value="{{ $customer->car_number }}"/>
</div>
<div class="mb-3">
	<label for="comment">{{ __( 'Комментарий' ) }}</label>
	<textarea id="comment" class="form-control" rows="4" name="comment">{{ $customer->comment }}</textarea>
	<div class="form-text">{{ __( 'Сюда стоит записывать всякие нюансы по части клиента (например: шумные). Ограничений нет. Желательно разные категории информации разделять, чтобы в дальнейшем их можно было проанализировать и добавить что-нибудь новое' ) }}</div>
</div>
<input class="btn btn-primary" type="submit" value="{{ __( 'Сохранить' ) }}"/>
<a class="btn btn-danger" href="{{ url( '/customers' ) }}">{{ __( 'Отмена' ) }}</a>
</form>
@if ( $attributes->has( 'orders' ) )
<h2>{{ __( 'Заявки' ) }}</h2>

<table class="table"><thead><tr>
	<th>{{ __( '#' ) }}</th>
	<th>{{ __( 'Статус' ) }}</th>
	<th>{{ __( 'Апартаменты' ) }}</th>
	<th>{{ __( 'Цена' ) }}</th>
	<th>{{ __( 'С' ) }}</th>
	<th>{{ __( 'По' ) }}</th>
	<th>{{ __( 'Кол-во человек' ) }}</th>
</tr></thead><tbody>
@foreach( $attributes->get( 'orders' ) as $order )
<tr>
	<td><a href="{{ url( '/orders' ) }}/{{ $order->id }}">{{ $order->id }}</a></td>
	<td>{{ $order->status->title( ) }}</td>
	<td><a href="{{ url( '/apartments' ) }}/{{ $order->apartment->id }}">{{ $order->apartment->title }}</a></td>
	<td>{{ $order->apartmentPrice->price }}</td>
	<td>{{ $order->from->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->to->format( 'd.m.Y H:i' ) }}</td>
	<td>{{ $order->persons_number }}</td>
</tr>
@endforeach
</tbody></table>
@if ( $attributes->has( 'last-page' ) )
<nav><ul class="pagination justify-content-center">
@for( $pageNumber = 1; $pageNumber <= $attributes->get( 'last-page' ); ++$pageNumber )
<li @class( [ 'page-item', 'active' => $pageNumber == $attributes->get( 'current-page' ) ] )><a class="page-link" href="{{ url( '/customers' ) }}/{{ $customer->id }}?page={{ $pageNumber }}">{{ $pageNumber }}</a></li>
@endfor
</ul></nav>
@endif
@endif