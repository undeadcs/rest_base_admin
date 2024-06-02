<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Storage;

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
	
	public function test_find_by_period( ) : void {
		$periodFrom = new \DateTime( '2024-06-02 00:00:00' );
		$periodTo = new \DateTime( '2024-06-08 00:00:00' );
		
		$pastPeriodTo = ( clone $periodFrom )->modify( '-1 day' );
		$pastPeriodFrom = ( clone $pastPeriodTo )->modify( '-1 week' );
		
		$futurePeriodFrom = ( clone $periodTo )->modify( '+1 day' );
		$futurePeriodTo = ( clone $futurePeriodFrom )->modify( '+1 week' );
		
		// старые заявки, до периода
		$pastOrders = Order::factory( )
			->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $pastPeriodFrom->format( 'Y-m-d H:i:s' ), $pastPeriodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $pastPeriodTo
			] )
			->create( );
		
		// будущие заявки после периода
		$futureOrders =  Order::factory( )
			->count( 10 )
			->sequence( fn( ) => [
				'from' => $futurePeriodFrom,
				'to' => $this->faker->dateTimeBetween( $futurePeriodFrom->format( 'Y-m-d H:i:s' ), $futurePeriodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые оканчиваются внутри периода
		$endInPeriodOrders = Order::factory( )
			->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $pastPeriodFrom->format( 'Y-m-d H:i:s' ), $pastPeriodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $this->faker->dateTimeBetween( $periodFrom->format( 'Y-m-d H:i:s' ), $periodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые начинаются внутри периода
		$beginInPeriodOrders = Order::factory( )
			->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $periodFrom->format( 'Y-m-d H:i:s' ), $periodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $this->faker->dateTimeBetween( $futurePeriodFrom->format( 'Y-m-d H:i:s' ), $futurePeriodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые входят в период
		$insidePeriodOrders = Order::factory( )
			->count( 10 )
			->sequence( function( Sequence $sequence ) use( $periodFrom, $periodTo ) {
				$t1 = $this->faker->dateTimeBetween( $periodFrom->format( 'Y-m-d H:i:s' ), $periodTo->format( 'Y-m-d H:i:s' ) );
				$t2 = $this->faker->dateTimeBetween( $periodFrom->format( 'Y-m-d H:i:s' ), $periodTo->format( 'Y-m-d H:i:s' ) );
				
				return ( $t1 < $t2 ) ? [ 'from' => $t1, 'to' => $t2 ] : [ 'from' => $t2, 'to' => $t1 ];
			} )
			->create( );
		
		// заявки, которые охватывают период
		$coverPeriodOrders = Order::factory( )
			->count( 10 )
			->sequence( function( Sequence $sequence ) use( $pastPeriodFrom, $pastPeriodTo, $futurePeriodFrom, $futurePeriodTo ) {
				$t1 = $this->faker->dateTimeBetween( $pastPeriodFrom->format( 'Y-m-d H:i:s' ), $pastPeriodTo->format( 'Y-m-d H:i:s' ) );
				$t2 = $this->faker->dateTimeBetween( $futurePeriodFrom->format( 'Y-m-d H:i:s' ), $futurePeriodTo->format( 'Y-m-d H:i:s' ) );
				
				return ( $t1 < $t2 ) ? [ 'from' => $t1, 'to' => $t2 ] : [ 'from' => $t2, 'to' => $t1 ];
			} )
			->create( );
		
		$expectedOrders = $endInPeriodOrders->concat( $beginInPeriodOrders )
			->concat( $insidePeriodOrders )
			->concat( $coverPeriodOrders )
			->sortBy( 'id' );
		
		$totalCount = $expectedOrders->count( );
		$expectedOrders = $expectedOrders->slice( 0, 25 );
		
		$this->getJson( '/api/orders/find-by-period?from='.$periodFrom->format( 'Ymd' ).'&to='.$periodTo->format( 'Ymd' ) )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $expectedOrders->values( )->toArray( ) ] );
	}
}
