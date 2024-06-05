<?php
namespace Tests\Mocking;

use Mockery\Matcher\Closure;
use App\Models\Apartment;

trait ApartmentArgument {
	protected function ApartmentArgument( Apartment $apartment ) : Closure {
		return \Mockery::on( function( $value ) use( $apartment ) {
			$this->assertInstanceOf( Apartment::class, $value );
			$this->assertEquals( $apartment->id,		$value->id );
			$this->assertEquals( $apartment->title,		$value->title );
			$this->assertEquals( $apartment->type,		$value->type );
			$this->assertEquals( $apartment->number,	$value->number );
			$this->assertEquals( $apartment->capacity,	$value->capacity );
			$this->assertEquals( $apartment->comment,	$value->comment );
			
			return true;
		} );
	}
}
