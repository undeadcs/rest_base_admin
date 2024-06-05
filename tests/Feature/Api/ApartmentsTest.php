<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Apartment;
use App\Models\Order;

class ApartmentsTest extends TestCase {
	use RefreshDatabase, WithFaker, OrderPeriodUtils;
	
	public function test_listing_first_page( ) : void {
		$totalCount = 30;
		$apartments = Apartment::factory( )->hasPrices( 1 )->count( $totalCount )->create( );
		
		$this->getJson( '/api/apartments' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $apartments->sortByDesc( 'id' )->slice( 0, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_listing_second_page( ) : void {
		$totalCount = 30;
		$apartments = Apartment::factory( )->hasPrices( 1 )->count( $totalCount )->create( );
		
		$this->getJson( '/api/apartments/?page=2' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' =>  $apartments->sortByDesc( 'id' )->slice( 25, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_instance( ) : void {
		$apartment = Apartment::factory( )->create( );
		
		$this->getJson( '/api/apartments/'.$apartment->id )
			->assertStatus( 200 )
			->assertJson( $apartment->toArray( ) );
	}
	
	public function test_instance_not_found( ) : void {
		$this->getJson( '/api/apartments/1' )->assertStatus( 404 );
	}
	
	public function test_prices( ) : void {
		$apartment = Apartment::factory( )->hasPrices( 3 )->create( );
		
		$this->getjson( '/api/apartments/'.$apartment->id.'/prices' )
			->assertStatus( 200 )
			->assertJson( $apartment->prices->toArray( ) );
	}
	
	public function test_prices_instance_not_found( ) : void {
		$this->getJson( '/api/apartments/1/prices' )->assertStatus( 404 );
	}
	
	public function test_orders_first_page( ) : void {
		$apartment = Apartment::factory( )->hasOrders( 30 )->create( );
		$totalCount = $apartment->orders->count( );
		$data = $apartment->orders->sortByDesc( 'id' )->slice( 0, 25 )->values( )->toArray( );
		
		$this->getjson( '/api/apartments/'.$apartment->id.'/orders' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $data ] );
	}
	
	public function test_orders_second_page( ) : void {
		$apartment = Apartment::factory( )->hasOrders( 30 )->create( );
		$totalCount = $apartment->orders->count( );
		$data = $apartment->orders->sortByDesc( 'id' )->slice( 25, 25 )->values( )->toArray( );
		
		$this->getjson( '/api/apartments/'.$apartment->id.'/orders?page=2' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $data ] );
	}
	
	public function test_orders_by_period_first_page( ) : void {
		$apartment = Apartment::factory( )->hasPrices( 1 )->create( );
		$info = $this->SeedWeekOrders( new \DateTime, Order::factory( )->for( $apartment ) );
		
		$expectedOrders = $info->endInPeriodOrders->concat( $info->beginInPeriodOrders )
			->concat( $info->insidePeriodOrders )
			->concat( $info->coverPeriodOrders )
			->sortBy( 'id' );
		
		$totalCount = $expectedOrders->count( );
		$expectedOrders = $expectedOrders->slice( 0, 25 );
		
		$this->getJson(
				'/api/apartments/'.$apartment->id.'/orders-by-period?from='.$info->periodFrom->format( 'YmdHis' ).'&to='.$info->periodTo->format( 'YmdHis' )
			)
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $expectedOrders->values( )->toArray( ) ] );
	}
	
	public function test_orders_by_period_second_page( ) : void {
		$apartment = Apartment::factory( )->hasPrices( 1 )->create( );
		$info = $this->SeedWeekOrders( new \DateTime, Order::factory( )->for( $apartment ) );
		
		$expectedOrders = $info->endInPeriodOrders->concat( $info->beginInPeriodOrders )
			->concat( $info->insidePeriodOrders )
			->concat( $info->coverPeriodOrders )
			->sortBy( 'id' );
		
		$totalCount = $expectedOrders->count( );
		$expectedOrders = $expectedOrders->slice( 25, 25 );
		
		$this->getJson(
				'/api/apartments/'.$apartment->id.'/orders-by-period?from='.$info->periodFrom->format( 'YmdHis' ).'&to='.$info->periodTo->format( 'YmdHis' ).'&page=2'
			)
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $expectedOrders->values( )->toArray( ) ] );
	}
}
