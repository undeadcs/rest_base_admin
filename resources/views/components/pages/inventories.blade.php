<x-layout>
	<x-top-nav :items="$top_nav_items"/>
	<div class="mb-3"><a class="btn btn-success" href="{{ url( '/inventories/add' ) }}">{{ __( 'Добавить' ) }}</a></div>
	<x-lists.inventories :paginator="$paginator"/>
</x-layout>