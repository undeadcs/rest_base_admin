<form class="mb-3" method="post" action="{{ url( $order->id ? '/orders/'.$order->id : '/orders' ) }}" autocomplete="off">
@csrf
@if ( $order->id )
@method( 'PUT' )
<input type="hidden" name="id" value="{{ $order->id }}"/>
@endif
<input id="customer_id" type="hidden" name="customer_id" value="{{ $order->customer_id }}"/>
@if ( $order->id )
<div class="mb-3">
	<label for="status" class="form-label">{{ __( 'Статус' ) }}</label>
	<select id="status" class="form-select" name="status" autofocus="autofocus">
	@foreach( $attributes->get( 'statuses' ) as $value => $title )
	<option value="{{ $value }}"@if ( $order->status->value == $value ) selected="selected" @endif>{{ $title }}</option>
	@endforeach
	</select>
</div>
@endif
<div class="mb-3">
	<label class="form-label" for="apartment_id">{{ __( 'Апартаменты' ) }}</label>
	<select id="apartment_id" class="form-select" name="apartment_id" @if ( !$order->id ) autofocus="autofocus"@endif>
		@foreach( $apartments as $apartment )
		<option value="{{ $apartment->id }}"@if ( $apartment->id == $order->apartment_id || $apartment->id === old( 'apartment_id' ) ) selected="selected" @endif>{{ $apartment->title }}</option>
		@endforeach
	</select>
</div>
<div class="mb-3">
	<input id="find_customer" class="form-control" type="text" name="find_customer" value="" placeholder="{{ __( 'Поиск клиента' ) }}"/>
	<div class="form-text">{{ __( 'Для поиска клиента нужно начать вводить телефон, номер машины или имя. Затем выбрать один из результатов. Если клиент отсутствует, то добавить нового в соседней вкладке, а потом повторить поиск.' ) }}</div>
</div>
<div class="row mb-3"><div class="col">
	<label for="name">{{ __( 'Имя' ) }}</label>
	<input id="name" class="form-control" type="text" name="customer[name]" value="{{ $order->customer_id ? $order->customer->name : '' }}" readonly="readonly"/>
</div><div class="col">
	<label for="phone_number">{{ __( 'Телефон' ) }}</label>
	<input id="phone_number" class="form-control" type="text" name="customer[phone_number]" value="{{ $order->customer_id ? $order->customer->phone_number : '' }}" readonly="readonly"/>
</div></div>
<div class="row mb-3"><div class="col">
	<label for="car_number">{{ __( 'Номер машины' ) }}</label>
	<input id="car_number" class="form-control" type="text" name="customer[car_number]" value="{{ $order->customer_id ? $order->customer->car_number : '' }}" readonly="readonly"/>
</div><div class="col">
	<label for="customer_comment">{{ __( 'Комментарий о клиенте' ) }}</label>
	<textarea id="customer_comment" class="form-control" rows="4" name="customer[comment]" readonly="readonly">{{ $order->customer_id ? $order->customer->comment : '' }}</textarea>
</div></div>
<div class="row mb-3">
	<div class="col-2">
		<label class="form-label" for="from">{{ __( 'С' ) }}</label>
		<input id="from" class="form-control" type="text" name="from" value="{{ $order->from->format( 'd.m.Y' ) }}"/>
	</div>
	<div class="col-2">
		<label class="form-label" for="from-hour">&nbsp;</label>
		<div class="input-group">
			<input id="from-hour" class="form-control" type="text" name="from_hour" value="{{ $order->from->format( 'H' ) }}"/>
			<div class="input-group-text">{{ __( 'ч' ) }}</div>
		</div>
	</div>
	<div class="col-2">
		<label class="form-label" for="from-minute">&nbsp;</label>
		<div class="input-group">
			<input id="from-minute" class="form-control" type="text" name="from_minute" value="{{ $order->from->format( 'i' ) }}"/>
			<div class="input-group-text">{{ __( 'м' ) }}</div>
		</div>
	</div>
	<div class="col-2">
		<label class="form-label" for="to">{{ __( 'По' ) }}</label>
		<input id="to" class="form-control" type="text" name="to" value="{{ $order->to->format( 'd.m.Y' ) }}"/>
	</div>
	<div class="col-2">
		<label class="form-label" for="to-hour">&nbsp;</label>
		<div class="input-group">
			<input id="to-hour" class="form-control" type="text" name="to_hour" value="{{ $order->to->format( 'H' ) }}"/>
			<div class="input-group-text">{{ __( 'ч' ) }}</div>
		</div>
	</div>
	<div class="col-2">
		<label class="form-label" for="to-minute">&nbsp;</label>
		<div class="input-group">
			<input id="to-minute" class="form-control" type="text" name="to_minute" value="{{ $order->to->format( 'i' ) }}"/>
			<div class="input-group-text">{{ __( 'м' ) }}</div>
		</div>
	</div>
</div>
<div class="mb-3">
	<label class="form-label" for="persons_number">{{ __( 'Количество человек' ) }}</label>
	<input id="persons_number" class="form-control" type="text" name="persons_number" value="{{ $order->persons_number ?? old( 'persons_number' ) }}"/>
</div>
<div class="mb-3">
	<label class="form-label" for="comment">{{ __( 'Комментарий' ) }}</label>
	<textarea id="comment" class="form-control" rows="4" name="comment">{{ $order->comment ?? old( 'comment' ) }}</textarea>
	<div class="form-text">{{ __( 'Сюда стоит записывать всякие детали заказа. Желательно разные категории информации разделять, чтобы в дальнейшем их можно было проанализировать и добавить что-нибудь новое' ) }}</div>
</div>
@if ( $order->id )
<h2>{{ __( 'Инвентарь' ) }}</h2>
<div class="mb-1"><a id="add-inventory-row" class="btn btn-success" href="#">{{ __( 'добавить' ) }}</a></div>
<div id="inventory-list" class="mb-5">
@foreach( $order->inventories as $index => $inventory )
<input type="hidden" name="inventories[{{ $index }}][id]" value="{{ $inventory->pivot->id }}"/>
<div class="row mb-1">
	<div class="col-2">{{ $inventory->title }}</div>
	<div class="col"><input class="form-control" type="text" name="inventories[{{ $index }}][comment]" value="{{ $inventory->pivot->comment }}"/></div>
</div>
@endforeach
</div>
<h2>{{ __( 'Платежи' ) }}</h2>
<div class="mb-1"><a id="add-payment-row" class="btn btn-success" href="#">{{ __( 'добавить' ) }}</a></div>
<div id="payment-list" class="mb-5">
@foreach( $order->payments as $index => $payment )
<input type="hidden" name="payments[{{ $index }}][id]" value="{{ $payment->id }}"/>
<div class="row mb-1">
	<div class="col-2"><input class="form-control" type="text" name="payments[{{ $index }}][amount]" value="{{ $payment->amount }}"/></div>
	<div class="col"><input class="form-control" type="text" name="payments[{{ $index }}][comment]" value="{{ $payment->comment }}"/></div>
</div>
@endforeach
</div>
@endif
<input class="btn btn-primary" type="submit" value="{{ __( 'Сохранить' ) }}"/>
<a class="btn btn-danger" href="{{ url( '/orders' ) }}">{{ __( 'Отмена' ) }}</a>
</form>
<script>
$( '#from' ).datepicker( { dateFormat: 'dd.mm.yy', minDate: 0 } );
$( '#to' ).datepicker( { dateFormat: 'dd.mm.yy', minDate: 0 } );

$( '#find_customer' ).autocomplete( {
	minLength: 2,
	source: '{{ url( '/api/customers/find-for-order' ) }}',
	select: function( event, ui ) {
		let customer = ui.item.customer;
		
		$( '#customer_id' ).val( customer.id );
		$( '#name' ).val( customer.name );
		$( '#phone_number' ).val( customer.phone_number );
		$( '#car_number' ).val( customer.car_number );
		$( '#customer_comment' ).val( customer.comment );
		$( this ).val( '' );
		
		return false;
	},
	focus: function( event, ui ) { return false; },
	change: function( event, ui ) { return false; }
} );

@if ( $order->id )
var paymentsIndex = {{ $order->payments->count( ) }};

$( '#add-payment-row' ).click( function( ) {
	$( '#payment-list' ).append( $( '<input type="hidden" name="payments[' + paymentsIndex + '][id]" value="0"/>\
<div class="row mb-1">\
	<div class="col-2"><input class="form-control" type="text" name="payments[' + paymentsIndex + '][amount]" value=""/></div>\
	<div class="col"><input class="form-control" type="text" name="payments[' + paymentsIndex + '][comment]" value=""/></div>\
</div>' ) );
	++paymentsIndex;
	
	return false;
} );

var inventoriesIndex = {{ $order->inventories->count( ) }};

$( '#add-inventory-row' ).click( function( ) {
	$( '#inventory-list' ).append( $( '<input type="hidden" name="inventories[' + inventoriesIndex + '][id]" value="0"/>\
<div class="row mb-1">\
	<div class="col-2"><select class="form-select" name="inventories[' + inventoriesIndex + '][inventory_id]">\
@foreach( $attributes->get( 'inventories' ) as $inventory )<option value="{{ $inventory->id }}">{{ $inventory->title }}</option>\@endforeach
	</select></div>\
	<div class="col"><input class="form-control" type="text" name="inventories[' + inventoriesIndex + '][comment]" value=""/></div>\
</div>' ) );
	++inventoriesIndex;
	
	return false;
} );
@endif
</script>