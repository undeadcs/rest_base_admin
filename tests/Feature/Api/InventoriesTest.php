<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Inventory;

class InventoriesTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_listing_first_page( ) : void {
		$totalCount = 30;
		$inventories = Inventory::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/inventories' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $inventories->sortBy( 'title' )->slice( 0, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_listing_second_page( ) : void {
		$totalCount = 30;
		$inventories = Inventory::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/inventories/?page=2' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $inventories->sortBy( 'title' )->slice( 25, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_instance( ) : void {
		$inventory = Inventory::factory( )->create( );
		
		$this->getJson( '/api/inventories/'.$inventory->id )
			->assertStatus( 200 )
			->assertJson( $inventory->toArray( ) );
	}
	
	public function test_instance_not_found( ) : void {
		$this->getJson( '/api/inventories/1' )->assertStatus( 404 );
	}
	
	public function test_prices( ) : void {
		$inventory = Inventory::factory( )->hasPrices( 3 )->create( );
		
		$this->getjson( '/api/inventories/'.$inventory->id.'/prices' )
			->assertStatus( 200 )
			->assertJson( $inventory->prices->toArray( ) );
	}
	
	public function test_prices_instance_not_found( ) : void {
		$this->getJson( '/api/inventories/1/prices' )->assertStatus( 404 );
	}
}
