<form class="mb-3" method="post" action="{{ url( $inventory->id ? '/inventories/'.$inventory->id : '/inventories' ) }}" autocomplete="off">
@csrf
@if ( $inventory->id )
@method( 'PUT' )
<input type="hidden" name="id" value="{{ $inventory->id }}"/>
@endif
<div class="mb-3">
	<label for="title">{{ __( 'Наименование' ) }}</label>
	<input id="title" class="form-control" type="text" name="title" value="{{ $inventory->title }}" autofocus="autofocus"/>
	<div class="form-text">{{ __( 'например: чайник' ) }}</div>
</div>
<div class="mb-3">
	<label for="price">{{ __( 'Цена' ) }}</label>
	<input id="price" class="form-control" type="text" name="price" value="{{ $inventory->currentPrice ? $inventory->currentPrice->price : '' }}"/>
	<div class="form-text">{{ __( 'цена в рублях за сутки. будет использоваться при создании платежей и подсказок. например: 555.55' ) }}</div>
</div>
<div class="mb-3">
	<label for="comment">{{ __( 'Комментарий' ) }}</label>
	<textarea id="comment" class="form-control" rows="4" name="comment">{{ $inventory->comment }}</textarea>
	<div class="form-text">{{ __( 'Сюда стоит записывать всякие нюансы по части инвентаря. Желательно разные категории информации разделять, чтобы в дальнейшем их можно было проанализировать и добавить что-нибудь новое' ) }}</div>
</div>
<input class="btn btn-primary" type="submit" value="{{ __( 'Сохранить' ) }}"/>
<a class="btn btn-danger" href="{{ url( '/inventories' ) }}">{{ __( 'Отмена' ) }}</a>
</form>
@if ( $inventory->id && ( $inventory->prices->count( ) > 1 ) )
<h2>{{ __( 'История изменения цены' ) }}</h2>
<table class="table"><thead>
	<th class="col">{{ __( 'Цена' ) }}</th>
	<th class="col">{{ __( 'Дата начала периода действия' ) }}</th>
</thead>
@foreach( $inventory->prices as $price )
<tr><td>{{ $price->price }}</td><td>{{ $price->created_at }}</td></tr>
@endforeach
</table>
@endif
@if ( $orders && $orders->isNotEmpty( ) )
<h2>{{ __( 'Заявки' ) }}</h2>
<x-lists.orders :paginator="$orders"/>
@endif