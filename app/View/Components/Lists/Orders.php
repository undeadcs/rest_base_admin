<?php

namespace App\View\Components\Lists;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Orders extends Component {
	public LengthAwarePaginator $paginator;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( LengthAwarePaginator $paginator ) {
		$this->paginator = $paginator;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.lists.orders' );
	}
}
