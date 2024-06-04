<x-layout><div class="container-fluid">
	<x-top-nav :items="$top_nav_items"/>
	<h1>{{ __( 'Инвентарь' ) }}</h1>
	<div class="mb-3"><a class="btn btn-success" href="{{ url( '/inventories/add' ) }}">{{ __( 'Добавить' ) }}</a></div>
	<x-lists.inventories :paginator="$paginator"/>
</div></x-layout>