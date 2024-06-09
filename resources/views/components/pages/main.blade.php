<x-layout>
	<x-top-nav :items="$top_nav_items"/>
	<x-tables.schedule :apartments="$apartments" :days="$days" :order-index="$orderIndex"/>
</x-layout>