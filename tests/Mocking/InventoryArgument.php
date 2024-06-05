<?php
namespace Tests\Mocking;

use App\Models\Inventory;
use Mockery\Matcher\Closure;

trait InventoryArgument {
	protected function InventoryArgument( Inventory $inventory ) : Closure {
		return \Mockery::on( function( $value ) use( $inventory ) {
			$this->assertInstanceOf( Inventory::class, $value );
			$this->assertEquals( $inventory->id, $value->id );
			$this->assertEquals( $inventory->title, $value->title );
			$this->assertEquals( $inventory->comment, $value->comment );
			
			return true;
		} );
	}
}
