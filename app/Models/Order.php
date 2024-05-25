<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\OrderStatus;

/**
 * Заявка на апартаменты
 * 
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $customer_id
 * @property int $apartment_id
 * @property int $apartment_price_id
 * @property OrderStatus $status
 * @property string $from
 * @property string $to
 * @property int $persons_number
 * @property string $comment
 */
class Order extends Model {
	use HasFactory;
	
	protected $casts = [ 'from' => 'datetime', 'to' => 'datetime' ];
	
	public function status( ) : Attribute {
		return Attribute::make(
			get: fn( $value ) => OrderStatus::from( ( int ) $value )->value,
			set: fn( $value ) => $value instanceof OrderStatus ? $value->value : OrderStatus::from( ( int ) $value )->value
		);
	}
	
	public function customer( ) : BelongsTo {
		return $this->belongsTo( Customer::class );
	}
	
	public function apartment( ) : BelongsTo {
		return $this->belongsTo( Apartment::class );
	}
	
	public function price( ) : BelongsTo {
		return $this->belongsTo( ApartmentPrice::class );
	}
}
