<?php
namespace Tests\Feature\Api;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Database\Factories\OrderFactory;

trait OrderPeriodUtils {
	protected function SeedWeekOrders( \DateTime $periodFrom, OrderFactory $baseFactory ) : PeriodicOrdersInfo {
		$info = new PeriodicOrdersInfo;
		$info->periodFrom = ( clone $periodFrom )->setTime( 0, 0, 0 );
		$info->periodTo = ( clone $info->periodFrom )->modify( '+1 week -1 second' );
		$info->pastPeriodFrom = ( clone $info->periodFrom )->modify( '-1 week' );
		$info->pastPeriodTo = ( clone $info->periodFrom )->modify( '-1 day' )->setTime( 23, 59, 59 );
		$info->futurePeriodFrom = ( clone $info->periodTo )->modify( '+1 day' )->setTime( 0, 0, 0 );
		$info->futurePeriodTo = ( clone $info->periodTo )->modify( '+1 week' );
		
		/*dd(
			'period: '.$info->periodFrom->format( 'Y-m-d H:i:s' ).' => '.$info->periodTo->format( 'Y-m-d H:i:s' ),
			'past period: '.$info->pastPeriodFrom->format( 'Y-m-d H:i:s' ).' => '.$info->pastPeriodTo->format( 'Y-m-d H:i:s' ),
			'future period: '.$info->futurePeriodFrom->format( 'Y-m-d H:i:s' ).' => '.$info->futurePeriodTo->format( 'Y-m-d H:i:s' )
		);*/
		
		// старые заявки, до периода
		$info->pastOrders = $baseFactory->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $info->pastPeriodFrom->format( 'Y-m-d H:i:s' ), $info->pastPeriodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $info->pastPeriodTo
			] )
			->create( );
		
		// будущие заявки после периода
		$info->futureOrders = $baseFactory->count( 10 )
			->sequence( fn( ) => [
				'from' => $info->futurePeriodFrom,
				'to' => $this->faker->dateTimeBetween( $info->futurePeriodFrom->format( 'Y-m-d H:i:s' ), $info->futurePeriodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые оканчиваются внутри периода
		$info->endInPeriodOrders = $baseFactory->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $info->pastPeriodFrom->format( 'Y-m-d H:i:s' ), $info->pastPeriodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $this->faker->dateTimeBetween( $info->periodFrom->format( 'Y-m-d H:i:s' ), $info->periodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые начинаются внутри периода
		$info->beginInPeriodOrders = $baseFactory->count( 10 )
			->sequence( fn( Sequence $sequence ) => [
				'from' => $this->faker->dateTimeBetween( $info->periodFrom->format( 'Y-m-d H:i:s' ), $info->periodTo->format( 'Y-m-d H:i:s' ) ),
				'to' => $this->faker->dateTimeBetween( $info->futurePeriodFrom->format( 'Y-m-d H:i:s' ), $info->futurePeriodTo->format( 'Y-m-d H:i:s' ) )
			] )
			->create( );
		
		// заявки, которые входят в период
		$info->insidePeriodOrders = $baseFactory->count( 10 )
			->sequence( function( Sequence $sequence ) use( $info ) {
				$t1 = $this->faker->dateTimeBetween( $info->periodFrom->format( 'Y-m-d H:i:s' ), $info->periodTo->format( 'Y-m-d H:i:s' ) );
				$t2 = $this->faker->dateTimeBetween( $info->periodFrom->format( 'Y-m-d H:i:s' ), $info->periodTo->format( 'Y-m-d H:i:s' ) );
				
				return ( $t1 < $t2 ) ? [ 'from' => $t1, 'to' => $t2 ] : [ 'from' => $t2, 'to' => $t1 ];
			} )
			->create( );
		
		// пограничный случай должен попадать, т.к. входит в период
		$info->insidePeriodOrders->add( $baseFactory->create( [
			'from' => $info->periodFrom->format( 'Y-m-d H:i:s' ),
			'to' => $info->periodTo->format( 'Y-m-d H:i:s' )
		] ) );
		
		// заявки, которые охватывают период
		$info->coverPeriodOrders = $baseFactory->count( 10 )
			->sequence( function( Sequence $sequence ) use( $info ) {
				$t1 = $this->faker->dateTimeBetween( $info->pastPeriodFrom->format( 'Y-m-d H:i:s' ), $info->pastPeriodTo->format( 'Y-m-d H:i:s' ) );
				$t2 = $this->faker->dateTimeBetween( $info->futurePeriodFrom->format( 'Y-m-d H:i:s' ), $info->futurePeriodTo->format( 'Y-m-d H:i:s' ) );
				
				return ( $t1 < $t2 ) ? [ 'from' => $t1, 'to' => $t2 ] : [ 'from' => $t2, 'to' => $t1 ];
			} )
			->create( );
		
		return $info;
	}
}
