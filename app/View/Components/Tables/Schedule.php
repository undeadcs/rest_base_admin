<?php

namespace App\View\Components\Tables;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Order;
use App\Models\Apartment;
use Carbon\Carbon;
use Illuminate\Support\Collection as BaseCollection;

class Schedule extends Component {
	public Collection $apartments;
	public BaseCollection $days;
	public array $orderIndex;
	public int $lowestHourForOrder;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( Collection $apartments, BaseCollection $days, array $orderIndex, int $lowestHourForOrder = 14 ) {
		$this->apartments			= $apartments;
		$this->days					= $days;
		$this->orderIndex			= $orderIndex;
		$this->lowestHourForOrder	= $lowestHourForOrder;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.tables.schedule' );
	}
	
	public function FindOrderByDay( Apartment $apartment, Carbon $day ) : ?Order {
		return $this->orderIndex[ $apartment->id ][ $day->format( 'Y-m-d' ) ] ?? null;
	}
	
	public function HasOrdersAtDay( Apartment $apartment, Carbon $day ) : bool {
		return isset( $this->orderIndex[ $apartment->id ][ $day->format( 'Y-m-d' ) ] );
	}
	
	public function TimeLeftForOrder( Order $order, Carbon $day, Apartment $apartment ) : ?Carbon {
		$dayStr = $day->format( 'Ymd' );
		
		if ( $order->to->format( 'Ymd' ) != $dayStr ) {
			return null;
		}
		
		$nextDay = ( clone $order->to )->modify( '+1 day' );
		
		if ( isset( $this->orderIndex[ $apartment->id ][ $nextDay->format( 'Y-m-d' ) ] ) ) {
			return null;
		}
		
		$dateAfterCleaning = ( clone $order->to )->modify( '+3 hour' );
		
		return ( ( int ) $dateAfterCleaning->format( 'H' ) < $this->lowestHourForOrder ) ? $dateAfterCleaning : null;
	}
	
	public function ShortCustomerName( string $name ) : string {
		return ( mb_strlen( $name ) > 12 ) ? mb_substr( $name, 0, 12 ).'...' : $name;
	}
}
