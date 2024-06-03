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
	public int $lowestHourForOrder = 20;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( Collection $apartments, BaseCollection $days, array $orderIndex ) {
		$this->apartments = $apartments;
		$this->days = $days;
		$this->orderIndex = $orderIndex;
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
	
	public function TimeLeftForOrder( Order $order, Carbon $day ) : ?Carbon {
		if ( $order->to->format( 'Ymd' ) != $day->format( 'Ymd' ) ) {
			return null;
		}
		
		$dateAfterCleaning = ( clone $order->to )->modify( '+3 hour' );
		
		return ( ( int ) $dateAfterCleaning->format( 'H' ) < $this->lowestHourForOrder ) ? $dateAfterCleaning : null;
	}
	
	public function ShortCustomerName( string $name ) : string {
		return ( mb_strlen( $name ) > 12 ) ? mb_substr( $name, 0, 12 ).'...' : $name;
	}
}
