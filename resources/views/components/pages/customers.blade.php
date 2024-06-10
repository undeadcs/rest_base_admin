<x-layout>
	<x-slot:styles>
		<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.css' ) }}"/>
		<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.theme.css' ) }}"/>
		<link rel="stylesheet" href="{{ asset( 'css/jquery-ui.structure.css' ) }}"/>
	</x-slot>
	<x-slot:scripts>
		<script src="{{ asset( 'js/jquery-3.7.1.min.js' ) }}"></script>
		<script src="{{ asset( 'js/jquery-ui.js' ) }}"></script>
	</x-slot>
	<x-top-nav :items="$top_nav_items"/>
	<div class="row mb-3">
		<div class="col-1"><a class="btn btn-success" href="{{ url( '/customers/add' ) }}">{{ __( 'Добавить' ) }}</a></div>
		<div class="col"><input id="find_customer" class="form-control" value="" placeholder="{{ __( 'Поиск' ) }}"/></div>
	</div>
	<x-lists.customers :paginator="$paginator"/>
<script>
$( '#find_customer' ).autocomplete( {
	minLength: 2,
	source: '{{ url( '/api/customers/find-for-order' ) }}',
	select: function( event, ui ) {
		let customer = ui.item.customer;
		
		window.location.href = '{{ url( '/customers' ) }}/' + customer.id;
		
		return false;
	},
	focus: function( event, ui ) { return false; },
	change: function( event, ui ) { return false; }
} );
</script>
</x-layout>