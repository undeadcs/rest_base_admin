<form class="mb-3" method="post" action="{{ url( $apartment->id ? '/apartments/'.$apartment->id : '/apartments' ) }}" autocomplete="off">
@csrf
@if ( $apartment->id )
@method( 'PUT' )
<input type="hidden" name="id" value="{{ $apartment->id }}"/>
@endif
<div class="mb-3">
	<label for="title">{{ __( 'Наименование' ) }}</label>
	<input id="title" class="form-control" type="text" name="title" value="{{ $apartment->title ?? old( 'title' ) }}" autofocus="autofocus"/>
	<div class="form-text">{{ __( 'для показа в календаре и заявках. стоит как-то систематически их называть, чтобы исключить путаницу' ) }}</div>
</div>
<div class="mb-3">
	<label for="type" class="form-label">{{ __( 'Тип' ) }}</label>
	<select id="type" class="form-select" name="type">
	@foreach( $types as $value => $title )
	<option value="{{ $value }}"@if ( $apartment->type->value == $value ) selected="selected" @endif>{{ $title }}</option>
	@endforeach
	</select>
</div>
<div class="mb-3">
	<label for="number">{{ __( 'Номер' ) }}</label>
	<input id="number" class="form-control" type="text" name="number" value="{{ $apartment->number ?? old( 'number' ) }}"/>
	<div class="form-text">{{ __( 'уникальный номер среди всех. например: 15' ) }}</div>
</div>
<div class="mb-3">
	<label for="capacity">{{ __( 'Вместимость' ) }}</label>
	<input id="capacity" class="form-control" type="text" name="capacity" value="{{ $apartment->capacity ?? old( 'capacity' ) }}"/>
	<div class="form-text">{{ __( 'кол-во спальных мест. позволит показывать подсказку при составлении заявок на несколько человек' ) }}</div>
</div>
<div class="mb-3">
	<label for="price">{{ __( 'Цена' ) }}</label>
	<input id="price" class="form-control" type="text" name="price" value="{{ $apartment->currentPrice ? $apartment->currentPrice->price : old( 'price' ) }}"/>
	<div class="form-text">{{ __( 'цена в рублях за сутки. будет использоваться при создании платежей и подсказок. например: 555.55' ) }}</div>
</div>
<div class="mb-3">
	<label for="comment">{{ __( 'Комментарий' ) }}</label>
	<textarea id="comment" class="form-control" rows="4" name="comment">{{ $apartment->comment ?? old( 'comment' ) }}</textarea>
	<div class="form-text">{{ __( 'Сюда стоит записывать всякие нюансы по части домика (например: меняли матрас 2 года назад :)). Ограничений нет. Желательно разные категории информации разделять, чтобы в дальнейшем их можно было проанализировать и добавить что-нибудь новое' ) }}</div>
</div>
<input class="btn btn-primary" type="submit" value="{{ __( 'Сохранить' ) }}"/>
<a class="btn btn-danger" href="{{ url( '/apartments' ) }}">{{ __( 'Отмена' ) }}</a>
</form>
@if ( $apartment->id && ( $apartment->prices->count( ) > 1 ) )
<h2>{{ __( 'История изменения цены' ) }}</h2>
<table class="table"><thead><tr>
	<th class="col">{{ __( 'Цена' ) }}</th>
	<th class="col">{{ __( 'Дата начала периода действия' ) }}</th>
</tr></thead><tbody>
@foreach( $apartment->prices as $price )
<tr><td>{{ $price->price }}</td><td>{{ $price->created_at }}</td></tr>
@endforeach
</tbody></table>
@endif
@if ( $orders && $orders->isNotEmpty( ) )
<h2>{{ __( 'Заявки' ) }}</h2>
<x-lists.orders :paginator="$orders" :hide-apartment="true"/>
@endif