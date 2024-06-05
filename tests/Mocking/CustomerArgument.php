<?php
namespace Tests\Mocking;

use Mockery\Matcher\Closure;
use App\Models\Customer;

trait CustomerArgument {
	protected function CustomerArgument( Customer $customer ) : Closure {
		return \Mockery::on( function( $value ) use( $customer ) {
			$this->assertInstanceOf( Customer::class, $value );
			$this->assertEquals( $customer->id,				$value->id );
			$this->assertEquals( $customer->name,			$value->name );
			$this->assertEquals( $customer->phone_number,	$value->phone_number );
			$this->assertEquals( $customer->car_number,		$value->car_number );
			$this->assertEquals( $customer->comment,		$value->comment );
			
			return true;
		} );
	}
}
