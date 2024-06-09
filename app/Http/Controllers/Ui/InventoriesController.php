<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Inventory;
use App\Services\Navigation;

class InventoriesController extends Controller {
	protected Navigation $navigation;
	
	public function __construct( Navigation $navigation ) {
		$this->navigation = $navigation;
	}
	
	public function index( Request $request, InventoryRepository $inventories ) : View {
		return view( 'components.pages.'.TopPage::Inventories->value, [
			'top_nav_items'	=> $this->navigation->items( TopPage::Inventories ),
			'paginator'		=> $inventories->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->navigation->items( TopPage::Inventories ), 'inventory' => new Inventory ] );
	}
	
	public function edit( Inventory $inventory, Request $request, InventoryRepository $inventories ) : View {
		return view( 'components.pages.inventory-form', [
			'top_nav_items'	=> $this->navigation->items( TopPage::Inventories ),
			'inventory'		=> $inventory,
			'orders'		=> $inventories->ListOrdersWithApartment( $inventory, ( int ) $request->input( 'page' ) )
		] );
	}
}
