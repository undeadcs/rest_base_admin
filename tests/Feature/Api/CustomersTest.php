<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Sequence;
use PHPUnit\Framework\Attributes\DataProvider;

class CustomersTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_listing_first_page( ) : void {
		$totalCount = 60;
		$customers = Customer::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/customers' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $customers->sortBy( 'name' )->slice( 0, 25 )->values( )->toArray( ) ] );
	}
	
	public function test_listing_second_page( ) : void {
		$totalCount = 60;
		$customers = Customer::factory( )->count( $totalCount )->create( );
		
		$this->getJson( '/api/customers/?page=2' )
			->assertStatus( 200 )
			->assertJson( [ 'totalCount' => $totalCount, 'data' => $customers->sortBy( 'name' )->slice( 25, 25 )->values( )->toArray( ) ] );
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
	
	public static function searchByPhoneNumberPartProvider( ) : array {
		$sequence = new Sequence( [ 'phone_number' => '1112223334242' ], [ 'phone_number' => '2224445554242' ], [ 'phone_number' => '5556667774242' ] );
		
		return [ [
			$sequence, '/222/', '222'
		], [
			$sequence, '/4242/', '4242'
		] ];
	}
	
	#[ DataProvider( 'searchByPhoneNumberPartProvider' ) ]
	public function test_search_by_phone_number_part( Sequence $sequence, string $pattern, string $searchPart ) : void {
		$customers = Customer::factory( )
			->state( $sequence )
			->count( 3 )
			->create( );
		
		$data = [ ];
		$customers->sortBy( 'name' )->each( function( Customer $customer ) use( &$data, $pattern ) {
			if ( preg_match( $pattern, $customer->phone_number ) ) {
				$data[ ] = [
					'label' => $customer->phone_number.' ('.$customer->name.', '.$customer->car_number.')',
					'value' => $customer->id,
					'customer' => $customer->toArray( )
				];
			}
		} );
		
		$this->getJson( '/api/customers/find-for-order/?term='.urlencode( $searchPart ) )
			->assertStatus( 200 )
			->assertJson( $data );
	}
	
	public static function searchByCarNumberPartProvider( ) : array {
		$sequence = new Sequence( [ 'car_number' => 'А111АА4242' ], [ 'car_number' => '2224445554242' ], [ 'car_number' => '5556667774242' ] );
		
		return [ [
			$sequence, '/АА/', 'АА'
		], [
			$sequence, '/4242/', '4242'
		] ];
	}
	
	#[ DataProvider( 'searchByCarNumberPartProvider' ) ]
	public function test_search_by_car_number_part( Sequence $sequence, string $pattern, string $searchPart ) : void {
		$customers = Customer::factory( )
			->state( $sequence )
			->count( 3 )
			->create( );
		
		$data = [ ];
		$customers->sortBy( 'name' )->each( function( Customer $customer ) use( &$data, $pattern ) {
			if ( preg_match( $pattern, $customer->car_number ) ) {
				$data[ ] = [
					'label' => $customer->car_number.' ('.$customer->name.', '.$customer->phone_number.')',
					'value' => $customer->id,
					'customer' => $customer->toArray( )
				];
			}
		} );
		
		$this->getJson( '/api/customers/find-for-order/?term='.urlencode( $searchPart ) )
			->assertStatus( 200 )
			->assertJson( $data );
	}
	
	public static function searchByNamePartProvider( ) : array {
		$sequence = new Sequence( [ 'name' => 'Петров Алексей' ], [ 'name' => 'Пупкин Александр' ], [ 'name' => 'Алексеев Петр' ] );
		
		return [ [
			$sequence, '/Петр/', 'Петр' // 2 совпадения
		], [
			$sequence, '/Алекс/', 'Алекс', // 3 совпадения
		], [
			$sequence, '/Александр/', 'Александр' // 1 совпадение
		] ];
	}
	
	#[ DataProvider( 'searchByNamePartProvider' ) ]
	public function test_search_by_name_part( Sequence $sequence, string $pattern, string $searchPart ) : void {
		$customers = Customer::factory( )
			->state( $sequence )
			->count( 3 )
			->create( );
		
		$data = [ ];
		$customers->sortBy( 'name' )->each( function( Customer $customer ) use( &$data, $pattern ) {
			if ( preg_match( $pattern, $customer->name ) ) {
				$data[ ] = [
					'label' => $customer->name.' ('.$customer->phone_number.', '.$customer->car_number.')',
					'value' => $customer->id,
					'customer' => $customer->toArray( )
				];
			}
		} );
		
		$this->getJson( '/api/customers/find-for-order/?term='.urlencode( $searchPart ) )
			->assertStatus( 200 )
			->assertJson( $data );
	}
}
