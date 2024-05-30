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
	
	public function index( InventoryRepository $inventories ) : View {
		$columns = [
			( object ) [ 'fieldName' => 'title',	'title' => __( 'Наименование'	) ],
			( object ) [ 'fieldName' => 'price',	'title' => __( 'Цена'			) ]
		];
		
		return view( 'components.pages.'.TopPage::Inventories->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'inventories' => $inventories->List( ),
			'columns' => $columns,
			'baseUrl' => url( '/inventories' ),
			'linkFieldName' => 'title',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/inventories/add' ),
			'customs' => [
				'price' => fn( Inventory $inventory ) => $inventory->currentPrice ? $inventory->currentPrice->price : '-'
			]
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => new Inventory ] );
	}
	
	public function edit( Inventory $inventory ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => $inventory ] );
	}
}
