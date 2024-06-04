<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\TopNavBar;
use App\Repositories\ApartmentRepository;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Enums\ApartmentType;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Collection;

class PagesController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function main( Request $request, ApartmentRepository $apartments ) : View {
		$from = Date::createFromFormat( 'd.m.Y', $request->get( 'f', today( )->format( 'd.m.Y' ) ) )->setTime( 0, 0 );
		$to = Date::createFromFormat( 'd.m.Y', $request->get( 't', today( )->modify( '+6 days' )->format( 'd.m.Y' ) ) )->setTime( 23, 59, 59 );
		
		$houses = $apartments->GetHouses( );
		
		$orderIndex = [ ];
		
		$houses->each( function( Apartment $apartment ) use( &$orderIndex, $apartments, $from, $to ) {
			$paginator = $apartments->ListOrdersByPeriod( $apartment, $from, $to );
			
			if ( $paginator->total( ) ) {
				if ( $paginator->lastPage( ) > 1 ) {
					dump( $apartment->toArray( ), $from->format( 'Y-m-d H:i:s' ).' '.$to->format( 'Y-m-d H:i:s' ), $paginator->lastPage( ) );
				}
				
				foreach( $paginator->items( ) as $order ) {
					$begin = ( clone $order->from )->setTime( 0, 0, 0 );
					$end = ( clone $order->to )->setTime( 23, 59, 59 );
					
					for( ; $begin <= $end; $begin->modify( '+1 day' ) ) {
						$orderIndex[ $apartment->id ][ $begin->format( 'Y-m-d' ) ][ ] = $order;
					}
				}
			}
		} );
		
		$days = new Collection;
		
		while( $from < $to ) {
			$days[ ] = clone $from;
			$from->modify( '+1 day' );
		}
		
		return view( 'components.pages.'.TopPage::Main->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'apartments' => $houses,
			'days' => $days,
			'orderIndex' => $orderIndex
		] );
	}
}
