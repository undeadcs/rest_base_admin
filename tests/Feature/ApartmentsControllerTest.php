<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Apartment;
use App\Repositories\ApartmentRepository;
use Mockery\MockInterface;
use App\Models\ApartmentPrice;
use Mockery\Matcher\Closure;

class ApartmentsControllerTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_add_failed( ) : void {
		$apartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $apartment->title,
			'number'	=> $apartment->number,
			'type'		=> $apartment->type->value,
			'capacity'	=> $apartment->capacity,
			'price'		=> $this->faker->randomFloat( ),
			'comment'	=> $apartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment ) {
			$mock->shouldReceive( 'Add' )
				->with( $apartment->title, $apartment->type, $apartment->number, $apartment->capacity, $apartment->comment )
				->once( )
				->andReturn( null );
		} ) );
		
		$url = '/apartments/add';
		$this->from( $url )->post( '/apartments', $data )->assertRedirect( $url );
	}
	
	public function test_price_add_failed( ) : void {
		$apartment = Apartment::factory( )->create( );
		$price = $this->faker->randomFloat( );
		$data = [
			'title'		=> $apartment->title,
			'number'	=> $apartment->number,
			'type'		=> $apartment->type->value,
			'capacity'	=> $apartment->capacity,
			'price'		=> $price,
			'comment'	=> $apartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $price ) {
			$mock->shouldReceive( 'Add' )
				->with( $apartment->title, $apartment->type, $apartment->number, $apartment->capacity, $apartment->comment )
				->once( )
				->andReturn( $apartment );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $apartment, $price )
				->once( )
				->andReturn( null );
		} ) );
		
		$url = '/apartments/add';
		$this->from( $url )->post( '/apartments', $data )->assertRedirect( $url );
	}
	
	public function test_update_failed( ) : void {
		$apartment = Apartment::factory( )->create( );
		$updateApartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $updateApartment->title,
			'number'	=> $updateApartment->number,
			'type'		=> $apartment->type->value,
			'capacity'	=> $updateApartment->capacity,
			'price'		=> $this->faker->randomFloat( ),
			'comment'	=> $updateApartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $updateApartment ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->ApartmentArgument( $apartment ), $updateApartment->title, $updateApartment->type, $updateApartment->number,
					$updateApartment->capacity, $updateApartment->comment
				)
				->once( )
				->andReturn( false );
		} ) );
		
		$url = '/apartments/'.$apartment->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	protected function ApartmentArgument( Apartment $apartment ) : Closure {
		return \Mockery::on( function( $value ) use( $apartment ) {
			// в фабрике id идет в конце из-за этого проваливается прямое сравнение объектов
			$this->assertEquals( $value->id, $apartment->id );
			$this->assertEquals( $value->title, $apartment->title );
			$this->assertEquals( $value->number, $apartment->number );
			$this->assertEquals( $value->capacity, $apartment->capacity );
			$this->assertEquals( $value->comment, $apartment->comment );
			
			return true;
		} );
	}
	
	public function test_update_price_add_failed( ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$apartment = Apartment::factory( )->has( ApartmentPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateApartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $updateApartment->title,
			'number'	=> $updateApartment->number,
			'type'		=> $updateApartment->type->value,
			'capacity'	=> $updateApartment->capacity,
			'price'		=> $newPrice,
			'comment'	=> $updateApartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $updateApartment, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->ApartmentArgument( $apartment ), $updateApartment->title, $updateApartment->type, $updateApartment->number,
					$updateApartment->capacity, $updateApartment->comment
				)
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->ApartmentArgument( $apartment ), $newPrice )
				->once( )
				->andReturn( null );
		} ) );
		
		$url = '/apartments/'.$apartment->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	public function test_update_price_same_not_called( ) : void {
		$price = 100.0;
		$apartment = Apartment::factory( )->has( ApartmentPrice::factory( )->state( [ 'price' => $price ] ), 'prices' )->create( );
		$updateApartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $updateApartment->title,
			'number'	=> $updateApartment->number,
			'type'		=> $updateApartment->type->value,
			'capacity'	=> $updateApartment->capacity,
			'price'		=> $price,
			'comment'	=> $updateApartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $updateApartment, $price ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->ApartmentArgument( $apartment ), $updateApartment->title, $updateApartment->type, $updateApartment->number,
					$updateApartment->capacity, $updateApartment->comment
				)
				->once( )
				->andReturn( true );
			$mock->shouldNotReceive( 'PriceAdd' );
		} ) );
		
		$url = '/apartments/'.$apartment->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/apartments' );
	}
	
	public function test_update_price_without_current_price( ) : void {
		$price = 100.0;
		$apartment = Apartment::factory( )->create( );
		$updateApartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $updateApartment->title,
			'number'	=> $updateApartment->number,
			'type'		=> $updateApartment->type->value,
			'capacity'	=> $updateApartment->capacity,
			'price'		=> $price,
			'comment'	=> $updateApartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $updateApartment, $price ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->ApartmentArgument( $apartment ), $updateApartment->title, $updateApartment->type, $updateApartment->number,
					$updateApartment->capacity, $updateApartment->comment
				)
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->ApartmentArgument( $apartment ), $price )
				->once( )
				->andReturn( ApartmentPrice::factory( )->state( [ 'apartment_id' => $apartment ] )->create( ) );
		} ) );
		
		$url = '/apartments/'.$apartment->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/apartments' );
	}
	
	public function test_update_price_add_success( ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$apartment = Apartment::factory( )->has( ApartmentPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateApartment = Apartment::factory( )->make( );
		$data = [
			'title'		=> $updateApartment->title,
			'number'	=> $updateApartment->number,
			'type'		=> $updateApartment->type->value,
			'capacity'	=> $updateApartment->capacity,
			'price'		=> $newPrice,
			'comment'	=> $updateApartment->comment
		];
		
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment, $updateApartment, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->ApartmentArgument( $apartment ), $updateApartment->title, $updateApartment->type, $updateApartment->number,
					$updateApartment->capacity, $updateApartment->comment
				)
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->ApartmentArgument( $apartment ), $newPrice )
				->once( )
				->andReturn( ApartmentPrice::factory( )->state( [ 'apartment_id' => $apartment ] )->create( ) );
		} ) );
		
		$url = '/apartments/'.$apartment->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/apartments' );
	}
}
