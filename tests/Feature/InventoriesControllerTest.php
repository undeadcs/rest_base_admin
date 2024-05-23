<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use Mockery\MockInterface;
use App\Models\InventoryPrice;
use Mockery\Matcher\Closure;

class InventoriesControllerTest extends TestCase {
	use RefreshDatabase, WithFaker;
	
	public function test_add_failed( ) : void {
		$inventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $inventory->title,
			'price'	=> $this->faker->randomFloat( )
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory ) {
			$mock->shouldReceive( 'Add' )->with( $inventory->title )->andReturn( null );
		} ) );
		
		$url = '/inventories/add';
		$this->from( $url )->post( '/inventories', $data )->assertRedirect( $url );
	}
	
	public function test_price_add_failed( ) : void {
		$inventory = Inventory::factory( )->create( );
		$price = $this->faker->randomFloat( );
		$data = [
			'title'	=> $inventory->title,
			'price'	=> $price
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $price ) {
			$mock->shouldReceive( 'Add' )->with( $inventory->title )->andReturn( $inventory );
			$mock->shouldReceive( 'PriceAdd' )->with( $inventory, $price )->andReturn( null );
		} ) );
		
		$url = '/inventories/add';
		$this->from( $url )->post( '/inventories', $data )->assertRedirect( $url );
	}
	
	public function test_update_failed( ) : void {
		$inventory = Inventory::factory( )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $updateInventory->title,
			'price'	=> $this->faker->randomFloat( )
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory ) {
			$mock->shouldReceive( 'Update' )->with( $this->InventoryArgument( $inventory ), $updateInventory->title )->andReturn( false );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	protected function InventoryArgument( Inventory $inventory ) : Closure {
		return \Mockery::on( function( $value ) use( $inventory ) {
			// в фабрике id идет в конце из-за этого проваливается прямое сравнение объектов
			$this->assertEquals( $value->id, $inventory->id );
			$this->assertEquals( $value->title, $inventory->title );
			
			return true;
		} );
	}
	
	public function test_update_price_add_failed( ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $updateInventory->title,
			'price'	=> $newPrice
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )->with( $this->InventoryArgument( $inventory ), $newPrice )->andReturn( null );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	public function test_update_price_same_not_called( ) : void {
		$price = 100.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $price ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $updateInventory->title,
			'price'	=> $price
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $price ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title )
				->andReturn( true );
			$mock->shouldNotReceive( 'PriceAdd' );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/inventories' );
	}
	
	public function test_update_price_without_current_price( ) : void {
		$price = 100.0;
		$inventory = Inventory::factory( )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $updateInventory->title,
			'price'	=> $price
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $price ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->InventoryArgument( $inventory ), $price )
				->andReturn( InventoryPrice::factory( )->state( [ 'inventory_id' => $inventory ] )->create( ) );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/inventories' );
	}
	
	public function test_update_price_add_success( ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'	=> $updateInventory->title,
			'price'	=> $newPrice
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->InventoryArgument( $inventory ), $newPrice )
				->andReturn( InventoryPrice::factory( )->state( [ 'inventory_id' => $inventory ] )->create( ) );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/inventories' );
	}
}
