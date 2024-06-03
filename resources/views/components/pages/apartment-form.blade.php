<x-layout><div class="container">
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $apartment->id ) {{ __( 'Изменение данных апартаментов' ) }} @else {{ __( 'Добавление апартаментов' ) }} @endif</h1>
	<x-errors/>
	@if ( $apartment->id )
	<x-forms.apartment :apartment="$apartment" :types="$types" :orders="$orders" :current-page="$currentPage" :last-page="$lastPage"/>
	@else
	<x-forms.apartment :apartment="$apartment" :types="$types"/>
	@endif
</div></x-layout>