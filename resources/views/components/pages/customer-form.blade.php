<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $customer->id ) {{ __( 'Изменение данных клиента' ) }} @else {{ __( 'Добавление клиента' ) }} @endif</h1>
	<x-errors/>
	@if ( $customer->id )
	<x-forms.customer :customer="$customer" :orders="$orders"/>
	@else
	<x-forms.customer :customer="$customer"/>
	@endif
</div></x-layout>