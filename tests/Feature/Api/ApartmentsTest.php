<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Apartment;

class ApartmentsTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_listing( ) : void {
		$totalCount = 30;
		$apartments = Apartment::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/apartments' )
			->assertStatus( 200 )
			->assertJson( $apartments->sortByDesc( 'number' )->values( )->toArray( ) );
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
}
