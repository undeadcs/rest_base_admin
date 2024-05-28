<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Apartment as ApartmentModel;

class Apartment extends Component {
	public ApartmentModel $apartment;
	public array $types;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( ApartmentModel $apartment, array $types ) {
		$this->apartment = $apartment;
		$this->types = $types;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.apartment' );
	}
}
