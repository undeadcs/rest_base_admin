<x-layout><div class="container-fluid">
	<x-top-nav :items="$top_nav_items"/>
	<h1>{{ __( 'Инвентарь' ) }}</h1>
	<x-tables.entity-instances :instances="$inventories" :columns="$columns" :base-url="$baseUrl" :link-field-name="$linkFieldName"
		:edit-field-name="$editFieldName" :new-entity-url="$newEntityUrl" :customs="$customs"/>
</div></x-layout>