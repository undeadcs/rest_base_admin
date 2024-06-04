<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\InventoryRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoriesController extends Controller {
	protected InventoryRepository $inventories;
	
	public function __construct( InventoryRepository $inventories ) {
		$this->inventories = $inventories;
	}
	
	public function index( Request $request ) : JsonResponse {
		$paginator = $this->inventories->List( ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
	
	public function instance( Inventory $inventory ) : JsonResponse {
		return response( )->json( $inventory->toArray( ) );
	}
	
	public function prices( Inventory $inventory ) : JsonResponse {
		return response( )->json( $inventory->prices->toArray( ) );
	}
}
