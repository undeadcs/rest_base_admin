<x-layout><div class="container-fluid">
	<x-top-nav :items="$top_nav_items"/>
	<div class="mb-3"><a class="btn btn-success" href="{{ url( '/apartments/add' ) }}">{{ __( 'Добавить' ) }}</a></div>
	<x-lists.apartments :paginator="$paginator"/>
</div></x-layout>