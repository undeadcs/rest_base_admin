<form class="mb-3" method="post" action="{{ url( $customer->id ? '/customers/'.$customer->id : '/customers' ) }}" autocomplete="off">
@csrf
@if ( $customer->id )
@method( 'PUT' )
<input type="hidden" name="id" value="{{ $customer->id }}"/>
@endif
<div class="mb-3">
	<label for="name">{{ __( 'Имя' ) }}</label>
	<input id="name" class="form-control" type="text" name="name" value="{{ $customer->name ?? old( 'name' ) }}" autofocus="autofocus"/>
</div>
<div class="mb-3">
	<label for="phone_number">{{ __( 'Телефон' ) }}</label>
	<input id="phone_number" class="form-control" type="text" name="phone_number" value="{{ $customer->phone_number ?? old( 'phone_number' ) }}"/>
</div>
<div class="mb-3">
	<label for="car_number">{{ __( 'Номер машины' ) }}</label>
	<input id="car_number" class="form-control" type="text" name="car_number" value="{{ $customer->car_number ?? old( 'car_number' ) }}"/>
</div>
<div class="mb-3">
	<label for="comment">{{ __( 'Комментарий' ) }}</label>
	<textarea id="comment" class="form-control" rows="4" name="comment">{{ $customer->comment }}</textarea>
	<div class="form-text">{{ __( 'Сюда стоит записывать всякие нюансы по части клиента (например: шумные). Ограничений нет. Желательно разные категории информации разделять, чтобы в дальнейшем их можно было проанализировать и добавить что-нибудь новое' ) }}</div>
</div>
<input class="btn btn-primary" type="submit" value="{{ __( 'Сохранить' ) }}"/>
<a class="btn btn-danger" href="{{ url( '/customers' ) }}">{{ __( 'Отмена' ) }}</a>
</form>
@if ( $orders && $orders->isNotEmpty( ) )
<h2>{{ __( 'Заявки' ) }}</h2>
<x-lists.orders :paginator="$orders" :hide-customer="true"/>
@endif