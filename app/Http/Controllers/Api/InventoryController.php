<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Inventory;

class InventoryController extends Controller {
	protected InventoryRepository $inventories;
	
	public function __construct( InventoryRepository $inventories ) {
		$this->inventories = $inventories;
	}
	
	public function index( ) : JsonResponse {
		return response( )->json( $this->inventories->List( )->toArray( ) );
	}
	
	public function instance( Inventory $inventory ) : JsonResponse {
		return response( )->json( $inventory->toArray( ) );
	}
	
	public function prices( Inventory $inventory ) : JsonResponse {
		return response( )->json( $inventory->prices->toArray( ) );
	}
}
