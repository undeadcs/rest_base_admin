<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Customer as CustomerModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Customer extends Component {
	public CustomerModel $customer;
	public ?LengthAwarePaginator $orders;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( CustomerModel $customer, ?LengthAwarePaginator $orders = null ) {
		$this->customer = $customer;
		$this->orders = $orders;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.customer' );
	}
}
