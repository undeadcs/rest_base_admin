<?php
namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\Carbon;

trait DateSerialize {
	protected function serializeDate( \DateTimeInterface $date ) {
        return ( $date instanceof \DateTimeImmutable ) ?
        	CarbonImmutable::instance( $date )->toISOString( true ) :
        	Carbon::instance( $date )->toISOString( true );
    }
}