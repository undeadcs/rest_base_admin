<x-layout><div class="container-fluid">
	<x-top-nav :items="$top_nav_items"/>
	<h1>{{ __( 'Клиенты' ) }}</h1>
	<div class="mb-3"><a class="btn btn-success" href="{{ url( '/customers/add' ) }}">{{ __( 'Добавить' ) }}</a></div>
	<x-lists.customers :paginator="$paginator"/>
</div></x-layout>