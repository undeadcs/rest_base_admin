<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use Mockery\MockInterface;
use App\Models\InventoryPrice;
use Tests\Mocking\InventoryArgument;
use PHPUnit\Framework\Attributes\DataProvider;

class InventoriesControllerTest extends TestCase {
	use RefreshDatabase, WithFaker, InventoryArgument;
	
	public function test_add_failed( ) : void {
		$inventory = Inventory::factory( )->make( );
		$data = [
			'title'		=> $inventory->title,
			'price'		=> $this->faker->randomFloat( ),
			'comment'	=> $inventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory ) {
			$mock->shouldReceive( 'Add' )->with( $inventory->title, $inventory->comment )->once( )->andReturn( null );
		} ) );
		
		$url = '/inventories/add';
		$this->from( $url )->post( '/inventories', $data )->assertRedirect( $url );
	}
	
	public function test_price_add_failed( ) : void {
		$inventory = Inventory::factory( )->create( );
		$price = $this->faker->randomFloat( );
		$data = [
			'title'		=> $inventory->title,
			'price'		=> $price,
			'comment'	=> $inventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $price ) {
			$mock->shouldReceive( 'Add' )->with( $inventory->title, $inventory->comment )->once( )->andReturn( $inventory );
			$mock->shouldReceive( 'PriceAdd' )->with( $inventory, $price )->once( )->andReturn( null );
		} ) );
		
		$url = '/inventories/add';
		$this->from( $url )->post( '/inventories', $data )->assertRedirect( $url );
	}
	
	public static function commentProvider( ) : array {
		return [
			'comment_filled' => [ fake( )->text( ) ],
			'comment_empty' => [ '' ]
		];
	}
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_add_success( string $comment ) : void {
		$inventory = Inventory::factory( )->hasPrices( 1 )->create( [ 'comment' => $comment ] );
		$data = [
			'title'		=> $inventory->title,
			'price'		=> $inventory->currentPrice->price,
			'comment'	=> $inventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory ) {
			$mock->shouldReceive( 'Add' )->with( $inventory->title, $inventory->comment )->once( )->andReturn( $inventory );
			$mock->shouldReceive( 'PriceAdd' )->with( $inventory, $inventory->currentPrice->price )->once( )->andReturn( $inventory->currentPrice );
		} ) );
		
		$this->from( '/inventories/add' )->post( '/inventories', $data )->assertRedirect( '/inventories' );
	}
	
	public function test_update_failed( ) : void {
		$inventory = Inventory::factory( )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'		=> $updateInventory->title,
			'price'		=> $this->faker->randomFloat( ),
			'comment'	=> $updateInventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title, $updateInventory->comment )
				->once( )
				->andReturn( false );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	public function test_update_price_add_failed( ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'		=> $updateInventory->title,
			'price'		=> $newPrice,
			'comment'	=> $updateInventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title, $updateInventory->comment )
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )->with( $this->InventoryArgument( $inventory ), $newPrice )->once( )->andReturn( null );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( $url );
	}
	
	public function test_update_price_same_not_called( ) : void {
		$price = 100.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $price ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( );
		$data = [
			'title'		=> $updateInventory->title,
			'price'		=> $price,
			'comment'	=> $updateInventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $price ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title, $updateInventory->comment )
				->once( )
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
			'title'		=> $updateInventory->title,
			'price'		=> $price,
			'comment'	=> $updateInventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $price ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title, $updateInventory->comment )
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->InventoryArgument( $inventory ), $price )
				->once( )
				->andReturn( InventoryPrice::factory( )->state( [ 'inventory_id' => $inventory ] )->create( ) );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/inventories' );
	}
	
	#[ DataProvider( 'commentProvider' ) ]
	public function test_update_price_add_success( string $comment ) : void {
		$oldPrice = 100.0;
		$newPrice = 200.0;
		$inventory = Inventory::factory( )->has( InventoryPrice::factory( )->state( [ 'price' => $oldPrice ] ), 'prices' )->create( );
		$updateInventory = Inventory::factory( )->make( [ 'comment' => $comment ] );
		$data = [
			'title'		=> $updateInventory->title,
			'price'		=> $newPrice,
			'comment'	=> $updateInventory->comment
		];
		
		$this->instance( InventoryRepository::class, \Mockery::mock( InventoryRepository::class, function( MockInterface $mock ) use ( $inventory, $updateInventory, $newPrice ) {
			$mock->shouldReceive( 'Update' )
				->with( $this->InventoryArgument( $inventory ), $updateInventory->title, $updateInventory->comment )
				->once( )
				->andReturn( true );
			$mock->shouldReceive( 'PriceAdd' )
				->with( $this->InventoryArgument( $inventory ), $newPrice )
				->once( )
				->andReturn( InventoryPrice::factory( )->state( [ 'inventory_id' => $inventory ] )->create( ) );
		} ) );
		
		$url = '/inventories/'.$inventory->id;
		$this->from( $url )->put( $url, $data )->assertRedirect( '/inventories' );
	}
}
