<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $inventory->id ) {{ __( 'Изменение данных инвентаря' ) }} @else {{ __( 'Добавление инвентаря' ) }} @endif</h1>
	<x-errors/>
	@if ( $inventory->id )
	<x-forms.inventory :inventory="$inventory" :orders="$orders"/>
	@else
	<x-forms.inventory :inventory="$inventory"/>
	@endif
</div></x-layout>