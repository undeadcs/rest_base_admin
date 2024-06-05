<?php

namespace App\Http\Controllers;

use App\Repositories\InventoryRepository;
use App\Http\Requests\InventoryRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Inventory;

class InventoriesController extends Controller {
	protected InventoryRepository $inventories;
	
	public function __construct( InventoryRepository $inventories ) {
		$this->inventories = $inventories;
	}
	
	public function add( InventoryRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		$inventory = $this->inventories->Add( $input[ 'title' ], $input[ 'comment' ] ?? '' );
		if ( !$inventory ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		if ( !$this->inventories->PriceAdd( $inventory, ( float ) $input[ 'price' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение цены' ) ] );
		}
		
		return redirect( '/inventories' )->with( 'success', __( 'Инвентарь добавлен' ) );
	}
	
	public function update( Inventory $inventory, InventoryRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		if ( !$this->inventories->Update( $inventory, $input[ 'title' ], $input[ 'comment' ] ?? '' ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		
		$newPrice = ( float ) $input[ 'price' ];
		
		if ( !$inventory->currentPrice || ( $inventory->currentPrice->price != $newPrice ) ) {
			if ( !$this->inventories->PriceAdd( $inventory, $newPrice ) ) {
				return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение цены' ) ] );
			}
		}
		
		return redirect( '/inventories' )->with( 'success', __( 'Инвентарь сохранен' ) );
	}
}
