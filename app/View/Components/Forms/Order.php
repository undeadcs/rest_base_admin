<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Order as OrderModel;
use Illuminate\Database\Eloquent\Collection;

class Order extends Component {
	public OrderModel $order;
	public Collection $apartments;
	public array $statuses;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( OrderModel $order, Collection $apartments, array $statuses ) {
		$this->order = $order;
		$this->apartments = $apartments;
		$this->statuses = $statuses;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.order' );
	}
}
