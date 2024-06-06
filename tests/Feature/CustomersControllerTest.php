<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Mocking\CustomerArgument;

class CustomersControllerTest extends TestCase {
	use RefreshDatabase, WithFaker, CustomerArgument;
	
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
	
	public static function commentProvider( ) : array {
		return [
			'car_and_comment_filled' => [ fake( )->text( ), fake( )->regexify( '[0-9A-Z]{6}' ) ],
			'car_and_comment_empty' => [ '', '' ]
		];
	}
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_add_success( string $comment, string $carNumber ) : void {
		$customer = Customer::factory( )->create( [ 'car_number' => $carNumber, 'comment' => $comment ] );
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
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_update_success( string $comment, string $carNumber ) : void {
		$customer = Customer::factory( )->create( );
		$updateCustomer = Customer::factory( )->make( [ 'car_number' => $carNumber, 'comment' => $comment ] );
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
