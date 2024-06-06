<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Inventory as InventoryModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Inventory extends Component {
	public InventoryModel $inventory;
	public ?LengthAwarePaginator $orders;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( InventoryModel $inventory, ?LengthAwarePaginator $orders = null ) {
		$this->inventory = $inventory;
		$this->orders = $orders;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.inventory' );
	}
}
