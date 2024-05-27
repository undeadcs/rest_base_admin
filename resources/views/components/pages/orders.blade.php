<x-layout><div class="container-fluid">
	<x-top-nav :items="$top_nav_items"/>
	<h1>{{ __( 'Заявки' ) }}</h1>
	<x-tables.entity-instances :instances="$orders" :columns="$columns" :base-url="$baseUrl" :link-field-name="$linkFieldName"
		:edit-field-name="$editFieldName" :new-entity-url="$newEntityUrl" :current-page="$currentPage" :last-page="$lastPage" :customs="$customs"/>
</div></x-layout>