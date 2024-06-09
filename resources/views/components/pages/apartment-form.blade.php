<x-layout>
	<x-top-nav :items="$top_nav_items"/>
	<h1>@if ( $apartment->id ) {{ __( 'Изменение данных апартаментов' ) }} @else {{ __( 'Добавление апартаментов' ) }} @endif</h1>
	<x-errors/>
	@if ( $apartment->id )
	<x-forms.apartment :apartment="$apartment" :types="$types" :orders="$orders"/>
	@else
	<x-forms.apartment :apartment="$apartment" :types="$types"/>
	@endif
</x-layout>