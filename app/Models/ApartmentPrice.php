<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Цена за апартаменты
 * 
 * @property int $id
 * @property int $apartment_id
 * @property string $created_at
 * @property float $price
 */
class ApartmentPrice extends Model {
	use HasFactory;
	
	public $timestamps = false;
	
	protected function casts( ) : array {
		return [ 'created_at' => 'datetime' ];
	}
	
	public function apartment( ) : BelongsTo {
		return $this->belongsTo( Apartment::class );
	}
}
