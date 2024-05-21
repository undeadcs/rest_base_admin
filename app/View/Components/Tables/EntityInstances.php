<?php

namespace App\View\Components\Tables;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EntityInstances extends Component {
	public Collection $instances;
	public array $columns;
	public string $baseUrl;
	public string $linkFieldName;
	public string $editFieldName;
	public string $newEntityUrl;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct(
		Collection $instances, array $columns, string $baseUrl, string $linkFieldName, string $editFieldName, string $newEntityUrl
	) {
		$this->instances = $instances;
		$this->columns = $columns;
		$this->baseUrl = $baseUrl;
		$this->linkFieldName = $linkFieldName;
		$this->editFieldName = $editFieldName;
		$this->newEntityUrl = $newEntityUrl;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.tables.entity-instances' );
	}
	
	public function editLink( Model $instance ) : string {
		return url( $this->baseUrl.'/'.$instance->{ $this->editFieldName } );
	}
}
