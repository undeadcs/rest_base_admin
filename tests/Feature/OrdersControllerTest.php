<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Mockery\MockInterface;
use App\Models\Apartment;
use Mockery\Matcher\Closure;
use App\Repositories\OrderRepository;
use App\Repositories\ApartmentRepository;
use App\Models\Payment;
use App\Models\Inventory;
use Carbon\Carbon;
use Tests\Mocking\InventoryArgument;
use Tests\Mocking\ApartmentArgument;
use Tests\Mocking\CustomerArgument;
use PHPUnit\Framework\Attributes\DataProvider;

class OrdersControllerTest extends TestCase {
	use RefreshDatabase, WithFaker, InventoryArgument, ApartmentArgument, CustomerArgument;
	
	protected function ExpectsFindCustomer( Customer $customer ) : void {
		$this->instance( CustomerRepository::class, \Mockery::mock( CustomerRepository::class, function( MockInterface $mock ) use ( $customer ) {
			$mock->shouldReceive( 'Find' )->with( $customer->id )->once( )->andReturn( $customer );
		} ) );
	}
	
	protected function ExpectsFindApartment( Apartment $apartment ) : void {
		$this->instance( ApartmentRepository::class, \Mockery::mock( ApartmentRepository::class, function( MockInterface $mock ) use ( $apartment ) {
			$mock->shouldReceive( 'Find' )->with( $apartment->id )->once( )->andReturn( $apartment );
		} ) );
	}
	
	protected function CarbonArgument( Carbon $time ) : Closure {
		return \Mockery::on( function( $value ) use( $time ) {
			$this->assertInstanceOf( Carbon::class, $value );
			$this->assertEquals( $time, $value );
			
			return true;
		} );
	}
	
	protected function ExpectsAddingOrder( Customer $customer, Apartment $apartment, Order $order, ?Order $returnOrder ) : void {
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $customer, $apartment, $order, $returnOrder ) {
				$mock->shouldReceive( 'Add' )
					->with(
						$this->CustomerArgument( $customer ), $this->ApartmentArgument( $apartment ),
						$this->CarbonArgument( $order->from ), $this->CarbonArgument( $order->to ),
						$order->persons_number, $order->comment
					)
					->once( )
					->andReturn( $returnOrder );
			}
		) );
	}
	
	public function test_adding_order_add_failed( ) : void {
		$apartment = Apartment::factory( )->hasPrices( 2 )->create( );
		$customer = Customer::factory( )->create( );
		$order = Order::factory( )->state( [ 'apartment_id' => $apartment, 'customer_id' => null, 'status' => OrderStatus::Pending ] )->make( );
		$data = [
			'apartment_id'	=> $apartment->id,
			'customer_id'	=> $customer->id,
			'from'			=> $order->from->format( 'd.m.Y' ),
			'to'			=> $order->to->format( 'd.m.Y' ),
			'persons_number' => $order->persons_number,
			'comment'		=> $order->comment,
			'from_hour'		=> $order->from->format( 'H' ),
			'from_minute'	=> $order->from->format( 'i' ),
			'to_hour'		=> $order->to->format( 'H' ),
			'to_minute'		=> $order->to->format( 'i' )
		];
		
		$this->ExpectsFindCustomer( $customer );
		$this->ExpectsFindApartment( $apartment );
		$this->ExpectsAddingOrder( $customer, $apartment, $order, null );
		
		$this->from( '/orders/add' )->post( '/orders', $data )->assertRedirect( '/orders/add' );
	}
	
	public static function commentProvider( ) : array {
		return [
			'comment_filled' => [ fake( )->text( ) ],
			'comment_empty' => [ '' ]
		];
	}
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_adding_order_add_success( string $comment ) : void {
		$order = Order::factory( )->state( [ 'status' => OrderStatus::Pending ] )->create( [ 'comment' => $comment ] );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'from'			=> $order->from->format( 'd.m.Y' ),
			'to'			=> $order->to->format( 'd.m.Y' ),
			'persons_number' => $order->persons_number,
			'comment'		=> $order->comment,
			'from_hour'		=> $order->from->format( 'H' ),
			'from_minute'	=> $order->from->format( 'i' ),
			'to_hour'		=> $order->to->format( 'H' ),
			'to_minute'		=> $order->to->format( 'i' )
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		$this->ExpectsAddingOrder( $order->customer, $order->apartment, $order, $order );
		
		$this->from( '/orders/add' )->post( '/orders', $data )->assertRedirect( '/orders' );
	}
	
	protected function OrderArgument( Order $order ) : Closure {
		return \Mockery::on( function( $value ) use( $order ) {
			$this->assertInstanceOf( Order::class, $value );
			$this->assertEquals( $order->id,					$value->id );
			$this->assertEquals( $order->customer_id,			$value->customer_id );
			$this->assertEquals( $order->apartment_id,			$value->apartment_id );
			$this->assertEquals( $order->apartment_price_id,	$value->apartment_price_id );
			$this->assertEquals( $order->status,				$value->status );
			$this->assertEquals( $order->from,					$value->from );
			$this->assertEquals( $order->to,					$value->to );
			$this->assertEquals( $order->persons_number,		$value->persons_number );
			$this->assertEquals( $order->comment,				$value->comment );
			
			return true;
		} );
	}
	
	protected function PaymentArgument( Payment $payment ) : Closure {
		return \Mockery::on( function( $value ) use( $payment ) {
			$this->assertInstanceOf( Payment::class, $value );
			$this->assertEquals( $payment->id,			$value->id );
			$this->assertEquals( $payment->order_id,	$value->order_id );
			$this->assertEquals( $payment->amount,		$value->amount );
			$this->assertEquals( $payment->comment,		$value->comment );
			
			return true;
		} );
	}
	
	protected function ExpectsUpdatingOrder(
		Order $order, Order $updateOrder, Customer $customer, Apartment $apartment, bool $result
	) : void {
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $customer, $apartment, $order, $updateOrder, $result ) {
				$mock->shouldReceive( 'Update' )
					->with(
						$this->OrderArgument( $order ), $this->CustomerArgument( $customer ), $this->ApartmentArgument( $apartment ),
						$updateOrder->status, $this->CarbonArgument( $updateOrder->from ), $this->CarbonArgument( $updateOrder->to ),
						$updateOrder->persons_number, $updateOrder->comment
					)
					->once( )
					->andReturn( $result );
			}
		) );
	}
	
	public function test_updating_order_update_failed( ) : void {
		$order = Order::factory( )->create( );
		$updateOrder = Order::factory( )->make( );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' )
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		$this->ExpectsUpdatingOrder( $order, $updateOrder, $order->customer, $order->apartment, false );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_updating_order_update_success( string $comment ) : void {
		$order = Order::factory( )->create( );
		$updateOrder = Order::factory( )->make( [ 'comment' => $comment ] );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' )
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		$this->ExpectsUpdatingOrder( $order, $updateOrder, $order->customer, $order->apartment, true );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/orders' );
	}
	
	public function test_updating_payment_add( ) : void {
		$order = Order::factory( )->create( );
		$updateOrder = Order::factory( )->make( );
		$payment = Payment::factory( )->state( [ 'order_id' => null ] )->make( );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' ),
			'payments'		=> [ [
				'id'		=> 0,
				'amount'	=> $payment->amount,
				'comment'	=> $payment->comment
			] ]
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $order, $updateOrder, $payment ) {
				$mock->shouldReceive( 'Update' )
					->with(
						$this->OrderArgument( $order ), $this->CustomerArgument( $order->customer ), $this->ApartmentArgument( $order->apartment ),
						$updateOrder->status, $this->CarbonArgument( $updateOrder->from ), $this->CarbonArgument( $updateOrder->to ),
						$updateOrder->persons_number, $updateOrder->comment
					)
					->once( )
					->andReturn( true );
				
				$mock->shouldReceive( 'PaymentAdd' )
					->with( $this->OrderArgument( $order ), $payment->amount, $payment->comment )
					->once( )
					->andReturn( $payment );
			}
		) );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/orders' );
	}
	
	public function test_updating_payment_update( ) : void {
		$order = Order::factory( )->hasPayments( 1 )->create( );
		$payment = $order->payments->first( );
		$updateOrder = Order::factory( )->make( );
		$updatePayment = Payment::factory( )->state( [ 'order_id' => null ] )->make( );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' ),
			'payments'		=> [ [
				'id'		=> $payment->id,
				'amount'	=> $updatePayment->amount,
				'comment'	=> $updatePayment->comment
			] ]
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $order, $updateOrder, $payment, $updatePayment ) {
				$mock->shouldReceive( 'Update' )
					->with(
						$this->OrderArgument( $order ), $this->CustomerArgument( $order->customer ), $this->ApartmentArgument( $order->apartment ),
						$updateOrder->status, $this->CarbonArgument( $updateOrder->from ), $this->CarbonArgument( $updateOrder->to ),
						$updateOrder->persons_number, $updateOrder->comment
					)
					->once( )
					->andReturn( true );
				
				$mock->shouldReceive( 'PaymentUpdate' )
					->with( $this->PaymentArgument( $payment ), $updatePayment->amount, $updatePayment->comment )
					->once( )
					->andReturn( true );
			}
		) );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/orders' );
	}
	
	public function test_updating_inventory_add( ) : void {
		$order = Order::factory( )->create( );
		$updateOrder = Order::factory( )->make( );
		$inventory = Inventory::factory( )->create( );
		$inventoryComment = $this->faker->text( );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' ),
			'inventories'	=> [ [
				'id'			=> 0,
				'inventory_id'	=> $inventory->id,
				'comment'		=> $inventoryComment
			] ]
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $order, $updateOrder, $inventory, $inventoryComment ) {
				$mock->shouldReceive( 'Update' )
					->with(
						$this->OrderArgument( $order ), $this->CustomerArgument( $order->customer ), $this->ApartmentArgument( $order->apartment ),
						$updateOrder->status, $this->CarbonArgument( $updateOrder->from ), $this->CarbonArgument( $updateOrder->to ),
						$updateOrder->persons_number, $updateOrder->comment
					)
					->once( )
					->andReturn( true );
				
				$mock->shouldReceive( 'InventoryAdd' )
					->with( $this->OrderArgument( $order ), $this->InventoryArgument( $inventory ), $inventoryComment )
					->once( )
					->andReturn( true );
			}
		) );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/orders' );
	}
	
	public function test_updating_inventory_update( ) : void {
		$order = Order::factory( )->hasAttached( Inventory::factory( )->count( 1 ), fn( ) => [ 'comment' => fake( )->text( ) ] )->create( );
		$updateOrder = Order::factory( )->make( );
		$inventory = $order->inventories->first( );
		$inventoryComment = $this->faker->text( );
		$data = [
			'apartment_id'	=> $order->apartment->id,
			'customer_id'	=> $order->customer->id,
			'status'		=> $updateOrder->status->value,
			'from'			=> $updateOrder->from->format( 'd.m.Y' ),
			'to'			=> $updateOrder->to->format( 'd.m.Y' ),
			'persons_number' => $updateOrder->persons_number,
			'comment'		=> $updateOrder->comment,
			'from_hour'		=> $updateOrder->from->format( 'H' ),
			'from_minute'	=> $updateOrder->from->format( 'i' ),
			'to_hour'		=> $updateOrder->to->format( 'H' ),
			'to_minute'		=> $updateOrder->to->format( 'i' ),
			'inventories' => [ [
				'id'		=> $inventory->id,
				'comment'	=> $inventoryComment
			] ]
		];
		
		$this->ExpectsFindCustomer( $order->customer );
		$this->ExpectsFindApartment( $order->apartment );
		
		$this->instance( OrderRepository::class, \Mockery::mock(
			OrderRepository::class,
			function( MockInterface $mock ) use ( $order, $updateOrder, $inventory, $inventoryComment ) {
				$mock->shouldReceive( 'Update' )
					->with(
						$this->OrderArgument( $order ), $this->CustomerArgument( $order->customer ), $this->ApartmentArgument( $order->apartment ),
						$updateOrder->status, $this->CarbonArgument( $updateOrder->from ), $this->CarbonArgument( $updateOrder->to ),
						$updateOrder->persons_number, $updateOrder->comment
					)
					->once( )
					->andReturn( true );
				
				$mock->shouldReceive( 'InventoryUpdate' )
					->with( $this->OrderArgument( $order ), $this->InventoryArgument( $inventory ), $inventoryComment )
					->once( )
					->andReturn( true );
			}
		) );
		
		$url = '/orders/'.$order->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/orders' );
	}
}
