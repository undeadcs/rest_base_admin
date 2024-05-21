<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Бронь апартаментов
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $apartment_id
 * @property int $apartment_price_id
 * @property string $from
 * @property string $to
 * @property int $persons_number
 * @property string $comment
 */
class Reserv extends Model {
	use HasFactory;
	
	public function customer( ) : BelongsTo {
		return $this->belongsTo( Customer::class );
	}
	
	public function apartment( ) : BelongsTo {
		return $this->belongsTo( Apartment::class );
	}
	
	public function apartmentPrice( ) : BelongsTo {
		return $this->belongsTo( ApartmentPrice::class );
	}
}
