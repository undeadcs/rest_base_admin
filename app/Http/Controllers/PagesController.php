<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\Navigation;
use App\Repositories\ApartmentRepository;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Enums\ApartmentType;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Collection;

class PagesController extends Controller {
	protected Navigation $navigation;
	
	public function __construct( Navigation $navigation ) {
		$this->navigation = $navigation;
	}
	
	public function main( Request $request, ApartmentRepository $apartments ) : View {
		$from = Date::createFromFormat( 'd.m.Y', $request->get( 'f', today( )->format( 'd.m.Y' ) ) )->setTime( 0, 0 );
		$to = Date::createFromFormat( 'd.m.Y', $request->get( 't', today( )->modify( '+6 days' )->format( 'd.m.Y' ) ) )->setTime( 23, 59, 59 );
		$apartmentType = ApartmentType::from( ( int ) $request->get( 'a' ) );
		
		$currentApartments = match( $apartmentType ) {
			ApartmentType::House => $apartments->GetHouses( ),
			ApartmentType::TentPlace => $apartments->GetTents( ),
			ApartmentType::HotelRoom => $apartments->GetHouses( ) // stub
		};
		
		$orderIndex = [ ];
		
		$currentApartments->each( function( Apartment $apartment ) use( &$orderIndex, $apartments, $from, $to ) {
			$paginator = $apartments->ListOrdersByPeriod( $apartment, $from, $to );
			
			if ( $paginator->total( ) ) {
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
		$day = clone $from;
		
		while( $day < $to ) {
			$days[ ] = clone $day;
			$day->modify( '+1 day' );
		}
		
		return view( 'components.pages.'.TopPage::Main->value, [
			'top_nav_items'	=> $this->navigation->items( TopPage::Main ),
			'apartments'	=> $currentApartments,
			'days'			=> $days,
			'orderIndex'	=> $orderIndex,
			'currentApartmentType' => $apartmentType,
			'apartmentTypeItems' => $this->navigation->ApartmentTypeItems( $apartmentType, $from->format( 'd.m.Y' ), $to->format( 'd.m.Y' ) )
		] );
	}
}
