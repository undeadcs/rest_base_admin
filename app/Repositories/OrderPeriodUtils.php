<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

trait OrderPeriodUtils {
	protected function ApplyQueryPeriodCondition( Builder $query, string $from, string $to ) : Builder {
		/*$query->whereBetween( 'from', [ $from, $to ] ) // работает
			->orWhereBetween( 'to', [ $from, $to ] )
			->orWhereRaw( '? BETWEEN "from" and "to"', $from )
			->orWhereRaw( '? BETWEEN "from" and "to"', $to );
		//*/
		$query->where( function( Builder $query ) use( $from, $to ) {
			$query->where( 'from', '<=', $from )
				->where( 'to', '>=', $from );
		} )->orWhere( function( Builder $query ) use( $from, $to ) {
			$query->where( 'from', '>=', $from )
				->where( 'from', '<=', $to );
		} );
		/*/
		$query->where( function( Builder $query ) use( $from, $to ) { // beginInPeriod
			$query->where( 'from', '>=', $from )
				->where( 'from', '<=', $to );
		} )->orWhere( function( Builder $query ) use( $from, $to ) { // endInPeriod
			$query->where( 'to', '>=', $from )
				->where( 'to', '<=', $to );
		} )->orWhere( function( Builder $query ) use( $from, $to ) { // insidePeriod
			$query->where( 'from', '>=', $from )
				->where( 'to', '<=', $to );
		} )->orWhere( function( Builder $query ) use( $from, $to ) { // coverPeriod
			$query->where( 'from', '<=', $from )
				->where( 'to', '>=', $to );
		} );
		//*/
		return $query;
	}
}
