<x-slot:styles>
	<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.css' ) }}"/>
	<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.theme.css' ) }}"/>
	<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.structure.css' ) }}"/>
</x-slot>
<x-slot:scripts>
	<script src="{{ asset( 'js/jquery-3.7.1.min.js' ) }}"></script>
	<script src="{{ asset( 'js/jquery-ui.js' ) }}"></script>
	<script src="{{ asset( 'js/jquery-datepicker-localization.js' ) }}"></script>
</x-slot>
<form id="week-from-form" class="row mb-3" action="/" method="get" autocomplete="off"><div class="col-2"><div class="input-group">
	<div class="input-group-text">{{ __( 'с' ) }}</div>
	<input id="from" class="form-control" type="text" name="f" value="{{ $days->first( )->format( 'd.m.Y' ) }}"/>
</div></div><div class="col-2"><div class="input-group">
	<div class="input-group-text">{{ __( 'по' ) }}</div>
	<input id="to" class="form-control" type="text" name="t" value="{{ $days->last( )->format( 'd.m.Y' ) }}"/>
</div></div><div class="col">
	<input class="btn btn-primary" type="submit" value="{{ __( 'применить' ) }}"/>
	<a class="btn btn-secondary" href="/">{{ __( 'сброс' ) }}</a>
</div></form>
<div class=""><table class="table align-middle"><thead><tr>
	<th class="col-1" style="min-width: 150px;">&nbsp;</th>
	@foreach( $days as $day )
	<th>{{ $day->format( 'd.m.Y' ) }}</th>
	@endforeach
</tr></thead><tbody>
@foreach( $apartments as $apartment )
<tr>
	<td class="table-secondary"><a href="{{ url( '/apartments' ) }}/{{ $apartment->id }}">{{ $apartment->title }}</a></td>
	@foreach( $days as $day )
	@if ( $HasOrdersAtDay( $apartment, $day ) )
		<td>
		@foreach( $orderIndex[ $apartment->id ][ $day->format( 'Y-m-d' ) ] as $order )
		<a href="{{ url( '/orders' ) }}/{{ $order->id }}">{{ __( 'заявка' ) }}</a><br/>
		<a href="{{ url( '/customers' ) }}/{{ $order->customer->id }}">{{ $ShortCustomerName( $order->customer->name ) }}</a><br/>
		{{ $order->customer->phone_number }}
		@if ( $loop->count > 1 )
			<br/>
			@if ( $loop->first ) {{ __( 'по' ).' '.$order->to->format( 'H:i' ) }} @else {{ __( 'с' ).' '.$order->from->format( 'H:i' ) }} @endif
		@endif
		@if ( !$loop->last ) <br/> @endif
		@if ( $loop->last && ( $nextOrderTime = $TimeLeftForOrder( $order, $day, $apartment ) ) )
			<br/>
			<a class="btn btn-primary" href="{{ url( '/orders' ) }}/add?from={{ $day->format( 'Ymd' ).$nextOrderTime->format( 'Hi' ) }}&amp;apartment_id={{ $apartment->id }}">{{ __( 'Свободно c' ).' '.$nextOrderTime->format( 'H:i' ) }}</a>
		@endif
		@endforeach
		</td>
	@else
		<td class="table-success"><a class="btn btn-primary" href="{{ url( '/orders' ) }}/add?from={{ $day->format( 'Ymd' ) }}&amp;apartment_id={{ $apartment->id }}">{{ __( 'Свободно' ) }}</a></td>
	@endif
	@endforeach
</tr>
@endforeach
</tbody></table></div>
<script>
$( '#from' ).datepicker( {
	dateFormat: 'dd.mm.yy',
	onSelect: ( text, obj ) => {
		let dateFrom = $.datepicker.parseDate( 'dd.mm.yy', text );
		let dateTo = $.datepicker.parseDate( 'dd.mm.yy', $( '#to' ).val( ) );
		
		if ( dateFrom > dateTo ) {
			$( '#from' ).datepicker( 'setDate', dateTo );
			$( '#to' ).datepicker( 'setDate', dateFrom );
		}
	}
} );

$( '#to' ).datepicker( {
	dateFormat: 'dd.mm.yy',
	onSelect: ( text, obj ) => {
		let dateFrom = $.datepicker.parseDate( 'dd.mm.yy', $( '#from' ).val( ) );
		let dateTo = $.datepicker.parseDate( 'dd.mm.yy', text );
		
		if ( dateFrom > dateTo ) {
			$( '#from' ).datepicker( 'setDate', dateTo );
			$( '#to' ).datepicker( 'setDate', dateFrom );
		}
	}
} );
</script>