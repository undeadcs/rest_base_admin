<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $customer->id ) {{ __( 'Изменение данных клиента' ) }} @else {{ __( 'Добавление клиента' ) }} @endif</h1>
	<x-errors/>
	<x-forms.customer :customer="$customer"/>
</div></x-layout>