<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;

class CustomersTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_listing_first_page( ) : void {
		$totalCount = 60;
		$customers = Customer::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/customers' )
			->assertStatus( 200 )
			->assertJson( $customers->sortBy( 'name' )->slice( 0, 25 )->values( )->toArray( ) );
	}
	
	public function test_listing_second_page( ) : void {
		$totalCount = 60;
		$customers = Customer::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/customers/?page=2' )
			->assertStatus( 200 )
			->assertJson( $customers->sortBy( 'name' )->slice( 25, 25 )->values( )->toArray( ) );
	}
	
	public function test_instance( ) : void {
		$customer = Customer::factory( )->create( );
		
		$this->getJson( '/api/customers/'.$customer->id )
			->assertStatus( 200 )
			->assertJson( $customer->toArray( ) );
	}
	
	public function test_instance_not_found( ) : void {
		$this->getJson( '/api/customers/1' )->assertStatus( 404 );
	}
}
