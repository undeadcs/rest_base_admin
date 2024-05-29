<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Order;

class OrdersController extends Controller {
	protected OrderRepository $orders;
	
	public function __construct( OrderRepository $orders ) {
		$this->orders = $orders;
	}
	
	public function index( Request $request ) : JsonResponse {
		$paginator = $this->orders->List( ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
	
	public function instance( Order $order ) : JsonResponse {
		return response( )->json( $order->toArray( ) );
	}
	
	public function inventories( Order $order ) : JsonResponse {
		$data = [ ];
		
		foreach( $order->inventories as $inventory ) {
			$row = $inventory->toArray( );
			$row[ 'pivot' ] = [
				'id' => $inventory->pivot->id,
				'comment' => $inventory->pivot->comment
			];
			$data[ ] = $row;
		}
		
		return response( )->json( $data );
	}
}
