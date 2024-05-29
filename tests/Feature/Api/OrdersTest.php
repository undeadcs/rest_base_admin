<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;

class OrdersTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	protected function CreateOrders( int $totalCount ) : Collection {
		return Order::factory( )->count( $totalCount )->create( )->sort( function( Order $l, Order $r ) {
			if ( $l->updated_at != $r->updated_at ) {
				return $l->updated_at > $r->updated_at ? -1 : 1;
			}
			if ( $l->created_at != $r->created_at ) {
				return $l->created_at > $r->created_at ? -1 : 1;
			}
			
			return ( $l->id == $r->id ) ? 0 : ( $l->id > $r->id ? -1 : 1 );
		} );
	}
	
	public function test_listing_first_page( ) : void {
		$totalCount = 30;
		$orders = $this->CreateOrders( $totalCount );
		
		$this->getJson( '/api/orders' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $orders->slice( 0, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_listing_second_page( ) : void {
		$totalCount = 30;
		$orders = $this->CreateOrders( $totalCount );
		
		$this->getJson( '/api/orders?page=2' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $orders->slice( 25, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_instance( ) : void {
		$order = Order::factory( )->create( );
		
		$this->getJson( '/api/orders/'.$order->id )
			->assertStatus( 200 )
			->assertJson( $order->toArray( ) );
	}
	
	public function test_instance_not_found( ) : void {
		$this->getJson( '/api/orders/1' )->assertStatus( 404 );
	}
	
	public function test_payments( ) : void {
		$order = Order::factory( )->hasPayments( 10 )->create( );
		
		$this->getJson( '/api/orders/'.$order->id.'/payments' )
			->assertStatus( 200 )
			->assertJson( $order->payments->toArray( ) );
	}
	
	public function test_inventories( ) : void {
		$order = Order::factory( )
			->hasAttached(
				Inventory::factory( )->count( 3 ),
				fn( ) => [ 'comment' => $this->faker->text( ) ]
			)
			->create( );
		
		$data = [ ];
		
		foreach( $order->inventories as $inventory ) {
			$row = $inventory->toArray( );
			$row[ 'pivot' ] = [
				'id' => $inventory->pivot->id,
				'comment' => $inventory->pivot->comment
			];
			$data[ ] = $row;
		}
		
		$this->getJson( '/api/orders/'.$order->id.'/inventories' )
			->assertStatus( 200 )
			->assertJson( $data );
	}
}
