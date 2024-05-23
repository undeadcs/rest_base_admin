<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Inventory as InventoryModel;

class Inventory extends Component {
	public InventoryModel $inventory;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( InventoryModel $inventory ) {
		$this->inventory = $inventory;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.forms.inventory' );
	}
}
