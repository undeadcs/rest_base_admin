<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $inventory->id ) {{ __( 'Изменение данных инвентаря' ) }} @else {{ __( 'Добавление инвентаря' ) }} @endif</h1>
	<x-errors/>
	<x-forms.inventory :inventory="$inventory"/>
</div></x-layout>