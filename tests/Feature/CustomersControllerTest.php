<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Mockery\MockInterface;
use Mockery\Matcher\Closure;

class CustomersControllerTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_add_failed( ) : void {
		$customer = Customer::factory( )->make( );
		$data = [
			'name'			=> $customer->name,
			'phone_number'	=> $customer->phone_number,
			'car_number'	=> $customer->car_number,
			'comment'		=> $customer->comment
		];
		
		$this->instance( CustomerRepository::class, \Mockery::mock( CustomerRepository::class, function( MockInterface $mock ) use ( $customer ) {
			$mock->shouldReceive( 'Add' )
				->with( $customer->name, $customer->phone_number, $customer->car_number, $customer->comment )
				->once( )
				->andReturn( null );
		} ) );
		
		$url = '/customers/add';
		$this->from( $url )->post( '/customers', $data )->assertRedirect( $url );
	}
	
	public function test_add_success( ) : void {
		$customer = Customer::factory( )->create( );
		$data = [
			'name'			=> $customer->name,
			'phone_number'	=> $customer->phone_number,
			'car_number'	=> $customer->car_number,
			'comment'		=> $customer->comment
		];
		
		$this->instance( CustomerRepository::class, \Mockery::mock( CustomerRepository::class, function( MockInterface $mock ) use ( $customer ) {
			$mock->shouldReceive( 'Add' )
				->with( $customer->name, $customer->phone_number, $customer->car_number, $customer->comment )
				->once( )
				->andReturn( $customer );
		} ) );
		
		$this->from( '/customers/add' )->post( '/customers', $data )->assertRedirect( '/customers' );
	}
	
	protected function CustomerArgument( Customer $customer ) : Closure {
		return \Mockery::on( function( $value ) use( $customer ) {
			// в фабрике id идет в конце из-за этого проваливается прямое сравнение объектов
			$this->assertEquals( $value->id, $customer->id );
			$this->assertEquals( $value->name, $customer->name );
			$this->assertEquals( $value->phone_number, $customer->phone_number );
			$this->assertEquals( $value->car_number, $customer->car_number );
			$this->assertEquals( $value->comment, $customer->comment );
			
			return true;
		} );
	}
	
	public function test_update_failed( ) : void {
		$customer = Customer::factory( )->create( );
		$updateCustomer = Customer::factory( )->make( );
		$data = [
			'name'			=> $updateCustomer->name,
			'phone_number'	=> $updateCustomer->phone_number,
			'car_number'	=> $updateCustomer->car_number,
			'comment'		=> $updateCustomer->comment
		];
		
		$this->instance( CustomerRepository::class, \Mockery::mock( CustomerRepository::class, function( MockInterface $mock ) use ( $customer, $updateCustomer ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->CustomerArgument( $customer ), $updateCustomer->name, $updateCustomer->phone_number, $updateCustomer->car_number,
					$updateCustomer->comment
				)
				->once( )
				->andReturn( false );
		} ) );
		
		$url = '/customers/'.$customer->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	public function test_update_success( ) : void {
		$customer = Customer::factory( )->create( );
		$updateCustomer = Customer::factory( )->make( );
		$data = [
			'name'			=> $updateCustomer->name,
			'phone_number'	=> $updateCustomer->phone_number,
			'car_number'	=> $updateCustomer->car_number,
			'comment'		=> $updateCustomer->comment
		];
		
		$this->instance( CustomerRepository::class, \Mockery::mock( CustomerRepository::class, function( MockInterface $mock ) use ( $customer, $updateCustomer ) {
			$mock->shouldReceive( 'Update' )
				->with(
					$this->CustomerArgument( $customer ), $updateCustomer->name, $updateCustomer->phone_number, $updateCustomer->car_number,
					$updateCustomer->comment
				)
				->once( )
				->andReturn( true );
		} ) );
		
		$url = '/customers/'.$customer->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/customers' );
	}
}
