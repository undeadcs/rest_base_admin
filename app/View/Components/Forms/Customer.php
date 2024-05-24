<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Customer as CustomerModel;

class Customer extends Component {
	public CustomerModel $customer;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( CustomerModel $customer ) {
		$this->customer = $customer;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.customer' );
	}
}
