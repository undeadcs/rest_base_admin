<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Customer;
use App\Models\Inventory;

class ThreeWeeksSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		$this->call( [ ApartmentsSeeder::class, CustomersSeeder::class, InventoriesSeeder::class ] );

		$periodFrom = ( new \DateTime )->setTime( 0, 0, 0 );
		$periodTo = ( clone $periodFrom )->modify( '+1 week -1 second' );
		$pastPeriodFrom = ( clone $periodFrom )->modify( '-1 week' );
		$pastPeriodTo = ( clone $periodFrom )->modify( '-1 day' )->setTime( 23, 59, 59 );
		$futurePeriodFrom = ( clone $periodTo )->modify( '+1 day' )->setTime( 0, 0, 0 );
		$futurePeriodTo = ( clone $periodTo )->modify( '+1 week' );
		$customers = Customer::all( );
		$apartments = Apartment::all( );
		$inventories = Inventory::all( );
		
		foreach( $apartments as $apartment ) {
			// с прошлой недели начинается, на текущей заканчивается
			$customer = $customers->random( );
			
			$order = Order::factory( )
				->for( $apartment )
				->for( $apartment->currentPrice )
				->for( $customer )
				->hasPayments( 2 )
				->sequence( fn( Sequence $sequence ) => [
					'from' => fake( )->dateTimeBetween( $pastPeriodFrom, $pastPeriodTo ),
					'to' => fake( )->dateTimeBetween( $periodFrom, ( clone $periodTo )->modify( '-3 day' ) )
				] )
				->create( );
			
			$order->inventories( )->attach( $inventories->random( ), [ 'comment' => fake( )->text( ) ] );
			$order->inventories( )->attach( $inventories->random( ), [ 'comment' => fake( )->text( ) ] );

			do {
				$nextCustomer = $customers->random( );
			} while( $customer->id == $nextCustomer->id );
			
			// с текущей недели начинается, на следующей заканчивается
			$order = Order::factory( )
				->for( $apartment )
				->for( $apartment->currentPrice )
				->for( $nextCustomer )
				->hasPayments( 2 )
				->sequence( fn( Sequence $sequence ) => [
					'from' => fake( )->dateTimeBetween( ( clone $order->to )->modify( '+2 hour' ), $periodTo ),
					'to' => fake( )->dateTimeBetween( $futurePeriodFrom, $futurePeriodTo )
				] )
				->create( );
			
			$order->inventories( )->attach( $inventories->random( ), [ 'comment' => fake( )->text( ) ] );
		}
	}
}
