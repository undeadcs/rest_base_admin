<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Apartment as ApartmentModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Apartment extends Component {
	public ApartmentModel $apartment;
	public array $types;
	public ?LengthAwarePaginator $orders;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( ApartmentModel $apartment, array $types, ?LengthAwarePaginator $orders = null ) {
		$this->apartment = $apartment;
		$this->types = $types;
		$this->orders = $orders;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.apartment' );
	}
}
