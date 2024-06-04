<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Inventory;
use App\Services\TopNavBar;

class InventoriesController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function index( Request $request, InventoryRepository $inventories ) : View {
		return view( 'components.pages.'.TopPage::Inventories->value, [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'paginator'		=> $inventories->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => new Inventory ] );
	}
	
	public function edit( Inventory $inventory ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => $inventory ] );
	}
}
