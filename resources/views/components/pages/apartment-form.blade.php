<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $apartment->id ) {{ __( 'Изменение данных апартаментов' ) }} @else {{ __( 'Добавление апартаментов' ) }} @endif</h1>
	<x-errors/>
	<x-forms.apartment :apartment="$apartment"/>
</div></x-layout>