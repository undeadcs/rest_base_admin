<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Inventory;

class InventoriesTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_listing( ) : void {
		$inventories = Inventory::factory( )->count( 10 )->create( );
		
		$this->getJson( '/api/inventories' )
			->assertStatus( 200 )
			->assertJson( $inventories->sortBy( 'title' )->values( )->toArray( ) );
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
