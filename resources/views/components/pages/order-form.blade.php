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
	<h1>@if ( $order->id ) {{ __( 'Изменение данных заявки' ) }} @else {{ __( 'Добавление заявки' ) }} @endif</h1>
	<x-errors/>
	@if ( $order->id )
	<x-forms.order :order="$order" :apartments="$apartments" :statuses="$statuses" :inventories="$inventories"/>
	@else
	<x-forms.order :order="$order" :apartments="$apartments"/>
	@endif
</x-layout>